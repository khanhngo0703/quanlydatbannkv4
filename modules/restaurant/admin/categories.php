<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$table = NV_PREFIXLANG . '_' . $module_data . '_menu_categories';
$page_title = "Quản lý danh mục thực đơn";

// Xóa danh mục
$id = $nv_Request->get_int('del', 'get', 0);
$checkss = $nv_Request->get_string('checkss', 'get', '');
if ($id > 0 && $checkss == md5($id . NV_CHECK_SESSION)) {
    restaurant_delete_category($table, $id);
    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA .
        "&" . NV_NAME_VARIABLE . "=" . $module_name . "&op=categories");
}

// Lấy danh sách
$list = restaurant_get_categories($table);

// Render danh sách
$contents = restaurant_render_categories($list);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
