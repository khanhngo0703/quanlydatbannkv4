<?php

if (!defined('NV_IS_MOD_RESTAURANT')) die('Stop!!!');

// Kiểm tra login
$user_logged_in = !empty($_SESSION['restaurant_user_id']);
if (!$user_logged_in) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=login');
}

// URL trang reservation
$list_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=reservation';

// Lấy flash message nếu có (tương thích PHP < 7)
$error = isset($_SESSION['restaurant_error_msg']) ? $_SESSION['restaurant_error_msg'] : '';
$success = isset($_SESSION['restaurant_success_msg']) ? $_SESSION['restaurant_success_msg'] : '';
$last_reservation_id = isset($_SESSION['restaurant_last_reservation_id']) ? $_SESSION['restaurant_last_reservation_id'] : 0;

// Xóa session sau khi lấy
unset($_SESSION['restaurant_error_msg'], $_SESSION['restaurant_success_msg'], $_SESSION['restaurant_last_reservation_id']);


// Xử lý submit form
if ($nv_Request->get_string('submit', 'post') == '1') {
    $table_id = $nv_Request->get_int('table_id', 'post', 0);
    $reservation_date = $nv_Request->get_string('reservation_date', 'post', '');
    $reservation_time = $nv_Request->get_string('reservation_time', 'post', '');

    $result = restaurant_create_reservation($db, $_SESSION['restaurant_user_id'], $table_id, $reservation_date, $reservation_time);

    if ($result['success']) {
        $_SESSION['restaurant_success_msg'] = $result['message'];
        $_SESSION['restaurant_last_reservation_id'] = $result['reservation_id'];
    } else {
        $_SESSION['restaurant_error_msg'] = $result['message'];
    }

    nv_redirect_location($list_url);
}

// Lấy danh sách bàn
$tables = restaurant_get_tables($db);

// Giao diện
$xtpl = new XTemplate('reservation.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('ERROR', $error);
$xtpl->assign('SUCCESS', $success);
$xtpl->assign('FORM_ACTION', $list_url);
$xtpl->assign('LAST_RESERVATION_ID', $last_reservation_id);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);

// Nếu có thông báo -> parse block
if (!empty($error)) {
    $xtpl->parse('main.error');
}
if (!empty($success)) {
    $xtpl->parse('main.success');
    if ($last_reservation_id) {
        $xtpl->parse('main.success_action');
    }
}

// Parse danh sách bàn
foreach ($tables as $table) {
    switch ($table['status']) {
        case 'trong':
            $borderClass = 'border-success';
            $badgeClass = 'bg-success';
            $disabled = '';
            $statusText = 'trống';
            break;
        case 'dat_truoc':
            $borderClass = 'border-warning';
            $badgeClass = 'bg-warning';
            $disabled = 'disabled';
            $statusText = 'đã đặt trước';
            break;
        case 'dang_su_dung':
            $borderClass = 'border-danger';
            $badgeClass = 'bg-danger';
            $disabled = 'disabled';
            $statusText = 'đang sử dụng';
            break;
        case 'bao_tri':
            $borderClass = 'border-secondary';
            $badgeClass = 'bg-secondary';
            $disabled = 'disabled';
            $statusText = 'bảo trì';
            break;
        default:
            $borderClass = 'border-secondary';
            $badgeClass = 'bg-secondary';
            $disabled = 'disabled';
            $statusText = $table['status'];
            break;
    }

    $xtpl->assign('TABLE_ID', $table['table_id']);
    $xtpl->assign('TABLE_NAME', $table['table_name']);
    $xtpl->assign('TABLE_CAPACITY', $table['capacity']);
    $xtpl->assign('TABLE_LOCATION', !empty($table['location']) ? $table['location'] : '—');
    $xtpl->assign('TABLE_BORDER_CLASS', $borderClass);
    $xtpl->assign('TABLE_BADGE_CLASS', $badgeClass);
    $xtpl->assign('TABLE_STATUS_TEXT', $statusText);
    $xtpl->assign('TABLE_DISABLED', $disabled);
    $xtpl->parse('main.table_item');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
