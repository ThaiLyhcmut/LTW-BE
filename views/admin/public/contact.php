<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin liên hệ</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($page) && !empty($page)): ?>
                    <form action="/admin/public/contact/update" method="POST">
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($contactInfo['address']) ? htmlspecialchars($contactInfo['address']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($contactInfo['phone']) ? htmlspecialchars($contactInfo['phone']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($contactInfo['email']) ? htmlspecialchars($contactInfo['email']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="url" class="form-control" id="facebook" name="facebook" value="<?php echo isset($contactInfo['facebook']) ? htmlspecialchars($contactInfo['facebook']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="url" class="form-control" id="twitter" name="twitter" value="<?php echo isset($contactInfo['twitter']) ? htmlspecialchars($contactInfo['twitter']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="url" class="form-control" id="instagram" name="instagram" value="<?php echo isset($contactInfo['instagram']) ? htmlspecialchars($contactInfo['instagram']) : ''; ?>">
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="typeX_desc">Type X Description</label>
                                    <input type="text" class="form-control" id="typeX_desc" name="typeX_desc" 
                                        value="<?php echo isset($page['typeX_desc']) ? htmlspecialchars($page['typeX_desc']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="typeX_total">Type X Total</label>
                                    <input type="number" class="form-control" id="typeX_total" name="typeX_total" 
                                        value="<?php echo isset($page['typeX_total']) ? (int)$page['typeX_total'] : '0'; ?>">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <a href="/admin/public" class="btn btn-secondary">Quay lại</a>
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
    </div>
</div> 