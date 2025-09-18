<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/youth_union_chapter_functions.php';

checkLogin(__DIR__ . '/../authentication_login.php');
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) ?? '';
$chapters = getAllChapters($search);

include __DIR__ . '/header.php';
?>

<div class="container-fluid">
    <h3 class="mt-3 mb-4">DANH SÁCH LIÊN CHI ĐOÀN</h3>

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
    <form method="get" action="/BTL/views/youth_union_chapter.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm liên chi đoàn hoặc đoàn trưởng..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <a href="/BTL/views/youth_union_chapter/youth_union_chapter_create.php" class="btn btn-primary mb-3">Thêm liên chi đoàn</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Tên liên chi đoàn</th>
                <th scope="col">Đoàn trưởng</th>
                <th scope="col">Ngày thành lập</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($chapters)): ?>
                <?php foreach ($chapters as $chapter): ?>
                    <tr>
                        <td><?= htmlspecialchars($chapter['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($chapter['ten'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($chapter['doan_truong_id'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($chapter['ngay_thanh_lap'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($chapter['trang_thai'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/BTL/views/youth_union_chapter/youth_union_chapter_edit.php?id=<?= htmlspecialchars($chapter['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <button class="btn btn-danger btn-sm" onclick="showDeleteModal(<?= htmlspecialchars($chapter['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($chapter['ten'], ENT_QUOTES, 'UTF-8') ?>')">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Chưa có dữ liệu liên chi đoàn</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa liên chi đoàn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc muốn xóa liên chi đoàn <strong id="chapterName"></strong>? Hành động này không thể hoàn tác.
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
        document.getElementById('chapterName').textContent = name;
        document.getElementById('deleteLink').setAttribute('href', '/BTL/handle/youth_union_chapter_process.php?action=delete&id=' + id);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

<?php
include __DIR__ . '/footer.php';
?>