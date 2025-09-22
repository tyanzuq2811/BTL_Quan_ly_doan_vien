<?php
require_once __DIR__ . '/../functions/db_connection.php';

$conn = getDbConnection();

try {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM doan_vien");
    $total_members = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM chi_doan");
    $total_branches = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM su_kien");
    $total_events = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM doan_phi WHERE trang_thai = 'Chưa nộp'");
    $unpaid_fees = mysqli_fetch_assoc($result)['total'];

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

include __DIR__ . '/header.php';
?>
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
<?php include __DIR__ . '/footer.php'; ?>
