<?php require "./views/layout/admin.layout.top.php"; ?>

<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Chỉnh sửa thông tin trợ giúp</h3>
                    <p class="text-subtitle text-muted">Cập nhật thông tin công ty và hướng dẫn sử dụng</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/index">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/help">Trợ giúp</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Chỉnh sửa thông tin tổng quan</h4>
            </div>
            <div class="card-body">
                <form action="/admin/help/update" method="POST" id="aboutForm">
                    <input type="hidden" name="id" value="<?= isset($aboutInfo['id']) ? $aboutInfo['id'] : '' ?>">
                    
                    <!-- Thông tin tổng quan -->
                    <div class="form-group mb-4">
                        <label for="title" class="form-label">Tiêu đề công ty</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= isset($aboutInfo['title']) ? htmlspecialchars($aboutInfo['title']) : '' ?>" required>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="desc" class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" id="desc" name="desc" rows="3"><?= isset($aboutInfo['desc']) ? htmlspecialchars($aboutInfo['desc']) : '' ?></textarea>
                    </div>
                    
                    <!-- Thống kê -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h5>Thống kê chính</h5>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="type1_total" class="form-label">Thống kê 1 - Số lượng</label>
                                <input type="text" class="form-control" id="type1_total" name="type1_total" value="<?= isset($aboutInfo['type1_total']) ? htmlspecialchars($aboutInfo['type1_total']) : '' ?>">
                            </div>
                            <div class="form-group mb-4">
                                <label for="type1_desc" class="form-label">Thống kê 1 - Mô tả</label>
                                <input type="text" class="form-control" id="type1_desc" name="type1_desc" value="<?= isset($aboutInfo['type1_desc']) ? htmlspecialchars($aboutInfo['type1_desc']) : '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="type2_total" class="form-label">Thống kê 2 - Số lượng</label>
                                <input type="text" class="form-control" id="type2_total" name="type2_total" value="<?= isset($aboutInfo['type2_total']) ? htmlspecialchars($aboutInfo['type2_total']) : '' ?>">
                            </div>
                            <div class="form-group mb-4">
                                <label for="type2_desc" class="form-label">Thống kê 2 - Mô tả</label>
                                <input type="text" class="form-control" id="type2_desc" name="type2_desc" value="<?= isset($aboutInfo['type2_desc']) ? htmlspecialchars($aboutInfo['type2_desc']) : '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="type3_total" class="form-label">Thống kê 3 - Số lượng</label>
                                <input type="text" class="form-control" id="type3_total" name="type3_total" value="<?= isset($aboutInfo['type3_total']) ? htmlspecialchars($aboutInfo['type3_total']) : '' ?>">
                            </div>
                            <div class="form-group mb-4">
                                <label for="type3_desc" class="form-label">Thống kê 3 - Mô tả</label>
                                <input type="text" class="form-control" id="type3_desc" name="type3_desc" value="<?= isset($aboutInfo['type3_desc']) ? htmlspecialchars($aboutInfo['type3_desc']) : '' ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tiêu đề các phần -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h5>Tiêu đề các phần nội dung</h5>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="title1" class="form-label">Tiêu đề phần 1</label>
                                <input type="text" class="form-control" id="title1" name="title1" value="Nội dung giới thiệu" readonly>
                                <small class="text-muted">Tiêu đề mặc định không thể thay đổi</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="title2" class="form-label">Tiêu đề phần 2</label>
                                <input type="text" class="form-control" id="title2" name="title2" value="<?= isset($aboutInfo['title2']) ? htmlspecialchars($aboutInfo['title2']) : 'Phần nội dung phụ' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="title3" class="form-label">Tiêu đề phần 3</label>
                                <input type="text" class="form-control" id="title3" name="title3" value="<?= isset($aboutInfo['title3']) ? htmlspecialchars($aboutInfo['title3']) : 'Phần nội dung bổ sung' ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phần 1: Giới thiệu -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h5>Phần 1: Nội dung giới thiệu</h5>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="section1_content" class="form-label">Nội dung</label>
                        <textarea class="form-control editor" id="section1_content" name="section1[content]" rows="5"><?= isset($aboutInfo['section1']['content']) ? htmlspecialchars_decode($aboutInfo['section1']['content']) : '' ?></textarea>
                    </div>
                    
                    <div class="image-urls-container" id="section1_urls">
                        <label class="form-label">URL hình ảnh</label>
                        <?php
                        $section1Urls = isset($aboutInfo['section1']['urls']) ? (array)$aboutInfo['section1']['urls'] : [];
                        if (empty($section1Urls)) {
                            // Thêm một trường rỗng nếu không có URL nào
                            ?>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="section1[urls][]" placeholder="https://example.com/image.jpg">
                                <button class="btn btn-outline-secondary add-url-btn" type="button" data-section="section1"><i class="bi bi-plus"></i></button>
                            </div>
                            <?php
                        } else {
                            foreach ($section1Urls as $index => $url) {
                                ?>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="section1[urls][]" value="<?= htmlspecialchars($url) ?>" placeholder="https://example.com/image.jpg">
                                    <?php if ($index === count($section1Urls) - 1) { ?>
                                        <button class="btn btn-outline-secondary add-url-btn" type="button" data-section="section1"><i class="bi bi-plus"></i></button>
                                    <?php } else { ?>
                                        <button class="btn btn-outline-danger remove-url-btn" type="button"><i class="bi bi-trash"></i></button>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    
                    <!-- Phần 2: Nội dung phụ -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h5>Phần 2: <span id="title2_display"><?= isset($aboutInfo['title2']) ? htmlspecialchars($aboutInfo['title2']) : 'Phần nội dung phụ' ?></span></h5>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="section2_content" class="form-label">Nội dung</label>
                        <textarea class="form-control editor" id="section2_content" name="section2[content]" rows="5"><?= isset($aboutInfo['section2']['content']) ? htmlspecialchars_decode($aboutInfo['section2']['content']) : '' ?></textarea>
                    </div>
                    
                    <div class="image-urls-container" id="section2_urls">
                        <label class="form-label">URL hình ảnh</label>
                        <?php
                        $section2Urls = isset($aboutInfo['section2']['urls']) ? (array)$aboutInfo['section2']['urls'] : [];
                        if (empty($section2Urls)) {
                            ?>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="section2[urls][]" placeholder="https://example.com/image.jpg">
                                <button class="btn btn-outline-secondary add-url-btn" type="button" data-section="section2"><i class="bi bi-plus"></i></button>
                            </div>
                            <?php
                        } else {
                            foreach ($section2Urls as $index => $url) {
                                ?>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="section2[urls][]" value="<?= htmlspecialchars($url) ?>" placeholder="https://example.com/image.jpg">
                                    <?php if ($index === count($section2Urls) - 1) { ?>
                                        <button class="btn btn-outline-secondary add-url-btn" type="button" data-section="section2"><i class="bi bi-plus"></i></button>
                                    <?php } else { ?>
                                        <button class="btn btn-outline-danger remove-url-btn" type="button"><i class="bi bi-trash"></i></button>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    
                    <!-- Phần 3: Nội dung bổ sung -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h5>Phần 3: <span id="title3_display"><?= isset($aboutInfo['title3']) ? htmlspecialchars($aboutInfo['title3']) : 'Phần nội dung bổ sung' ?></span></h5>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="section3_content" class="form-label">Nội dung</label>
                        <textarea class="form-control editor" id="section3_content" name="section3[content]" rows="5"><?= isset($aboutInfo['section3']['content']) ? htmlspecialchars_decode($aboutInfo['section3']['content']) : '' ?></textarea>
                    </div>
                    
                    <div class="image-urls-container" id="section3_urls">
                        <label class="form-label">URL hình ảnh</label>
                        <?php
                        $section3Urls = isset($aboutInfo['section3']['urls']) ? (array)$aboutInfo['section3']['urls'] : [];
                        if (empty($section3Urls)) {
                            ?>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="section3[urls][]" placeholder="https://example.com/image.jpg">
                                <button class="btn btn-outline-secondary add-url-btn" type="button" data-section="section3"><i class="bi bi-plus"></i></button>
                            </div>
                            <?php
                        } else {
                            foreach ($section3Urls as $index => $url) {
                                ?>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="section3[urls][]" value="<?= htmlspecialchars($url) ?>" placeholder="https://example.com/image.jpg">
                                    <?php if ($index === count($section3Urls) - 1) { ?>
                                        <button class="btn btn-outline-secondary add-url-btn" type="button" data-section="section3"><i class="bi bi-plus"></i></button>
                                    <?php } else { ?>
                                        <button class="btn btn-outline-danger remove-url-btn" type="button"><i class="bi bi-trash"></i></button>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-end">
                            <a href="/admin/help" class="btn btn-light me-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php require "./views/layout/admin.layout.bottom.php"; ?>

<script src="/assets/vendors/tinymce/tinymce.min.js"></script>
<script>
    // Khởi tạo TinyMCE
    tinymce.init({
        selector: '.editor',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        height: 300
    });
    
    // Cập nhật tiêu đề hiển thị khi người dùng nhập
    document.getElementById('title2').addEventListener('input', function() {
        document.getElementById('title2_display').textContent = this.value || 'Phần nội dung phụ';
    });
    
    document.getElementById('title3').addEventListener('input', function() {
        document.getElementById('title3_display').textContent = this.value || 'Phần nội dung bổ sung';
    });
    
    // Xử lý thêm/xóa trường URL
    document.addEventListener('click', function(e) {
        // Thêm URL mới
        if (e.target.classList.contains('add-url-btn') || e.target.closest('.add-url-btn')) {
            const button = e.target.classList.contains('add-url-btn') ? e.target : e.target.closest('.add-url-btn');
            const section = button.dataset.section;
            const container = document.getElementById(section + '_urls');
            const inputGroup = button.closest('.input-group');
            
            // Thay đổi nút thêm thành nút xóa cho trường hiện tại
            button.classList.remove('btn-outline-secondary', 'add-url-btn');
            button.classList.add('btn-outline-danger', 'remove-url-btn');
            button.innerHTML = '<i class="bi bi-trash"></i>';
            button.removeAttribute('data-section');
            
            // Tạo trường input mới với nút thêm
            const newGroup = document.createElement('div');
            newGroup.className = 'input-group mb-3';
            newGroup.innerHTML = `
                <input type="text" class="form-control" name="${section}[urls][]" placeholder="https://example.com/image.jpg">
                <button class="btn btn-outline-secondary add-url-btn" type="button" data-section="${section}"><i class="bi bi-plus"></i></button>
            `;
            
            container.appendChild(newGroup);
        }
        
        // Xóa URL
        if (e.target.classList.contains('remove-url-btn') || e.target.closest('.remove-url-btn')) {
            const inputGroup = e.target.closest('.input-group');
            inputGroup.remove();
        }
    });
    
    // Validate form trước khi submit
    document.getElementById('aboutForm').addEventListener('submit', function(e) {
        // Cập nhật nội dung từ TinyMCE trước khi submit
        tinymce.triggerSave();
        
        // Kiểm tra các trường bắt buộc
        const title = document.getElementById('title').value.trim();
        
        if (!title) {
            e.preventDefault();
            alert('Vui lòng nhập tiêu đề công ty!');
            return false;
        }
        
        // Có thể thêm validation khác ở đây
        
        return true;
    });
</script>