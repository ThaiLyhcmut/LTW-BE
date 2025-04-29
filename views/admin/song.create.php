<?php
require "./views/layout/admin.layout.top.php";
?>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <h1>Thêm bài hát mới</h1>

  <div class="card">
    <div class="card-header">Form thêm bài hát</div>
    <div class="card-body">
      <form id="createSongForm" enctype="multipart/form-data" method="POST" action="/admin/song/create">
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
          <label for="topic" class="form-label">Chủ đề</label>
          <select class="form-control" id="topic" name="topic_id" disabled>
            <option value="">Chọn chủ đề</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="album" class="form-label">Album</label>
          <select class="form-control" id="album" name="album_id" disabled>
            <option value="">Chọn album</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="title" class="form-label">Tiêu đề bài hát</label>
          <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
          <label for="duration" class="form-label">Thời gian (giây)</label>
          <input type="number" class="form-control" id="duration" name="duration" required>
        </div>
        <div class="mb-3">
          <label for="lyric" class="form-label">Lời bài hát</label>
          <textarea class="form-control" id="lyric" name="lyric" rows="5"></textarea>
        </div>
        <div class="mb-3">
          <label for="fileAudio" class="form-label">File âm thanh</label>
          <input type="file" class="form-control" id="fileAudio" name="fileAudio" accept="audio/*" required>
        </div>
        <div class="mb-3">
          <label for="file" class="form-label">Ảnh bìa</label>
          <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm bài hát</button>
        <a href="/admin/songs" class="btn btn-secondary">Quay lại</a>
      </form>
    </div>
  </div>
</div>

<script>
// JavaScript để xử lý các select động và form submit
const countrySelect = document.getElementById('country');
const singerSelect = document.getElementById('singer');
const albumSelect = document.getElementById('album');
const topicSelect = document.getElementById('topic');
const token = localStorage.getItem("auth_token");

// Xử lý khi chọn quốc gia
countrySelect.addEventListener('change', async function() {
    const countryId = this.value;
    singerSelect.disabled = true;
    albumSelect.disabled = true;
    topicSelect.disabled = true;
    singerSelect.innerHTML = '<option value="">Chọn ca sĩ</option>';
    albumSelect.innerHTML = '<option value="">Chọn album</option>';
    topicSelect.innerHTML = '<option value="">Chọn chủ đề</option>';

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

        // Fetch topics
        try {
            const topicResponse = await fetch(`/topic/data`, {
                method: "POST",
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    country_code: countryId
                })
            });
            const topicData = await topicResponse.json();
            if (topicResponse.ok && topicData.data) {
                topicData.data.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic.id;
                    option.textContent = topic.name;
                    topicSelect.appendChild(option);
                });
                topicSelect.disabled = false;
            } else {
                alert('Không tìm thấy chủ đề cho quốc gia này.');
            }
        } catch (error) {
            console.error('Lỗi khi lấy chủ đề:', error);
            alert('Đã có lỗi xảy ra khi lấy danh sách chủ đề.');
        }
    }
});

// Xử lý khi chọn ca sĩ
singerSelect.addEventListener('change', async function() {
    const singerId = this.value;
    albumSelect.disabled = true;
    albumSelect.innerHTML = '<option value="">Chọn album</option>';

    if (singerId) {
        // Fetch albums
        try {
            const albumResponse = await fetch(`/album/data`, {
                method: "POST",
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    singer_id: singerId
                })
            });
            const albumData = await albumResponse.json();
            if (albumResponse.ok && albumData.data) {
                albumData.data.forEach(album => {
                    const option = document.createElement('option');
                    option.value = album.id;
                    option.textContent = album.title;
                    albumSelect.appendChild(option);
                });
                albumSelect.disabled = false;
            } else {
                alert('Không tìm thấy album cho ca sĩ này.');
            }
        } catch (error) {
            console.error('Lỗi khi lấy album:', error);
            alert('Đã có lỗi xảy ra khi lấy danh sách album.');
        }
    }
});

// Xử lý form submit
document.getElementById('createSongForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Ngăn submit mặc định

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Vô hiệu hóa button để tránh submit lặp
    submitButton.disabled = true;
    submitButton.textContent = 'Đang thêm...';
    console.log(formData)
    try {
        const response = await fetch('/song', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            body: formData // Gửi FormData để xử lý file upload
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Thêm bài hát thất bại');
        }

        alert('Thêm bài hát thành công!');
        window.location.href = '/admin/songs'; // Chuyển hướng về danh sách bài hát
    } catch (error) {
        console.error('Lỗi:', error);
        alert('Đã có lỗi xảy ra: ' + error.message);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Thêm bài hát';
    }
});
</script>

<?php
require "./views/layout/admin.layout.bottom.php";
?>