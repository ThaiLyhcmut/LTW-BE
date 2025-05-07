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
                    <h3>Trung tâm trợ giúp</h3>
                    <p class="text-subtitle text-muted">Quản lý thông tin công ty và hướng dẫn sử dụng</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/index">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Trợ giúp</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Thông tin tổng quan công ty -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Thông tin tổng quan công ty</h4>
                <a href="/admin/help/edit" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Chỉnh sửa
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 ><?= isset($aboutInfo['title']) ? htmlspecialchars($aboutInfo['title']) : 'Chưa cập nhật tiêu đề' ?></h5>
                        <p class="text-muted mb-4"><?= isset($aboutInfo['desc']) ? htmlspecialchars($aboutInfo['desc']) : 'Chưa cập nhật mô tả' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thống kê chính</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="stats-card text-center p-4 bg-primary bg-opacity-10 rounded">
                            <h2 class="font-extrabold mb-1"><?= isset($aboutInfo['type1_total']) ? htmlspecialchars($aboutInfo['type1_total']) : '0' ?></h2>
                            <p class="text-muted font-bold" style="font-weight: bold"><?= isset($aboutInfo['type1_desc']) ? htmlspecialchars($aboutInfo['type1_desc']) : 'Chưa cập nhật' ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="stats-card text-center p-4 bg-success bg-opacity-10 rounded">
                            <h2 class="font-extrabold mb-1"><?= isset($aboutInfo['type2_total']) ? htmlspecialchars($aboutInfo['type2_total']) : '0' ?></h2>
                            <p class="text-muted" style="font-weight: bold"><?= isset($aboutInfo['type2_desc']) ? htmlspecialchars($aboutInfo['type2_desc']) : 'Chưa cập nhật' ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="stats-card text-center p-4 bg-warning bg-opacity-10 rounded">
                            <h2 class="font-extrabold mb-1"><?= isset($aboutInfo['type3_total']) ? htmlspecialchars($aboutInfo['type3_total']) : '0' ?></h2>
                            <p class="text-muted" style="font-weight: bold"><?= isset($aboutInfo['type3_desc']) ? htmlspecialchars($aboutInfo['type3_desc']) : 'Chưa cập nhật' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
function renderSection($section, $title, $alertClass) {
    // Kiểm tra nếu section không rỗng và có dữ liệu
    if (!empty($section) && is_array($section)) {
        // Lấy nội dung (nếu có)
        $content = isset($section['content']) ? htmlspecialchars_decode($section['content']) : 'Không có nội dung';
        
        // Lấy danh sách URLs - sửa từ 'url' thành 'urls' để khớp với cấu trúc dữ liệu
        $urls = isset($section['urls']) ? (array)$section['urls'] : [];
        ?>
        <div class="section-content">
            <h3><?= htmlspecialchars($title) ?></h3>
            
            <?php if (!empty($content)): ?>
                <div class="content-box"><?= $content ?></div>
            <?php else: ?>
                <div class="alert alert-light-info">
                    <i class="bi bi-info-circle"></i> Không có nội dung mô tả.
                </div>
            <?php endif; ?>
            
            <div><strong>Danh sách URL hình ảnh:</strong></div>
            <?php if (!empty($urls)): ?>
                <div class="image-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                    <?php foreach ($urls as $url): ?>
                        <?php if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)): ?>
                            <div class="image-item" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                                <a href="<?= htmlspecialchars($url) ?>" target="_blank">
                                    <img src="<?= htmlspecialchars($url) ?>" alt="Hình ảnh" style="max-width: 100%; height: auto; display: block;">
                                </a>
                                <div style="word-break: break-all; font-size: 0.8em; margin-top: 5px;">
                                    <?= htmlspecialchars(substr($url, 0, 50)) ?>...
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert <?= $alertClass ?>">
                    <i class="bi bi-exclamation-triangle"></i> Chưa có URL hình ảnh nào được thêm vào.
                </div>
            <?php endif; ?>
        </div>
        <?php
    } else {
        ?>
        <div class="alert <?= $alertClass ?>">
            <i class="bi bi-info-circle"></i> Chưa có nội dung cho <?= strtolower($title) ?>.
        </div>
        <?php
    }
}
?>
<!-- Phần 1 -->
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Nội dung giới thiệu</h4>
    </div>
    <div class="card-body">
        <?php renderSection($aboutInfo['section1'], 'phần giới thiệu', 'alert-light-primary'); ?>
    </div>
</div>

<!-- Phần 2 -->
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= isset($aboutInfo['title2']) ? htmlspecialchars($aboutInfo['title2']) : 'Phần nội dung phụ' ?></h4>
    </div>
    <div class="card-body">
        <?php renderSection($aboutInfo['section2'], 'phần nội dung phụ', 'alert-light-secondary'); ?>
    </div>
</div>

<!-- Phần 3 -->
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= isset($aboutInfo['title3']) ? htmlspecialchars($aboutInfo['title3']) : 'Phần nội dung bổ sung' ?></h4>
    </div>
    <div class="card-body">
        <?php renderSection($aboutInfo['section3'], 'phần nội dung bổ sung', 'alert-light-info'); ?>
    </div>
</div>

        <!-- Hướng dẫn sử dụng -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hướng dẫn sử dụng</h4>
            </div>
            <div class="card-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Cách cập nhật thông tin công ty
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <ol>
                                    <li>Nhấp vào nút <strong>"Chỉnh sửa"</strong> ở góc phải trên cùng của phần Thông tin công ty.</li>
                                    <li>Điền thông tin cần thiết vào biểu mẫu.</li>
                                    <li>Nhấp vào <strong>"Lưu thông tin"</strong> để cập nhật dữ liệu.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Quản lý nội dung các phần
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p>Để quản lý nội dung các phần:</p>
                                <ol>
                                    <li>Sử dụng trình soạn thảo văn bản khi chỉnh sửa thông tin.</li>
                                    <li>Bạn có thể thêm hình ảnh, định dạng văn bản và thêm liên kết.</li>
                                    <li>Nội dung sẽ được lưu dưới dạng JSON để đảm bảo tính linh hoạt.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Cập nhật số liệu thống kê
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p>Để cập nhật số liệu thống kê:</p>
                                <ol>
                                    <li>Điền thông tin vào các trường <strong>Total</strong> và <strong>Description</strong> cho từng loại.</li>
                                    <li>Các thông số này sẽ hiển thị trên trang giới thiệu của công ty.</li>
                                    <li>Đảm bảo cung cấp dữ liệu chính xác để tạo ấn tượng tốt với khách hàng.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require "./views/layout/admin.layout.bottom.php"; ?>

<script>
    const debugSections = <?= json_encode([
        'section1' => $aboutInfo['section1'],
        'section2' => $aboutInfo['section2'],
        'section3' => $aboutInfo['section3'],
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>;
    console.log("=== Debug thông tin section1 - section3 ===");
    console.log(debugSections);
</script>
