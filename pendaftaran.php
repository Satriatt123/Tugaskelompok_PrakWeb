<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELIFE - Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%);
            --accent-color: #a561ff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--primary-gradient);
            background-attachment: fixed;
            width: 100%;
            min-height: 200px;
        }

        .navbar-custom {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .navbar-brand { font-weight: 700; color: #2d3436 !important; font-size: 1.6rem; }
        .navbar-brand span { color: var(--accent-color); }

        .auth-section { padding: 60px 0; animation: slideUp 0.8s ease forwards; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .clear-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .form-label { font-weight: 600; color: #2d3436; }

        .custom-input {
            background: white !important;
            border: 2px solid #eee !important;
            border-radius: 15px !important;
            padding: 12px 18px !important;
            transition: 0.3s;
        }

        .custom-input:focus {
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 15px rgba(165, 97, 255, 0.2) !important;
        }

        .btn-next {
            background: #2d3436 !important;
            border: none;
            border-radius: 15px;
            padding: 14px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-next:hover {
            background: var(--accent-color) !important;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(165, 97, 255, 0.3);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">RE<span>LIFE</span></a>
            <div class="ms-auto">
                <a class="nav-link fw-semibold" href="login.php">LOGIN</a>
            </div>
        </div>
    </nav>

    <div class="auth-section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="clear-card p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Halo!</h2>
                            <p class="text-muted">Mulai langkah sehatmu hari ini.</p>
                        </div>
                        
                        <form action="prosessimpan.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control custom-input" placeholder="Nama Anda" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Email</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="email@contoh.com" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small">Password</label>
                                <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                            </div>

                            <button type="submit" class="btn btn-next w-100 text-white shadow">BUAT AKUN</button>
                            <p class="text-center mt-4 small">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold" style="color: var(--accent-color);">Masuk</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>