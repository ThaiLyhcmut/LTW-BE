<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mazer Admin Dashboard</title>

    <link rel="shortcut icon" href="/public/assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="/public/assets/compiled/css/app.css">
    <link rel="stylesheet" href="/public/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="/public/assets/compiled/css/auth.css">
</head>

<body>
    <script src="/public/assets/static/js/initTheme.js"></script>
    <div id="auth" class="bg-dark text-white">
        <div class="row h-100">
            <div class="col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <img src="/public/image/logo.svg" alt="logo" class="h-[42px]" />
                        <div class="text-primary font-[700] xl:text-[30px] text-[18px] ml-[20px]">
                            ThaiLyMusic
                        </div>
                    </div>
                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

                    <!-- Thay đổi action form để gửi đến /login -->
                    <form id="login-form">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" id="email" name="email" class="form-control form-control-xl" placeholder="Email">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" id="password" name="password" class="form-control form-control-xl" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit">Log in</button>
                    </form>

                    <script>
                        document.getElementById('login-form').addEventListener('submit', function(e) {
                            e.preventDefault();

                            var email = document.getElementById('email').value;
                            var password = document.getElementById('password').value;

                            var data = {
                                email: email,
                                password: password
                            };

                            fetch('/login', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.token) {
                                        // Lưu token vào localStorage (hoặc sessionStorage nếu muốn token chỉ tồn tại trong phiên làm việc)
                                        localStorage.setItem('auth_token', data.token);
                                        document.cookie = "auth_token=" + data.token + "; path=/; max-age=3600"; 
                                        // Chuyển hướng người dùng đến trang indexx
                                        window.location.href = '/admin/index';  // Hoặc sử dụng window.location.replace('/indexx') nếu không muốn lưu trang đăng nhập trong lịch sử duyệt web
                                    } else {
                                        console.error('Token not received:', data);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                        });
                    </script>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Don't have an account? <a href="auth-register.html" class="font-bold">Sign
                                up</a>.</p>
                        <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
