<?php
require_once("../../functions/auth.php");
require_once("../../functions/student_functions.php");
checkLogin();

$user_id = $_SESSION['user_id'];
$events = getAllEvents(); // hàm này bạn có trong event_functions.php
$my_events = getStudentEvents($user_id);
$my_event_ids = !empty($my_events) ? array_column($my_events, 'id') : [];

$events = getAllEvents(); // danh sách tất cả sự kiện
?>

<?php include("../../views/student/header_student.php"); ?>

<div class="body-wrapper">
  <div class="container-fluid mt-4">

    <!-- Thông báo -->
    <?php if (isset($_SESSION['msg'])): ?>
      <div class="alert alert-info text-center fw-bold">
        <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
      </div>
    <?php endif; ?>

    <!-- Danh sách sự kiện -->
    <div class="card shadow-sm p-4 mb-4">
      <h3 class="mb-4">🎉 Danh sách sự kiện</h3>
      <?php if (!empty($events)): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary text-center">
              <tr>
                <th>Tên sự kiện</th>
                <th>Ngày tổ chức</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($events as $e): ?>
                <?php
                  $event_date = new DateTime($e['ngay_to_chuc']);
                  $today = new DateTime();
                  $diff = $today->diff($event_date);
                  $is_future = $diff->invert === 0;
                  $days_left = $diff->days;
                  $can_action = $is_future && $days_left >= 3;
                  $is_registered = in_array($e['id'], $my_event_ids);
                ?>
                <tr>
                  <td><?php echo htmlspecialchars($e['ten_su_kien']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($e['ngay_to_chuc']); ?></td>
                  <td class="text-center">
                    <?php if ($is_registered): ?>
                      <form method="POST" action="../../handle/student_process.php" style="display:inline;">
                        <input type="hidden" name="action" value="cancel_event">
                        <input type="hidden" name="event_id" value="<?php echo $e['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger"
                                <?php echo !$can_action ? 'disabled title="Chỉ được hủy trước 3 ngày"' : ''; ?>>
                          Hủy đăng ký
                        </button>
                      </form>
                    <?php else: ?>
                      <form method="POST" action="../../handle/student_process.php" style="display:inline;">
                        <input type="hidden" name="action" value="register_event">
                        <input type="hidden" name="event_id" value="<?php echo $e['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-success"
                                <?php echo !$can_action ? 'disabled title="Chỉ được đăng ký trước 3 ngày"' : ''; ?>>
                          Đăng ký
                        </button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">Chưa có sự kiện nào.</p>
      <?php endif; ?>
    </div>

    <!-- Sự kiện đã tham gia -->
    <div class="card shadow-sm p-4">
      <h3 class="mb-4">📌 Sự kiện đã tham gia</h3>
      <?php if (!empty($my_events)): ?>
        <ul class="list-group">
          <?php foreach ($my_events as $me): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><?php echo htmlspecialchars($me['ten_su_kien']); ?></span>
              <span class="badge bg-info text-dark">
                <?php echo htmlspecialchars($me['ngay_to_chuc']); ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">Bạn chưa tham gia sự kiện nào.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php include("../../views/footer.php"); ?>
