<?php
session_start();
$nama = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'User';
$jk = isset($_SESSION['tema_user']) ? $_SESSION['tema_user'] : 'netral';

$warna_background = "#a561ff";
$warna_button = "#6c757d";
$warna_h1 = "#ffffff";
$warna_navbar = "#343a40"; 
$warna_teks_navbar = "#ffffff";
$warna_text_button = "#ffffff";
$teks_sapaan = "Selamat datang ";

if ($jk == 'Perempuan'){
    $warna_background = "#ffc0cb";
    $warna_button = "#ffffff";
    $warna_teks_button = "#000000";
    $warna_h1 = "#000000";
    $warna_navbar = "#ffffff";
    $warna_teks_navbar = "#000000";
    $teks_sapaan = " Cantik";
    $gambar_welcome = "asset\perempuan.webp";
}
elseif ($jk == 'Laki-Laki'){
    $warna_background = "#add8e6";
    $warna_button = "#000000";
    $warna_teks_button = "#ffffff";
    $warna_h1 = "#ffffff";
    $warna_navbar = "#000000";
    $warna_teks_navbar = "#ffffff";
    $teks_sapaan = " Ganteng";
    $gambar_welcome = "asset\image_5b92aac0.png";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ReLife</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: <?php echo $warna_background; ?> !important;
            font-family: sans-serif;
            transition: background-color 0.5s ease;
        }
        .container {
            padding: 20px;
            text-align:center;
        }
        .nav-link-custom {
        color: <?php echo $warna_teks_navbar; ?> !important;
        text-decoration: none;
        padding: 14px 20px;
        display: block;
        transition: 0.3s;
        }

        .nav-link-custom:hover {
        color: <?php echo ($jk == 'Perempuan') ? '#ffc0cb' : '#add8e6'; ?> !important; 
        }

        .btn-continue {
        margin-top: 0;
        background-color: <?php echo $warna_button; ?>;
        width: 100%;
        max-width: 200px;
        text-decoration: none;
        color: <?php echo $warna_teks_button; ?> !important;
        border: none;
        padding: 15px 50px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-block; 
        transition: all 0.3s ease;
        }

        .btn-continue:hover {
        color: <?php echo ($jk == 'Perempuan') ? '#ffc0cb' : '#add8e6'; ?> !important;
        }

        .card-img-top {
        height: 450px;
        object-fit: cover;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

</head>
<body>

   
  <ul style="list-style-type: none; width: 100%; margin:0; padding: 0; overflow: hidden; background-color: <?php echo $warna_navbar; ?>">
                <li> <a class="nav-link-custom" href="#">RE<span>LIFE</span></a></li>  
                <li style="float:right"> <a class="nav-link-custom" href="">Contact Us</a></li>
                <li style="float:right"> <a class="nav-link-custom" href="">Blog</a></li>
                <li style="float:right"> <a class="nav-link-custom" href="">Team</a></li>
                <li style="float:right"> <a class="nav-link-custom" href="">About</a></li>
                <li style="float:right"> <a class="nav-link-custom" href="">Home</a></li>
            </ul>

    <div class="container-welcome d-flex flex-column align-items-center justify-content-center">
        <div class="text-center mb-5 mt-5">
            <h1 style="color: <?php echo $color_h1;?>"><?php echo "Halo " . $nama . $teks_sapaan . " Selamat Datang di"?> <span>ReLife</span></h1>
            <p class="text-light">Langkah awal menuju versi terbaik dirimu dimulai di sini.</p>
        </div>

        <div class="container">
            <div class="card-group shadow-lg">
                <div class="card glass-card-welcome">
                    <img src="<?php echo $gambar_welcome; ?>" class="card-img-top" alt="Tracking">
                    <div class="card-body text-center">
                        <p class="card-text text-white">Ready for some wins? Start tracking, it's easy!</p>
                    </div>
                </div>
                <div class="card glass-card-welcome">
                    <img src="asset/value-prop-2-legacy.webp" class="card-img-top" alt="Impact">
                    <div class="card-body text-center">
                        <p class="card-text text-white">Discover the impact of your food and fitness.</p>
                    </div>
                </div>
                <div class="card glass-card-welcome">
                    <img src="asset/value-prop-3.webp" class="card-img-top" alt="Habit">
                    <div class="card-body text-center">
                        <p class="card-text text-white">And make mindful eating a habit for life.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="name.php" class="btn-continue">Continue</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>