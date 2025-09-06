<!-- BEGIN: main -->
<div class="reservation-wrapper">
  <div class="reservation-container">
    <h2 class="title">Đặt bàn tại nhà hàng</h2>

    <!-- BEGIN: error -->
    <div class="alert alert-danger text-center mb-3">{ERROR}</div>
    <!-- END: error -->

    <!-- BEGIN: success -->
    <div class="alert alert-success text-center mb-3">{SUCCESS}</div>
    <!-- END: success -->

    <!-- BEGIN: success_action -->
    <div class="text-center mb-3">
      <a href="{FORM_ACTION}" class="btn btn-outline-primary me-2">➕ Đặt thêm bàn</a>
      <a href="index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&op=order&reservation_id={LAST_RESERVATION_ID}" class="btn btn-success">
        🍽️ Đặt món cho bàn này
      </a>
    </div>
    <!-- END: success_action -->

    <form action="{FORM_ACTION}" method="post">
      <div class="tables-grid">
        <!-- BEGIN: table_item -->
        <div class="table-card {TABLE_BORDER_CLASS}">
          <h5 class="mb-2">{TABLE_NAME}</h5>
          <p class="mb-1">{TABLE_CAPACITY} chỗ</p>
          <p class="mb-1">{TABLE_LOCATION}</p>
          <p class="mb-2"><span class="badge {TABLE_BADGE_CLASS}">{TABLE_STATUS_TEXT}</span></p>
          <div>
            <input type="radio" name="table_id" value="{TABLE_ID}" {TABLE_DISABLED}> Chọn bàn
          </div>
        </div>
        <!-- END: table_item -->
      </div>

      <div class="form-inline">
        <div>
          <label for="reservation_date" class="form-label">Ngày đặt</label>
          <input type="date" id="reservation_date" name="reservation_date" class="form-control" required>
        </div>
        <div>
          <label for="reservation_time" class="form-label">Giờ đặt</label>
          <input type="time" id="reservation_time" name="reservation_time" class="form-control" required>
        </div>
      </div>

      <div class="text-center mt-4">
        <input type="hidden" name="submit" value="1">
        <button type="submit" class="btn btn-primary px-4">Đặt bàn ngay</button>
      </div>
    </form>
  </div>
</div>

<style>
/* wrapper riêng, bỏ container theme */
.reservation-wrapper {
  padding: 30px 15px;
  overflow-x: hidden;
}
.reservation-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 20px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.reservation-container .title {
  text-align: center;
  margin-bottom: 25px;
  font-weight: bold;
}

/* grid hiển thị bàn */
.tables-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 25px;
}
@media (max-width: 992px) {
  .tables-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (max-width: 576px) {
  .tables-grid {
    grid-template-columns: 1fr;
  }
}

.table-card {
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  transition: 0.2s;
  background: #fafafa;
}
.table-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

/* form ngày giờ */
.form-inline {
  display: flex;
  justify-content: center;
  gap: 20px;
}
.form-inline div {
  display: flex;
  flex-direction: column;
  min-width: 200px;
}
@media (max-width: 576px) {
  .form-inline {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
<!-- END: main -->
