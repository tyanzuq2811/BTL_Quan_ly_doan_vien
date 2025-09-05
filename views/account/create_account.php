<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/account_functions.php';
checkLogin(__DIR__ . '/../../index.php');
include '../menu.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm tài khoản</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">Thêm tài khoản</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <form method="POST" action="../../handle/account_process.php">
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label for="doan_vien_id" class="form-label">ID đoàn viên</label>
                            <input type="number" class="form-control" id="doan_vien_id" name="doan_vien_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="ten_dang_nhap" name="ten_dang_nhap" required>
                        </div>
                        <div class="mb-3">
                            <label for="mat_khau" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
                        </div>
                        <div class="mb-3">
                            <label for="vai_tro" class="form-label">Vai trò</label>
                            <select class="form-control" id="vai_tro" name="vai_tro" required>
                                <option value="DoanVien">DoanVien</option>
                                <option value="ChiDoan">ChiDoan</option>
                                <option value="LienChi">LienChi</option>
                                <option value="DoanTruong">DoanTruong</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="trang_thai" class="form-label">Trạng thái</label>
                            <select class="form-control" id="trang_thai" name="trang_thai" required>
                                <option value="Hoạt động">Hoạt động</option>
                                <option value="Khoá">Khoá</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
                        <a href="../account.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc nội dung chính -->
    <script src="/BTL/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="/BTL/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/BTL/assets/js/sidebarmenu.js"></script>
    <script src="/BTL/assets/js/app.min.js"></script>
    <script src="/BTL/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="/BTL/assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>