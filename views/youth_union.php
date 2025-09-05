<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
include './menu.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh Sách Tổ Chức Đoàn</title>
    <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../../assets/css/styles.min.css">
</head>
<body>
    <!-- Bắt đầu nội dung chính -->
    <div class="body-wrapper" style="margin-left:260px; padding:20px;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">DANH SÁCH TỔ CHỨC ĐOÀN</h3>

                    <?php
                    // Hiển thị thông báo thành công từ session
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_SESSION['success_message']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                        unset($_SESSION['success_message']); // Xóa session sau khi hiển thị
                    }
                    // Hiển thị thông báo lỗi từ URL (nếu có)
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

                    <a href="youth_union/create_youth_union.php" class="btn btn-primary mb-3">Thêm tổ chức đoàn</a>

                    <form method="GET" class="d-flex mb-3" action=" youth_union.php">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" 
                                   placeholder="Nhập tên tổ chức đoàn..." 
                                   value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                            <button type="submit" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.098zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
                        </div>
                        <a href="youth_union.php" class="btn btn-secondary ms-2">Reset</a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên tổ chức</th>
                                    <th>Loại</th>
                                    <th>Cấp trên</th>
                                    <th>Ngày thành lập</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            require '../handle/youth_union_process.php';
                            $unions = handleGetAllYouthUnions($_GET['keyword'] ?? '');

                            foreach ($unions as $union) {
                                ?>
                                <tr>
                                    <td><?= $union["id"] ?></td>
                                    <td><?= htmlspecialchars($union["ten"]) ?></td>
                                    <td><?= htmlspecialchars($union["loai"]) ?></td>
                                    <td><?= htmlspecialchars($union["parent_name"] ?? "Không có") ?></td>
                                    <td><?= htmlspecialchars($union["ngay_thanh_lap"] ?? "Chưa xác định") ?></td>
                                    <td><?= htmlspecialchars($union["trang_thai"]) ?></td>
                                    <td>
                                        <a href="youth_union/edit_youth_union.php?id=<?= $union["id"] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                        <a href="../handle/youth_union_process.php?action=delete&id=<?= $union["id"] ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa tổ chức đoàn này?')">Xóa</a>
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
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/sidebarmenu.js"></script>
    <script src="../../assets/js/app.min.js"></script>
    <script src="../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>