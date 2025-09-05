<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/due_functions.php';
checkLogin(__DIR__ . '/../../index.php');
include '../menu.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Dues</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">ADD DUES</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <form method="POST" action="../../handle/due_process.php">
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label for="doan_vien_id" class="form-label">Member ID</label>
                            <input type="number" class="form-control" id="doan_vien_id" name="doan_vien_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="loai_doan_phi_id" class="form-label">Dues Type ID</label>
                            <input type="number" class="form-control" id="loai_doan_phi_id" name="loai_doan_phi_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="nam_hoc" class="form-label">Academic Year</label>
                            <input type="text" class="form-control" id="nam_hoc" name="nam_hoc" placeholder="e.g., 2024-2025" required>
                        </div>
                        <div class="mb-3">
                            <label for="hoc_ky" class="form-label">Semester</label>
                            <select class="form-control" id="hoc_ky" name="hoc_ky" required>
                                <option value="HK1">HK1</option>
                                <option value="HK2">HK2</option>
                                <option value="Cả năm">Cả năm</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="so_tien" class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="so_tien" name="so_tien" required>
                        </div>
                        <div class="mb-3">
                            <label for="ngay_nop" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="ngay_nop" name="ngay_nop">
                        </div>
                        <div class="mb-3">
                            <label for="trang_thai" class="form-label">Status</label>
                            <select class="form-control" id="trang_thai" name="trang_thai" required>
                                <option value="Chưa nộp">Chưa nộp</option>
                                <option value="Đã nộp">Đã nộp</option>
                                <option value="Miễn giảm">Miễn giảm</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Dues</button>
                        <a href="dues.php" class="btn btn-secondary">Back</a>
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