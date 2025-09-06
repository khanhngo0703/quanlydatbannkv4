<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$table = NV_PREFIXLANG . '_' . $module_data . '_menu_categories';
$page_title = "Thêm / Sửa danh mục thực đơn";

$id = $nv_Request->get_int('id', 'get,post', 0);

// Lấy dữ liệu cũ
$data = [
    'category_id' => 0,
    'name' => '',
    'description' => ''
];

if ($id > 0) {
    $data = restaurant_get_category($table, $id);
}

// Lưu dữ liệu
if ($nv_Request->isset_request('save', 'post')) {
    $data['name'] = $nv_Request->get_title('name', 'post', '');
    $data['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);

    restaurant_save_category($table, $id, $data);

    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA .
        "&" . NV_NAME_VARIABLE . "=" . $module_name . "&op=categories");
}

// Render form
$contents = restaurant_render_form_category($data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
