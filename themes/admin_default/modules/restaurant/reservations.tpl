<!-- BEGIN: main -->
<div class="d-flex justify-content-between mb-3">
  <h3>Danh sách đặt bàn</h3>
  <a class="btn btn-primary" href="{ADD_URL}">+ Thêm đặt bàn</a>
</div>
<!-- BEGIN: success -->
<div class="alert alert-success">{SUCCESS}</div>
<!-- END: success -->
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>ID</th><th>Bàn</th><th>Username</th><th>Thời gian</th><th>Trạng thái</th><th>Tạo lúc</th><th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: loop -->
    <tr>
      <td>{ROW.reservation_id}</td>
      <td>{ROW.table_name}</td>
      <td>{ROW.username}</td>
      <td>{ROW.reserve_dt}</td>
      <td>{ROW.status_text}</td>
      <td>{ROW.datetime}</td>
      <td>
        <!-- BEGIN: approve -->
        <a class="btn btn-sm btn-success" href="{ROW.approve_url}" onclick="return confirm('Xác nhận phê duyệt đặt bàn này?');">Phê duyệt</a>
        <!-- END: approve -->
        <a class="btn btn-sm btn-outline-danger" href="{ROW.del_url}" onclick="return confirm('Xóa bản ghi này?');">Xóa</a>
      </td>
    </tr>
    <!-- END: loop -->
  </tbody>
</table>
<!-- END: main -->
