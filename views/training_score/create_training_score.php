<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/training_score_functions.php';
checkLogin(__DIR__ . '/../../index.php');
include '../menu.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tạo điểm rèn luyện</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">Thêm điểm rèn luyện</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <form method="POST" action="../../handle/training_score_process.php">
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label for="doan_vien_id" class="form-label">ID đoàn viên </label>
                            <input type="number" class="form-control" id="doan_vien_id" name="doan_vien_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="nam_hoc" class="form-label">Năm học</label>
                            <input type="text" class="form-control" id="nam_hoc" name="nam_hoc" placeholder="e.g., 2024-2025" required>
                        </div>
                        <div class="mb-3">
                            <label for="hoc_ky" class="form-label">Học kỳ</label>
                            <select class="form-control" id="hoc_ky" name="hoc_ky" required>
                                <option value="HK1">HK1</option>
                                <option value="HK2">HK2</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="diem" class="form-label">Điểm</label>
                            <input type="number" min="0" max="100" class="form-control" id="diem" name="diem" required>
                        </div>
                        <div class="mb-3">
                            <label for="xep_loai" class="form-label">Xếp loại</label>
                            <select class="form-control" id="xep_loai" name="xep_loai">
                                <option value="Xuất sắc">Xuất sắc</option>
                                <option value="Tốt">Tốt</option>
                                <option value="Khá">Khá</option>
                                <option value="Trung bình">Trung bình</option>
                                <option value="Yếu">Yếu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ghi_chu" class="form-label">Note</label>
                            <textarea class="form-control" id="ghi_chu" name="ghi_chu"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Tạo điểm rèn luyện</button>
                        <a href="../training_score.php" class="btn btn-secondary">Quay lại</a>
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