<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/student_functions.php';
require_once __DIR__ . '/../../functions/db_connection.php';

checkLogin();
$conn = getDbConnection();

$user_id = $_SESSION['user_id']; // id của tài khoản
$student = getStudentByAccount($user_id);
$fees = getStudentUnionFees($student['id']);
$scores = getStudentScores($student['id']);
$events = getStudentEvents($student['id']);
$awards = getStudentAwards($student['id']);
$disciplines = getStudentDisciplines($student['id']);
?>
 <?php include __DIR__ . "/header_student.php"; ?>
<!doctype html>
<html lang="vi">
<head>
  <style>
    body {
      background: #f8f9fa;
      font-family: "Segoe UI", Tahoma, sans-serif;
    }

    /* Card nhiều màu */
    .card {
      border: none;
      border-radius: 16px;
      padding: 20px;
      transition: all 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    }
    .card-profile { background: #e3f2fd; }
    .card-fee { background: #fff3e0; }
    .card-score { background: #f3e5f5; }
    .card-event { background: #e8f5e9; }
    .card-award { background: #fce4ec; }
    .card-discipline { background: #ede7f6; }

    h3.section-title {
      font-weight: 600;
      margin-top: 30px;
      margin-bottom: 20px;
      color: #333;
      border-left: 5px solid #0d6efd;
      padding-left: 10px;
    }

    ul.list-unstyled li {
      padding: 6px 0;
      font-size: 15px;
      border-bottom: 1px dashed #ccc;
    }
    ul.list-unstyled li:last-child {
      border: none;
    }

    /* Responsive cho mobile */
    @media (max-width: 768px) {
      .body-wrapper {
        margin-left: 0;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
<div class="page-wrapper" id="main-wrapper">

  <!-- Sidebar + header đoàn viên -->

  <!-- Nội dung -->
  <div class="body-wrapper">
    <div class="container-fluid">
      <h3 class="mb-2">👋 Xin chào, <span class="text-primary"><?php echo htmlspecialchars($student['ho_ten']); ?></span></h3>

      <div class="row g-4">
        <!-- Hồ sơ -->
        <div class="col-md-6 col-lg-3">
          <div class="card card-profile shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <iconify-icon icon="solar:user-bold-duotone" class="fs-2 text-primary me-2"></iconify-icon>
              <h5 class="mb-0">Hồ sơ</h5>
            </div>
            <p><strong>Họ tên:</strong> <?php echo $student['ho_ten']; ?></p>
            <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
            <p><strong>Chi đoàn:</strong> <?php echo $student['chi_doan']; ?></p>
          </div>
        </div>

        <!-- Đoàn phí -->
        <div class="col-md-6 col-lg-3">
          <div class="card card-fee shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <iconify-icon icon="solar:money-bag-bold-duotone" class="fs-2 text-success me-2"></iconify-icon>
              <h5 class="mb-0">Đoàn phí</h5>
            </div>
            <?php if (!empty($fees)): ?>
              <p><strong>Năm học:</strong> <?php echo $fees[0]['nam_hoc']; ?></p>
              <p><strong>Tình trạng:</strong> <?php echo $fees[0]['trang_thai']; ?></p>
            <?php else: ?>
              <p>Chưa có dữ liệu</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Điểm rèn luyện -->
        <div class="col-md-6 col-lg-3">
          <div class="card card-score shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <iconify-icon icon="solar:star-bold-duotone" class="fs-2 text-warning me-2"></iconify-icon>
              <h5 class="mb-0">Điểm rèn luyện</h5>
            </div>
            <?php if (!empty($scores)): ?>
              <p><strong>Học kỳ:</strong> <?php echo $scores[0]['hoc_ky']; ?></p>
              <p><strong>Điểm:</strong> <?php echo $scores[0]['diem']; ?></p>
            <?php else: ?>
              <p>Chưa có dữ liệu</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Sự kiện -->
        <div class="col-md-6 col-lg-3">
          <div class="card card-event shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <iconify-icon icon="solar:calendar-bold-duotone" class="fs-2 text-danger me-2"></iconify-icon>
              <h5 class="mb-0">Sự kiện</h5>
            </div>
            <?php if (!empty($events)): ?>
              <p><strong><?php echo $events[0]['ten_su_kien']; ?></strong></p>
              <p><?php echo $events[0]['ngay_to_chuc']; ?></p>
            <?php else: ?>
              <p>Chưa có sự kiện</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Khen thưởng & kỷ luật -->
      <div class="row g-2">
        <!-- Khen thưởng -->
        <div class="col-lg-6">
          <div class="card card-award shadow-sm h-100">
            <h5 class="mb-3">Khen thưởng</h5>
            <?php if (!empty($awards)): ?>
              <ul class="list-unstyled">
                <?php foreach ($awards as $aw): ?>
                  <li>🏅 <?php echo $aw['noi_dung']; ?> <span class="text-muted">(<?php echo $aw['ngay_quyet_dinh']; ?>)</span></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>Chưa có</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Kỷ luật -->
        <div class="col-lg-6">
          <div class="card card-discipline shadow-sm h-100">
            <h5 class="mb-3">Kỷ luật</h5>
            <?php if (!empty($disciplines)): ?>
              <ul class="list-unstyled">
                <?php foreach ($disciplines as $dl): ?>
                  <li>⚠️ <?php echo $dl['noi_dung']; ?> <span class="text-muted">(<?php echo $dl['ngay']; ?>)</span></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>Chưa có</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

<?php include(__DIR__ . '../../footer.php'); ?>
</html>
