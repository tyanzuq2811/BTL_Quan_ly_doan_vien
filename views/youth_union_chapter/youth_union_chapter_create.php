<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Thêm liên chi đoàn</title>
  <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="/BTL/assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="/BTL/assets/images/logos/logo-light.svg" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="dashboard.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6 d-flex"></iconify-icon>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Danh mục quản lý</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="/BTL/views/youth_union_chapter.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                </span>
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
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-6" class="fs-6"></iconify-icon>
              <span class="hide-menu">AUTH</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="../authentication_login.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:login-3-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Login</span>
              </a>
            </li>
          </ul>
          <div class="unlimited-access hide-menu bg-primary-subtle position-relative mb-7 mt-7 rounded-3"> 
            <div class="d-flex">
              <div class="unlimited-access-img">
                <img src="../assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
              </div>
            </div>
          </div>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
  </div>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
            <h3 class="mt-3 mb-4">THÊM LIÊN CHI ĐOÀN</h3>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="../../handle/youth_union_chapter_process.php" method="POST">
                <input type="hidden" name="action" value="create">
                
                <div class="mb-3">
                <label for="ma_lien_chi_doan" class="form-label">Mã Liên chi đoàn</label>
                <input type="text" class="form-control" id="ma_lien_chi_doan" name="ma_lien_chi_doan" required>
                </div>

                <div class="mb-3">
                <label for="ten" class="form-label">Tên Liên chi đoàn</label>
                <input type="text" class="form-control" id="ten" name="ten" required>
                </div>

                <div class="mb-3">
                <label for="doan_truong_id" class="form-label">Đoàn trưởng</label>
                <input type="text" class="form-control" id="doan_truong_id" name="doan_truong_id">
                </div>

                <div class="mb-3">
                <label for="ngay_thanh_lap" class="form-label">Ngày thành lập</label>
                <input type="date" class="form-control" id="ngay_thanh_lap" name="ngay_thanh_lap">
                </div>

                <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select class="form-select" id="trang_thai" name="trang_thai">
                    <option value="Hoạt động">Hoạt động</option>
                    <option value="Ngừng hoạt động">Ngừng hoạt động</option>
                </select>
                </div>

                <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Thêm Liên chi đoàn</button>
                <a href="../youth_union_chapter.php" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
            </div>
        </div>
    </div>
    </div> <!-- .body-wrapper -->
    </div> <!-- .page-wrapper -->
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