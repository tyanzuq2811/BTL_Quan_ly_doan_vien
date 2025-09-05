<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/training_score_functions.php';
checkLogin(__DIR__ . '/../../index.php');
include '../menu.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../training_score.php?error=Invalid training score ID");
    exit();
}

$trainingScore = getTrainingScoreById($_GET['id']);
if (!$trainingScore) {
    header("Location: ../training_score.php?error=Training score not found");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa điểm rèn luyện</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">Sửa điểm rèn luyện</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <form method="POST" action="../../handle/training_score_process.php">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $trainingScore['id'] ?>">

                        <div class="mb-3">
                            <label for="doan_vien_id" class="form-label">ID đoàn viên</label>
                            <input type="number" class="form-control" id="doan_vien_id" name="doan_vien_id" value="<?= htmlspecialchars($trainingScore['doan_vien_id']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nam_hoc" class="form-label">Năm học</label>
                            <input type="text" class="form-control" id="nam_hoc" name="nam_hoc" value="<?= htmlspecialchars($trainingScore['nam_hoc']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="hoc_ky" class="form-label">Học kỳ</label>
                            <select class="form-control" id="hoc_ky" name="hoc_ky" required>
                                <option value="HK1" <?= $trainingScore['hoc_ky'] == 'HK1' ? 'selected' : '' ?>>HK1</option>
                                <option value="HK2" <?= $trainingScore['hoc_ky'] == 'HK2' ? 'selected' : '' ?>>HK2</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="diem" class="form-label">Điểm</label>
                            <input type="number" min="0" max="100" class="form-control" id="diem" name="diem" value="<?= htmlspecialchars($trainingScore['diem']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="xep_loai" class="form-label">Classification</label>
                            <select class="form-control" id="xep_loai" name="xep_loai">
                                <option value="Xuất sắc" <?= $trainingScore['xep_loai'] == 'Xuất sắc' ? 'selected' : '' ?>>Xuất sắc</option>
                                <option value="Tốt" <?= $trainingScore['xep_loai'] == 'Tốt' ? 'selected' : '' ?>>Tốt</option>
                                <option value="Khá" <?= $trainingScore['xep_loai'] == 'Khá' ? 'selected' : '' ?>>Khá</option>
                                <option value="Trung bình" <?= $trainingScore['xep_loai'] == 'Trung bình' ? 'selected' : '' ?>>Trung bình</option>
                                <option value="Yếu" <?= $trainingScore['xep_loai'] == 'Yếu' ? 'selected' : '' ?>>Yếu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ghi_chu" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="ghi_chu" name="ghi_chu"><?= htmlspecialchars($trainingScore['ghi_chu'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật điểm rèn luyện</button>
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