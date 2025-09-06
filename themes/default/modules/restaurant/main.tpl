<!-- BEGIN: main -->
<div class="container my-5">

  <!-- Hero Section -->
  <div class="row align-items-center mb-5 g-4">
    <div class="col-md-6 order-md-1 order-2">
      <h1 class="display-5 fw-bold mb-3">Chào mừng đến với Nhà hàng</h1>
      <p class="lead text-muted mb-4">Trải nghiệm ẩm thực tuyệt vời, đặt bàn nhanh chóng và đặt món yêu thích chỉ trong vài bước.</p>

      <!-- Guest buttons -->
      <!-- BEGIN: guest -->
      <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{LOGIN_URL}" class="btn btn-primary btn-lg shadow-sm rounded-pill">
          <i class="bi bi-box-arrow-in-right me-2"></i> Đăng nhập
        </a>
        <a href="{REGISTER_URL}" class="btn btn-success btn-lg shadow-sm rounded-pill">
          <i class="bi bi-person-plus me-2"></i> Đăng ký
        </a>
      </div>
      <!-- END: guest -->

      <!-- User buttons -->
      <!-- BEGIN: user -->
      <div class="mb-3">
        <p class="h5">Xin chào, <span class="text-primary fw-bold">{USERNAME}</span></p>
        <div class="d-flex flex-wrap gap-2">
          <a href="{RESERVATION_URL}" class="btn btn-warning btn-lg shadow-sm rounded-pill">
            <i class="bi bi-calendar-check me-2"></i> Đặt bàn
          </a>          
          <a href="{LOGOUT_URL}" class="btn btn-danger btn-lg shadow-sm rounded-pill">
            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
          </a>
        </div>
      </div>
      <!-- END: user -->

    </div>
    <div class="col-md-6 text-center order-md-2 order-1">
      <div class="hero-img shadow rounded overflow-hidden">
        <img src="{NV_BASE_SITEURL}themes/default/images/restaurant_hero.jpg" class="img-fluid w-10" alt="Nhà hàng">
      </div>
    </div>
  </div>

  <!-- Features Section -->
  <div class="row text-center g-4 row-bottom">
    <div class="col-md-4">
      <div class="card h-100 border-0 shadow-lg rounded-4">
        <div class="card-body p-4">
          <i class="bi bi-star-fill text-warning display-3 mb-3"></i>
          <h5 class="card-title fw-bold mb-2">Ẩm thực đặc sắc</h5>
          <p class="card-text text-muted">Hương vị tinh tế từ các món ăn tươi ngon, chế biến chuyên nghiệp.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100 border-0 shadow-lg rounded-4">
        <div class="card-body p-4">
          <i class="bi bi-calendar2-check-fill text-success display-3 mb-3"></i>
          <h5 class="card-title fw-bold mb-2">Đặt bàn dễ dàng</h5>
          <p class="card-text text-muted">Chọn bàn nhanh chóng, không cần xếp hàng, tiện lợi cho bạn và gia đình.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100 border-0 shadow-lg rounded-4">
        <div class="card-body p-4">
          <i class="bi bi-basket-fill text-info display-3 mb-3"></i>
          <h5 class="card-title fw-bold mb-2">Đặt món nhanh chóng</h5>
          <p class="card-text text-muted">Giao diện đặt món trực quan, thanh toán dễ dàng, món ăn chuẩn vị nhà hàng.</p>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Optional CSS riêng cho module -->
<style>
.hero-img img {
  max-height: 220px;
  object-fit: cover;
  border-radius: 1rem;
}
.row-bottom{
  margin-top: 33px;
}
@media (max-width: 768px) {
  .hero-img img {
    max-height: 300px;
  }
  .display-5 {
    font-size: 2rem !important;
  }
  .lead {
    font-size: 1rem !important;
  }
}
</style>
<!-- END: main -->
