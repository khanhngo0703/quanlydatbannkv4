<!-- BEGIN: main -->
<div class="container mt-4">
    <h1 class="mb-4">{LANG.dashboard}</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.total_tables}</h5>
                    <p class="card-text fs-4 fw-bold">{TOTAL_TABLES}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.total_menu}</h5>
                    <p class="card-text fs-4 fw-bold">{TOTAL_MENU}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.total_reservations}</h5>
                    <p class="card-text fs-4 fw-bold">{TOTAL_RESERVATIONS}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.total_orders}</h5>
                    <p class="card-text fs-4 fw-bold">{TOTAL_ORDERS}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.total_revenue}</h5>
                    <p class="card-text fs-4 fw-bold">{TOTAL_REVENUE}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.total_customers}</h5>
                    <p class="card-text fs-4 fw-bold">{TOTAL_CUSTOMERS}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row mb-5">
        <!-- Biểu đồ tròn đơn hàng -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.chart_orders}</h5>
                    <canvas id="ordersChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ cột doanh thu -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{LANG.chart_revenue}</h5>
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-3">{LANG.latest_orders}</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>{LANG.order_id}</th>
                <th>{LANG.customer}</th>
                <th>{LANG.total}</th>
                <th>{LANG.status}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: order -->
            <tr>
                <td>{ORDER_ID}</td>
                <td>{USERNAME}</td>
                <td>{TOTAL_PRICE}</td>
                <td>{STATUS}</td>
            </tr>
            <!-- END: order -->

            <!-- BEGIN: no_order -->
            <tr>
                <td colspan="4" class="text-center">{LANG.no_order}</td>
            </tr>
            <!-- END: no_order -->
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Biểu đồ tròn số đơn hàng
    const ordersChart = new Chart(document.getElementById('ordersChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: {MONTH_LABELS},
            datasets: [{
                label: '{LANG.orders_per_month}',
                data: {ORDERS_DATA},
                backgroundColor: [
                    '#007bff','#28a745','#dc3545','#ffc107','#17a2b8',
                    '#6f42c1','#fd7e14','#20c997','#6610f2','#e83e8c',
                    '#6c757d','#198754'
                ]
            }]
        }
    });

    // Biểu đồ cột doanh thu
    const revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {MONTH_LABELS},
            datasets: [{
                label: '{LANG.revenue_per_month}',
                data: {REVENUE_DATA},
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<!-- END: main -->
