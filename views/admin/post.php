<?php
require "./views/layout/admin.layout.top.php";
?>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <h1>Danh sách bài viết</h1>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Bộ lọc</span>
      <a href="/admin/post/create" class="btn btn-info btn-sm">Thêm bài viết</a>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Search Filter -->
        <div class="col-md-12 mb-3">
          <form action="/admin/posts" method="GET">
            <div class="d-flex">
              <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài viết" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
              <button type="submit" class="btn btn-success ml-2">Tìm</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Danh sách bài viết</div>
    <div class="card-body">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Hình ảnh</th>
            <th>Tiêu đề</th>
            <th style="width: 40%;">Mô tả</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $posts = json_decode($data, true)['data'];
          $totalPage = json_decode($data, true)['total_page'];
          
          // Lọc bài viết theo tìm kiếm  
          $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
          if ($searchQuery) {
            $posts = array_filter($posts, function ($post) use ($searchQuery) {
              return stripos($post['title'], $searchQuery) !== false || 
                     stripos($post['desc'], $searchQuery) !== false;
            });
          }
          
          // Xác định trang hiện tại
          $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
          $currentPage = max(1, $currentPage);
          
          $postsPerPage = 10; // Số bài viết mỗi trang
          $offset = ($currentPage - 1) * $postsPerPage;
          $count = $offset + 1;

          foreach ($posts as $post):
          ?>
            <tr>
              <td id="<?php echo htmlspecialchars($post['id']); ?>"><?php echo htmlspecialchars($count); ?></td>
              <td><img src="<?php echo htmlspecialchars($post['img']); ?>" alt="Post image" width="50"></td>
              <td><?php echo htmlspecialchars($post['title']); ?></td>
              <td style="white-space: pre-wrap;"><?php echo htmlspecialchars($post['desc']); ?></td>
              <td>
                <a href="/admin/post/edit?id=<?php echo htmlspecialchars($post['id']); ?>" class="btn btn-warning btn-sm">Sửa</a>
                <button onclick="deleteDB('/post','<?php echo htmlspecialchars($post['id']); ?>')" class="btn btn-danger btn-sm">Xóa</button>
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
      // Back button
      if ($currentPage > 1) {
        echo "<li class='page-item'><a class='page-link' href='/admin/posts?page=" . ($currentPage - 1) . "'>Trước</a></li>";
      }

      // Page numbers
      for ($i = 1; $i <= $totalPage; $i++) {
        echo "<li class='page-item " . ($i == $currentPage ? "active" : "") . "'><a class='page-link' href='/admin/posts?page={$i}'>{$i}</a></li>";
      }

      // Next button
      if ($currentPage < $totalPage) {
        echo "<li class='page-item'><a class='page-link' href='/admin/posts?page=" . ($currentPage + 1) . "'>Sau</a></li>";
      }
      ?>
    </ul>
  </nav>
</div>

<?php
require "./views/layout/admin.layout.bottom.php";
?>