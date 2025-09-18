<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/training_score_functions.php';

checkLogin(__DIR__ . '/../authentication_login.php');
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) ?? '';
$scores = getAllScores($search);

include __DIR__ . '/header.php';
?>

<div class="container-fluid">
    <h3 class="mt-3 mb-4">DANH SÁCH ĐIỂM RÈN LUYỆN</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="get" action="/BTL/views/training_score.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên đoàn viên hoặc năm học..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <a href="/BTL/views/training_score/training_score_create.php" class="btn btn-primary mb-3">Thêm điểm rèn luyện</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Đoàn viên</th>
                <th scope="col">Năm học</th>
                <th scope="col">Học kỳ</th>
                <th scope="col">Điểm</th>
                <th scope="col">Xếp loại</th>
                <th scope="col">Ghi chú</th>
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($scores)): ?>
                <?php foreach ($scores as $score): ?>
                    <tr>
                        <td><?= htmlspecialchars($score['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($score['doan_vien_ho_ten'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($score['nam_hoc'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($score['hoc_ky'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($score['diem'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($score['xep_loai'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($score['ghi_chu'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/BTL/views/training_score/training_score_edit.php?id=<?= htmlspecialchars($score['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <button class="btn btn-danger btn-sm" onclick="showDeleteModal(<?= htmlspecialchars($score['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($score['doan_vien_ho_ten'], ENT_QUOTES, 'UTF-8') ?>')">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">Chưa có dữ liệu điểm rèn luyện</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa điểm rèn luyện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc muốn xóa điểm rèn luyện của đoàn viên <strong id="scoreMemberName"></strong>? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a id="deleteLink" href="#" class="btn btn-danger">Xóa</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Clear success/error messages from URL after 2 seconds
    if (window.location.search.includes("success") || window.location.search.includes("error")) {
        setTimeout(() => {
            window.history.replaceState({}, document.title, window.location.pathname + (window.location.search.includes("search") ? "?search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" : ""));
        }, 2000);
    }

    // Show delete confirmation modal
    function showDeleteModal(id, name) {
        document.getElementById('scoreMemberName').textContent = name;
        document.getElementById('deleteLink').setAttribute('href', '/BTL/handle/training_score_process.php?action=delete&id=' + id);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
include __DIR__ . '/footer.php';
?>