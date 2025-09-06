<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = 'Thêm/Sửa bàn ăn';
$table = NV_PREFIXLANG . '_' . $module_data . '_tables';

$id = $nv_Request->get_int('id', 'get,post', 0);
$data = [
    'table_id' => $id,
    'table_name' => '',
    'capacity' => 4,
    'location' => '',
    'status' => 'trong'
];

// Nếu có id => lấy dữ liệu từ DB
if ($id > 0) {
    $row = restaurant_get_table($table, $id);
    if ($row) {
        $data = $row;
    } else {
        // Nếu không tồn tại id thì quay về danh sách
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=tables');
    }
}

// Nếu submit form
if ($nv_Request->get_int('save', 'post', 0)) {
    $data['table_name'] = $nv_Request->get_title('table_name', 'post', '');
    $data['capacity']   = $nv_Request->get_int('capacity', 'post', 0);
    $data['location']   = $nv_Request->get_title('location', 'post', '');
    $data['status']     = $nv_Request->get_title('status', 'post', 'trong');

    restaurant_save_table($table, $id, $data);

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=tables');
}

// Render form
$contents = restaurant_render_form_table($data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
