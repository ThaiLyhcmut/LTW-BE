<?php require "./views/layout/admin.layout.top.php"; ?>

<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <h1>Danh sách ca sĩ</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Bộ lọc</span>
            <a href="admin/singer/create" class="btn btn-info btn-sm">Add Singer</a>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Search Filter -->
                <div class="col-md-4 mb-3">
                    <form action="/admin/singers" method="GET">
                        <div class="d-flex">
                            <select name="country" class="form-select mr-2 ml-2">
                                <option value="">Tất cả quốc gia</option>
                                <?php
                                foreach ($country as $item): ?>
                                    <option value="<?php echo htmlspecialchars($item['code']); ?>"
                                        <?php echo (isset($_GET['country']) && $_GET['country'] == $item['code']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 mb-3">
                    <form action="/admin/singers" method="GET">
                        <div class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên ca sĩ"
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit" class="btn btn-success ml-2">Tìm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    $decodedData = json_decode($data, true);
    $singers = $decodedData['data'];
    $totalPage = $decodedData['total_page'];
    // Lọc tìm kiếm theo tên
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
    if ($searchQuery) {
        $singers = array_filter($singers, function ($singer) use ($searchQuery) {
            return stripos($singer['name'], $searchQuery) !== false;
        });
    }

    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $currentPage = max(1, $currentPage);

    // BỎ đoạn phân trang sau vì dữ liệu đã được phân trang từ server
    // $songsPerPage = 2;
    // $offset = ($currentPage - 1) * $songsPerPage;
    // $songs = array_slice($songs, $offset, $songsPerPage);

    // Tính vị trí bắt đầu của item trên trang hiện tại
    $songsPerPage = 2; // Giữ lại biến này để tính toán
    $offset = ($currentPage - 1) * $songsPerPage;
    ?>

    <div class="card mt-4">
        <div class="card-header">Danh sách ca sĩ</div>
        <div class="card-body">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Tên ca sĩ</th>
                        <th>Quốc gia</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = $offset + 1;
                    foreach ($singers as $singer):
                    ?>
                        <tr>
                            <td id="<?php echo htmlspecialchars($singer['id']); ?>"><?php echo htmlspecialchars($count); ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($singer['avatar_url']); ?>" alt="Avatar" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                            </td>
                            <td><?php echo htmlspecialchars($singer['name']); ?></td>
                            <td><?php echo htmlspecialchars($singer['country_code']); ?></td>
                            <td><?php echo htmlspecialchars($singer['created_at']); ?></td>
                            <td>
                                <a href="admin/singer/edit/<?php echo htmlspecialchars($singer['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="admin/singer/delete/<?php echo htmlspecialchars($singer['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php $count++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/admin/singers?page=<?php echo $currentPage - 1; ?>">Back</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                        <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="/admin/singers?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPage): ?>
                        <li class="page-item">
                            <a class="page-link" href="/admin/singers?page=<?php echo $currentPage + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

</div>

<?php require "./views/layout/admin.layout.bottom.php"; ?>