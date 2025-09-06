<?php
if (!defined('NV_IS_MOD_RESTAURANT')) die('Stop!!!');

$op = $nv_Request->get_string('op', 'get', 'main');
$page_title = 'Nhà hàng - Trang chủ';

// Xử lý logout
if ($op === 'logout') {
    restaurant_logout($module_name);
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

// Kiểm tra session frontend
$user_logged_in = restaurant_is_logged_in();
$urls = restaurant_get_urls($module_name);

if ($user_logged_in) {
    $xtpl->assign('USERNAME', restaurant_get_username());
    $xtpl->assign('RESERVATION_URL', $urls['reservation_url']);
    $xtpl->assign('ORDER_URL', $urls['order_url']);
    $xtpl->assign('LOGOUT_URL', $urls['logout_url']);
    $xtpl->parse('main.user');
} else {
    $xtpl->assign('LOGIN_URL', $urls['login_url']);
    $xtpl->assign('REGISTER_URL', $urls['register_url']);
    $xtpl->parse('main.guest');
}

// Parse main
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
