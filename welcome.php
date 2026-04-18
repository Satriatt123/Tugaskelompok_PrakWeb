<?php
session_start();
$nama = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'User';
$jk = isset($_SESSION['tema_user']) ? $_SESSION['tema_user'] : 'netral';

$warna_background = "linear-gradient(135deg, #a561ff 0%, #6c5ce7 100%)";
$warna_button = "#2d3436";
$warna_teks_button = "#ffffff";
$warna_h1 = "#ffffff";
$warna_navbar = "rgba(0, 0, 0, 0.2)"; 
$warna_teks_navbar = "#ffffff";
$teks_sapaan = " Selamat datang ";
$gambar_welcome = "asset/default.webp"; // Pastikan file ada

if ($jk == 'Perempuan'){
    $warna_background = "linear-gradient(135deg, #ffc0cb 0%, #ff9a9e 100%)";
    $warna_button = "#ffffff";
    $warna_teks_button = "#ff3bc4";
    $warna_h1 = "#2d3436";
    $warna_navbar = "rgba(255, 255, 255, 0.4)";
    $warna_teks_navbar = "#2d3436";
    $teks_sapaan = " Cantik ✨";
    $gambar_welcome = "asset\pexels-shvets-production-8007162.jpg";
}
elseif ($jk == 'Laki-Laki'){
    $warna_background = "linear-gradient(135deg, #add8e6 0%, #74b9ff 100%)";
    $warna_button = "#2d3436";
    $warna_teks_button = "#ffffff";
    $warna_h1 = "#ffffff";
    $warna_navbar = "rgba(0, 0, 0, 0.2)";
    $warna_teks_navbar = "#ffffff";
    $teks_sapaan = " Ganteng 😎";
    $gambar_welcome = "asset/image_5b92aac0.png";
}
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
        body {
            background: <?php echo $warna_background; ?> !important;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: <?php echo $warna_h1; ?>;
            min-height: 100vh;
            margin: 0;
        }

        .nav-custom {
            background: <?php echo $warna_navbar; ?>;
            backdrop-filter: blur(10px);
            padding: 15px 50px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-link-custom {
            color: <?php echo $warna_teks_navbar; ?> !important;
            font-weight: 600;
            text-decoration: none;
            margin-left: 20px;
            transition: 0.3s;
        }

        .nav-link-custom:hover { opacity: 0.7; }

        .container-welcome { padding: 80px 20px; }

        .glass-card-welcome {
            background: rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 20px !important;
            overflow: hidden;
            transition: transform 0.3s ease;
            margin: 10px;
        }

        .glass-card-welcome:hover {
            transform: translateY(-10px);
        }

        .card-img-top {
            height: 300px;
            object-fit: cover;
        }

        .card-body p {
            font-size: 0.9rem;
            font-weight: 400;
            color: <?php echo ($jk == 'Perempuan') ? '#2d3436' : '#ffffff'; ?>;
        }

        .btn-continue {
            background-color: <?php echo $warna_button; ?>;
            color: <?php echo $warna_teks_button; ?> !important;
            padding: 15px 60px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        h1 span { font-weight: 700; text-decoration: underline; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg nav-custom">
        <div class="container-fluid">
            <a class="nav-link-custom" href="#" style="font-size: 1.5rem;">RE<span>LIFE</span></a>
            <div class="ms-auto">
                <a class="nav-link-custom d-none d-md-inline" href="">Home</a>
                <a class="nav-link-custom d-none d-md-inline" href="">About</a>
                <a class="nav-link-custom" href="login.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container container-welcome d-flex flex-column align-items-center">
        <div class="text-center mb-5">
            <h1 style="font-size: 2.5rem;">Halo <?php echo $nama . ", " . $teks_sapaan; ?></h1>
            <h3>Selamat Datang di <span>ReLife</span></h3>
            <p style="opacity: 0.8;">Langkah awal menuju versi terbaik dirimu dimulai di sini.</p>
        </div>

        <div class="row w-100 justify-content-center">
            <div class="col-md-4">
                <div class="card glass-card-welcome">
                    <img src="<?php echo $gambar_welcome; ?>" class="card-img-top" alt="Tracking">
                    <div class="card-body text-center">
                        <p>Ready for some wins? Start tracking, it's easy!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card glass-card-welcome">
                    <img src="asset/value-prop-2-legacy.webp" class="card-img-top" alt="Impact">
                    <div class="card-body text-center">
                        <p>Discover the impact of your food and fitness.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card glass-card-welcome">
                    <img src="asset/value-prop-3.webp" class="card-img-top" alt="Habit">
                    <div class="card-body text-center">
                        <p>And make mindful eating a habit for life.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="goal.php" class="btn-continue shadow">Continue Your Journey</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>