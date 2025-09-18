<?php
// Include file kết nối database - Updated path
require_once __DIR__ . '/../functions/db_connection.php';

// Lấy kết nối
$conn = getDbConnection();

// Lấy thống kê tổng quan
try {
    // Tổng số đoàn viên
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM doan_vien");
    $total_members = mysqli_fetch_assoc($result)['total'];

    // Tổng số chi đoàn
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM chi_doan");
    $total_branches = mysqli_fetch_assoc($result)['total'];

    // Tổng số sự kiện
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM su_kien");
    $total_events = mysqli_fetch_assoc($result)['total'];

    // Số đoàn viên chưa nộp đoàn phí
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM doan_phi WHERE trang_thai = 'Chưa nộp'");
    $unpaid_fees = mysqli_fetch_assoc($result)['total'];

    // Lấy danh sách đoàn viên với thông tin chi tiết
    $members_query = "
        SELECT 
            dv.mssv,
            dv.ho_ten,
            dv.email,
            cd.ten as chi_doan,
            COALESCE(drl.diem, 0) as diem,
            COALESCE(drl.xep_loai, 'Chưa đánh giá') as xep_loai,
            COALESCE(dp.trang_thai, 'Chưa có') as trang_thai_doan_phi
        FROM doan_vien dv
        LEFT JOIN chi_doan cd ON dv.chi_doan_id = cd.id
        LEFT JOIN diem_ren_luyen drl ON dv.id = drl.doan_vien_id
        LEFT JOIN doan_phi dp ON dv.id = dp.doan_vien_id
        ORDER BY dv.mssv
    ";
    $members = mysqli_query($conn, $members_query);

    // Thống kê theo chi đoàn
    $branch_stats_query = "
        SELECT 
            cd.ten,
            COUNT(dv.id) as so_thanh_vien,
            ROUND((COUNT(dv.id) * 100.0 / (SELECT COUNT(*) FROM doan_vien)), 1) as ti_le
        FROM chi_doan cd
        LEFT JOIN doan_vien dv ON cd.id = dv.chi_doan_id
        GROUP BY cd.id, cd.ten
        ORDER BY so_thanh_vien DESC
    ";
    $branch_stats = mysqli_query($conn, $branch_stats_query);

    // Lấy sự kiện gần đây
    $events_query = "
        SELECT 
            ten_su_kien,
            mo_ta,
            DATE_FORMAT(ngay_to_chuc, '%d/%m/%Y') as ngay_to_chuc_formatted,
            cap_to_chuc
        FROM su_kien 
        ORDER BY ngay_to_chuc DESC 
        LIMIT 3
    ";
    $recent_events = mysqli_query($conn, $events_query);

    // Lấy thông báo gần đây
    $notifications_query = "
        SELECT 
            tb.tieu_de,
            tb.noi_dung,
            tb.cap_to_chuc,
            dv.ho_ten as nguoi_gui
        FROM thong_bao tb
        LEFT JOIN doan_vien dv ON tb.nguoi_gui = dv.id
        ORDER BY tb.id DESC
        LIMIT 2
    ";
    $recent_notifications = mysqli_query($conn, $notifications_query);

} catch(Exception $e) {
    echo "Lỗi: " . $e->getMessage();
    exit;
}
?>

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
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="../assets/images/logos/logo-light.svg" alt="" />
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
              <a class="sidebar-link" href="youth_union_chapter.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Quản lý liên chi đoàn</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="youth_union_team.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:danger-circle-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Quản lý chi đoàn</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="member.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:bookmark-square-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                </span>
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
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
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
      <!--  Header End -->
  <div class="container-fluid">
    <!-- Statistics Cards Row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 zoom-in bg-primary-subtle shadow-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?php echo $total_members; ?></h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-users text-danger"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Tổng đoàn viên</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-7 d-flex text-primary"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 zoom-in bg-warning-subtle shadow-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?php echo $total_branches; ?></h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-2 rounded-circle bg-light-warning round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-building text-warning"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Chi đoàn</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <iconify-icon icon="solar:buildings-3-bold-duotone" class="fs-7 d-flex text-warning"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 zoom-in bg-info-subtle shadow-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?php echo $total_events; ?></h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-2 rounded-circle bg-light-info round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-calendar text-info"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Sự kiện</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <iconify-icon icon="solar:calendar-bold-duotone" class="fs-7 d-flex text-info"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 zoom-in bg-danger-subtle shadow-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?php echo $unpaid_fees; ?></h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-alert-circle text-danger"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Nợ đoàn phí</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <iconify-icon icon="solar:wallet-money-bold-duotone" class="fs-7 d-flex text-danger"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Danh sách đoàn viên -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                        Danh sách Đoàn viên
                        <span>
                            <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                        </span>
                    </h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0">MSSV</th>
                                    <th scope="col">Họ tên</th>
                                    <th scope="col">Chi đoàn</th>
                                    <th scope="col" class="text-center">Điểm RL</th>
                                    <th scope="col" class="text-center">Đoàn phí</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php if($members && mysqli_num_rows($members) > 0): ?>
                                    <?php while($member = mysqli_fetch_assoc($members)): ?>
                                    <tr>
                                        <th scope="row" class="ps-0 fw-medium">
                                            <span class="table-link1 text-truncate d-block"><?php echo htmlspecialchars($member['mssv']); ?></span>
                                        </th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="fw-semibold mb-1"><?php echo htmlspecialchars($member['ho_ten']); ?></h6>
                                                    <span class="fs-2 d-block text-muted"><?php echo htmlspecialchars($member['email']); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($member['chi_doan']) ?: 'Chưa có'; ?></span>
                                        </td>
                                        <td class="text-center fw-medium">
                                            <?php if($member['diem'] > 0): ?>
                                                <span class="badge 
                                                    <?php 
                                                        if($member['diem'] >= 90) echo 'bg-success-subtle text-success';
                                                        elseif($member['diem'] >= 80) echo 'bg-info-subtle text-info';
                                                        elseif($member['diem'] >= 70) echo 'bg-warning-subtle text-warning';
                                                        else echo 'bg-danger-subtle text-danger';
                                                    ?>">
                                                    <?php echo htmlspecialchars($member['diem'] . ' - ' . $member['xep_loai']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa có</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fw-medium">
                                            <?php if($member['trang_thai_doan_phi'] == 'Đã nộp'): ?>
                                                <span class="badge bg-success-subtle text-success">Đã nộp</span>
                                            <?php elseif($member['trang_thai_doan_phi'] == 'Chưa nộp'): ?>
                                                <span class="badge bg-danger-subtle text-danger">Chưa nộp</span>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa có</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Chưa có dữ liệu đoàn viên</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê chi đoàn -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center gap-2 mb-5 pb-3">
                        Thành viên theo Chi đoàn
                        <span>
                            <iconify-icon icon="solar:pie-chart-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                        </span>
                    </h5>
                    
                    <?php if($branch_stats && mysqli_num_rows($branch_stats) > 0): ?>
                        <?php 
                        $branch_data = [];
                        while($branch = mysqli_fetch_assoc($branch_stats)) {
                            $branch_data[] = $branch;
                        }
                        $branch_count = count($branch_data);
                        ?>
                        
                        <div class="row">
                            <?php foreach($branch_data as $index => $branch): ?>
                            <div class="col-<?php echo $branch_count == 3 ? '4' : '6'; ?>">
                                <iconify-icon icon="solar:buildings-3-bold-duotone" class="fs-7 d-flex 
                                    <?php echo $index == 0 ? 'text-primary' : ($index == 1 ? 'text-secondary' : 'text-success'); ?>"></iconify-icon>
                                <span class="fs-11 mt-2 d-block text-nowrap"><?php echo htmlspecialchars($branch['ten']); ?></span>
                                <h4 class="mb-0 mt-1"><?php echo htmlspecialchars($branch['ti_le']); ?>%</h4>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="vstack gap-4 mt-7 pt-2">
                            <?php foreach($branch_data as $index => $branch): ?>
                            <div>
                                <div class="hstack justify-content-between">
                                    <span class="fs-3 fw-medium"><?php echo htmlspecialchars($branch['ten']); ?></span>
                                    <h6 class="fs-3 fw-medium text-dark lh-base mb-0"><?php echo htmlspecialchars($branch['ti_le']); ?>%</h6>
                                </div>
                                <div class="progress mt-6" role="progressbar">
                                    <div class="progress-bar 
                                        <?php echo $index == 0 ? 'bg-primary' : ($index == 1 ? 'bg-secondary' : 'bg-success'); ?>" 
                                        style="width: <?php echo htmlspecialchars($branch['ti_le']); ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Chưa có dữ liệu chi đoàn</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sự kiện và Thông báo -->
    <div class="row">
        <!-- Sự kiện gần đây -->
        <div class="col-lg-6">
            <div class="card overflow-hidden hover-img">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                        Sự kiện gần đây
                        <span>
                            <iconify-icon icon="solar:calendar-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                        </span>
                    </h5>
                    <?php if($recent_events && mysqli_num_rows($recent_events) > 0): ?>
                        <?php while($event = mysqli_fetch_assoc($recent_events)): ?>
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                            <div class="me-3">
                                <span class="badge bg-light-primary text-primary fs-2 py-1 px-2"><?php echo htmlspecialchars($event['cap_to_chuc']); ?></span>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1"><?php echo htmlspecialchars($event['ten_su_kien']); ?></h6>
                                <p class="mb-1 fs-2 text-muted"><?php echo htmlspecialchars($event['mo_ta']); ?></p>
                                <div class="d-flex align-items-center fs-2 text-muted">
                                    <i class="ti ti-calendar me-1"></i><?php echo htmlspecialchars($event['ngay_to_chuc_formatted']); ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted">Chưa có sự kiện nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Thông báo gần đây -->
        <div class="col-lg-6">
            <div class="card overflow-hidden hover-img">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                        Thông báo mới nhất
                        <span>
                            <iconify-icon icon="solar:bell-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                        </span>
                    </h5>
                    <?php if($recent_notifications && mysqli_num_rows($recent_notifications) > 0): ?>
                        <?php while($notification = mysqli_fetch_assoc($recent_notifications)): ?>
                        <div class="d-flex align-items-start mb-4 pb-3 border-bottom">
                            <div class="me-3">
                                <span class="badge bg-light-info text-info fs-2 py-1 px-2"><?php echo htmlspecialchars($notification['cap_to_chuc']); ?></span>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1"><?php echo htmlspecialchars($notification['tieu_de']); ?></h6>
                                <p class="mb-1 fs-2 text-muted"><?php echo htmlspecialchars(mb_substr($notification['noi_dung'], 0, 100) . '...'); ?></p>
                                <div class="d-flex align-items-center fs-2 text-muted">
                                    <i class="ti ti-user me-1"></i>Từ: <?php echo htmlspecialchars($notification['nguoi_gui']) ?: 'Hệ thống'; ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted">Chưa có thông báo nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
  </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>
  <script src="../assets/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
