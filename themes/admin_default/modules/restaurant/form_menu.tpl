<!-- BEGIN: main -->
<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">Danh mục</label>
    <select name="category_id" class="form-select">
      <!-- BEGIN: cat -->
      <option value="{CAT_ID}" {CAT_SEL}>{CAT_NAME}</option>
      <!-- END: cat -->
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Tên món</label>
    <input type="text" name="name" value="{ROW.name}" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Giá</label>
    <input type="text" name="price" value="{ROW.price}" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Hình ảnh</label>
    <!-- BEGIN: image_block -->
    <div class="mb-2">
        <img src="{ROW.image_url}" alt="Ảnh món" style="max-width:150px;">
    </div>
    <!-- END: image_block -->
    <input type="file" name="image_file" class="form-control">
  </div>

  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control" rows="4">{ROW.description}</textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-select">
      <!-- BEGIN: status -->
      <option value="{OPT_VAL}" {OPT_SEL}>{OPT_TEXT}</option>
      <!-- END: status -->
    </select>
  </div>

  <button type="submit" name="save" value="1" class="btn btn-success">Lưu</button>
  <a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&op=menu" class="btn btn-secondary">Hủy</a>
</form>


<!-- END: main -->
