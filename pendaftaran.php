<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELIFE - Registration Clear View</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%);
            --accent-color: #ff3bc4;
            --text-dark: #2d3436;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--primary-gradient);
            background-attachment: fixed;
            min-height: 100vh;
        }

        .navbar-custom {
            padding: 15px 50px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .navbar-brand { font-weight: 700; color: var(--text-dark) !important; }
        .nav-link { color: #444 !important; font-weight: 500; }

        .auth-section { padding: 60px 0; }
        
        .clear-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border: 1px solid white;
        }

        .clear-card h2 { color: var(--text-dark); }
        .clear-card p { color: #636e72; }

        .form-label {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .custom-input, .custom-select {
            background-color: #ffffff !important;
            border: 2px solid #dfe6e9 !important;
            border-radius: 12px !important;
            padding: 12px 15px !important;
            color: #2d3436 !important; 
            transition: all 0.3s ease;
        }

        .custom-input::placeholder {
            color: #b2bec3;
        }

        .custom-input:focus, .custom-select:focus {
            border-color: #a561ff !important;
            box-shadow: 0 0 8px rgba(255, 59, 196, 0.2) !important;
            outline: none;
        }

        .btn-next {
            background: #2d3436 !important;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-next:hover {
            background: #a561ff !important;
            transform: translateY(-2px);
        }

        .btn-back {
            border: 2px solid #dfe6e9;
            color: #636e72;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-back:hover { background: #f8f9fa; color: #2d3436; }

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">RE<span>LIFE</span></a>
            <div class="ms-auto d-none d-lg-flex">
                <a class="nav-link px-3" href="#">Home</a>
                <a class="nav-link px-3" href="#">About</a>
                <a class="nav-link px-3" href="#">Contact</a>
            </div>
        </div>
    </nav>

    <div class="auth-section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-sm-10">
                    <div class="clear-card p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Halo!</h2>
                            <p class="small">Boleh kami tahu informasi Anda?</p>
                        </div>
                        
                        <form action="prosessimpan.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small">Email</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="contoh@mail.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small">Password</label>
                                <input type="password" name="password" class="form-control custom-input" placeholder="Masukkan password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control custom-input" placeholder="Nama lengkap Anda" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small">Jenis Kelamin</label>
                                <select name="jk" class="form-select custom-select">
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <div class="d-flex gap-3">
                                <a href="welcome.php" class="btn btn-back flex-grow-1 py-2">BACK</a>
                                <button type="submit" class="btn btn-next flex-grow-1 text-white py-2 shadow">NEXT</button>
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