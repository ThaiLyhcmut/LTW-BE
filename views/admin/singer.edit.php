<?php 
if (!isset($singer) || empty($singer)) {
    if (isset($data)) {
        $singer = json_decode($data, true);
    } else {
        echo "<div class='alert alert-danger'>Không tìm thấy thông tin ca sĩ</div>";
        exit;
    }
}

require "./views/layout/admin.layout.top.php"; 
?>

<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Chỉnh sửa thông tin ca sĩ</h3>
                <p class="text-subtitle text-muted">Cập nhật thông tin ca sĩ trong hệ thống</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/singers">Ca sĩ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin ca sĩ</h4>
            </div>
            <div class="card-body">
                <!-- Dynamic Alert -->
                <div id="alert" class="alert alert-dismissible fade show d-none" role="alert">
                    <span id="alert-message"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <?php if (isset($message)): ?>
                    <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($message['content']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form id="editSingerForm" action="/singer/edit" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($singer['id']) ?>">
                    
                    <div class="form-body">
                        <div class="row">
                            <!-- Left Column - Avatar Preview and Tips -->
                            <div class="col-md-4">
                                <div class="avatar-preview-container text-center mb-4">
                                    <img id="avatar-preview" src="<?= htmlspecialchars($singer['avatar_url'] ?? '/assets/images/default-avatar.png') ?>" 
                                         alt="Avatar Preview" class="img-fluid rounded-circle" 
                                         style="width: 200px; height: 200px; object-fit: cover;">
                                    <div class="mt-2">
                                        <span class="text-muted">Avatar hiện tại</span>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mt-4">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-lightbulb"></i> Mẹo</h6>
                                        <ul class="ps-3 mb-0">
                                            <li>Sử dụng ảnh vuông để hiển thị tốt nhất</li>
                                            <li>Kích thước khuyến nghị: 500x500px</li>
                                            <li>Định dạng: JPG, PNG, WEBP</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column - Form Fields -->
                            <div class="col-md-8">
                                <!-- Singer Name -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Tên ca sĩ <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="name" class="form-control" name="name" 
                                               value="<?= htmlspecialchars($singer['name'] ?? '') ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Country -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="country" class="form-label">Quốc gia <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="country_code" id="country" class="form-select" required>
                                            <option value="">-- Chọn quốc gia --</option>
                                            <?php foreach ($country as $item): ?>
                                                <option value="<?= htmlspecialchars($item['code']) ?>" 
                                                    <?= (isset($singer['country_code']) && $singer['country_code'] == $item['code']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Avatar Upload -->
                                <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">Ảnh đại diện</label>
                                        </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="file" class="form-label">Tải lên ảnh đại diện</label>
                                            <input class="form-control" type="file" id="file" name="file" accept="image/jpeg,image/png,image/gif">
                                            <div class="form-text">Hiện tại: <a href="<?= htmlspecialchars($singer['avatar_url']) ?>" target="_blank"><?= htmlspecialchars(basename($singer['avatar_url'])) ?></a></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Buttons -->
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <a href="/admin/singers" class="btn btn-light me-2">Hủy</a>
                                        <button type="submit" id="submitBtn" class="btn btn-primary">Cập nhật</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.getElementById('editSingerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Client-side validation
        const cover = document.getElementById('file').files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedImage = ['image/jpeg', 'image/png', 'image/gif'];
        const submitBtn = document.getElementById('submitBtn');

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
        console.log(form)
        const formData = new FormData(form);
        const token = localStorage.getItem("auth_token")
        fetch('/singer/edit', {
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
                    setTimeout(() => window.location.href = '/admin/singers', 2000); // Redirect after 2s
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

<?php require "./views/layout/admin.layout.bottom.php"; ?>