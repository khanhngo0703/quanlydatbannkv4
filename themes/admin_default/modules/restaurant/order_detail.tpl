<!-- BEGIN: main -->
<div class="card shadow-sm mb-3">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0">Chi tiết đơn hàng #{ORDER.order_id}</h5>
  </div>
  <div class="card-body">
    <p><strong>Reservation:</strong> {ORDER.reservation_id}</p>
    <p><strong>User ID:</strong> {ORDER.user_id}</p>
    <p><strong>Bàn:</strong> {ORDER.table_name}</p>
    <p><strong>Trạng thái:</strong> {ORDER.status_text}</p>
    <p><strong>Ngày đặt:</strong> {ORDER.time}</p>
    <p><strong>Tổng tiền:</strong> {ORDER.total_format}</p>

    <h6 class="mt-4">Danh sách món:</h6>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>Món ăn</th>
          <th>Số lượng</th>
          <th>Đơn giá</th>
          <th>Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <!-- BEGIN: items -->
        <tr>
          <td>{ITEM.item_name}</td>
          <td>{ITEM.quantity}</td>
          <td>{ITEM.price_format}</td>
          <td>{ITEM.total_format}</td>
        </tr>
        <!-- END: items -->
      </tbody>
    </table>

    <a href="{BACK_URL}" class="btn btn-secondary mt-3">⬅ Quay lại danh sách</a>
  </div>
</div>
<!-- END: main -->
