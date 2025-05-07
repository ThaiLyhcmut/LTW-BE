<?php
require "./views/layout/admin.layout.top.php"
?>
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <h3>System Information</h3>
    </div>
    <div class="page-content">
        <!-- Quick Access Buttons -->
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Quick Access</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="/admin/users" class="btn btn-primary btn-lg w-100 mb-3">
                                    <i class="bi bi-people"></i> Users Management
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="/admin/public" class="btn btn-success btn-lg w-100 mb-3">
                                    <i class="bi bi-file-earmark-text"></i> Public Pages
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Statistics -->
        <section class="row">
            <div class="col-12 col-lg-3">
                <a href="/admin/songs" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Total Songs</h6>
                                    <h6 class="font-extrabold mb-0"><?php echo $totalSongs; ?></h6>
                                </div>
                                <div class="avatar bg-primary">
                                    <i class="bi bi-music-note"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-3">
                <a href="/admin/singers" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Total Singers</h6>
                                    <h6 class="font-extrabold mb-0"><?php echo $totalSingers; ?></h6>
                                </div>
                                <div class="avatar bg-success">
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-3">
                <a href="/admin/albums" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Total Albums</h6>
                                    <h6 class="font-extrabold mb-0"><?php echo $totalAlbums; ?></h6>
                                </div>
                                <div class="avatar bg-warning">
                                    <i class="bi bi-collection"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-3">
                <a href="/admin/users" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold">Total Users</h6>
                                    <h6 class="font-extrabold mb-0"><?php echo $totalUsers; ?></h6>
                                </div>
                                <div class="avatar bg-danger">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- System Status -->
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>System Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6>PHP Version</h6>
                                    <p class="text-muted"><?php echo phpversion(); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6>Database Status</h6>
                                    <p class="text-muted">Connected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- User Information -->
        <?php if (isset($_SESSION['user'])): ?>
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>User Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="<?php echo $_SESSION['user']['avatar_url']; ?>" class="rounded-circle" width="100" height="100">
                            </div>
                            <div class="col-md-10">
                                <h5><?php echo $_SESSION['user']['username']; ?></h5>
                                <p class="text-muted"><?php echo $_SESSION['user']['email']; ?></p>
                                <p>Role: <?php echo $_SESSION['user']['role']; ?></p>
                                <p>Joined: <?php echo date('Y-m-d', strtotime($_SESSION['user']['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>
<?php
require "./views/layout/admin.layout.bottom.php"
?>