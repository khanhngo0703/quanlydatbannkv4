<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

// require_once 'admin.functions.php';

$page_title = 'Thêm/Sửa món ăn';
$table = NV_PREFIXLANG . '_' . $module_data . '_menu_items';
$cat_table = NV_PREFIXLANG . '_' . $module_data . '_menu_categories';

$id = $nv_Request->get_int('id', 'get,post', 0);

// Lấy dữ liệu món ăn
$data = $id ? menu_get_item($table, $id) : [
    'item_id' => 0,
    'category_id' => 0,
    'name' => '',
    'description' => '',
    'price' => 0,
    'image_url' => '',
    'status' => 'con'
];

// Lưu dữ liệu khi submit form
if ($nv_Request->get_int('save', 'post', 0)) {
    $data['category_id'] = $nv_Request->get_int('category_id', 'post', 0);
    $data['name'] = $nv_Request->get_title('name', 'post', '');
    $data['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);
    $data['price'] = (float) $nv_Request->get_title('price', 'post', '0');
    $data['status'] = $nv_Request->get_title('status', 'post', 'con');

    // Upload ảnh
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = NV_ROOTDIR . '/uploads/' . $module_name;
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $filename = basename($_FILES['image_file']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $target_path = $upload_dir . '/' . $new_filename;

            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
                $data['image_url'] = NV_BASE_SITEURL . 'uploads/' . $module_name . '/' . $new_filename;
            }
        }
    }

    // Gọi hàm lưu món ăn
    menu_save_item($table, $id, $data);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=menu');
}

// Lấy danh mục món ăn
$cats = $db->query('SELECT category_id, name FROM ' . $cat_table . ' ORDER BY name ASC')->fetchAll();

// Render form
$contents = menu_render_form($data, $cats);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
