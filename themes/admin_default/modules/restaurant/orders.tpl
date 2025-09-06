<!-- BEGIN: main -->
<div class="d-flex justify-content-between mb-3">
  <h3>Danh sách đơn đặt món</h3>
  <a class="btn btn-primary" href="{ADD_URL}">+ Tạo đơn</a>
</div>
<!-- BEGIN: success -->
<div class="alert alert-success">{SUCCESS}</div>
<!-- END: success -->
<table class="table table-striped table-bordered">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Reservation</th>
      <th>Username</th>
      <th>Bàn</th>
      <th>Tổng tiền</th>
      <th>Trạng thái</th>
      <th>Thời gian</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: loop -->
    <tr>
      <td>{ROW.order_id}</td>
      <td>{ROW.reservation_id}</td>
      <td>{ROW.username}</td>
      <td>{ROW.table_name}</td>
      <td>{ROW.total_format}</td>
      <td>{ROW.status_text}</td>
      <td>{ROW.time}</td>
      <td>
        <!-- BEGIN: confirm -->
        <a class="btn btn-sm btn-success" href="{ROW.confirm_url}" onclick="return confirm('Xác nhận đơn hàng này?');">
          ✅ Xác nhận
        </a>
        <!-- END: confirm -->
        <a class="btn btn-sm btn-outline-danger" href="{ROW.detail_url}">Chi tiết</a>
        <a class="btn btn-sm btn-outline-danger" href="{ROW.del_url}" onclick="return confirm('Xóa đơn này?');">Xóa</a>
      </td>
    </tr>
    <!-- END: loop -->
  </tbody>
</table>
<!-- END: main -->
