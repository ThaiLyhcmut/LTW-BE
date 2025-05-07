<?php
require "./views/layout/admin.layout.top.php";
?>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <h1>Thêm album mới</h1>

  <div class="card">
    <div class="card-header">Form thêm album</div>
    <div class="card-body">
      <form id="createAlbumForm" enctype="multipart/form-data" method="POST" action="/admin/album/create">
        <div class="mb-3">
          <label for="country" class="form-label">Quốc gia</label>
          <select class="form-control" id="country" name="country_id" required>
            <option value="" selected>Chọn quốc gia</option>
            <?php foreach ($country as $c): ?>
              <option value="<?= htmlspecialchars($c['code']) ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="singer" class="form-label">Ca sĩ</label>
          <select class="form-control" id="singer" name="singer_id" required disabled>
            <option value="">Chọn ca sĩ</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="title" class="form-label">Tên album</label>
          <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
          <label for="release_year" class="form-label">Năm phát hành</label>
          <input type="number" class="form-control" id="release_year" name="release_year" required>
        </div>
        <div class="mb-3">
          <label for="file" class="form-label">Ảnh bìa</label>
          <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm album</button>
        <a href="/admin/albums" class="btn btn-secondary">Quay lại</a>
      </form>
    </div>
  </div>
</div>

<script>
// JavaScript để xử lý các select động và form submit
const countrySelect = document.getElementById('country');
const singerSelect = document.getElementById('singer');
const token = localStorage.getItem("auth_token");

// Xử lý khi chọn quốc gia
countrySelect.addEventListener('change', async function() {
    const countryId = this.value;
    singerSelect.disabled = true;
    singerSelect.innerHTML = '<option value="">Chọn ca sĩ</option>';

    if (countryId) {
        // Fetch singers
        try {
            const singerResponse = await fetch(`/singer/data`, {
                method: "POST",
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    country_code: countryId
                })
            });
            const singerData = await singerResponse.json();
            if (singerResponse.ok && singerData.data) {
                singerData.data.forEach(singer => {
                    const option = document.createElement('option');
                    option.value = singer.id;
                    option.textContent = singer.name;
                    singerSelect.appendChild(option);
                });
                singerSelect.disabled = false;
            } else {
                alert('Không tìm thấy ca sĩ cho quốc gia này.');
            }
        } catch (error) {
            console.error('Lỗi khi lấy ca sĩ:', error);
            alert('Đã có lỗi xảy ra khi lấy danh sách ca sĩ.');
        }
    }
});

// Xử lý form submit
document.getElementById('createAlbumForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Ngăn submit mặc định

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Vô hiệu hóa button để tránh submit lặp
    submitButton.disabled = true;
    submitButton.textContent = 'Đang thêm...';

    try {
        const response = await fetch('/album', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            body: formData // Gửi FormData để xử lý file upload
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Thêm album thất bại');
        }

        alert('Thêm album thành công!');
        window.location.href = '/admin/albums'; // Chuyển hướng về danh sách album
    } catch (error) {
        console.error('Lỗi:', error);
        alert('Đã có lỗi xảy ra: ' + error.message);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Thêm album';
    }
});
</script>

<?php
require "./views/layout/admin.layout.bottom.php";
?>