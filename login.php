<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife - Login</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%);
            --accent-purple: #a561ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--primary-gradient);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 0;
        }
        
        .navbar-brand { font-weight: 700; font-size: 1.6rem; color: #2d3436 !important; }
        .navbar-brand span { color: var(--accent-purple); }

        .auth-section {
            flex-grow: 1; /* Mendorong form ke tengah antara navbar dan footer */
            display: flex;
            align-items: center;
            padding: 100px 0 50px 0;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }

        .custom-input {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.8);
            color: #333;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .custom-input:focus {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 15px rgba(165, 97, 255, 0.2);
            border-color: var(--accent-purple);
            outline: none;
        }

        .btn-login {
            background: #2d3436;
            color: white;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: var(--accent-purple);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(165, 97, 255, 0.3);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">RE<span>LIFE</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="index.php">Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="glass-card p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Selamat Datang 👋</h3>
                            <p class="text-secondary small">Masuk untuk melihat progres diet & aktivitasmu.</p>
                        </div>
                        
                        <form action="proseslogin.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-dark small fw-bold">Email</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="email@contoh.com" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-bold">Password</label>
                                <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                            </div>
                            
                            <button type="submit" class="btn btn-login w-100 fw-bold py-3 shadow-sm">LOGIN</button>
                            
                            <div class="text-center mt-4">
                                <p class="small text-secondary mb-0">Belum Punya Akun? 
                                    <a href="pendaftaran.php" style="color: var(--accent-purple);" class="fw-bold text-decoration-none">Daftar di sini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>