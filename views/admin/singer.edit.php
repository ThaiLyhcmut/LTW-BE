<?php
if (!isset($singer) || empty($singer)) {
    if (isset($data)) {
        $singer = json_decode($data, true);
    } else {
        // Xử lý lỗi nếu không có dữ liệu
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
                <!-- Error/Success Message -->
                <div id="alert" class="alert d-none" role="alert"></div>

                <?php if (isset($message)): ?>
                    <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message['content']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form id="editSingerForm" action="/admin/singer/update" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($singer['id']); ?>">
                    
                    <div class="form-body">
                        <div class="row">
                            <!-- Avatar Preview -->
                            <div class="col-md-4">
                                <div class="avatar-preview-container text-center mb-4">
                                    <img id="avatar-preview" src="<?php echo htmlspecialchars($singer['avatar_url'] ?? '/assets/images/default-avatar.png'); ?>" 
                                         alt="Avatar" class="img-fluid rounded-circle" 
                                         style="width: 200px; height: 200px; object-fit: cover;">
                                    <div class="mt-2">
                                        <span class="text-muted">Avatar hiện tại</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <!-- Tên ca sĩ -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Tên ca sĩ <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="name" class="form-control" name="name" 
                                               value="<?php echo htmlspecialchars($singer['name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Quốc gia -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="country" class="form-label">Quốc gia <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <select name="country_code" id="country" class="form-select">
                                                <option value="">-- Chọn quốc gia --</option>
                                                <?php if (isset($country) && !empty($country)): ?>
                                                    <?php foreach ($country as $item): ?>
                                                        <option value="<?php echo htmlspecialchars($item['code']); ?>" 
                                                            <?php echo (isset($singer['country_code']) && $singer['country_code'] == $item['code']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($item['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <span class="input-group-text">hoặc</span>
                                            <input type="text" class="form-control" id="custom_country" name="custom_country" 
                                                   placeholder="Nhập tên quốc gia khác">
                                        </div>
                                        <div class="form-text">Chọn từ danh sách hoặc nhập tên quốc gia khác.</div>
                                    </div>
                                </div>
                                
                                <!-- Tiểu sử -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="biography" class="form-label">Tiểu sử</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea id="biography" class="form-control" name="biography" rows="4"><?php echo htmlspecialchars($singer['biography'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                                
                                <!-- Avatar Upload -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="avatar" class="form-label">Avatar mới</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="file" id="avatar" class="form-control" name="avatar" accept="image/*" 
                                               onchange="previewImage(this)">
                                        <div class="form-text">Để trống nếu không muốn thay đổi avatar.</div>
                                    </div>
                                </div>
                                
                                
                                <!-- Trạng thái -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Trạng thái</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                                <?php echo (isset($singer['status']) && $singer['status'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="status">Hiển thị</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Buttons -->
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
    
    <!-- Ca khúc của ca sĩ này -->
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Các ca khúc</h4>
                <a href="/admin/song/create?singer_id=<?php echo htmlspecialchars($singer['id'] ?? ''); ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm ca khúc
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên bài hát</th>
                                <th>Thể loại</th>
                                <th>Lượt nghe</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($songs) && !empty($songs)): ?>
                                <?php foreach ($songs as $index => $song): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($song['title']); ?></td>
                                        <td><?php echo htmlspecialchars($song['genre_name'] ?? ''); ?></td>
                                        <td><?php echo number_format($song['listen_count'] ?? 0); ?></td>
                                        <td><?php echo htmlspecialchars($song['created_at'] ?? ''); ?></td>
                                        <td>
                                            <a href="/admin/song/edit?id=<?php echo htmlspecialchars($song['id']); ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="/admin/song/delete/<?php echo htmlspecialchars($song['id']); ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa bài hát này?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Chưa có bài hát nào của ca sĩ này.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Xử lý chọn quốc gia hoặc nhập quốc gia
    const countrySelect = document.getElementById('country');
    const customCountry = document.getElementById('custom_country');
    
    // Khi chọn từ dropdown, xóa giá trị nhập tay
    countrySelect.addEventListener('change', function() {
        if (this.value) {
            customCountry.value = '';
        }
    });
    
    // Khi nhập vào ô custom, xóa giá trị dropdown
    customCountry.addEventListener('input', function() {
        if (this.value) {
            countrySelect.value = '';
        }
    });

    document.getElementById('editSingerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang xử lý...';

        // Kiểm tra chọn hoặc nhập quốc gia
        const countryValue = countrySelect.value;
        const customCountryValue = customCountry.value;
        
        if (!countryValue && !customCountryValue) {
            showAlert('danger', 'Vui lòng chọn hoặc nhập quốc gia.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Cập nhật';
            return;
        }

        // Client-side validation
        const avatar = document.getElementById('avatar').files[0];
        if (avatar) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (!allowedTypes.includes(avatar.type)) {
                showAlert('danger', 'Avatar phải là định dạng JPEG, PNG hoặc GIF.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Cập nhật';
                return;
            }
            
            if (avatar.size > maxSize) {
                showAlert('danger', 'Kích thước avatar không được vượt quá 5MB.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Cập nhật';
                return;
            }
        }

        // Submit form
        const formData = new FormData(this);
        const token = localStorage.getItem("auth_token") || '';
        
        // Thêm xử lý đặc biệt cho quốc gia trước khi gửi form
        if (customCountry.value) {
            // Nếu người dùng nhập tên quốc gia tùy chỉnh, thêm vào formData
            formData.append('country_name', customCountry.value);
        }

        fetch('singer/edit', {
            method: 'POST',
            body: formData,
            headers: {
                'Authorization': token ? 'Bearer ' + token : ''
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Cập nhật';

            if (data.success) {
                showAlert('success', 'Cập nhật thông tin ca sĩ thành công!');
                // Redirect sau 2 giây
                setTimeout(() => window.location.href = '/admin/singers', 2000);
            } else {
                showAlert('danger', data.message || 'Có lỗi xảy ra khi cập nhật thông tin ca sĩ.');
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Cập nhật';
            showAlert('danger', 'Lỗi kết nối: ' + error.message);
        });
    });

    function showAlert(type, message) {
        const alert = document.getElementById('alert');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.classList.remove('d-none');
        setTimeout(() => alert.classList.add('d-none'), 5000); // Ẩn sau 5 giây
    }
</script>

<?php require "./views/layout/admin.layout.bottom.php"; ?>