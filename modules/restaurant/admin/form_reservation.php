<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = 'Thêm/Sửa đặt bàn';
$table = NV_PREFIXLANG . '_' . $module_data . '_reservations';
$tbl_tables = NV_PREFIXLANG . '_' . $module_data . '_tables';

$id = $nv_Request->get_int('id', 'get,post', 0);
$data = [
    'reservation_id' => $id,
    'user_id' => 0,
    'table_id' => 0,
    'reservation_date' => date('Y-m-d'),
    'reservation_time' => '18:00:00',
    'status' => 'cho_duyet',
    'created_at' => NV_CURRENTTIME
];

if ($id) {
    $data = $db->query('SELECT * FROM ' . $table . ' WHERE reservation_id=' . $id)->fetch();
    if (!$data) nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservations');
}

if ($nv_Request->get_int('save', 'post', 0)) {
    $data['user_id'] = $nv_Request->get_int('user_id', 'post', 0);
    $data['table_id'] = $nv_Request->get_int('table_id', 'post', 0);
    $data['reservation_date'] = $nv_Request->get_title('reservation_date', 'post', date('Y-m-d'));
    $data['reservation_time'] = $nv_Request->get_title('reservation_time', 'post', '18:00:00');
    $data['status'] = $nv_Request->get_title('status', 'post', 'cho_duyet');

    if ($id) {
        $sql = 'UPDATE ' . $table . ' SET user_id=:uid, table_id=:tid, reservation_date=:d, reservation_time=:t, status=:st WHERE reservation_id=' . $id;
    } else {
        $sql = 'INSERT INTO ' . $table . ' (user_id, table_id, reservation_date, reservation_time, status, created_at)
                VALUES (:uid, :tid, :d, :t, :st, ' . NV_CURRENTTIME . ')';
    }
    $sth = $db->prepare($sql);
    $sth->bindParam(':uid', $data['user_id'], PDO::PARAM_INT);
    $sth->bindParam(':tid', $data['table_id'], PDO::PARAM_INT);
    $sth->bindParam(':d', $data['reservation_date'], PDO::PARAM_STR);
    $sth->bindParam(':t', $data['reservation_time'], PDO::PARAM_STR);
    $sth->bindParam(':st', $data['status'], PDO::PARAM_STR);
    $sth->execute();

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservations');
}

$tables = $db->query('SELECT table_id, table_name FROM ' . $tbl_tables . ' ORDER BY table_name ASC')->fetchAll();

$xtpl = new XTemplate('form_reservation.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('ROW', $data);

foreach ($tables as $t) {
    $xtpl->assign('TB', ['id' => $t['table_id'], 'name' => $t['table_name'], 'sel' => ($data['table_id'] == $t['table_id']) ? 'selected' : '']);
    $xtpl->parse('main.table');
}
foreach (['cho_duyet' => 'Chờ duyệt', 'da_duyet' => 'Đã duyệt', 'da_huy' => 'Đã hủy', 'hoan_thanh' => 'Hoàn thành'] as $v => $txt) {
    $xtpl->assign('OPT', ['val' => $v, 'text' => $txt, 'sel' => ($data['status'] == $v) ? 'selected' : '']);
    $xtpl->parse('main.status');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
