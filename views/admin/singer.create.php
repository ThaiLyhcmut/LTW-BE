<?php 
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
                <h3>Thêm ca sĩ mới</h3>
                <p class="text-subtitle text-muted">Bổ sung ca sĩ mới vào hệ thống</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/singers">Ca sĩ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin ca sĩ mới</h4>
            </div>
            
            <div class="card-body">
                <?php if (isset($message)): ?>
                    <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($message['content']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form id="createSingerForm" enctype="multipart/form-data" method="POST" action="/singer/create" class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <!-- Left Column - Avatar Preview and Tips -->
                            <div class="col-md-4">
                                <div class="avatar-preview-container text-center mb-4">
                                    <img id="avatar-preview" src="/assets/images/default-avatar.png" 
                                        alt="Avatar Preview" class="img-fluid rounded-circle" 
                                        style="width: 200px; height: 200px; object-fit: cover;">
                                    <div class="mt-2">
                                        <span class="text-muted">Xem trước avatar</span>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mt-4">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-lightbulb"></i> Mẹo</h6>
                                        <ul class="ps-3 mb-0">
                                            <li>Sử dụng ảnh vuông để hiển thị tốt nhất</li>
                                            <li>Kích thước khuyến nghị: 500x500px</li>
                                            <li>Định dạng: JPG, PNG, WEBP</li>
                                            <li>Hãy nhập URL đầy đủ có bao gồm https://</li>
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
                                            placeholder="Nhập tên ca sĩ" required>
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
                                                <option value="<?= htmlspecialchars($item['code']) ?>">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Biography -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="biography" class="form-label">Tiểu sử</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea id="biography" class="form-control" name="biography" 
                                                rows="4" placeholder="Nhập tiểu sử ca sĩ (không bắt buộc)"></textarea>
                                    </div>
                                </div>
                                
                                <!-- Avatar File Upload -->
                                <div class="mb-3">
                                    <label for="avatar_file" class="form-label">Ảnh đại diện <span class="text-danger">*</span></label>
                                    <input type="file" id="avatar_file" class="form-control" name="file" accept="image/*" required>
                                    <div class="form-text">Chọn file ảnh từ máy tính (JPG, PNG, WEBP)</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Buttons -->
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="/admin/singers" class="btn btn-light me-2">Hủy</a>
                                <button type="submit" class="btn btn-primary">Thêm ca sĩ</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const form = document.getElementById('createSingerForm');
    const token = localStorage.getItem("auth_token");
    const avatarPreview = document.getElementById('avatar-preview');
    const defaultAvatar = '/assets/images/default-avatar.png';
    const avatarFileInput = document.getElementById('avatar_file');
    const nameInput = document.getElementById('name');
    const countrySelect = document.getElementById('country');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Event Handlers
    const handleAvatarPreview = () => {
        if (avatarFileInput.files && avatarFileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => avatarPreview.src = e.target.result;
            reader.readAsDataURL(avatarFileInput.files[0]);
            avatarFileInput.classList.remove('is-invalid');
            avatarFileInput.classList.add('is-valid');
        } else {
            avatarFileInput.classList.remove('is-valid');
            avatarPreview.src = defaultAvatar;
        }
    };

    const handleFormSubmit = async (event) => {
        event.preventDefault();
        
        // Validate required fields
        if (!nameInput.value.trim()) {
            alert('Vui lòng nhập tên ca sĩ');
            return;
        }

        if (!countrySelect.value) {
            alert('Vui lòng chọn quốc gia');
            return;
        }

        if (!avatarFileInput.files[0]) {
            alert('Vui lòng chọn ảnh đại diện');
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('name', nameInput.value.trim());
        formData.append('country_code', countrySelect.value);
        formData.append('biography', document.getElementById('biography').value.trim());
        formData.append('file', avatarFileInput.files[0]);

        // Disable submit button during processing
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Đang xử lý...`;

        try {
            const response = await fetch('/singer', {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` },
                body: formData
            });

            const result = await response.json();
            
            if (!response.ok) throw new Error(result.message || 'Lỗi máy chủ');

            window.location.href = '/admin/singers?success=' + encodeURIComponent(result.message);
            
        } catch (error) {
            showErrorAlert(error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Thêm ca sĩ';
        }
    };

    const showErrorAlert = (message) => {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        form.parentNode.insertBefore(alertDiv, form.nextSibling);
    };

    // Event Listeners
    avatarFileInput.addEventListener('change', handleAvatarPreview);
    form.addEventListener('submit', handleFormSubmit);
    
    // Real-time validation
    nameInput.addEventListener('input', function() {
        this.classList.toggle('is-valid', this.value.trim().length > 0);
        this.classList.toggle('is-invalid', !this.value.trim());
    });

    countrySelect.addEventListener('change', function() {
        this.classList.toggle('is-valid', this.value !== '');
        this.classList.toggle('is-invalid', this.value === '');
    });
});
</script>

<?php require "./views/layout/admin.layout.bottom.php"; ?>