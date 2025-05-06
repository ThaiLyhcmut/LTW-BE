<?php
// Giải mã JSON thành mảng PHP
$post = json_decode($data, true);
require "./views/layout/admin.layout.top.php";
?>

<div id="main" class="">
    <h2 class="text-center mb-4">Chỉnh sửa bài viết: <?= htmlspecialchars($post['title']) ?></h2>

    <!-- Error/Success Message -->
    <div id="alert" class="alert d-none" role="alert"></div>

    <form id="editPostForm" method="POST" action="/post/edit" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">

        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề bài viết</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="desc" class="form-label">Nội dung</label>
            <textarea class="form-control" id="desc" name="desc" rows="5" required><?= htmlspecialchars($post['desc']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Tải lên ảnh đại diện</label>
            <input class="form-control" type="file" id="file" name="file" accept="image/jpeg,image/png,image/gif">
            <div class="form-text">Hiện tại: <a href="<?= htmlspecialchars($post['img']) ?>" target="_blank"><?= htmlspecialchars(basename($post['img'])) ?></a></div>
        </div>

        <button type="submit" class="btn btn-primary w-100" id="submitBtn">Lưu thay đổi</button>
    </form>
</div>

<!-- Bootstrap JS từ CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.getElementById('editPostForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Client-side validation
        const cover = document.getElementById('file').files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedImage = ['image/jpeg', 'image/png', 'image/gif'];
        const submitBtn = document.getElementById('submitBtn');

        if (cover) {
            if (!allowedImage.includes(cover.type)) {
                showAlert('danger', 'Ảnh đại diện phải là JPEG, PNG hoặc GIF.');
                return;
            }
            if (cover.size > maxSize) {
                showAlert('danger', 'Ảnh đại diện không được vượt quá 10MB.');
                return;
            }
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang lưu...';

        // Submit form via AJAX
        const form = this;
        const formData = new FormData(form);
        const token = localStorage.getItem("auth_token");
        fetch('/post/edit', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Lưu thay đổi';

            if (data.message) {
                if (data.message.includes('completed')) {
                    showAlert('success', data.message);
                    setTimeout(() => window.location.href = '/admin/posts', 2000); // Redirect after 2s
                } else {
                    showAlert('danger', data.message);
                }
            } else {
                showAlert('danger', 'Đã xảy ra lỗi không xác định.');
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Lưu thay đổi';
            showAlert('danger', 'Lỗi kết nối: ' + error.message);
        });
    });

    function showAlert(type, message) {
        const alert = document.getElementById('alert');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.classList.remove('d-none');
        setTimeout(() => alert.classList.add('d-none'), 5000); // Hide after 5s
    }
</script>

<?php
require "./views/layout/admin.layout.bottom.php";
?>