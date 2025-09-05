<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/youth_union_functions.php'; // Có thể cần sửa thành member_functions.php nếu liên quan đến doan_vien
checkLogin(__DIR__ . '/../../index.php');
include '../menu.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm Đoàn Viên</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/favicon.png" /> <!-- Đường dẫn tuyệt đối -->
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css"> <!-- Đường dẫn tuyệt đối -->
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">THÊM ĐOÀN VIÊN</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <form method="POST" action="../../handle/member_process.php">
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label for="mssv" class="form-label">MSSV</label>
                            <input type="text" class="form-control" id="mssv" name="mssv" required>
                        </div>
                        <div class="mb-3">
                            <label for="ho_ten" class="form-label">Họ tên</label>
                            <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
                        </div>
                        <div class="mb-3">
                            <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh" required>
                        </div>
                        <div class="mb-3">
                            <label for="gioi_tinh" class="form-label">Giới tính</label>
                            <select class="form-control" id="gioi_tinh" name="gioi_tinh" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai">
                        </div>
                        <div class="mb-3">
                            <label for="dia_chi" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="dia_chi" name="dia_chi"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ngay_vao_doan" class="form-label">Ngày vào đoàn</label>
                            <input type="date" class="form-control" id="ngay_vao_doan" name="ngay_vao_doan">
                        </div>

                        <button type="submit" class="btn btn-primary">Thêm đoàn viên</button>
                        <a href="member.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc nội dung chính -->
    <script src="/BTL/assets/libs/jquery/dist/jquery.min.js"></script> <!-- Đường dẫn tuyệt đối -->
    <script src="/BTL/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script> <!-- Đường dẫn tuyệt đối -->
    <script src="/BTL/assets/js/sidebarmenu.js"></script> <!-- Đường dẫn tuyệt đối -->
    <script src="/BTL/assets/js/app.min.js"></script> <!-- Đường dẫn tuyệt đối -->
    <script src="/BTL/assets/libs/apexcharts/dist/apexcharts.min.js"></script> <!-- Đường dẫn tuyệt đối -->
    <script src="/BTL/assets/libs/simplebar/dist/simplebar.js"></script> <!-- Đường dẫn tuyệt đối -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>