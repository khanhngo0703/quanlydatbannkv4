<!-- BEGIN: main -->
<div class="container mt-4">
  <h2>Đăng nhập tài khoản</h2>

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
      <label for="password" class="form-label">Mật khẩu</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <input type="hidden" name="submit" value="1">
    <button type="submit" class="btn btn-primary">Đăng nhập</button>
  </form>
</div>
<!-- END: main -->
