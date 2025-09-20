<?php
require_once __DIR__ . '/../../functions/event_functions.php';
require_once __DIR__ . '/../../functions/youth_union_team_functions.php';
require_once __DIR__ . '/../../functions/youth_union_chapter_functions.php';
require_once __DIR__ . '/../../functions/auth.php';

checkLogin(__DIR__ . '/../../authentication_login.php');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
$event = getEventById($id);
$teams = getAllTeams();
$chapters = getAllChapters();

if (!$event) {
    header("Location: /BTL/views/event.php?error=Sự kiện không tồn tại");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Event</title>
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
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/BTL/views/event.php" aria-expanded="false">
                                <span><iconify-icon icon="solar:calendar-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Quản lý sự kiện</span>
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
                        <h2>Sửa sự kiện</h2>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/BTL/handle/event_process.php?action=edit" onsubmit="return validateForm()">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($event['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label class="form-label">Tên sự kiện</label>
                                <input type="text" name="ten_su_kien" class="form-control" value="<?= htmlspecialchars($event['ten_su_kien'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="mo_ta" class="form-control" rows="4"><?= htmlspecialchars($event['mo_ta'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày tổ chức</label>
                                <input type="date" name="ngay_to_chuc" class="form-control" value="<?= htmlspecialchars($event['ngay_to_chuc'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cấp tổ chức</label>
                                <select name="cap_to_chuc" class="form-select" required onchange="updateCapIdOptions()">
                                    <option value="">-- Chọn cấp tổ chức --</option>
                                    <option value="DoanTruong" <?= $event['cap_to_chuc'] == 'DoanTruong' ? 'selected' : '' ?>>Đoàn Trường</option>
                                    <option value="LienChi" <?= $event['cap_to_chuc'] == 'LienChi' ? 'selected' : '' ?>>Liên Chi</option>
                                    <option value="ChiDoan" <?= $event['cap_to_chuc'] == 'ChiDoan' ? 'selected' : '' ?>>Chi Đoàn</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tên cấp tổ chức</label>
                                <select name="cap_id" id="cap_id" class="form-select" required>
                                    <option value="">-- Chọn tên cấp tổ chức --</option>
                                    <?php if ($event['cap_to_chuc'] == 'DoanTruong'): ?>
                                        <option value="1" selected>Đoàn Trường</option>
                                    <?php elseif ($event['cap_to_chuc'] == 'LienChi'): ?>
                                        <?php foreach ($chapters as $chapter): ?>
                                            <option value="<?= htmlspecialchars($chapter['id'], ENT_QUOTES, 'UTF-8') ?>" <?= $chapter['id'] == $event['cap_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($chapter['ten'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php elseif ($event['cap_to_chuc'] == 'ChiDoan'): ?>
                                        <?php foreach ($teams as $team): ?>
                                            <option value="<?= htmlspecialchars($team['id'], ENT_QUOTES, 'UTF-8') ?>" <?= $team['id'] == $event['cap_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($team['ten'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="Sắp diễn ra" <?= $event['trang_thai'] == 'Sắp diễn ra' ? 'selected' : '' ?>>Sắp diễn ra</option>
                                    <option value="Đang diễn ra" <?= $event['trang_thai'] == 'Đang diễn ra' ? 'selected' : '' ?>>Đang diễn ra</option>
                                    <option value="Kết thúc" <?= $event['trang_thai'] == 'Kết thúc' ? 'selected' : '' ?>>Kết thúc</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/BTL/views/event.php" class="btn btn-secondary">Hủy</a>
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
        const teams = <?= json_encode($teams) ?>;
        const chapters = <?= json_encode($chapters) ?>;
        
        function validateForm() {
            const tenSuKien = document.querySelector('input[name="ten_su_kien"]').value.trim();
            const ngayToChuc = document.querySelector('input[name="ngay_to_chuc"]').value;
            const capToChuc = document.querySelector('select[name="cap_to_chuc"]').value;
            const capId = document.querySelector('select[name="cap_id"]').value;
            if (!tenSuKien) {
                alert('Vui lòng nhập tên sự kiện.');
                return false;
            }
            if (!ngayToChuc) {
                alert('Vui lòng chọn ngày tổ chức.');
                return false;
            }
            if (!capToChuc) {
                alert('Vui lòng chọn cấp tổ chức.');
                return false;
            }
            if (!capId) {
                alert('Vui lòng chọn tên cấp tổ chức.');
                return false;
            }
            return true;
        }

        function updateCapIdOptions() {
            const capToChuc = document.querySelector('select[name="cap_to_chuc"]').value;
            const capIdSelect = document.querySelector('select[name="cap_id"]');
            capIdSelect.innerHTML = '<option value="">-- Chọn tên cấp tổ chức --</option>';

            if (capToChuc === 'DoanTruong') {
                capIdSelect.innerHTML += '<option value="1">Đoàn Trường</option>';
            } else if (capToChuc === 'LienChi') {
                chapters.forEach(chapter => {
                    capIdSelect.innerHTML += `<option value="${chapter.id}">${chapter.ten}</option>`;
                });
            } else if (capToChuc === 'ChiDoan') {
                teams.forEach(team => {
                    capIdSelect.innerHTML += `<option value="${team.id}">${team.ten}</option>`;
                });
            }
        }
    </script>
</body>
</html>