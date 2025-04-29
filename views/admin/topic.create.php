<?php
require "./views/layout/admin.layout.top.php";
?>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <h1>Thêm topic mới</h1>

  <div class="card">
    <div class="card-header">Form thêm topic</div>
    <div class="card-body">
      <form id="createSongForm" enctype="multipart/form-data" method="POST" action="/admin/song/create">
        <div class="mb-3">
          <label for="country" class="form-label">Quốc gia</label>
          <select class="form-control" id="country" name="country_code" required>
            <option value="" selected>Chọn quốc gia</option>
            <?php foreach ($country as $c): ?>
              <option value="<?= htmlspecialchars($c['code']) ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Tiêu đề </label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Mô tả</label>
          <textarea class="form-control" name="description" id="description"></textarea>
          
        </div>
        <div class="mb-3">
          <label for="file" class="form-label">Ảnh bìa</label>
          <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm topic</button>
        <a href="/admin/songs" class="btn btn-secondary">Quay lại</a>
      </form>
    </div>
  </div>
</div>

<script>
// JavaScript để xử lý select động và form submit
const topicSelect = document.getElementById('topic');
const token = localStorage.getItem("auth_token");
// Xử lý form submit
document.getElementById('createSongForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Ngăn submit mặc định

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Vô hiệu hóa button để tránh submit lặp
    submitButton.disabled = true;
    submitButton.textContent = 'Đang thêm...';

    try {
        const response = await fetch('/topic', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            body: formData // Gửi FormData để xử lý file upload
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Thêm topic thất bại');
        }

        alert('Thêm topic thành công!');
        window.location.href = '/admin/topics'; // Chuyển hướng về danh sách bài hát
    } catch (error) {
        console.error('Lỗi:', error);
        alert('Đã có lỗi xảy ra: ' + error.message);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Thêm topic';
    }
});
</script>

<?php
require "./views/layout/admin.layout.bottom.php";
?>