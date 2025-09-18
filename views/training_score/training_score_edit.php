<?php
require_once __DIR__ . '/../../functions/training_score_functions.php';
require_once __DIR__ . '/../../functions/member_functions.php';
require_once __DIR__ . '/../../functions/auth.php';

checkLogin(__DIR__ . '/../../authentication_login.php');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
$score = getScoreById($id);
$members = getAllMembers();

if (!$score) {
    header("Location: /BTL/views/training_score.php?error=Điểm rèn luyện không tồn tại");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Training Score</title>
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
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/dashboard.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                            <span class="hide-menu">Danh mục quản lý</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/youth_union_chapter.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý liên chi đoàn</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/youth_union_team.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý chi đoàn</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/member.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:user-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý đoàn viên</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/participation_history.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:history-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý lịch sử tham gia</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/union_fee.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:money-bag-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý đoàn phí</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/training_score.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:star-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý điểm rèn luyện</span>
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
                        <h2>Sửa điểm rèn luyện</h2>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/BTL/handle/training_score_process.php?action=edit" onsubmit="return validateForm()">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($score['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label class="form-label">Đoàn viên</label>
                                <select name="doan_vien_id" class="form-select" required>
                                    <option value="">-- Chọn đoàn viên --</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= htmlspecialchars($member['id'], ENT_QUOTES, 'UTF-8') ?>" <?= $member['id'] == $score['doan_vien_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($member['ho_ten'] . ' (' . $member['mssv'] . ')', ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Năm học</label>
                                <input type="text" name="nam_hoc" class="form-control" value="<?= htmlspecialchars($score['nam_hoc'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Học kỳ</label>
                                <select name="hoc_ky" class="form-select" required>
                                    <option value="">-- Chọn học kỳ --</option>
                                    <option value="HK1" <?= $score['hoc_ky'] == 'HK1' ? 'selected' : '' ?>>HK1</option>
                                    <option value="HK2" <?= $score['hoc_ky'] == 'HK2' ? 'selected' : '' ?>>HK2</option>
                                    <option value="HK3" <?= $score['hoc_ky'] == 'HK3' ? 'selected' : '' ?>>HK3</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Điểm</label>
                                <input type="number" name="diem" class="form-control" value="<?= htmlspecialchars($score['diem'], ENT_QUOTES, 'UTF-8') ?>" required min="0" max="100" oninput="updateRank()">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Xếp loại</label>
                                <select name="xep_loai" class="form-select" readonly>
                                    <option value="">-- Chọn điểm để xếp loại --</option>
                                    <option value="Xuất sắc" <?= $score['xep_loai'] == 'Xuất sắc' ? 'selected' : '' ?>>Xuất sắc</option>
                                    <option value="Tốt" <?= $score['xep_loai'] == 'Tốt' ? 'selected' : '' ?>>Tốt</option>
                                    <option value="Khá" <?= $score['xep_loai'] == 'Khá' ? 'selected' : '' ?>>Khá</option>
                                    <option value="Trung bình" <?= $score['xep_loai'] == 'Trung bình' ? 'selected' : '' ?>>Trung bình</option>
                                    <option value="Yếu" <?= $score['xep_loai'] == 'Yếu' ? 'selected' : '' ?>>Yếu</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="4"><?= htmlspecialchars($score['ghi_chu'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/BTL/views/training_score.php" class="btn btn-secondary">Hủy</a>
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
    <script>
        function validateForm() {
            const doanVienId = document.querySelector('select[name="doan_vien_id"]').value;
            const namHoc = document.querySelector('input[name="nam_hoc"]').value.trim();
            const hocKy = document.querySelector('select[name="hoc_ky"]').value;
            const diem = document.querySelector('input[name="diem"]').value;
            if (!doanVienId) {
                alert('Vui lòng chọn đoàn viên.');
                return false;
            }
            if (!namHoc) {
                alert('Vui lòng nhập năm học.');
                return false;
            }
            if (!hocKy) {
                alert('Vui lòng chọn học kỳ.');
                return false;
            }
            if (!diem || diem < 0 || diem > 100) {
                alert('Vui lòng nhập điểm từ 0 đến 100.');
                return false;
            }
            return true;
        }

        function updateRank() {
            const diemInput = document.querySelector('input[name="diem"]');
            const xepLoaiSelect = document.querySelector('select[name="xep_loai"]');
            const diem = parseInt(diemInput.value);
            if (isNaN(diem) || diem < 0 || diem > 100) {
                xepLoaiSelect.value = '';
                return;
            }
            if (diem >= 90) {
                xepLoaiSelect.value = 'Xuất sắc';
            } else if (diem >= 80) {
                xepLoaiSelect.value = 'Tốt';
            } else if (diem >= 70) {
                xepLoaiSelect.value = 'Khá';
            } else if (diem >= 50) {
                xepLoaiSelect.value = 'Trung bình';
            } else {
                xepLoaiSelect.value = 'Yếu';
            }
        }
    </script>
</body>
</html>