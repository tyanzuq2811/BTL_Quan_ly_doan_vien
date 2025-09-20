<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/event_functions.php';

checkLogin(__DIR__ . '/../authentication_login.php');
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) ?? '';
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$limit = 10; 
$offset = ($page - 1) * $limit;
$events = getAllEvents($search, $limit, $offset);
$total_events = getTotalEvents($search);
$total_pages = ceil($total_events / $limit);

include __DIR__ . '/header.php';
?>

<div class="container-fluid">
    <h3 class="mt-3 mb-4">DANH SÁCH SỰ KIỆN</h3>

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

    <form method="get" action="/BTL/views/event.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên sự kiện hoặc năm học..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <a href="/BTL/views/event/event_create.php" class="btn btn-primary mb-3">Thêm sự kiện</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Tên sự kiện</th>
                <th scope="col">Mô tả</th>
                <th scope="col">Ngày tổ chức</th>
                <th scope="col">Cấp tổ chức</th>
                <th scope="col">Tên cấp tổ chức</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($event['ten_su_kien'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($event['mo_ta'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($event['ngay_to_chuc'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($event['cap_to_chuc'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($event['cap_ten'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($event['trang_thai'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/BTL/views/event/event_edit.php?id=<?= htmlspecialchars($event['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <button class="btn btn-danger btn-sm" onclick="showDeleteModal(<?= htmlspecialchars($event['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($event['ten_su_kien'], ENT_QUOTES, 'UTF-8') ?>')">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">Chưa có dữ liệu sự kiện</td>
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
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc muốn xóa sự kiện <strong id="eventName"></strong>? Hành động này không thể hoàn tác.
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
        document.getElementById('eventName').textContent = name;
        document.getElementById('deleteLink').setAttribute('href', '/BTL/handle/event_process.php?action=delete&id=' + id);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
include __DIR__ . '/footer.php';
?>