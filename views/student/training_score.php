<?php
require_once("../../functions/auth.php");
require_once("../../functions/student_functions.php");
checkLogin();

$user_id = $_SESSION['user_id'];
$scores = getStudentScores($user_id);
?>

<?php include("../../views/student/header_student.php"); ?>

<div class="body-wrapper">
  <div class="container-fluid mt-4">

    <div class="card shadow-sm p-4">
      <h3 class="mb-4">📊 Điểm rèn luyện</h3>

      <?php if (!empty($scores)): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary text-center">
              <tr>
                <th>Học kỳ</th>
                <th>Điểm</th>
                <th>Xếp loại</th>
                <th>Nhận xét</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($scores as $s): ?>
                <tr>
                  <td class="text-center"><?php echo htmlspecialchars($s['hoc_ky']); ?></td>
                  <td class="text-center fw-bold"><?php echo htmlspecialchars($s['diem']); ?></td>
                  <td class="text-center">
                    <span class="badge bg-success"><?php echo htmlspecialchars($s['xep_loai']); ?></span>
                  </td>
                  <td><?php echo htmlspecialchars($s['nhan_xet']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">Chưa có dữ liệu điểm rèn luyện.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php include("../../views/footer.php"); ?>