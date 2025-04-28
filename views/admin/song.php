<?php
require "./views/layout/admin.layout.top.php";
?>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <h1>Danh sách bài hát</h1>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Bộ lọc</span>
      <a href="admin/song/create" class="btn btn-info btn-sm">Add Song</a>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Status Filter -->
        <div class="col-md-4 mb-3">
          <form action="/admin/songs" method="GET">
            <div class="d-flex">
              <select name="status" class="form-select mr-2">
                <option value="">Tất cả</option>
                <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
              </select>
              <button type="submit" class="btn btn-primary">Lọc</button>
            </div>
          </form>
        </div>

        <!-- Search Filter -->
        <div class="col-md-8 mb-3">
          <form action="/admin/songs" method="GET">
            <div class="d-flex">
              <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài hát" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
              <button type="submit" class="btn btn-success ml-2">Tìm</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="card">
    <div class="card-header">Danh sách bài hát</div>
    <div class="card-body">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Hình ảnh</th>
            <th>Tiêu đề</th>
            <th>Thời gian</th>
            <th>Tạo bởi</th>
            <th>Cập nhật bởi</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $songs = json_decode($data, true)['data'];
          // Lọc bài hát theo trạng thái và tìm kiếm
          $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
          $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
          if ($statusFilter) {
            $songs = array_filter($songs, function ($song) use ($statusFilter) {
              return $song['status'] == $statusFilter;
            });
          }
          if ($searchQuery) {
            $songs = array_filter($songs, function ($song) use ($searchQuery) {
              return stripos($song['title'], $searchQuery) !== false;
            });
          }

          $totalSongs = count($songs);
          $songsPerPage = 10;
          $totalPage = ceil($totalSongs / $songsPerPage);
          $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
          $currentPage = max(1, $currentPage);
          $offset = ($currentPage - 1) * $songsPerPage;
          $songs = array_slice($songs, $offset, $songsPerPage);

          foreach ($songs as $song):
          ?>
            <tr>
              <td><?php echo htmlspecialchars($song['id']); ?></td>
              <td><img src="<?php echo htmlspecialchars($song['cover_url']); ?>" alt="Cover image" width="50"></td>
              <td><?php echo htmlspecialchars($song['title']); ?></td>
              <td><?php echo $song['duration']; ?> seconds</td>
              <td><?php echo htmlspecialchars($song['name']); ?></td>
              <td><?php echo htmlspecialchars($song['updated_by'] ?? 'N/A'); ?></td>
              <td>
                <a href="admin/song/detail/<?php echo htmlspecialchars($song['id']); ?>" class="btn btn-info btn-sm">Detail</a>
                <a href="admin/song/edit/<?php echo htmlspecialchars($song['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="admin/song/delete/<?php echo htmlspecialchars($song['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <nav class="mt-3">
    <ul class="pagination justify-content-center">
      <?php
      // Back button
      if ($currentPage > 1) {
        echo "<li class='page-item'><a class='page-link' href='/admin/songs?page=" . ($currentPage - 1) . "'>Back</a></li>";
      }

      // Page numbers
      for ($i = 1; $i <= $totalPage; $i++) {
        $activeClass = $i == $currentPage ? "active" : "";
        echo "<li class='page-item {$activeClass}'><a class='page-link' href='/admin/songs?page={$i}'>{$i}</a></li>";
      }

      // Next button
      if ($currentPage < $totalPage) {
        echo "<li class='page-item'><a class='page-link' href='/admin/songs?page=" . ($currentPage + 1) . "'>Next</a></li>";
      }
      ?>
    </ul>
  </nav>
</div>

<?php
require "./views/layout/admin.layout.bottom.php";
?>