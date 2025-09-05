<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
include './menu.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách đoàn phí</title>
    <link rel="shortcut icon" type="image/png" href="/BTL/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/BTL/assets/css/styles.min.css">
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">DANH SÁCH ĐOÀN PHÍ</h3>

                    <?php
                    if (isset($_GET['success'])) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['success']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>

                    <script>
                        setTimeout(() => {
                            let alertNode = document.querySelector('.alert');
                            if (alertNode) {
                                let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                                bsAlert.close();
                            }
                        }, 3000);
                    </script>

                    <a href="create_due.php" class="btn btn-primary mb-3">Thêm đoàn phí</a>

                    <form method="GET" class="d-flex mb-3" action="due.php">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" 
                                   placeholder="Nhập tên đoàn viên hoặc năm học..." 
                                   value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                            <button type="submit" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.098zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
                        </div>
                        <a href="due.php" class="btn btn-secondary ms-2">Reset</a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đoàn viên</th>
                                    <th>Mã loại đoàn phí</th>
                                    <th>Năm học</th>
                                    <th>Học kỳ</th>
                                    <th>Số tiền</th>
                                    <th>Ngày nộp</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            require '../handle/due_process.php';
                            $dues = handleGetAllDues($_GET['keyword'] ?? '');

                            foreach ($dues as $due) {
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($due["id"]) ?></td>
                                    <td><?= htmlspecialchars($due["member_name"] ?? "Chưa rõ") ?></td>
                                    <td><?= htmlspecialchars($due["loai_doan_phi_id"]) ?></td>
                                    <td><?= htmlspecialchars($due["nam_hoc"]) ?></td>
                                    <td><?= htmlspecialchars($due["hoc_ky"]) ?></td>
                                    <td><?= htmlspecialchars(number_format($due["so_tien"], 2)) ?> VND</td>
                                    <td><?= htmlspecialchars($due["ngay_nop"] ?? "Chưa nộp") ?></td>
                                    <td><?= htmlspecialchars($due["trang_thai"]) ?></td>
                                    <td>
                                        <a href="edit_due.php?id=<?= $due["id"] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                        <a href="../../handle/due_process.php?action=delete&id=<?= $due["id"] ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa khoản đoàn phí này?')">Xóa</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc nội dung chính -->
    <script src="/BTL/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="/BTL/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/BTL/assets/js/sidebarmenu.js"></script>
    <script src="/BTL/assets/js/app.min.js"></script>
    <script src="/BTL/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="/BTL/assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
