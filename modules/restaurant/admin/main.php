<?php

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['admin_main'];
$op = $nv_Request->get_string('op', 'get', 'dashboard');

switch ($op) {
    case 'tables':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/tables.php';
        break;
    case 'form_table':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/form_table.php';
        break;
    case 'categories':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/categories.php';
        break;
    case 'form_categories':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/form_categories.php';
        break;
    case 'menu':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/menu.php';
        break;
    case 'form_menu':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/form_menu.php';
        break;
    case 'reservations':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/reservations.php';
        break;
    case 'form_reservation':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/form_reservation.php';
        break;
    case 'orders':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/orders.php';
        break;
    case 'form_order':
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/form_order.php';
        break;

    default:
        $xtpl = new XTemplate(
            'main.tpl',
            NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
        );
        $xtpl->assign('LANG', $lang_module);

        // Thống kê tổng
        $total_tables = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tables')->fetchColumn();
        $total_menu = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_menu_items')->fetchColumn();
        $total_reservations = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reservations')->fetchColumn();
        $total_orders = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders')->fetchColumn();
        $total_revenue = $db->query('SELECT SUM(total_amount) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders')->fetchColumn();
        $total_revenue = $total_revenue ? number_format($total_revenue, 0, ',', '.') . ' ₫' : '0 ₫';
        $total_customers = $db->query('SELECT COUNT(DISTINCT user_id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders')->fetchColumn();

        $xtpl->assign('TOTAL_TABLES', intval($total_tables));
        $xtpl->assign('TOTAL_MENU', intval($total_menu));
        $xtpl->assign('TOTAL_RESERVATIONS', intval($total_reservations));
        $xtpl->assign('TOTAL_ORDERS', intval($total_orders));
        $xtpl->assign('TOTAL_REVENUE', $total_revenue);
        $xtpl->assign('TOTAL_CUSTOMERS', intval($total_customers));

        // Lấy dữ liệu đơn hàng & doanh thu theo tháng (12 tháng gần nhất)
        $months = range(1, 12);
        $ordersData = [];
        $revenueData = [];

        foreach ($months as $i) {
            $orders = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders 
            WHERE MONTH(FROM_UNIXTIME(order_date)) = $i AND YEAR(FROM_UNIXTIME(order_date)) = YEAR(CURDATE())")->fetchColumn();
            $ordersData[] = intval($orders);

            $revenue = $db->query("SELECT SUM(total_amount) FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders 
            WHERE MONTH(FROM_UNIXTIME(order_date)) = $i AND YEAR(FROM_UNIXTIME(order_date)) = YEAR(CURDATE())")->fetchColumn();
            $revenueData[] = $revenue ? round($revenue) : 0;
        }

        $xtpl->assign('MONTH_LABELS', json_encode($months));
        $xtpl->assign('ORDERS_DATA', json_encode($ordersData));
        $xtpl->assign('REVENUE_DATA', json_encode($revenueData));

        // Đơn hàng mới nhất
        $sql = 'SELECT o.order_id, o.user_id, u.username, o.total_amount, o.payment_status
                FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders o
                LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON o.user_id = u.userid
                ORDER BY o.order_id DESC 
                LIMIT 5';
        $result = $db->query($sql);


        $map = [
            'cho_xac_nhan'  => 'Chờ xác nhận',
            'da_thanh_toan' => 'Đã thanh toán',
            'huy'           => 'Hủy'
        ];

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch()) {
                $statusText = isset($map[$row['payment_status']])
                    ? $map[$row['payment_status']]
                    : 'Không xác định';

                $xtpl->assign('ORDER_ID', $row['order_id']);
                $xtpl->assign('USERNAME', !empty($row['username']) ? $row['username'] : 'Khách vãng lai');
                $xtpl->assign('TOTAL_PRICE', number_format($row['total_amount'], 0, ',', '.') . ' ₫');
                $xtpl->assign('STATUS', $statusText);
                $xtpl->parse('main.order');
            }
        } else {
            $xtpl->parse('main.no_order');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
        break;
}
