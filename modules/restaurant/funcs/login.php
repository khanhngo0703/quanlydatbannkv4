<?php

if (!defined('NV_IS_MOD_RESTAURANT')) die('Stop!!!');

$error = '';

if ($nv_Request->get_string('submit', 'post') == '1') {
    $username = $nv_Request->get_string('username', 'post', '');
    $password = $nv_Request->get_string('password', 'post', '');

    // Gọi hàm từ functions.php
    if (restaurant_login_user($db, $username, $password)) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name);
    } else {
        $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
    }
}

// Khởi tạo template
$xtpl = new XTemplate('login.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('ERROR', $error);
$xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=login');

// Parse và hiển thị
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
