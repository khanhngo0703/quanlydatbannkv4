<?php
if (!defined('NV_IS_MOD_RESTAURANT')) die('Stop!!!');

// Kiểm tra login
$user_logged_in = !empty($_SESSION['restaurant_user_id']);
if (!$user_logged_in) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=login');
}

$error = '';
$success = '';
$show_form = true;

// Lấy reservation_id từ URL
$reservation_id = $nv_Request->get_int('reservation_id', 'get', 0);

// Kiểm tra reservation
$reservation = restaurant_check_reservation($db, $reservation_id, $_SESSION['restaurant_user_id']);
if (!$reservation) {
    $error = $reservation_id > 0
        ? '❌ Bàn đặt này không tồn tại hoặc không thuộc về bạn'
        : '❌ Thiếu thông tin bàn đặt để gắn đơn hàng';
    $reservation_id = 0;
    $show_form = false;
}

// Lấy danh mục & món
$categories = $menu_items = [];
if ($show_form && $reservation_id > 0) {
    list($categories, $menu_items) = restaurant_get_categories_and_items($db);
}

// Xử lý submit form
if ($nv_Request->get_string('submit', 'post') == '1' && $reservation_id > 0 && $show_form) {
    $order_items = $nv_Request->get_array('quantity', 'post', []);
    $result = restaurant_place_order($db, $reservation_id, $_SESSION['restaurant_user_id'], $order_items);

    if ($result['success']) {
        $success = '✅ Đặt món thành công cho bàn #' . $reservation_id . '! Tổng tiền: ' . number_format($result['total_amount'], 0, ',', '.') . 'đ';
        $show_form = false;
    } else {
        $error = $result['error'];
    }
}

// Khởi tạo template
$xtpl = new XTemplate('order.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

// Gán biến cơ bản
$xtpl->assign('RESERVATION_ID', $reservation_id);
$xtpl->assign('ORDER_MORE_URL', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=order&reservation_id=' . $reservation_id);
$xtpl->assign('BACK_URL', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservation');
$xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=order&reservation_id=' . $reservation_id);
$xtpl->assign('FORM_HIDE', $show_form ? '' : 'style="display:none;"');

// Parse thông báo
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}
if (!empty($success)) {
    $xtpl->assign('SUCCESS', $success);
    $xtpl->parse('main.success');
    $xtpl->parse('main.success_action');
}

// Parse danh mục & món
// Parse danh mục & món
foreach ($categories as $cat) {
    $xtpl->assign('CATEGORY_NAME', $cat['name']);
    $xtpl->assign('CATEGORY_ID', $cat['category_id']);

    // Kiểm tra nếu có món trong danh mục
    if (!empty($menu_items[$cat['category_id']])) {
        foreach ($menu_items[$cat['category_id']] as $item) {
            // Gán thông tin món
            $xtpl->assign('ITEM_ID', $item['item_id']);
            $xtpl->assign('ITEM_NAME', $item['name']);
            $xtpl->assign('ITEM_PRICE', number_format($item['price'], 0, ',', '.'));

            // Kiểm tra trạng thái món
            if ($item['status'] == 'het') {
                $xtpl->assign('STATUS_TEXT', ' (Hết hàng)');
                $xtpl->assign('STATUS_CLASS', 'text-gray-400 line-through');
            } else {
                $xtpl->assign('STATUS_TEXT', '');
                $xtpl->assign('STATUS_CLASS', 'text-red-600 font-bold');
                // Nếu còn hàng thì parse input số lượng
                $xtpl->parse('main.category.item.item_if_con');
            }

            // Parse từng item
            $xtpl->parse('main.category.item');
        }
    }

    // Parse danh mục
    $xtpl->parse('main.category');
}


// Parse main
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
