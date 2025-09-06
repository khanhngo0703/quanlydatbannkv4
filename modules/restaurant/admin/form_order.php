<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = 'Thêm/Sửa đơn đặt món';
$table = NV_PREFIXLANG . '_' . $module_data . '_orders';
$item_table = NV_PREFIXLANG . '_' . $module_data . '_menu_items';
$res_table = NV_PREFIXLANG . '_' . $module_data . '_reservations';
$detail_table = NV_PREFIXLANG . '_' . $module_data . '_order_items';

$id = $nv_Request->get_int('id', 'get,post', 0);
$data = [
    'order_id' => $id,
    'reservation_id' => 0,
    'user_id' => 0,
    'order_date' => NV_CURRENTTIME,
    'payment_status' => 'cho_xac_nhan',
    'total_amount' => 0
];
$items_cart = []; // mảng chi tiết {item_id, quantity, price}

if ($id) {
    $data = $db->query('SELECT * FROM ' . $table . ' WHERE order_id=' . $id)->fetch();
    if (!$data) nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders');
    $items_cart = $db->query('SELECT * FROM ' . $detail_table . ' WHERE order_id=' . $id)->fetchAll();
}

if ($nv_Request->get_int('save', 'post', 0)) {
    $data['reservation_id'] = $nv_Request->get_int('reservation_id', 'post', 0);
    $data['user_id'] = $nv_Request->get_int('user_id', 'post', 0);
    $data['payment_status'] = $nv_Request->get_title('payment_status', 'post', 'cho_xac_nhan');

    if ($id) {
        $sql = 'UPDATE ' . $table . ' SET reservation_id=:rid, user_id=:uid, payment_status=:ps WHERE order_id=' . $id;
    } else {
        $sql = 'INSERT INTO ' . $table . ' (reservation_id, user_id, order_date, total_amount, payment_status)
                VALUES (:rid, :uid, ' . NV_CURRENTTIME . ', 0, :ps)';
    }
    $sth = $db->prepare($sql);
    $sth->bindParam(':rid', $data['reservation_id'], PDO::PARAM_INT);
    $sth->bindParam(':uid', $data['user_id'], PDO::PARAM_INT);
    $sth->bindParam(':ps', $data['payment_status'], PDO::PARAM_STR);
    $sth->execute();

    if (!$id) {
        $id = $db->lastInsertId();
    }

    // cập nhật chi tiết (đơn giản: xóa cũ, thêm mới)
    $db->query('DELETE FROM ' . $detail_table . ' WHERE order_id=' . intval($id));
    $post_items = $nv_Request->get_array('item_id', 'post', []);
    $post_qty   = $nv_Request->get_array('quantity', 'post', []);
    $post_price = $nv_Request->get_array('price', 'post', []);

    $total = 0;
    for ($i = 0; $i < count($post_items); $i++) {
        $it = intval($post_items[$i]);
        $qty = max(1, intval($post_qty[$i]));
        $price = (float)$post_price[$i];

        if ($it > 0) {
            $db->query('INSERT INTO ' . $detail_table . ' (order_id, item_id, quantity, price) VALUES (' . intval($id) . ', ' . $it . ', ' . $qty . ', ' . $price . ')');
            $total += $qty * $price;
        }
    }
    $db->query('UPDATE ' . $table . ' SET total_amount=' . $total . ' WHERE order_id=' . intval($id));

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=orders');
}

// dữ liệu hiển thị
$reservations = $db->query('SELECT reservation_id FROM ' . $res_table . ' ORDER BY reservation_id DESC')->fetchAll();
$menu_items = $db->query('SELECT item_id, name, price FROM ' . $item_table . ' ORDER BY name ASC')->fetchAll();

$xtpl = new XTemplate('form_order.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('ROW', $data);

foreach ($reservations as $r) {
    $xtpl->assign('RES', ['id' => $r['reservation_id'], 'sel' => ($data['reservation_id'] == $r['reservation_id']) ? 'selected' : '']);
    $xtpl->parse('main.res');
}

foreach (['cho_xac_nhan' => 'Chờ xác nhận', 'da_thanh_toan' => 'Đã thanh toán', 'huy' => 'Hủy'] as $v => $txt) {
    $xtpl->assign('OPT', ['val' => $v, 'text' => $txt, 'sel' => ($data['payment_status'] == $v) ? 'selected' : '']);
    $xtpl->parse('main.status');
}

if (empty($items_cart)) {
    // 1 dòng trống để thêm
    $items_cart[] = ['item_id' => 0, 'quantity' => 1, 'price' => 0];
}
foreach ($items_cart as $line) {
    $xtpl->assign('LINE', $line);
    // list món
    foreach ($menu_items as $mi) {
        $xtpl->assign('MI', [
            'id' => $mi['item_id'],
            'name' => $mi['name'],
            'sel' => ($line['item_id'] == $mi['item_id']) ? 'selected' : ''
        ]);
        $xtpl->parse('main.detail.loop_item.mi');
    }
    $xtpl->parse('main.detail.loop_item');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
