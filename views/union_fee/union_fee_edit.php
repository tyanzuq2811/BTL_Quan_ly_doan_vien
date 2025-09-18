<?php
require_once __DIR__ . '/../../functions/union_fee_functions.php';
require_once __DIR__ . '/../../functions/member_functions.php';
require_once __DIR__ . '/../../functions/auth.php';

checkLogin(__DIR__ . '/../../authentication_login.php');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
$fee = getFeeById($id);
$members = getAllMembers();
$fee_types = getAllFeeTypes();

if (!$fee) {
    header("Location: /BTL/views/union_fee.php?error=Đoàn phí không tồn tại");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Union Fee</title>
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
                        <h2>Sửa đoàn phí</h2>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/BTL/handle/union_fee_process.php?action=edit" onsubmit="return validateForm()">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($fee['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label class="form-label">Đoàn viên</label>
                                <select name="doan_vien_id" class="form-select" required>
                                    <option value="">-- Chọn đoàn viên --</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= htmlspecialchars($member['id'], ENT_QUOTES, 'UTF-8') ?>" <?= $member['id'] == $fee['doan_vien_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($member['ho_ten'] . ' (' . $member['mssv'] . ')', ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại đoàn phí</label>
                                <select name="loai_doan_phi_id" class="form-select" required onchange="updateAmount()">
                                    <option value="">-- Chọn loại đoàn phí --</option>
                                    <?php foreach ($fee_types as $fee_type): ?>
                                        <option value="<?= htmlspecialchars($fee_type['id'], ENT_QUOTES, 'UTF-8') ?>" data-amount="<?= htmlspecialchars($fee_type['so_tien'], ENT_QUOTES, 'UTF-8') ?>" <?= $fee_type['id'] == $fee['loai_doan_phi_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fee_type['ten'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Năm học</label>
                                <input type="text" name="nam_hoc" class="form-control" value="<?= htmlspecialchars($fee['nam_hoc'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số tiền</label>
                                <input type="number" name="so_tien" class="form-control" step="0.01" value="<?= htmlspecialchars($fee['so_tien'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày nộp</label>
                                <input type="date" name="ngay_nop" class="form-control" value="<?= htmlspecialchars($fee['ngay_nop'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="Đã nộp" <?= $fee['trang_thai'] == 'Đã nộp' ? 'selected' : '' ?>>Đã nộp</option>
                                    <option value="Chưa nộp" <?= $fee['trang_thai'] == 'Chưa nộp' ? 'selected' : '' ?>>Chưa nộp</option>
                                    <option value="Miễn giảm" <?= $fee['trang_thai'] == 'Miễn giảm' ? 'selected' : '' ?>>Miễn giảm</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/BTL/views/union_fee.php" class="btn btn-secondary">Hủy</a>
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
            const loaiDoanPhiId = document.querySelector('select[name="loai_doan_phi_id"]').value;
            const namHoc = document.querySelector('input[name="nam_hoc"]').value.trim();
            const soTien = document.querySelector('input[name="so_tien"]').value;
            if (!doanVienId) {
                alert('Vui lòng chọn đoàn viên.');
                return false;
            }
            if (!loaiDoanPhiId) {
                alert('Vui lòng chọn loại đoàn phí.');
                return false;
            }
            if (!namHoc) {
                alert('Vui lòng nhập năm học.');
                return false;
            }
            if (!soTien || soTien <= 0) {
                alert('Vui lòng nhập số tiền hợp lệ.');
                return false;
            }
            return true;
        }

        function updateAmount() {
            const select = document.querySelector('select[name="loai_doan_phi_id"]');
            const amountInput = document.querySelector('input[name="so_tien"]');
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.dataset.amount) {
                amountInput.value = selectedOption.dataset.amount;
            }
        }
    </script>
</body>
</html>