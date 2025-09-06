<!-- BEGIN: main -->
<div class="mb-3">
  <a href="{ADD_URL}" class="btn btn-primary">+ Thêm danh mục</a>
</div>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>ID</th>
      <th>Tên danh mục</th>
      <th>Mô tả</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: loop -->
    <tr>
      <td>{ROW.category_id}</td>
      <td>{ROW.name}</td>
      <td>{ROW.description}</td>
      <td>
        <a href="{ROW.edit_url}" class="btn btn-sm btn-outline-primary">Sửa</a>
        <a href="{ROW.del_url}" class="btn btn-sm btn-outline-primary" onclick="return confirm('Xóa danh mục này?');">Xóa</a>
      </td>
    </tr>
    <!-- END: loop -->
  </tbody>
</table>
<!-- END: main -->
