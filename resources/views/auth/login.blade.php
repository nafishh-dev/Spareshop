<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login E-Commerce</title>
    <!-- Bootstrap 5 CDN (Tanpa perlu install NPM/Node.js) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4">Login Toko</h4>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @elseif(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @elseif(isset($_GET['pesan']))
                        @if($_GET['pesan']=="belum_login")
                        <div class="alert alert-warning" id="notif">Anda belum login, silahkan login dulu</div>
                        @endif
                    @endif
                    
                     

                    <form action="/login" method="POST">
                        @csrf <!-- Wajib di Laravel untuk keamanan form -->
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>
                    <p class="text-center mt-3 mb-0">
                        Belum punya akun? <a href="/register">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>