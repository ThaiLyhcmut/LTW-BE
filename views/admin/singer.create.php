<?php require "./views/layout/admin.layout.top.php"; ?>

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
                    <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message['content']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="/admin/singer/store" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <!-- Avatar Preview -->
                            <div class="col-md-4">
                                <div class="avatar-preview-container text-center mb-4">
                                    <img id="avatar-preview" src="/assets/images/default-avatar.png" 
                                         alt="Avatar Preview" class="img-fluid rounded-circle" 
                                         style="width: 200px; height: 200px; object-fit: cover;">
                                    <div class="mt-2">
                                        <span class="text-muted">Xem trước avatar</span>
                                    </div>
                                </div>
                                
                                <!-- Tips Box -->
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
                            
                            <div class="col-md-8">
                                <!-- Tên ca sĩ -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Tên ca sĩ <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="name" class="form-control" name="name" 
                                               placeholder="Nhập tên ca sĩ" required>
                                    </div>
                                </div>
                                
                                <!-- Quốc gia -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="country" class="form-label">Quốc gia <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="country_code" id="country" class="form-select" required>
                                            <option value="">-- Chọn quốc gia --</option>
                                            <?php foreach ($country as $item): ?>
                                                <option value="<?php echo htmlspecialchars($item['code']); ?>">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Tiểu sử -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="biography" class="form-label">Tiểu sử</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea id="biography" class="form-control" name="biography" 
                                                  rows="4" placeholder="Nhập tiểu sử ca sĩ (không bắt buộc)"></textarea>
                                    </div>
                                </div>
                                
                                <!-- Avatar Upload -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="avatar" class="form-label">Avatar <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="file" id="avatar" class="form-control" name="avatar" 
                                               accept="image/*" onchange="previewImage(this)" required>
                                    </div>
                                </div>
                                
                                <!-- Trạng thái -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Trạng thái</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                                            <label class="form-check-label" for="status">Hiển thị</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Có nổi bật ca sĩ này không -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Nổi bật</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1">
                                            <label class="form-check-label" for="featured">Hiển thị trên trang chủ</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Thẻ tags -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="tags" class="form-label">Từ khóa</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="tags" class="form-control" name="tags" 
                                               placeholder="Các từ khóa cách nhau bởi dấu phẩy">
                                        <div class="form-text">Ví dụ: pop, ballad, rock, v-pop, k-pop, ...</div>
                                    </div>
                                </div>
                                
                                <!-- Buttons -->
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <a href="/admin/singers" class="btn btn-light me-2">Hủy</a>
                                        <button type="submit" class="btn btn-primary">Thêm ca sĩ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Gợi ý ca sĩ mới nhất -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Ca sĩ đã thêm gần đây</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (isset($recent_singers) && !empty($recent_singers)): ?>
                        <?php foreach ($recent_singers as $singer): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="<?php echo htmlspecialchars($singer['avatar_url']); ?>" class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($singer['name']); ?>" 
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <h6 class="card-title mb-0"><?php echo htmlspecialchars($singer['name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($singer['country_code']); ?></small>
                                    </div>
                                    <div class="card-footer p-2 text-center">
                                        <a href="/admin/singer/edit?id=<?php echo htmlspecialchars($singer['id']); ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p>Chưa có ca sĩ nào được thêm gần đây.</p>
                        </div>
                    <?php endif; ?>
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
    
</script>

<?php require "./views/layout/admin.layout.bottom.php"; ?>