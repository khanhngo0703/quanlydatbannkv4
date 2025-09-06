<!-- BEGIN: main -->
<form method="post">
  <div class="mb-3">
    <label class="form-label">Tên danh mục</label>
    <input type="text" name="name" value="{ROW.name}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control" rows="4">{ROW.description}</textarea>
  </div>
  <button type="submit" name="save" value="1" class="btn btn-success">Lưu</button>
  <a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&op=categories" class="btn btn-secondary">Hủy</a>
</form>
<!-- END: main -->
