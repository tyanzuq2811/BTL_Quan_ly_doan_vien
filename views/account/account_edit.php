<?php
session_start();
require_once __DIR__ . '/../../functions/account_functions.php';
require_once __DIR__ . '/../../functions/auth.php';

checkLogin(__DIR__ . '/../../authentication_login.php');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
$account = getAccountById($id);

if (!$account) {
    $_SESSION['error'] = 'Tài khoản không tồn tại';
    header("Location: /BTL/views/account.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa tài khoản</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/seodashlogo.png">
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="/BTL/views/dashboard.php" class="text-nowrap logo-img">
                        <img src="/BTL/assets/images/logos/logo-light.svg" alt="">
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                            <span class="hide-menu">Trang chủ</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/admin_dashboard.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Bảng quản trị</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                            <span class="hide-menu">Danh mục quản lý</span>
                        </li>
                         <a class="sidebar-link" href="account.php" aria-expanded="false">
                            <span>
                            <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Quản lý tài khoản</span>
                        </a>
                        </li>
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-6"></iconify-icon>
                            <span class="hide-menu">AUTH</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/authentication_login.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:login-3-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Login</span>
                            </a>
                        </li>
                    </ul>
                    <div class="unlimited-access hide-menu bg-primary-subtle position-relative mb-7 mt-7 rounded-3">
                        <div class="d-flex">
                            <div class="unlimited-access-img">
                                <img src="/BTL/assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>
        <div class="body-wrapper">
            <div class="container mt-3">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h2>Sửa tài khoản</h2>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="post" action="/BTL/handle/account_process.php?action=edit">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($account['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label class="form-label">Mã thành viên</label>
                                <input type="number" name="doan_vien_id" class="form-control" value="<?= htmlspecialchars($account['doan_vien_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Nhập mã thành viên (tùy chọn)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" name="ten_dang_nhap" class="form-control" value="<?= htmlspecialchars($account['ten_dang_nhap'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu (để trống để giữ nguyên)</label>
                                <input type="password" name="mat_khau" class="form-control" placeholder="Nhập mật khẩu mới (tùy chọn)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vai trò</label>
                                <select name="vai_tro" class="form-select" required>
                                    <option value="DoanVien" <?= $account['vai_tro'] === 'DoanVien' ? 'selected' : '' ?>>Đoàn viên</option>
                                    <option value="DoanTruong" <?= $account['vai_tro'] === 'DoanTruong' ? 'selected' : '' ?>>Đoàn trưởng</option>
                                    <option value="Admin" <?= $account['vai_tro'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="Hoạt động" <?= $account['trang_thai'] === 'Hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="Khoá" <?= $account['trang_thai'] === 'Khoá' ? 'selected' : '' ?>>Khoá</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/BTL/views/account.php" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/BTL/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="/BTL/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/BTL/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="/BTL/assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="/BTL/assets/js/sidebarmenu.js"></script>
    <script src="/BTL/assets/js/app.min.js"></script>
    <script src="/BTL/assets/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>