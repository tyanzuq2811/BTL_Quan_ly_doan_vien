<?php
session_start();
require_once 'functions/db_connection.php';
require_once 'functions/auth.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="assets/images/logos/logo-light.svg" alt="">
                </a>
                <form action="handle/login_process.php" method="POST">
                  <div class="mb-3">
                    <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="ten_dang_nhap" name="ten_dang_nhap" aria-describedby="emailHelp" placeholder="Nhập tên đăng nhập" required>
                  </div>
                  <div class="mb-4">
                    <label for="mat_khau" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="mat_khau" name="mat_khau" placeholder="Nhập mật khẩu" required>
                  </div>
                  <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                      <?php 
                      echo $_SESSION['error']; 
                      unset($_SESSION['error']);
                      ?>
                    </div>
                  <?php endif; ?>
                  <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                      <?php 
                      echo $_SESSION['success']; 
                      unset($_SESSION['success']);
                      ?>
                    </div>
                  <?php endif; ?>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Lưu thông tin
                      </label>
                    </div>
                    <a class="text-primary fw-bold" href="./forgot-password.html">Quên mật khẩu?</a>
                  </div>
                  <button type="submit" name="login" class="btn btn-primary w-100 py-8 fs-4 mb-4">Đăng nhập</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Bạn chưa có tài khoản?</p>
                    <a class="text-primary fw-bold ms-2" href="./authentication-register.html">Tạo tài khoản</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>