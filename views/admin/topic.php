<?php
require "./views/layout/admin.layout.top.php";
?>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <h1>Danh sách thể loại nhạc</h1>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Bộ lọc</span>
      <a href="admin/song/create" class="btn btn-info btn-sm">Add Category</a>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Search Filter -->
        <div class="col-md-4 mb-3">
          <form action="/admin/topics" method="GET">
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
          <form action="/admin/songs" method="GET">
            <div class="d-flex">
              <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên thể loại" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
              <button type="submit" class="btn btn-success ml-2">Tìm</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php
  $decodedData = json_decode($data, true);
  $categories = $decodedData['data'];
  $totalPage = $decodedData['total_page'];
  // Lọc theo từ khoá tìm kiếm nếu có
  $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
  if ($searchQuery) {
    $categories = array_filter($categories, function ($category) use ($searchQuery) {
      return stripos($category['name'], $searchQuery) !== false;
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
  $count = $offset + 1;

  ?>

  <div class="card">
    <div class="card-header">Danh sách thể loại</div>
    <div class="card-body">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Hình ảnh</th>
            <th>Tên thể loại</th>
            <th>Mô tả</th>
            <th>Mã quốc gia</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $count = $offset + 1; // Đảm bảo bắt đầu đếm từ đúng trang
          foreach ($categories as $category): ?>
            <tr>
              <td id="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($count); ?></td>
              <td><img src="<?php echo htmlspecialchars($category['image_url']); ?>" alt="Cover image" width="50"></td>
              <td><?php echo htmlspecialchars($category['name']); ?></td>
              <td><?php echo htmlspecialchars($category['description']); ?></td>
              <td><?php echo htmlspecialchars($category['country_code']); ?></td>
              <td><?php echo htmlspecialchars($category['created_at']); ?></td>
              <td>
                <a href="admin/song/detail/<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-info btn-sm">Detail</a>
                <a href="admin/song/edit/<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="admin/song/delete/<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
              </td>
            </tr>
            <?php $count++; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <nav class="mt-3">
    <ul class="pagination justify-content-center">
      <?php
      if ($currentPage > 1) {
        echo "<li class='page-item'><a class='page-link' href='/admin/topics?page=" . ($currentPage - 1) . "'>Back</a></li>";
      }
      for ($i = 1; $i <= $totalPage; $i++) {
        $activeClass = $i == $currentPage ? "active" : "";
        echo "<li class='page-item {$activeClass}'><a class='page-link' href='/admin/topics?page={$i}'>" . $i . "</a></li>";
      }
      if ($currentPage < $totalPage) {
        echo "<li class='page-item'><a class='page-link' href='/admin/topics?page=" . ($currentPage + 1) . "'>Next</a></li>";
      }
      ?>
    </ul>
  </nav>
</div>

<?php
require "./views/layout/admin.layout.bottom.php";
?>