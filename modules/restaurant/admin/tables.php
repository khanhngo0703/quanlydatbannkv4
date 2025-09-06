<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = 'Quản lý bàn ăn';
$table = NV_PREFIXLANG . '_' . $module_data . '_tables';

/* Xóa */
$del_id = $nv_Request->get_int('del', 'get', 0);
$checkss = $nv_Request->get_title('checkss', 'get', '');
if ($del_id > 0 && $checkss === md5($del_id . NV_CHECK_SESSION)) {
    if (restaurant_delete_table($table, $del_id)) {
        nv_htmlOutput('OK');
    }
}

/* Lấy danh sách */
$list = restaurant_get_tables($table);
$contents = restaurant_render_tables($list);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
