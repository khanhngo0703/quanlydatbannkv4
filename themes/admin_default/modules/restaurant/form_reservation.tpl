<!-- BEGIN: main -->
<form method="post">
  <div class="mb-3">
    <label class="form-label">User ID</label>
    <input type="number" name="user_id" value="{ROW.user_id}" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Bàn</label>
    <select name="table_id" class="form-select">
      <!-- BEGIN: table -->
      <option value="{TB.id}" {TB.sel}>{TB.name}</option>
      <!-- END: table -->
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Ngày đặt</label>
    <input type="date" name="reservation_date" value="{ROW.reservation_date}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Giờ đặt</label>
    <input type="time" name="reservation_time" value="{ROW.reservation_time}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-select">
      <!-- BEGIN: status -->
      <option value="{OPT.val}" {OPT.sel}>{OPT.text}</option>
      <!-- END: status -->
    </select>
  </div>
  <button type="submit" name="save" value="1" class="btn btn-success">Lưu</button>
  <a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&op=reservations" class="btn btn-secondary">Hủy</a>
</form>
<!-- END: main -->
