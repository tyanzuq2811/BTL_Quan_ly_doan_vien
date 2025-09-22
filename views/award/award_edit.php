<?php
require_once __DIR__ . '/../../functions/award_functions.php';
require_once __DIR__ . '/../../functions/member_functions.php';
require_once __DIR__ . '/../../functions/auth.php';

checkLogin(__DIR__ . '/../../authentication_login.php');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
$award = getAwardById($id);
$members = getAllMembers();
$award_types = getAllAwardTypes();

if (!$award) {
    header("Location: /BTL/views/award.php?error=Khen thưởng không tồn tại");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Award</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/seodashlogo.png">
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-center">
                    <a href="dashboard.php" class="text-nowrap logo-img">
                        <img src="/BTL/assets/images/logos/fitdnu_logo.png" alt="" width="80"/>
                        <img src="/BTL/assets/images/logos/aiotlab_logo.png" alt="" width="80" style="margin-left:22px;"/>
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
                            <a class="sidebar-link" href="/BTL/views/dashboard.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Bảng quản trị</span>
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
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/event.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:calendar-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý sự kiện</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/event_participation.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý tham gia sự kiện</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/award.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:medal-star-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý khen thưởng</span>
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
                        <h2>Sửa khen thưởng</h2>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/BTL/handle/award_process.php?action=edit" onsubmit="return validateForm()">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($award['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label class="form-label">Đoàn viên</label>
                                <select name="doan_vien_id" class="form-select" required>
                                    <option value="">-- Chọn đoàn viên --</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= htmlspecialchars($member['id'], ENT_QUOTES, 'UTF-8') ?>" <?= $member['id'] == $award['doan_vien_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($member['ho_ten'] . ' (' . $member['mssv'] . ')', ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại khen thưởng</label>
                                <select name="loai_id" class="form-select" required onchange="updateDescription()">
                                    <option value="">-- Chọn loại khen thưởng --</option>
                                    <?php foreach ($award_types as $type): ?>
                                        <option value="<?= htmlspecialchars($type['id'], ENT_QUOTES, 'UTF-8') ?>" data-description="<?= htmlspecialchars($type['mo_ta'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= $type['id'] == $award['loai_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($type['ten'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả loại khen thưởng</label>
                                <textarea id="loai_mo_ta" class="form-control" rows="3" readonly><?= htmlspecialchars($award['loai_mo_ta'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày quyết định</label>
                                <input type="date" name="ngay_quyet_dinh" class="form-control" value="<?= htmlspecialchars($award['ngay_quyet_dinh'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung</label>
                                <textarea name="noi_dung" class="form-control" rows="4"><?= htmlspecialchars($award['noi_dung'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/BTL/views/award.php" class="btn btn-secondary">Hủy</a>
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
            const loaiId = document.querySelector('select[name="loai_id"]').value;
            const ngayQuyetDinh = document.querySelector('input[name="ngay_quyet_dinh"]').value;
            if (!doanVienId) {
                alert('Vui lòng chọn đoàn viên.');
                return false;
            }
            if (!loaiId) {
                alert('Vui lòng chọn loại khen thưởng.');
                return false;
            }
            if (!ngayQuyetDinh) {
                alert('Vui lòng chọn ngày quyết định.');
                return false;
            }
            return true;
        }

        function updateDescription() {
            const loaiIdSelect = document.querySelector('select[name="loai_id"]');
            const descriptionArea = document.getElementById('loai_mo_ta');
            const selectedOption = loaiIdSelect.options[loaiIdSelect.selectedIndex];
            descriptionArea.value = selectedOption ? selectedOption.getAttribute('data-description') : '';
        }
    </script>
</body>
</html>