<?php
// Giải mã JSON thành mảng PHP
$topic = json_decode($data, true);
require "./views/layout/admin.layout.top.php";
?>

<div id="main" class="">
    <h2 class="text-center mb-4">Chỉnh sửa chủ đề: <?= htmlspecialchars($topic['name']) ?></h2>

    <!-- Error/Success Message -->
    <div id="alert" class="alert d-none" role="alert"></div>

    <form id="editTopicForm" method="POST" action="/topic/edit" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($topic['id']) ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Tên chủ đề</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($topic['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($topic['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="country_code" class="form-label">Mã quốc gia</label>
            <input type="text" class="form-control" id="country_code" name="country_code" value="<?= htmlspecialchars($topic['country_code']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Tải lên hình ảnh</label>
            <input class="form-control" type="file" id="file" name="file" accept="image/jpeg,image/png,image/gif">
            <div class="form-text">Hiện tại: <a href="<?= htmlspecialchars($topic['image_url']) ?>" target="_blank"><?= htmlspecialchars(basename($topic['image_url'])) ?></a></div>
        </div>

        <button type="submit" class="btn btn-primary w-100" id="submitBtn">Lưu thay đổi</button>
    </form>
</div>

<!-- Bootstrap JS từ CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.getElementById('editTopicForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Client-side validation
        const image = document.getElementById('file').files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedImage = ['image/jpeg', 'image/png', 'image/gif'];
        const submitBtn = document.getElementById('submitBtn');

        if (image) {
            if (!allowedImage.includes(image.type)) {
                showAlert('danger', 'Ảnh phải là JPEG, PNG hoặc GIF.');
                return;
            }
            if (image.size > maxSize) {
                showAlert('danger', 'Ảnh không được vượt quá 10MB.');
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
        fetch('/topic/edit', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token // Include token if needed
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
                    setTimeout(() => window.location.href = '/admin/topics', 2000); // Redirect after 2s
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
