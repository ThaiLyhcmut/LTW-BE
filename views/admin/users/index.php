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
        <div class="d-flex justify-content-between align-items-center">
            <h3>User Management</h3>
            <a href="/admin/info" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Info
            </a>
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>User List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Country</th>
                                        <th>VIP</th>
                                        <th>Avatar</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo (int)$user['id']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($user['avatar_url'])): ?>
                                                            <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="avatar" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
                                                        <?php endif; ?>
                                                        <?php echo htmlspecialchars($user['username']); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                                                        <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $user['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                        <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($user['country_code']) ? htmlspecialchars($user['country_code']) : 'N/A'; ?></td>
                                                <td>
                                                    <?php if (isset($user['vip']) && $user['vip']): ?>
                                                        <span class="badge bg-warning text-dark">VIP</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-dark">No</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($user['avatar_url'])): ?>
                                                        <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="avatar" width="32" height="32" class="rounded-circle">
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <a href="/admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <?php if ($user['status'] === 'active'): ?>
                                                            <form action="/admin/users/ban/<?php echo $user['id']; ?>" method="POST" class="d-inline">
                                                                <button type="submit" class="btn btn-sm btn-warning" title="Ban"
                                                                    onclick="return confirm('Are you sure you want to ban this user?')">
                                                                    <i class="bi bi-ban"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <form action="/admin/users/delete/<?php echo $user['id']; ?>" method="POST" class="d-inline">
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (isset($totalPages) && $totalPages > 1): ?>
                            <nav aria-label="Page navigation" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
require "./views/layout/admin.layout.bottom.php";
?>
