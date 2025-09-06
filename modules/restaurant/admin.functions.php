<?php
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) die('Stop!!!');

$allow_func = ['main','tables','form_table','categories','form_categories','menu','form_menu','reservations','form_reservation','orders','form_order','order_detail']; // Những chức năng admin được phép truy cập
/**
 * Lấy dữ liệu 1 bàn ăn theo ID
 */
function restaurant_get_table($table, $id)
{
    global $db;

    $stmt = $db->query('SELECT * FROM ' . $table . ' WHERE table_id=' . intval($id));
    return $stmt->fetch();
}

/**
 * Thêm hoặc cập nhật bàn ăn
 */
function restaurant_save_table($table, $id, $data)
{
    global $db;

    if ($id) {
        $sql = 'UPDATE ' . $table . ' 
                SET table_name=:name, capacity=:cap, location=:loc, status=:st 
                WHERE table_id=' . intval($id);
    } else {
        $sql = 'INSERT INTO ' . $table . ' (table_name, capacity, location, status) 
                VALUES (:name, :cap, :loc, :st)';
    }

    $sth = $db->prepare($sql);
    $sth->bindParam(':name', $data['table_name'], PDO::PARAM_STR);
    $sth->bindParam(':cap', $data['capacity'], PDO::PARAM_INT);
    $sth->bindParam(':loc', $data['location'], PDO::PARAM_STR);
    $sth->bindParam(':st', $data['status'], PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Xóa bàn ăn
 */
function restaurant_delete_table($table, $id)
{
    global $db;
    if ($id > 0) {
        $db->query('DELETE FROM ' . $table . ' WHERE table_id=' . intval($id));
        return true;
    }
    return false;
}

/**
 * Lấy danh sách bàn ăn
 */
function restaurant_get_tables($table)
{
    global $db;

    $stmt = $db->query('SELECT table_id, table_name, capacity, location, status FROM ' . $table . ' ORDER BY table_id DESC');
    $list = [];
    $status_map = [
        'trong' => 'Trống',
        'dat_truoc' => 'Đặt trước',
        'dang_su_dung' => 'Đang sử dụng',
        'bao_tri' => 'Bảo trì'
    ];

    while ($row = $stmt->fetch()) {
        $row['status_text'] = isset($status_map[$row['status']]) ? $status_map[$row['status']] : $row['status'];
        $list[] = $row;
    }
    return $list;
}


/**
 * Render danh sách bàn ăn
 */
function restaurant_render_tables($list)
{
    global $module_name, $module_file, $global_config;

    $xtpl = new XTemplate('tables.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    foreach ($list as $row) {
        $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=form_table&id=' . $row['table_id'];

        $row['del_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=tables&del=' . $row['table_id'] .
            '&checkss=' . md5($row['table_id'] . NV_CHECK_SESSION);

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    $xtpl->assign('ADD_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=form_table');

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Render form thêm/sửa bàn ăn
 */
function restaurant_render_form_table($data)
{
    global $module_file, $global_config;

    $xtpl = new XTemplate('form_table.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    // assign từng trường
    $xtpl->assign('TABLE_ID', $data['table_id']);
    $xtpl->assign('TABLE_NAME', $data['table_name']);
    $xtpl->assign('CAPACITY', $data['capacity']);
    $xtpl->assign('LOCATION', $data['location']);

    // select trạng thái
    $opts = [
        'trong' => 'Trống',
        'dat_truoc' => 'Đặt trước',
        'dang_su_dung' => 'Đang sử dụng',
        'bao_tri' => 'Bảo trì'
    ];
    foreach ($opts as $val => $text) {
        $xtpl->assign('OPT_VAL', $val);
        $xtpl->assign('OPT_TEXT', $text);
        $xtpl->assign('OPT_SEL', ($data['status'] == $val) ? 'selected' : '');
        $xtpl->parse('main.status_option');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}





/**
 * Lấy dữ liệu 1 danh mục theo ID
 */
function restaurant_get_category($table, $id)
{
    global $db;
    $stmt = $db->query('SELECT * FROM ' . $table . ' WHERE category_id=' . intval($id));
    return $stmt->fetch();
}

/**
 * Thêm hoặc cập nhật danh mục
 */
function restaurant_save_category($table, $id, $data)
{
    global $db;

    if ($id) {
        $sql = 'UPDATE ' . $table . ' 
                SET name=:name, description=:des 
                WHERE category_id=' . intval($id);
    } else {
        $sql = 'INSERT INTO ' . $table . ' (name, description) 
                VALUES (:name, :des)';
    }

    $sth = $db->prepare($sql);
    $sth->bindParam(':name', $data['name'], PDO::PARAM_STR);
    $sth->bindParam(':des', $data['description'], PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Xóa danh mục
 */
function restaurant_delete_category($table, $id)
{
    global $db;
    if ($id > 0) {
        $db->query('DELETE FROM ' . $table . ' WHERE category_id=' . intval($id));
        return true;
    }
    return false;
}

/**
 * Lấy danh sách danh mục
 */
function restaurant_get_categories($table)
{
    global $db;
    $stmt = $db->query('SELECT * FROM ' . $table . ' ORDER BY category_id DESC');
    $list = [];
    while ($row = $stmt->fetch()) {
        $list[] = $row;
    }
    return $list;
}

/**
 * Render danh sách danh mục
 */
function restaurant_render_categories($list)
{
    global $module_name, $module_file, $global_config;

    $xtpl = new XTemplate('categories.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    foreach ($list as $row) {
        $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=form_categories&id=' . $row['category_id'];

        $row['del_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=categories&del=' . $row['category_id'] .
            '&checkss=' . md5($row['category_id'] . NV_CHECK_SESSION);

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    $xtpl->assign('ADD_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=form_categories');

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Render form thêm/sửa danh mục
 */
function restaurant_render_form_category($data)
{
    global $module_file, $global_config;

    $xtpl = new XTemplate('form_categories.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    $xtpl->assign('ROW', $data);

    $xtpl->parse('main');
    return $xtpl->text('main');
}




/**
 * Lấy dữ liệu 1 món ăn theo ID
 */
function menu_get_item($table, $id)
{
    global $db;
    $stmt = $db->query('SELECT * FROM ' . $table . ' WHERE item_id=' . intval($id));
    return $stmt->fetch();
}

/**
 * Thêm hoặc cập nhật món ăn
 */
function menu_save_item($table, $id, $data)
{
    global $db;

    if ($id) {
        $sql = 'UPDATE ' . $table . ' 
                SET category_id=:cat, name=:name, description=:des, price=:price, image_url=:img, status=:st 
                WHERE item_id=' . intval($id);
    } else {
        $sql = 'INSERT INTO ' . $table . ' (category_id, name, description, price, image_url, status) 
                VALUES (:cat, :name, :des, :price, :img, :st)';
    }

    $sth = $db->prepare($sql);
    $sth->bindParam(':cat', $data['category_id'], PDO::PARAM_INT);
    $sth->bindParam(':name', $data['name'], PDO::PARAM_STR);
    $sth->bindParam(':des', $data['description'], PDO::PARAM_STR);
    $sth->bindParam(':price', $data['price']);
    $sth->bindParam(':img', $data['image_url'], PDO::PARAM_STR);
    $sth->bindParam(':st', $data['status'], PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Xóa món ăn
 */
function menu_delete_item($table, $id)
{
    global $db;
    if ($id > 0) {
        $db->query('DELETE FROM ' . $table . ' WHERE item_id=' . intval($id));
        return true;
    }
    return false;
}

/**
 * Lấy danh sách món ăn
 */
function menu_get_items($table, $cat_table)
{
    global $db;

    $sql = 'SELECT i.item_id, i.name, i.price, i.status, i.image_url, c.name AS cat_name
            FROM ' . $table . ' i
            LEFT JOIN ' . $cat_table . ' c ON i.category_id=c.category_id
            ORDER BY i.item_id DESC';
    $stmt = $db->query($sql);

    $list = [];
    $status_map = ['con' => 'Còn', 'het' => 'Hết'];
    while ($row = $stmt->fetch()) {
        $row['price_format'] = number_format($row['price'], 0, ',', '.') . ' đ';
        $row['status_text'] = isset($status_map[$row['status']]) ? $status_map[$row['status']] : $row['status'];
        $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $GLOBALS['module_name'] . '&op=form_menu&id=' . $row['item_id'];
        $row['del_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $GLOBALS['module_name'] . '&op=menu&del=' . $row['item_id'] .
            '&checkss=' . md5($row['item_id'] . NV_CHECK_SESSION);

        $list[] = $row;
    }

    return $list;
}

/**
 * Render danh sách món ăn
 */
function menu_render_list($list)
{
    global $module_file, $global_config;

    $xtpl = new XTemplate('menu.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    foreach ($list as $row) {
        $xtpl->assign('ROW', $row);

        // Parse ảnh nếu có cho từng món
        if (!empty($row['image_url'])) {
            $xtpl->parse('main.loop.image_block');
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->assign('ADD_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $GLOBALS['module_name'] . '&op=form_menu');

    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * Render form thêm/sửa món ăn
 */
function menu_render_form($data, $cats)
{
    global $module_file, $global_config;

    $xtpl = new XTemplate('form_menu.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    $xtpl->assign('ROW', $data);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $GLOBALS['module_name']);

    // Parse danh mục
    foreach ($cats as $c) {
    $xtpl->assign('CAT_ID', $c['category_id']);
    $xtpl->assign('CAT_NAME', $c['name']);
    $xtpl->assign('CAT_SEL', ($data['category_id'] == $c['category_id']) ? 'selected' : '');
    $xtpl->parse('main.cat');
}


    // Trạng thái
    foreach (['con' => 'Còn', 'het' => 'Hết'] as $val => $text) {
    $xtpl->assign('OPT_VAL', $val);
    $xtpl->assign('OPT_TEXT', $text);
    $xtpl->assign('OPT_SEL', ($data['status'] == $val) ? 'selected' : '');
    $xtpl->parse('main.status');
}

    // Parse ảnh nếu có
    if (!empty($data['image_url'])) {
        $xtpl->parse('main.image_block');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}