<?php
require_once __DIR__ . '/../../functions/auth.php'; 
require_once __DIR__ . '/../../functions/youth_union_functions.php'; 
checkLogin(__DIR__ . '/../../index.php'); 
include '../menu.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: youth_union.php?error=ID tổ chức đoàn không hợp lệ");
    exit();
}

$union = getYouthUnionById($_GET['id']);
if (!$union) {
    header("Location: youth_union.php?error=Không tìm thấy tổ chức đoàn");
    exit();
}

$parent_unions = getAllYouthUnions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa Tổ Chức Đoàn</title>
    <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../../assets/css/styles.min.css"> 
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">SỬA TỔ CHỨC ĐOÀN</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <form method="POST" action="../../handle/youth_union_process.php"> 
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $union['id'] ?>">

                        <div class="mb-3">
                            <label for="ten" class="form-label">Tên tổ chức đoàn</label>
                            <input type="text" class="form-control" id="ten" name="ten" value="<?= htmlspecialchars($union['ten']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="loai" class="form-label">Loại tổ chức</label>
                            <select class="form-control" id="loai" name="loai" required>
                                <option value="Chi đoàn" <?= $union['loai'] == 'Chi đoàn' ? 'selected' : '' ?>>Chi đoàn</option>
                                <option value="Liên chi đoàn/Đoàn khoa" <?= $union['loai'] == 'Liên chi đoàn/Đoàn khoa' ? 'selected' : '' ?>>Liên chi đoàn/Đoàn khoa</option>
                                <option value="Đoàn trường" <?= $union['loai'] == 'Đoàn trường' ? 'selected' : '' ?>>Đoàn trường</option>
                                <option value="Khác" <?= $union['loai'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cap_tren_id" class="form-label">Tổ chức cấp trên</label>
                            <select class="form-control" id="cap_tren_id" name="cap_tren_id">
                                <option value="">Không có</option>
                                <?php foreach ($parent_unions as $parent): ?>
                                    <option value="<?= $parent['id'] ?>" <?= $union['cap_tren_id'] == $parent['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($parent['ten']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ngay_thanh_lap" class="form-label">Ngày thành lập</label>
                            <input type="date" class="form-control" id="ngay_thanh_lap" name="ngay_thanh_lap" value="<?= $union['ngay_thanh_lap'] ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật tổ chức đoàn</button>
                        <a href="youth_union.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc nội dung chính -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/sidebarmenu.js"></script>
    <script src="../../assets/js/app.min.js"></script>
    <script src="../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>