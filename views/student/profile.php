<?php
require_once("../../functions/auth.php");
require_once("../../functions/student_functions.php");
checkLogin();

$account_id = $_SESSION['user_id'];
$student = getStudentByAccount($account_id);
?>

<?php include("../../views/student/header_student.php"); ?>

<div class="body-wrapper">
  <div class="container-fluid mt-4">

    <div class="card shadow-sm p-4">
      <h3 class="mb-4">📌 Hồ sơ cá nhân</h3>
      <form method="POST" action="../../handle/student_process.php" class="row g-3">
        <input type="hidden" name="action" value="update_profile">
        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">

        <!-- Email -->
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" 
                 value="<?php echo htmlspecialchars($student['email']); ?>">
        </div>

        <!-- Số điện thoại -->
        <div class="col-md-6">
          <label class="form-label">Số điện thoại</label>
          <input type="text" class="form-control" name="so_dien_thoai" 
                 value="<?php echo htmlspecialchars($student['so_dien_thoai']); ?>">
        </div>

        <!-- Địa chỉ -->
        <div class="col-12">
          <label class="form-label">Địa chỉ</label>
          <input type="text" class="form-control" name="dia_chi" 
                 value="<?php echo htmlspecialchars($student['dia_chi']); ?>">
        </div>

        <!-- Chi đoàn -->
        <div class="col-12">
          <label class="form-label">Chi đoàn</label>
          <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['chi_doan']); ?>" disabled>
        </div>

        <!-- Nút -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-primary">💾 Cập nhật</button>
        </div>
      </form>
    </div>

  </div>
</div>

<?php include("../../views/footer.php"); ?>
