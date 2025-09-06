<!-- BEGIN: main -->
<div class="d-flex justify-content-between mb-3">
  <h3>Danh sách món ăn</h3>
  <a class="btn btn-primary" href="{ADD_URL}">+ Thêm món</a>
</div>
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>ID</th><th>Tên món</th><th>Ảnh</th><th>Danh mục</th><th>Giá</th><th>Trạng thái</th><th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: loop -->
    <tr>
      <td>{ROW.item_id}</td>
      <td>{ROW.name}</td>
      <!-- BEGIN: image_block -->
      <td>
      <img src="{ROW.image_url}" alt="{ROW.name}" style="max-width:80px; max-height:80px;">
      </td>
      <!-- END: image_block -->
      <td>{ROW.cat_name}</td>
      <td>{ROW.price_format}</td>
      <td>{ROW.status_text}</td>
      <td>
        <a class="btn btn-sm btn-outline-primary" href="{ROW.edit_url}">Sửa</a>
        <a class="btn btn-sm btn-outline-primary" href="{ROW.del_url}" onclick="return confirm('Xóa món này?');">Xóa</a>
      </td>
    </tr>
    <!-- END: loop -->
  </tbody>
</table>
<!-- END: main -->
