<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/participation_history_functions.php';

checkLogin(__DIR__ . '/../authentication_login.php');
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) ?? '';
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$limit = 10; 
$offset = ($page - 1) * $limit;
$histories = getAllHistories($search, $limit, $offset);
$total_histories = getTotalHistories($search);
$total_pages = ceil($total_histories / $limit);

include __DIR__ . '/header.php';
?>

<div class="container-fluid">
    <h3 class="mt-3 mb-4">DANH SÁCH LỊCH SỬ THAM GIA</h3>

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

    <form method="get" action="/BTL/views/participation_history.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên đoàn viên hoặc chi đoàn..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <a href="/BTL/views/participation_history/participation_history_create.php" class="btn btn-primary mb-3">Thêm lịch sử tham gia</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Đoàn viên</th>
                <th scope="col">Chi đoàn</th>
                <th scope="col">Ngày bắt đầu</th>
                <th scope="col">Ngày kết thúc</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($histories)): ?>
                <?php foreach ($histories as $history): ?>
                    <tr>
                        <td><?= htmlspecialchars($history['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($history['doan_vien_ho_ten'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($history['chi_doan_ten'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($history['ngay_bat_dau'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($history['ngay_ket_thuc'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($history['trang_thai'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/BTL/views/participation_history/participation_history_edit.php?id=<?= htmlspecialchars($history['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <button class="btn btn-danger btn-sm" onclick="showDeleteModal(<?= htmlspecialchars($history['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($history['doan_vien_ho_ten'] . ' - ' . $history['chi_doan_ten'], ENT_QUOTES, 'UTF-8') ?>')">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Chưa có dữ liệu lịch sử tham gia</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>">Trang trước</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a></li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>">Trang sau</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa lịch sử tham gia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc muốn xóa lịch sử tham gia <strong id="historyName"></strong>? Hành động này không thể hoàn tác.
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
    if (window.location.search.includes("success") || window.location.search.includes("error")) {
        setTimeout(() => {
            window.history.replaceState({}, document.title, window.location.pathname + (window.location.search.includes("search") ? "?search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" : ""));
        }, 2000);
    }
    function showDeleteModal(id, name) {
        document.getElementById('historyName').textContent = name;
        document.getElementById('deleteLink').setAttribute('href', '/BTL/handle/participation_history_process.php?action=delete&id=' + id);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
include __DIR__ . '/footer.php';
?>