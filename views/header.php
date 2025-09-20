<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý đoàn viên</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="dashboard.php" class="text-nowrap logo-img">
            <img src="../assets/images/logos/logo-light.svg" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Trang chủ</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="dashboard.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6 d-flex"></iconify-icon>
                </span>
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
            <li class="sidebar-item">
              <a class="sidebar-link" href="/BTL/views/discipline.php" aria-expanded="false">
                <span><iconify-icon icon="solar:document-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Quản lý kỷ luật</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="/BTL/views/notification.php" aria-expanded="false">
                <span><iconify-icon icon="solar:bell-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Quản lý thông báo</span>
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
      </div>
    </aside>
    <div class="body-wrapper">
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="../authentication_login.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
