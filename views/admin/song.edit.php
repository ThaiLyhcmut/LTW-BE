<?php
// Giải mã JSON thành mảng PHP
$song = json_decode($data, true);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa bài hát</title>

    <!-- Thêm Bootstrap CSS từ CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            background-color: #f1f3f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
            font-weight: 600;
        }
        .btn-custom {
            background-color: #20c997;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            transition: background-color 0.3s;
            width: 100%;
            font-weight: 500;
        }
        .btn-custom:hover {
            background-color: #099268;
        }
        .btn-custom:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        .form-control, .form-control-file {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px;
            transition: border-color 0.3s;
        }
        .form-control:focus, .form-control-file:focus {
            border-color: #20c997;
            box-shadow: 0 0 5px rgba(32, 201, 151, 0.3);
        }
        label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }
        .mb-3 {
            margin-bottom: 20px !important;
        }
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        .form-label {
            font-size: 0.95rem;
        }
        .current-file {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
        .alert {
            display: none;
            margin-bottom: 20px;
        }
        @media (max-width: 576px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Chỉnh sửa bài hát: <?= htmlspecialchars($song['title']) ?></h2>

    <!-- Error/Success Message -->
    <div id="alert" class="alert" role="alert"></div>

    <form id="editSongForm" method="POST" action="/song/edit" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($song['id']) ?>">

        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề bài hát</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($song['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Thời lượng (giây)</label>
            <input type="number" class="form-control" id="duration" name="duration" value="<?= htmlspecialchars($song['duration']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="lyric" class="form-label">Lời bài hát</label>
            <textarea class="form-control" id="lyric" name="lyric" rows="5"><?= htmlspecialchars($song['lyric']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="fileAudio" class="form-label">Tải lên file âm nhạc</label>
            <input class="form-control" type="file" id="fileAudio" name="fileAudio" accept="audio/mpeg,audio/wav,audio/mp3">
            <div class="current-file">Hiện tại: <a href="<?= htmlspecialchars($song['file_url']) ?>" target="_blank"><?= htmlspecialchars(basename($song['file_url'])) ?></a></div>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Tải lên ảnh bìa</label>
            <input class="form-control" type="file" id="file" name="file" accept="image/jpeg,image/png,image/gif">
            <div class="current-file">Hiện tại: <a href="<?= htmlspecialchars($song['cover_url']) ?>" target="_blank"><?= htmlspecialchars(basename($song['cover_url'])) ?></a></div>
        </div>

        <button type="submit" class="btn btn-custom" id="submitBtn">Lưu thay đổi</button>
    </form>
</div>

<!-- Thêm Bootstrap JS từ CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.getElementById('editSongForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Client-side validation
        const audio = document.getElementById('fileAudio').files[0];
        const cover = document.getElementById('file').files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedAudio = ['audio/mpeg', 'audio/wav', 'audio/mp3'];
        const allowedImage = ['image/jpeg', 'image/png', 'image/gif'];
        const submitBtn = document.getElementById('submitBtn');

        if (audio) {
            if (!allowedAudio.includes(audio.type)) {
                showAlert('danger', 'File âm nhạc phải là MP3 hoặc WAV.');
                return;
            }
            if (audio.size > maxSize) {
                showAlert('danger', 'File âm nhạc không được vượt quá 10MB.');
                return;
            }
        }
        if (cover) {
            if (!allowedImage.includes(cover.type)) {
                showAlert('danger', 'Ảnh bìa phải là JPEG, PNG hoặc GIF.');
                return;
            }
            if (cover.size > maxSize) {
                showAlert('danger', 'Ảnh bìa không được vượt quá 10MB.');
                return;
            }
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang lưu...';

        // Submit form via AJAX
        const form = this;
        const formData = new FormData(form);

        fetch('/song/edit', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Lưu thay đổi';

            if (data.message) {
                if (data.message.includes('completed')) {
                    showAlert('success', data.message);
                    setTimeout(() => window.location.href = '/admin/songs', 2000); // Redirect after 2s
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
        alert.style.display = 'block';
        setTimeout(() => alert.style.display = 'none', 5000); // Hide after 5s
    }
</script>
</body>
</html>