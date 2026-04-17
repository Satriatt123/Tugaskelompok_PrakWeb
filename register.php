<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife - Join Now</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="#">RE<span>LIFE</span></a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="auth-section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="glass-card p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-white">Selamat Datang</h2>
                            <p class="text-light small">Daftar sekarang untuk mulai hidup sehat</p>
                        </div>
                        
                        <form action="proses_register.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-white small">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control custom-input" placeholder="Masukkan nama Anda">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small">Email</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="email@contoh.com">
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-white small">Password</label>
                                <input type="password" name="password" class="form-control custom-input" placeholder="••••••••">
                            </div>
                            
                            <button type="submit" class="btn btn-info w-100 fw-bold text-white py-2 shadow">DAFTAR SEKARANG</button>
                            
                            <div class="text-center mt-4">
                                <p class="small text-light">Sudah punya akun? <a href="login.php" class="text-info fw-bold text-decoration-none">Login di sini</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>