<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = 'Chi tiết đơn đặt món';

$order_id = $nv_Request->get_int('id', 'get', 0);
if ($order_id <= 0) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders');
}

$table       = NV_PREFIXLANG . '_' . $module_data . '_orders';
$res_table   = NV_PREFIXLANG . '_' . $module_data . '_reservations';
$tbl_tables  = NV_PREFIXLANG . '_' . $module_data . '_tables';
$items_table = NV_PREFIXLANG . '_' . $module_data . '_order_items';
$menu_table  = NV_PREFIXLANG . '_' . $module_data . '_menu_items'; // bảng món ăn

// Lấy thông tin đơn hàng
$sql = 'SELECT o.*, r.table_id, t.table_name 
        FROM ' . $table . ' o
        LEFT JOIN ' . $res_table . ' r ON o.reservation_id = r.reservation_id
        LEFT JOIN ' . $tbl_tables . ' t ON r.table_id = t.table_id
        WHERE o.order_id=' . $order_id;
$order = $db->query($sql)->fetch();

if (empty($order)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders');
}

$map = [
    'cho_xac_nhan'   => 'Chờ xác nhận',
    'da_thanh_toan'  => 'Đã thanh toán',
    'huy'            => 'Hủy'
];

$order['status_text']  = isset($map[$order['payment_status']]) ? $map[$order['payment_status']] : $order['payment_status'];
$order['time']         = nv_date('d/m/Y H:i', $order['order_date']);
$order['total_format'] = number_format($order['total_amount'], 0, ',', '.') . ' đ';

// Lấy chi tiết món trong đơn
$sql = 'SELECT i.*, m.name AS item_name 
        FROM ' . $items_table . ' i
        LEFT JOIN ' . $menu_table . ' m ON i.item_id = m.item_id
        WHERE i.order_id=' . $order_id;
$result = $db->query($sql);

$items = [];
while ($row = $result->fetch()) {
    $row['price_format'] = number_format($row['price'], 0, ',', '.') . ' đ';
    $row['total_format'] = number_format($row['price'] * $row['quantity'], 0, ',', '.') . ' đ';
    $items[] = $row;
}

// Gán dữ liệu cho template
$xtpl = new XTemplate('order_detail.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$xtpl->assign('ORDER', $order);

foreach ($items as $it) {
    $xtpl->assign('ITEM', $it);
    $xtpl->parse('main.items');
}

$xtpl->assign('BACK_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
    '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
