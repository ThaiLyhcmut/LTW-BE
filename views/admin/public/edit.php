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
        <h3>Edit About Page</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>About Page Information</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($page) && !empty($page)): ?>
                        <form action="/admin/public/update/<?php echo isset($page['id']) ? $page['id'] : ''; ?>" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                            value="<?php echo isset($page['title']) ? htmlspecialchars($page['title']) : ''; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="desc">Description</label>
                                        <textarea class="form-control" id="desc" name="desc" rows="3" required><?php echo isset($page['desc']) ? htmlspecialchars($page['desc']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type1_desc">Type 1 Description</label>
                                        <input type="text" class="form-control" id="type1_desc" name="type1_desc" 
                                            value="<?php echo isset($page['type1_desc']) ? htmlspecialchars($page['type1_desc']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type1_total">Type 1 Total</label>
                                        <input type="number" class="form-control" id="type1_total" name="type1_total" 
                                            value="<?php echo isset($page['type1_total']) ? (int)$page['type1_total'] : '0'; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type2_desc">Type 2 Description</label>
                                        <input type="text" class="form-control" id="type2_desc" name="type2_desc" 
                                            value="<?php echo isset($page['type2_desc']) ? htmlspecialchars($page['type2_desc']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type2_total">Type 2 Total</label>
                                        <input type="number" class="form-control" id="type2_total" name="type2_total" 
                                            value="<?php echo isset($page['type2_total']) ? (int)$page['type2_total'] : '0'; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type3_desc">Type 3 Description</label>
                                        <input type="text" class="form-control" id="type3_desc" name="type3_desc" 
                                            value="<?php echo isset($page['type3_desc']) ? htmlspecialchars($page['type3_desc']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type3_total">Type 3 Total</label>
                                        <input type="number" class="form-control" id="type3_total" name="type3_total" 
                                            value="<?php echo isset($page['type3_total']) ? (int)$page['type3_total'] : '0'; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title2">Title 2</label>
                                        <input type="text" class="form-control" id="title2" name="title2" 
                                            value="<?php echo isset($page['title2']) ? htmlspecialchars($page['title2']) : ''; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="section3">Section 3</label>
                                        <textarea class="form-control" id="section3" name="section3" rows="5"><?php echo isset($page['section3']) ? htmlspecialchars($page['section3']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Page</button>
                                    <a href="/admin/public" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                        <?php else: ?>
                        <div class="alert alert-danger">
                            Page not found or invalid page ID.
                        </div>
                        <a href="/admin/public" class="btn btn-secondary">Back to List</a>
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