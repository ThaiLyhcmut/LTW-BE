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
            <h3>About Page Management</h3>
            <a href="/admin/info" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Info
            </a>
        </div>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>About Page List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Type 1</th>
                                        <th>Type 2</th>
                                        <th>Type 3</th>
                                        <th>Title 2</th>
                                        <th>Section 3</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($pages)): ?>
                                        <?php foreach ($pages as $page): ?>
                                            <tr>
                                                <td><?php echo isset($page['id']) ? (int)$page['id'] : ''; ?></td>
                                                <td><?php echo isset($page['title']) ? htmlspecialchars($page['title']) : ''; ?></td>
                                                <td><?php echo isset($page['desc']) ? htmlspecialchars($page['desc']) : ''; ?></td>
                                                <td>
                                                    <?php if (isset($page['type1_desc']) || isset($page['type1_total'])): ?>
                                                        <div><?php echo isset($page['type1_desc']) ? htmlspecialchars($page['type1_desc']) : ''; ?></div>
                                                        <small class="text-muted">Total: <?php echo isset($page['type1_total']) ? (int)$page['type1_total'] : '0'; ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (isset($page['type2_desc']) || isset($page['type2_total'])): ?>
                                                        <div><?php echo isset($page['type2_desc']) ? htmlspecialchars($page['type2_desc']) : ''; ?></div>
                                                        <small class="text-muted">Total: <?php echo isset($page['type2_total']) ? (int)$page['type2_total'] : '0'; ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (isset($page['type3_desc']) || isset($page['type3_total'])): ?>
                                                        <div><?php echo isset($page['type3_desc']) ? htmlspecialchars($page['type3_desc']) : ''; ?></div>
                                                        <small class="text-muted">Total: <?php echo isset($page['type3_total']) ? (int)$page['type3_total'] : '0'; ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo isset($page['title2']) ? htmlspecialchars($page['title2']) : ''; ?></td>
                                                <td>
                                                    <?php if (isset($page['section3'])): ?>
                                                        <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($page['section3']); ?>">
                                                            <?php echo htmlspecialchars($page['section3']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <a href="/admin/public/edit/<?php echo isset($page['id']) ? $page['id'] : ''; ?>" 
                                                           class="btn btn-sm btn-primary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No pages found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
require "./views/layout/admin.layout.bottom.php";
?> 