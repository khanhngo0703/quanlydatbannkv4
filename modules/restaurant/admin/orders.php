<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = 'Quản lý đơn đặt món';

$table       = NV_PREFIXLANG . '_' . $module_data . '_orders';
$res_table   = NV_PREFIXLANG . '_' . $module_data . '_reservations';
$tbl_tables  = NV_PREFIXLANG . '_' . $module_data . '_tables';

/* Xóa đơn */
$del_id = $nv_Request->get_int('del', 'get', 0);
$checkss = $nv_Request->get_title('checkss', 'get', '');
$list_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders';

if ($del_id > 0 && $checkss === md5($del_id . NV_CHECK_SESSION)) {
    // Xóa chi tiết order_items
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_order_items WHERE order_id=' . intval($del_id));
    // Xóa order
    $db->query('DELETE FROM ' . $table . ' WHERE order_id=' . intval($del_id));

    // Set flash và redirect về danh sách
    $_SESSION['restaurant_admin_msg'] = 'Xóa đơn đặt món thành công!';
    nv_redirect_location($list_url);
}

/* Lấy flash message nếu có */
$success = '';
if (!empty($_SESSION['restaurant_admin_msg'])) {
    $success = $_SESSION['restaurant_admin_msg'];
    unset($_SESSION['restaurant_admin_msg']);
}

/* Xác nhận đơn */
$confirm_id = $nv_Request->get_int('confirm', 'get', 0);
$checkss = $nv_Request->get_title('checkss', 'get', '');
if ($confirm_id > 0 && $checkss === md5($confirm_id . NV_CHECK_SESSION)) {
    $sql = "UPDATE " . $table . " 
            SET payment_status='da_thanh_toan' 
            WHERE order_id=" . intval($confirm_id);
    $db->query($sql);

    $_SESSION['restaurant_admin_msg'] = 'Xác nhận thanh toán đơn thành công!';
    nv_redirect_location($list_url);
}

/* Danh sách */
/* Danh sách */
$sql = 'SELECT o.*, r.table_id, t.table_name, u.username 
        FROM ' . $table . ' o
        LEFT JOIN ' . $res_table . ' r ON o.reservation_id = r.reservation_id
        LEFT JOIN ' . $tbl_tables . ' t ON r.table_id = t.table_id
        LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON o.user_id = u.userid
        ORDER BY o.order_id DESC';
$stmt = $db->query($sql);


$xtpl = new XTemplate('orders.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$map = [
    'cho_xac_nhan'   => 'Chờ xác nhận',
    'da_thanh_toan'  => 'Đã thanh toán',
    'huy'            => 'Hủy'
];

// Nếu có flash success
if (!empty($success)) {
    $xtpl->assign('SUCCESS', $success);
    $xtpl->parse('main.success');
}

while ($row = $stmt->fetch()) {
    $row['total_format'] = number_format($row['total_amount'], 0, ',', '.') . ' đ';
    $row['status_text']  = isset($map[$row['payment_status']]) ? $map[$row['payment_status']] : $row['payment_status'];
    $row['time']         = nv_date('d/m/Y H:i', $row['order_date']);
    $row['username']     = !empty($row['username']) ? $row['username'] : ('User#' . $row['user_id']);
    
    $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=form_order&id=' . $row['order_id'];

    $row['del_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders&del=' . $row['order_id'] .
        '&checkss=' . md5($row['order_id'] . NV_CHECK_SESSION);

    $row['detail_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=order_detail&id=' . $row['order_id'];

    // Nếu trạng thái là "chờ xác nhận" thì gán url nút xác nhận
    if ($row['payment_status'] == 'cho_xac_nhan') {
        $row['confirm_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
            '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders&confirm=' . $row['order_id'] .
            '&checkss=' . md5($row['order_id'] . NV_CHECK_SESSION);
    } else {
        $row['confirm_url'] = '';
    }

    $xtpl->assign('ROW', $row);

    if (!empty($row['confirm_url'])) {
        $xtpl->parse('main.loop.confirm');
    }

    $xtpl->parse('main.loop');
}

$xtpl->assign('ADD_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
    '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=form_order');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
