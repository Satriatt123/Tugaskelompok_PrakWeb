<?php
session_start();

$nama = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Teman';
$jk   = isset($_SESSION['tema_user']) ? $_SESSION['tema_user'] : 'netral';

$warna_bg_sidebar = "linear-gradient(135deg, #667eea 0%, #764ba2 100%)";
$aksen            = "rgba(255, 255, 255, 0.2)";

if ($jk == 'Perempuan') {
    $warna_bg_sidebar = "linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)";
    $aksen            = "#ff3bc4";
} elseif ($jk == 'Laki-Laki') {
    $warna_bg_sidebar = "linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%)";
    $aksen            = "#0984e3";
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife - Tracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }
        .container-tracking {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .sidebar {
            width: 260px; /* Lebar Sidebar Tetap */
            background: <?php echo $warna_bg_sidebar ?>;
            color: white;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .logo {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 1px;
        }
        .nav-link {
            text-decoration: none;
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            transition: 0.3s;
            background: rgba(255, 255, 255, 0.1);
            font-weight: 500;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(5px);
        }
        /* Style untuk menu yang sedang aktif */
        .nav-link.active {
            background: white;
            color: <?php echo ($jk == 'Perempuan') ? '#ff3bc4' : '#0984e3'; ?>;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .content-area {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container-tracking">
        <aside class="sidebar">
            <div class="logo">ReLife</div>
            <p style="text-align: center; font-size: 0.9em;">Halo, <b><?php echo $nama; ?></b>!</p>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="tracking.php?page=dashboard" class="nav-link <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
                <a href="tracking.php?page=food" class="nav-link <?php echo ($page == 'food') ? 'active' : ''; ?>">Food Tracking</a>
                <a href="tracking.php?page=activity" class="nav-link <?php echo ($page == 'activity') ? 'active' : ''; ?>">Activity Tracking</a>
                <a href="tracking.php?page=goalsetting" class="nav-link <?php echo ($page == 'goalsetting') ? 'active' : ''; ?>">Setting Goal</a>
            </nav>
        </aside>    

        <main class="content-area">
            <?php 
                // Pengamanan (Whitelisting)
                $allowed_pages = ['dashboard', 'food', 'activity', 'goalsetting'];
                
                if (in_array($page, $allowed_pages)) {
                    // Pastikan folder 'page/' ada
                    $file = "page/" . $page . ".php";
                    if (file_exists($file)) {
                        include($file);
                    } else {
                        echo "<h3>File $file tidak ditemukan di folder page/</h3>";
                    }
                } else {
                    echo "<h1>Halaman Tidak Ditemukan</h1>";
                }
            ?>
        </main>
    </div>
</body>
</html>