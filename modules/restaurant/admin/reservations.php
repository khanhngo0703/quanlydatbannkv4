<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = 'Quản lý đặt bàn';
$table = NV_PREFIXLANG . '_' . $module_data . '_reservations';
$tbl_tables = NV_PREFIXLANG . '_' . $module_data . '_tables';

// URL danh sách (không có param hành động)
$list_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
    '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservations';

// Lấy flash message nếu có
$success = '';
if (!empty($_SESSION['restaurant_admin_msg'])) {
    $success = $_SESSION['restaurant_admin_msg'];
    unset($_SESSION['restaurant_admin_msg']);
}

/* Xóa */
$del_id = $nv_Request->get_int('del', 'get', 0);
$checkss = $nv_Request->get_title('checkss', 'get', '');
if ($del_id > 0 && $checkss === md5($del_id . NV_CHECK_SESSION)) {

    // Lấy table_id trước khi xóa
    $row_res = $db->query("SELECT table_id FROM " . $table . " WHERE reservation_id=" . intval($del_id))->fetch();

    if ($row_res && !empty($row_res['table_id'])) {
        // Cập nhật bàn thành "trong"
        $db->query("UPDATE " . $tbl_tables . " SET status='trong' WHERE table_id=" . intval($row_res['table_id']));
    }

    // Xóa đơn đặt bàn
    $db->query('DELETE FROM ' . $table . ' WHERE reservation_id=' . intval($del_id));

    // Set flash và redirect về danh sách
    $_SESSION['restaurant_admin_msg'] = 'Xóa đặt bàn thành công!';
    nv_redirect_location($list_url);
}


/* Phê duyệt */
$approve_id = $nv_Request->get_int('approve', 'get', 0);
if ($approve_id > 0 && $checkss === md5($approve_id . NV_CHECK_SESSION)) {
    // Cập nhật trạng thái của đơn đặt bàn
    $db->query("UPDATE " . $table . " SET status='da_duyet' WHERE reservation_id=" . intval($approve_id));

    // Lấy ra table_id của đơn này
    $row_res = $db->query("SELECT table_id FROM " . $table . " WHERE reservation_id=" . intval($approve_id))->fetch();

    if ($row_res && !empty($row_res['table_id'])) {
        // Cập nhật trạng thái bàn về "đặt trước"
        $db->query("UPDATE " . $tbl_tables . " SET status='dat_truoc' WHERE table_id=" . intval($row_res['table_id']));
    }

    // Set flash và redirect về danh sách (không còn param approve)
    $_SESSION['restaurant_admin_msg'] = 'Phê duyệt đặt bàn thành công!';
    nv_redirect_location($list_url);
}

/* Danh sách */
$sql = 'SELECT r.*, t.table_name, u.username
        FROM ' . $table . ' r 
        LEFT JOIN ' . $tbl_tables . ' t ON r.table_id = t.table_id
        LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON r.user_id = u.userid
        ORDER BY r.reservation_id DESC';
$stmt = $db->query($sql);

$xtpl = new XTemplate('reservations.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$status_map = [
    'cho_duyet' => 'Chờ duyệt',
    'da_duyet' => 'Đã duyệt',
    'da_huy' => 'Đã hủy',
    'hoan_thanh' => 'Hoàn thành'
];

// nếu có flash success, truyền vào template và parse block
if (!empty($success)) {
    $xtpl->assign('SUCCESS', $success);
    $xtpl->parse('main.success');
}

while ($row = $stmt->fetch()) {
    $row['status_text'] = isset($status_map[$row['status']]) ? $status_map[$row['status']] : $row['status'];
    $row['datetime'] = nv_date('d/m/Y', $row['created_at']);
    $row['reserve_dt'] = $row['reservation_date'] . ' ' . $row['reservation_time'];
    $row['username']     = !empty($row['username']) ? $row['username'] : ('User#' . $row['user_id']);


    // url hành động (approve / del)
    $row['approve_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservations&approve=' . intval($row['reservation_id']) .
        '&checkss=' . md5($row['reservation_id'] . NV_CHECK_SESSION);

    $row['del_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
        '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservations&del=' . intval($row['reservation_id']) .
        '&checkss=' . md5($row['reservation_id'] . NV_CHECK_SESSION);

    $xtpl->assign('ROW', $row);

    // Nếu trạng thái là "chờ duyệt" thì parse nút phê duyệt (inner block)
    if ($row['status'] == 'cho_duyet') {
        $xtpl->parse('main.loop.approve');
    }

    $xtpl->parse('main.loop');
}

$xtpl->assign('ADD_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA .
    '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=form_reservation');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
