<?php
require_once __DIR__ . '/../functions/db_connection.php';

$conn = getDbConnection();

try {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tai_khoan");
    $total_accounts = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT vai_tro, COUNT(*) as count FROM tai_khoan GROUP BY vai_tro");
    $role_stats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $role_stats[$row['vai_tro']] = $row['count'];
    }

    $result = mysqli_query($conn, "SELECT trang_thai, COUNT(*) as count FROM tai_khoan GROUP BY trang_thai");
    $status_stats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $status_stats[$row['trang_thai']] = $row['count'];
    }

    $accounts_query = "
        SELECT 
            tk.ten_dang_nhap,
            tk.vai_tro,
            tk.trang_thai,
            dv.ho_ten,
            dv.mssv,
            dv.email
        FROM tai_khoan tk
        LEFT JOIN doan_vien dv ON tk.doan_vien_id = dv.id
        ORDER BY tk.id DESC
        LIMIT 10
    ";
    $accounts = mysqli_query($conn, $accounts_query);

    $role_details_query = "
        SELECT 
            vai_tro,
            COUNT(*) as so_luong,
            ROUND((COUNT(*) * 100.0 / $total_accounts), 1) as ti_le
        FROM tai_khoan 
        GROUP BY vai_tro
        ORDER BY so_luong DESC
    ";
    $role_details = mysqli_query($conn, $role_details_query);

    $system_events_query = "
        SELECT 
            'Hệ thống' as ten_su_kien,
            CONCAT('Tài khoản mới: ', ten_dang_nhap) as mo_ta,
            DATE_FORMAT(CURDATE(), '%d/%m/%Y') as ngay_to_chuc_formatted,
            vai_tro as cap_to_chuc
        FROM tai_khoan 
        ORDER BY id DESC 
        LIMIT 3
    ";
    $recent_system_events = mysqli_query($conn, $system_events_query);

    $notifications_query = "
        SELECT 
            CONCAT('Thống kê: ', vai_tro) as tieu_de,
            CONCAT('Số lượng tài khoản: ', COUNT(*), ' (', ROUND((COUNT(*) * 100.0 / $total_accounts), 1), '%)') as noi_dung,
            'Hệ thống' as cap_to_chuc,
            'Admin' as nguoi_gui
        FROM tai_khoan 
        GROUP BY vai_tro
        ORDER BY COUNT(*) DESC
        LIMIT 2
    ";
    $recent_notifications = mysqli_query($conn, $notifications_query);

} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý tài khoản</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-center">
          <a href="dashboard.php" class="text-nowrap logo-img">
            <img src="../assets/images/logos/fitdnu_logo.png" alt="" width="80"/>
            <img src="../assets/images/logos/aiotlab_logo.png" alt="" width="80" style="margin-left:22px;"/>
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
              <a class="sidebar-link" href="admin_dashboard.php" aria-expanded="false">
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
              <a class="sidebar-link" href="account.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Quản lý tài khoản</span>
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
      <div class="container-fluid">
        <!-- Statistics Cards Row -->
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="card border-0 zoom-in bg-primary-subtle shadow-none">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="col-8">
                    <h4 class="fw-semibold mb-3"><?php echo $total_accounts; ?></h4>
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-2 rounded-circle bg-light-primary round-20 d-flex align-items-center justify-content-center">
                        <i class="ti ti-users text-primary"></i>
                      </span>
                      <p class="text-dark me-1 fs-3 mb-0">Tổng tài khoản</p>
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
            <div class="card border-0 zoom-in bg-success-subtle shadow-none">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="col-8">
                    <h4 class="fw-semibold mb-3"><?php echo $role_stats['Admin'] ?? 0; ?></h4>
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-2 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                        <i class="ti ti-shield-check text-success"></i>
                      </span>
                      <p class="text-dark me-1 fs-3 mb-0">Admin</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="d-flex justify-content-end">
                      <iconify-icon icon="solar:shield-check-bold-duotone" class="fs-7 d-flex text-success"></iconify-icon>
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
                    <h4 class="fw-semibold mb-3"><?php echo $role_stats['DoanTruong'] ?? 0; ?></h4>
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-2 rounded-circle bg-light-info round-20 d-flex align-items-center justify-content-center">
                        <i class="ti ti-building text-info"></i>
                      </span>
                      <p class="text-dark me-1 fs-3 mb-0">Đoàn trường</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="d-flex justify-content-end">
                      <iconify-icon icon="solar:buildings-3-bold-duotone" class="fs-7 d-flex text-info"></iconify-icon>
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
                    <h4 class="fw-semibold mb-3"><?php echo $role_stats['DoanVien'] ?? 0; ?></h4>
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-2 rounded-circle bg-light-warning round-20 d-flex align-items-center justify-content-center">
                        <i class="ti ti-user text-warning"></i>
                      </span>
                      <p class="text-dark me-1 fs-3 mb-0">Đoàn viên</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="d-flex justify-content-end">
                      <iconify-icon icon="solar:user-bold-duotone" class="fs-7 d-flex text-warning"></iconify-icon>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">
          <!-- Danh sách tài khoản -->
          <div class="col-lg-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                  Danh sách Tài khoản
                  <span>
                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                  </span>
                </h5>
                <div class="table-responsive">
                  <table class="table text-nowrap align-middle mb-0">
                    <thead>
                      <tr class="border-2 border-bottom border-primary border-0">
                        <th scope="col" class="ps-0">Tên đăng nhập</th>
                        <th scope="col">Họ tên</th>
                        <th scope="col">Vai trò</th>
                        <th scope="col" class="text-center">Trạng thái</th>
                        <th scope="col" class="text-center">MSSV</th>
                      </tr>
                    </thead>
                    <tbody class="table-group-divider">
                      <?php if ($accounts && mysqli_num_rows($accounts) > 0): ?>
                        <?php while ($account = mysqli_fetch_assoc($accounts)): ?>
                          <tr>
                            <th scope="row" class="ps-0 fw-medium">
                              <span class="table-link1 text-truncate d-block"><?php echo htmlspecialchars($account['ten_dang_nhap']); ?></span>
                            </th>
                            <td>
                              <div class="d-flex align-items-center">
                                <div>
                                  <h6 class="fw-semibold mb-1"><?php echo htmlspecialchars($account['ho_ten'] ?: 'Chưa có'); ?></h6>
                                  <span class="fs-2 d-block text-muted"><?php echo htmlspecialchars($account['email'] ?: 'Chưa có'); ?></span>
                                </div>
                              </div>
                            </td>
                            <td>
                              <span class="badge 
                                <?php 
                                  switch ($account['vai_tro']) {
                                    case 'Admin':
                                      echo 'bg-primary-subtle text-primary';
                                      break;
                                    case 'DoanTruong':
                                      echo 'bg-info-subtle text-info';
                                      break;
                                    default:
                                      echo 'bg-secondary-subtle text-secondary';
                                  }
                                ?>">
                                <?php echo htmlspecialchars($account['vai_tro']); ?>
                              </span>
                            </td>
                            <td class="text-center fw-medium">
                              <?php if ($account['trang_thai'] == 'Hoạt động'): ?>
                                <span class="badge bg-success-subtle text-success">Hoạt động</span>
                              <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger">Khóa</span>
                              <?php endif; ?>
                            </td>
                            <td class="text-center fw-medium">
                              <span class="text-muted"><?php echo htmlspecialchars($account['mssv'] ?: 'Chưa có'); ?></span>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="5" class="text-center text-muted">Chưa có dữ liệu tài khoản</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Thống kê theo vai trò -->
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title d-flex align-items-center gap-2 mb-5 pb-3">
                  Phân bổ theo Vai trò
                  <span>
                    <iconify-icon icon="solar:pie-chart-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                  </span>
                </h5>
                
                <?php if ($role_details && mysqli_num_rows($role_details) > 0): ?>
                  <?php 
                  $role_data = [];
                  while ($role = mysqli_fetch_assoc($role_details)) {
                    $role_data[] = $role;
                  }
                  $role_count = count($role_data);
                  ?>
                  
                  <div class="row">
                    <?php foreach ($role_data as $index => $role): ?>
                      <div class="col-<?php echo $role_count == 3 ? '4' : '6'; ?>">
                        <iconify-icon icon="solar:shield-check-bold-duotone" class="fs-7 d-flex 
                          <?php 
                            switch ($role['vai_tro']) {
                              case 'Admin':
                                echo 'text-primary';
                                break;
                              case 'DoanTruong':
                                echo 'text-info';
                                break;
                              default:
                                echo 'text-success';
                            }
                          ?>"></iconify-icon>
                        <span class="fs-11 mt-2 d-block text-nowrap"><?php echo htmlspecialchars($role['vai_tro']); ?></span>
                        <h4 class="mb-0 mt-1"><?php echo htmlspecialchars($role['ti_le']); ?>%</h4>
                      </div>
                    <?php endforeach; ?>
                  </div>

                  <div class="vstack gap-4 mt-7 pt-2">
                    <?php mysqli_data_seek($role_details, 0); ?>
                    <?php while ($role = mysqli_fetch_assoc($role_details)): ?>
                      <div>
                        <div class="hstack justify-content-between">
                          <span class="fs-3 fw-medium"><?php echo htmlspecialchars($role['vai_tro']); ?></span>
                          <h6 class="fs-3 fw-medium text-dark lh-base mb-0"><?php echo htmlspecialchars($role['ti_le']); ?>%</h6>
                        </div>
                        <div class="progress mt-6" role="progressbar">
                          <div class="progress-bar 
                            <?php 
                              switch ($role['vai_tro']) {
                                case 'Admin':
                                  echo 'bg-primary';
                                  break;
                                case 'DoanTruong':
                                  echo 'bg-info';
                                  break;
                                default:
                                  echo 'bg-success';
                              }
                            ?>" 
                            style="width: <?php echo htmlspecialchars($role['ti_le']); ?>%"></div>
                        </div>
                      </div>
                    <?php endwhile; ?>
                  </div>
                <?php else: ?>
                  <p class="text-muted">Chưa có dữ liệu vai trò</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Sự kiện và Thông báo -->
        <div class="row">
          <!-- Hoạt động hệ thống gần đây -->
          <div class="col-lg-6">
            <div class="card overflow-hidden hover-img">
              <div class="card-body">
                <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                  Hoạt động gần đây
                  <span>
                    <iconify-icon icon="solar:activity-rounded-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                  </span>
                </h5>
                <?php if ($recent_system_events && mysqli_num_rows($recent_system_events) > 0): ?>
                  <?php while ($event = mysqli_fetch_assoc($recent_system_events)): ?>
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
                  <p class="text-muted">Chưa có hoạt động nào</p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Thống kê mới nhất -->
          <div class="col-lg-6">
            <div class="card overflow-hidden hover-img">
              <div class="card-body">
                <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                  Thống kê mới nhất
                  <span>
                    <iconify-icon icon="solar:chart-line-bold-duotone" class="fs-7 d-flex text-muted"></iconify-icon>
                  </span>
                </h5>
                <?php if ($recent_notifications && mysqli_num_rows($recent_notifications) > 0): ?>
                  <?php while ($notification = mysqli_fetch_assoc($recent_notifications)): ?>
                    <div class="d-flex align-items-start mb-4 pb-3 border-bottom">
                      <div class="me-3">
                        <span class="badge bg-light-info text-info fs-2 py-1 px-2">Hệ thống</span>
                      </div>
                      <div>
                        <h6 class="fw-semibold mb-1"><?php echo htmlspecialchars($notification['tieu_de']); ?></h6>
                        <p class="mb-1 fs-2 text-muted"><?php echo htmlspecialchars($notification['noi_dung']); ?></p>
                        <div class="d-flex align-items-center fs-2 text-muted">
                          <i class="ti ti-chart-line me-1"></i>Từ: <?php echo htmlspecialchars($notification['nguoi_gui']); ?>
                        </div>
                      </div>
                    </div>
                  <?php endwhile; ?>
                <?php else: ?>
                  <p class="text-muted">Chưa có thống kê nào</p>
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