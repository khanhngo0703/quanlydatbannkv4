<!-- BEGIN: main -->
<form method="post">
  <div class="mb-3">
    <label class="form-label">Reservation</label>
    <select name="reservation_id" class="form-select">
      <!-- BEGIN: res -->
      <option value="{RES.id}" {RES.sel}>#{RES.id}</option>
      <!-- END: res -->
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">User ID</label>
    <input type="number" name="user_id" value="{ROW.user_id}" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Trạng thái thanh toán</label>
    <select name="payment_status" class="form-select">
      <!-- BEGIN: status -->
      <option value="{OPT.val}" {OPT.sel}>{OPT.text}</option>
      <!-- END: status -->
    </select>
  </div>

  <h5>Chi tiết món</h5>
  <div id="order-items">
    <!-- BEGIN: detail -->
    <!-- BEGIN: loop_item -->
    <div class="row g-2 align-items-center mb-2">
      <div class="col-md-6">
        <select name="item_id[]" class="form-select">
          <!-- BEGIN: mi -->
          <option value="{MI.id}" {MI.sel}>{MI.name}</option>
          <!-- END: mi -->
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="quantity[]" value="{LINE.quantity}" min="1" class="form-control" placeholder="Số lượng">
      </div>
      <div class="col-md-3">
        <input type="text" name="price[]" value="{LINE.price}" class="form-control" placeholder="Đơn giá">
      </div>
    </div>
    <!-- END: loop_item -->
    <!-- END: detail -->
  </div>

  <button type="submit" name="save" value="1" class="btn btn-success mt-2">Lưu</button>
  <a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&op=orders" class="btn btn-secondary mt-2">Hủy</a>
</form>
<!-- END: main -->
