<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/account_functions.php';
checkLogin(__DIR__ . '/../../index.php');
include '../menu.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../account.php?error=Invalid account ID");
    exit();
}

$account = getAccountById($_GET['id']);
if (!$account) {
    header("Location: ../account.php?error=Account not found");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa tài khoản</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">Sửa tài khoản</h3>

                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <form method="POST" action="../../handle/account_process.php">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $account['id'] ?>">

                        <div class="mb-3">
                            <label for="doan_vien_id" class="form-label">ID đoàn viên</label>
                            <input type="number" class="form-control" id="doan_vien_id" name="doan_vien_id" value="<?= htmlspecialchars($account['doan_vien_id']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="ten_dang_nhap" name="ten_dang_nhap" value="<?= htmlspecialchars($account['ten_dang_nhap']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="mat_khau" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
                        </div>
                        <div class="mb-3">
                            <label for="vai_tro" class="form-label">Vai trò</label>
                            <select class="form-control" id="vai_tro" name="vai_tro" required>
                                <option value="DoanVien" <?= $account['vai_tro'] == 'DoanVien' ? 'selected' : '' ?>>DoanVien</option>
                                <option value="ChiDoan" <?= $account['vai_tro'] == 'ChiDoan' ? 'selected' : '' ?>>ChiDoan</option>
                                <option value="LienChi" <?= $account['vai_tro'] == 'LienChi' ? 'selected' : '' ?>>LienChi</option>
                                <option value="DoanTruong" <?= $account['vai_tro'] == 'DoanTruong' ? 'selected' : '' ?>>DoanTruong</option>
                                <option value="Admin" <?= $account['vai_tro'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="trang_thai" class="form-label">Trạng thái</label>
                            <select class="form-control" id="trang_thai" name="trang_thai" required>
                                <option value="Hoạt động" <?= $account['trang_thai'] == 'Hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="Khoá" <?= $account['trang_thai'] == 'Khoá' ? 'selected' : '' ?>>Khoá</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật tài khoản</button>
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