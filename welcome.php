<?php
session_start();
$nama = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'User';
$jk_user = isset($_SESSION['tema_user']) ? $_SESSION['tema_user'] : 'netral';

$aksen = "#a561ff";
if ($jk_user == 'Perempuan') $aksen = "#ff3bc4";
if ($jk_user == 'Laki-Laki') $aksen = "#0984e3";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ReLife</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        *{outline : 2px solid red;}
        body {
            background: linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%);
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        .navbar-custom {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .navbar-brand { font-weight: 700; color: #2d3436 !important; font-size: 1.6rem; }
        .navbar-brand span { color: <?php echo $aksen; ?>; }

        .container-welcome { padding: 60px 0; animation: fadeIn 1s ease; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .glass-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .card-img-top { height: 200px; object-fit: cover; border-radius: 25px 25px 0 0; }

        .btn-journey {
            background: #2d3436;
            color: white !important;
            padding: 16px 50px;
            border-radius: 50px;
            font-weight: 700;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-journey:hover {
            background: <?php echo $aksen; ?>;
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">RE<span>LIFE</span></a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 d-none d-md-inline fw-bold text-dark">Halo, <?php echo $nama; ?>!</span>
                <a href="login.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container container-welcome text-center">
        <div class="mb-5">
            <h1 class="fw-bold display-5">Selamat Datang di <span style="color: <?php echo $aksen; ?>;">ReLife</span></h1>
            <p class="text-muted">Siap untuk menjadi versi terbaik dirimu?</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card glass-card h-100 border-0">
                    <img src="asset/default.webp" class="card-img-top" alt="Tracking">
                    <div class="card-body">
                        <p class="mb-0 fw-medium">Mulai catat makananmu dengan satu klik saja.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card glass-card h-100 border-0">
                    <img src="asset/value-prop-2-legacy.webp" class="card-img-top" alt="Impact">
                    <div class="card-body">
                        <p class="mb-0 fw-medium">Analisis kemajuan tubuhmu secara real-time.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <a href="personalmatriks.php" class="btn-journey shadow">CONTINUE JOURNEY</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</body>
</html>