<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

// require_once 'admin.functions.php';

$page_title = 'Quản lý thực đơn';
$table = NV_PREFIXLANG . '_' . $module_data . '_menu_items';
$cat_table = NV_PREFIXLANG . '_' . $module_data . '_menu_categories';

// Xóa món ăn
$del_id = $nv_Request->get_int('del', 'get', 0);
$checkss = $nv_Request->get_title('checkss', 'get', '');
if ($del_id > 0 && $checkss === md5($del_id . NV_CHECK_SESSION)) {
    menu_delete_item($table, $del_id);
    nv_htmlOutput('OK');
}

// Lấy danh sách món ăn
$list = menu_get_items($table, $cat_table);

// Render danh sách
$contents = menu_render_list($list);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
