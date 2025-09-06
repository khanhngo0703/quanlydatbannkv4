<?php

if (!defined('NV_IS_MOD_RESTAURANT')) die('Stop!!!');

$error = '';
$success = '';

if ($nv_Request->get_string('submit', 'post') == '1') {
    $username   = $nv_Request->get_string('username', 'post', '');
    $email      = $nv_Request->get_string('email', 'post', '');
    $password   = $nv_Request->get_string('password', 'post', '');
    $repassword = $nv_Request->get_string('repassword', 'post', '');

    // Gọi hàm xử lý đăng ký từ functions.php
    $result = restaurant_register_user($db, $username, $email, $password, $repassword);
    if ($result['success']) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=login');
    } else {
        $error = $result['message'];
    }
}

// Khởi tạo template
$xtpl = new XTemplate('register.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('ERROR', $error);
$xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=register');

// Parse và hiển thị
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
