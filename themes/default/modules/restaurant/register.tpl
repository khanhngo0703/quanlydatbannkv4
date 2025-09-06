<!-- BEGIN: main -->
<div class="container mt-4">
  <h2>Đăng ký tài khoản</h2>

  <!-- Hiển thị lỗi -->
  <!-- BEGIN: error -->
  <div class="alert alert-danger">{ERROR}</div>
  <!-- END: error -->

  <form method="post" action="{FORM_ACTION}">
    <div class="mb-3">
      <label for="username" class="form-label">Tên đăng nhập</label>
      <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Mật khẩu</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
      <label for="repassword" class="form-label">Nhập lại mật khẩu</label>
      <input type="password" class="form-control" id="repassword" name="repassword" required>
    </div>
    <input type="hidden" name="submit" value="1">
    <button type="submit" class="btn btn-success">Đăng ký</button>
  </form>
</div>
<!-- END: main -->
