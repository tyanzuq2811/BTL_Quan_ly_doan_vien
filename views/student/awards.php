<?php
require_once("../../functions/auth.php");
require_once("../../functions/student_functions.php");
checkLogin();

$user_id = $_SESSION['user_id'];
$awards = getStudentAwards($user_id);
$disciplines = getStudentDisciplines($user_id);
?>

<?php include("../../views/student/header_student.php"); ?> 

<div class="body-wrapper">
  <div class="container-fluid mt-4">
      <h2 class="mb-3">🏅 Khen thưởng</h2>
      <ul class="list-group mb-4">
          <?php if (!empty($awards)): ?>
              <?php foreach ($awards as $a): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                      <?php echo htmlspecialchars($a['noi_dung']); ?>
                      <span class="badge bg-success"><?php echo htmlspecialchars($a['ngay']); ?></span>
                  </li>
              <?php endforeach; ?>
          <?php else: ?>
              <li class="list-group-item">Không có khen thưởng nào.</li>
          <?php endif; ?>
      </ul>

      <h2 class="mb-3">⚠️ Kỷ luật</h2>
      <ul class="list-group">
          <?php if (!empty($disciplines)): ?>
              <?php foreach ($disciplines as $d): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                      <?php echo htmlspecialchars($d['noi_dung']); ?>
                      <span class="badge bg-danger"><?php echo htmlspecialchars($d['ngay']); ?></span>
                  </li>
              <?php endforeach; ?>
          <?php else: ?>
              <li class="list-group-item">Không có kỷ luật nào.</li>
          <?php endif; ?>
      </ul>
  </div>
</div>

<?php include("../../views/footer.php"); ?> 
