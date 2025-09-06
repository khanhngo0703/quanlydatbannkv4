<!-- BEGIN: main -->
<div class="d-flex justify-content-between mb-3">
  <h3>Danh sách bàn ăn</h3>
  <a class="btn btn-primary" href="{ADD_URL}">+ Thêm bàn</a>
</div>
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>ID</th><th>Tên bàn</th><th>Sức chứa</th><th>Vị trí</th><th>Trạng thái</th><th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: loop -->
    <tr>
      <td>{ROW.table_id}</td>
      <td>{ROW.table_name}</td>
      <td>{ROW.capacity}</td>
      <td>{ROW.location}</td>
      <td>{ROW.status_text}</td>
      <td>
        <a class="btn btn-sm btn-outline-primary" href="{ROW.edit_url}">Sửa</a>
        <a class="btn btn-sm btn-outline-danger" href="{ROW.del_url}" onclick="return confirm('Xóa bàn này?');">Xóa</a>
      </td>
    </tr>
    <!-- END: loop -->
  </tbody>
</table>
<!-- END: main -->
