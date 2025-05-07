<?php
require "./views/layout/admin.layout.top.php";
?>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <h1>Thêm post mới</h1>

  <div class="card">
    <div class="card-header">Form thêm post</div>
    <div class="card-body">
      <form id="createPostForm" enctype="multipart/form-data" method="POST" action="/admin/post/create">
        <div class="mb-3">
          <label for="title" class="form-label">Tiêu đề </label>
          <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
          <label for="desc" class="form-label">Mô tả</label>
          <textarea class="form-control" name="desc" id="desc"></textarea>
          
        </div>
        <div class="mb-3">
          <label for="file" class="form-label">Ảnh bìa</label>
          <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm post</button>
        <a href="/admin/posts" class="btn btn-secondary">Quay lại</a>
      </form>
    </div>
  </div>
</div>

<script>
// JavaScript để xử lý select động và form submit
const postSelect = document.getElementById('post');
const token = localStorage.getItem("auth_token");
// Xử lý form submit
document.getElementById('createPostForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Ngăn submit mặc định

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Vô hiệu hóa button để tránh submit lặp
    submitButton.disabled = true;
    submitButton.textContent = 'Đang thêm...';

    try {
        const response = await fetch('/post', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            body: formData // Gửi FormData để xử lý file upload
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Thêm post thất bại');
        }

        alert('Thêm post thành công!');
        window.location.href = '/admin/posts'; // Chuyển hướng về danh sách bài hát
    } catch (error) {
        console.error('Lỗi:', error);
        alert('Đã có lỗi xảy ra: ' + error.message);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Thêm post';
    }
});
</script>

<?php
require "./views/layout/admin.layout.bottom.php";
?>