<?php
require_once("../../functions/auth.php");
require_once("../../functions/student_functions.php");
checkLogin();

$user_id = $_SESSION['user_id'];
$fees = getStudentUnionFees($user_id);
?>

<?php include("../../views/student/header_student.php"); ?>

<div class="body-wrapper">
  <div class="container-fluid mt-4">

    <div class="card shadow-sm p-4">
      <h3 class="mb-4">💰 Đoàn phí</h3>

      <?php if (!empty($fees)): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary text-center">
              <tr>
                <th>Năm học</th>
                <th>Số tiền</th>
                <th>Tình trạng</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($fees as $f): ?>
                <tr>
                  <td class="text-center"><?php echo htmlspecialchars($f['nam_hoc']); ?></td>
                  <td class="text-center fw-bold"><?php echo number_format($f['so_tien'], 0, ',', '.'); ?> đ</td>
                  <td class="text-center">
                    <?php if ($f['trang_thai'] === 'Đã đóng'): ?>
                      <span class="badge bg-success">Đã đóng</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Chưa đóng</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">Chưa có dữ liệu đoàn phí.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php include("../../views/footer.php"); ?>
