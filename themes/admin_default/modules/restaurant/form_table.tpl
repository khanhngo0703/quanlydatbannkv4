<!-- BEGIN: main -->
<form method="post">
  <div class="mb-3">
    <label class="form-label">Tên bàn</label>
    <input type="text" name="table_name" value="{TABLE_NAME}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Sức chứa</label>
    <input type="number" name="capacity" value="{CAPACITY}" min="1" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Vị trí</label>
    <input type="text" name="location" value="{LOCATION}" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-select">
      <!-- BEGIN: status_option -->
      <option value="{OPT_VAL}" {OPT_SEL}>{OPT_TEXT}</option>
      <!-- END: status_option -->
    </select>
  </div>
  <button type="submit" name="save" value="1" class="btn btn-success">Lưu</button>
  <a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&op=tables" class="btn btn-secondary">Hủy</a>
</form>
<!-- END: main -->
