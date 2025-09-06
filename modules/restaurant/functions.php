<?php
if (!defined('NV_SYSTEM')) die('Stop!!!');
define('NV_IS_MOD_RESTAURANT', true);

/**
 * Kiểm tra xem user đã login chưa
 */
function restaurant_is_logged_in()
{
    return !empty($_SESSION['restaurant_user_id']);
}

/**
 * Lấy username hiện tại
 */
function restaurant_get_username()
{
    return isset($_SESSION['restaurant_user_name']) ? $_SESSION['restaurant_user_name'] : '';
}


/**
 * Logout user
 */
function restaurant_logout($module_name)
{
    unset($_SESSION['restaurant_user_id']);
    unset($_SESSION['restaurant_user_name']);
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name);
}

/**
 * Lấy menu (menu_items đang active)
 */
function restaurant_get_menu($db)
{
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_menu_items 
            WHERE status = "con"';
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Gán URL frontend
 */
function restaurant_get_urls($module_name)
{
    return [
        'reservation_url' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservation',
        'order_url'       => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=order',
        'logout_url'      => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=logout',
        'login_url'       => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=login',
        'register_url'    => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=register',
    ];
}


/**
 * Kiểm tra thông tin đăng nhập frontend user
 */
function restaurant_login_user($db, $username, $password)
{
    $stmt = $db->prepare('SELECT userid, username, password, group_id 
        FROM ' . NV_USERS_GLOBALTABLE . ' 
        WHERE username = :username AND group_id = 4');
    $stmt->execute(['username' => $username]);
    $row = $stmt->fetch();

    if ($row && md5($password) === $row['password']) {
        // Lưu session frontend riêng
        $_SESSION['restaurant_user_id']   = $row['userid'];
        $_SESSION['restaurant_user_name'] = $row['username'];
        return true;
    }

    return false;
}

/**
 * Kiểm tra đăng ký user frontend
 * @param PDO $db
 * @param string $username
 * @param string $email
 * @param string $password
 * @param string $repassword
 * @return array [success => bool, message => string]
 */
function restaurant_register_user($db, $username, $email, $password, $repassword)
{
    if ($password !== $repassword) {
        return ['success' => false, 'message' => 'Mật khẩu nhập lại không khớp'];
    }

    // Kiểm tra username đã tồn tại
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $exists = $stmt->fetch();

    if ($exists) {
        return ['success' => false, 'message' => 'Tên đăng ký đã tồn tại'];
    }

    // Thêm user mới vào nv4_users với group_id = 4 (Users)
    $stmt = $db->prepare(
        'INSERT INTO ' . NV_USERS_GLOBALTABLE . ' 
        (username, md5username, password, email, group_id, regdate, active) 
        VALUES (:username, :md5username, :password, :email, 4, :regdate, 1)'
    );

    $stmt->execute([
        'username'    => $username,
        'md5username' => nv_md5safe($username),
        'password'    => md5($password), // Lưu ý: chỉ test, NukeViet core hash phức tạp hơn
        'email'       => $email,
        'regdate'     => NV_CURRENTTIME
    ]);

    return ['success' => true, 'message' => 'Đăng ký thành công'];
}


/**
 * Lấy danh sách tất cả bàn
 */
function restaurant_get_tables($db)
{
    $stmt = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_tables ORDER BY table_id ASC');
    return $stmt->fetchAll();
}

/**
 * Tạo đặt bàn mới
 */
function restaurant_create_reservation($db, $user_id, $table_id, $reservation_date, $reservation_time)
{
    if (!$table_id || !$reservation_date || !$reservation_time) {
        return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'];
    }

    $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_reservations
        (user_id, table_id, reservation_date, reservation_time, status, created_at) 
        VALUES (:user_id, :table_id, :reservation_date, :reservation_time, :status, :created_at)');
    $stmt->execute([
        'user_id' => $user_id,
        'table_id' => $table_id,
        'reservation_date' => $reservation_date,
        'reservation_time' => $reservation_time,
        'status' => 'cho_duyet',
        'created_at' => time()
    ]);

    return [
        'success' => true,
        'message' => '✅ Đặt bàn thành công! Yêu cầu của bạn đang chờ quản trị viên duyệt.',
        'reservation_id' => $db->lastInsertId()
    ];
}


// Kiểm tra reservation hợp lệ cho user
function restaurant_check_reservation($db, $reservation_id, $user_id)
{
    if ($reservation_id <= 0) return false;

    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_reservations 
                          WHERE reservation_id=:rid AND user_id=:uid');
    $stmt->execute(['rid' => $reservation_id, 'uid' => $user_id]);
    return $stmt->fetch();
}

// Lấy danh mục và món
function restaurant_get_categories_and_items($db)
{
    $categories = $db->query(
        'SELECT * FROM ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_menu_categories'
    )->fetchAll(PDO::FETCH_ASSOC);

    $menu_items = [];
    $stmt = $db->query(
        'SELECT * FROM ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_menu_items'
    );
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $menu_items[$row['category_id']][] = $row;
    }

    return [$categories, $menu_items];
}


// Xử lý đặt món
function restaurant_place_order($db, $reservation_id, $user_id, $order_items)
{
    $total_amount = 0;
    $valid_items = [];

    foreach ($order_items as $item_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            $stmt = $db->prepare('SELECT item_id, price, name FROM ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_menu_items 
                                  WHERE item_id=:item_id AND status="con"');
            $stmt->execute(['item_id' => $item_id]);
            $item = $stmt->fetch();
            if ($item) {
                $valid_items[] = [
                    'item_id' => $item['item_id'],
                    'quantity' => $quantity,
                    'price' => $item['price'],
                    'name' => $item['name']
                ];
                $total_amount += $item['price'] * $quantity;
            }
        }
    }

    if ($total_amount > 0 && !empty($valid_items)) {
        try {
            $db->beginTransaction();

            // Insert đơn hàng
            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_orders
                (reservation_id, user_id, order_date, total_amount, payment_status) 
                VALUES (:reservation_id, :user_id, :order_date, :total_amount, :payment_status)');
            $stmt->execute([
                'reservation_id' => $reservation_id,
                'user_id' => $user_id,
                'order_date' => time(),
                'total_amount' => $total_amount,
                'payment_status' => 'cho_xac_nhan'
            ]);
            $order_id = $db->lastInsertId();

            // Insert từng món
            foreach ($valid_items as $item) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $GLOBALS['module_data'] . '_order_items
                    (order_id, item_id, quantity, price) VALUES (:order_id, :item_id, :quantity, :price)');
                $stmt->execute([
                    'order_id' => $order_id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            $db->commit();
            return ['success' => true, 'total_amount' => $total_amount];

        } catch (Exception $e) {
            $db->rollback();
            return ['success' => false, 'error' => '❌ Có lỗi xảy ra khi đặt món. Vui lòng thử lại!'];
        }
    } else {
        return ['success' => false, 'error' => '⚠️ Vui lòng chọn món ăn và số lượng hợp lệ'];
    }
}