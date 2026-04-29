<?php
session_start();
include 'koneksi.php';

$nama  = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Teman';
$jk    = isset($_SESSION['tema_user']) ? $_SESSION['tema_user'] : 'netral';
$tdee  = isset($_SESSION['tdee'])      ? $_SESSION['tdee']      : 0;
$bmr   = isset($_SESSION['bmr'])       ? $_SESSION['bmr']       : 0;

$goal  = isset($_POST['goal']) ? $_POST['goal'] : 'maintain';
$_SESSION['goal'] = $goal;

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "UPDATE users SET goal='$goal' WHERE id='$user_id'");
}

if ($goal == 'lose_weight') {
    $target = $tdee - 500;
    $goal_label = 'Menurunkan Berat';
    $goal_icon  = '📉';
    $goal_desc  = 'Defisit 500 kalori/hari dari kebutuhan harianmu.';
} elseif ($goal == 'gain_weight') {
    $target = $tdee + 300;
    $goal_label = 'Tambah Massa Otot';
    $goal_icon  = '💪';
    $goal_desc  = 'Surplus 300 kalori/hari dari kebutuhan harianmu.';
} else {
    $target = $tdee;
    $goal_label = 'Jaga Berat Badan';
    $goal_icon  = '⚖️';
    $goal_desc  = 'Konsumsi sesuai kebutuhan kalori harianmu.';
}

$warna_bg = "linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%)";
$aksen    = "#a561ff";

if ($jk == 'Perempuan') {
    $warna_bg = "linear-gradient(135deg, #ffc0cb 0%, #ff9a9e 100%)";
    $aksen    = "#ff3bc4";
} elseif ($jk == 'Laki-Laki') {
    $warna_bg = "linear-gradient(135deg, #add8e6 0%, #74b9ff 100%)";
    $aksen    = "#0984e3";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife - Hasil</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: <?php echo $warna_bg; ?>;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .navbar-brand { font-weight: 700; color: #2d3436 !important; font-size: 1.6rem; }
        .navbar-brand span { color: <?php echo $aksen; ?>; }

        .main-section {
            flex-grow: 1;
            padding: 50px 0;
            animation: fadeUp 0.7s ease forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .result-card {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid white;
            padding: 40px;
        }

        .stat-box {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            border: 2px solid #eee;
            transition: 0.3s;
        }

        .stat-box:hover {
            border-color: <?php echo $aksen; ?>;
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: <?php echo $aksen; ?>;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        .goal-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: <?php echo $aksen; ?>18;
            border: 2px solid <?php echo $aksen; ?>44;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            color: <?php echo $aksen; ?>;
            font-size: 0.95rem;
        }

        .target-highlight {
            background: <?php echo $warna_bg; ?>;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            color: white;
        }

        .target-highlight .big-number {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1;
        }

        .target-highlight .big-label {
            font-size: 0.85rem;
            opacity: 0.85;
            margin-top: 6px;
        }

        .btn-start {
            background: #2d3436;
            color: white !important;
            border: none;
            border-radius: 15px;
            padding: 14px 40px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-start:hover {
            background: <?php echo $aksen; ?>;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .divider {
            border: none;
            border-top: 1.5px solid #eee;
            margin: 24px 0;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">RE<span>LIFE</span></a>
            <div class="ms-auto">
                <span class="fw-semibold text-dark small">Halo, <?php echo htmlspecialchars($nama); ?>!</span>
            </div>
        </div>
    </nav>

    <div class="main-section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-6">
                    <div class="result-card">

                        <div class="text-center mb-4">
                            <h2 class="fw-bold mb-1">Hasil Kalkulasimu 🎯</h2>
                            <p class="text-muted small">Berikut kebutuhan kalori harian berdasarkan data fisikmu.</p>
                        </div>

                        <div class="text-center mb-4">
                            <div class="goal-badge">
                                <span><?php echo $goal_icon; ?></span>
                                <span><?php echo $goal_label; ?></span>
                            </div>
                            <p class="text-muted small mt-2 mb-0"><?php echo $goal_desc; ?></p>
                        </div>

                        <hr class="divider">

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-number"><?php echo number_format($bmr); ?></div>
                                    <div class="stat-label">BMR (kkal)</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-number"><?php echo number_format($tdee); ?></div>
                                    <div class="stat-label">TDEE (kkal)</div>
                                </div>
                            </div>
                        </div>

                        <div class="target-highlight mb-4">
                            <div class="big-number"><?php echo number_format($target); ?></div>
                            <div class="big-label">Target Kalori Harianmu (kkal)</div>
                        </div>

                        <div class="mb-4 p-3" style="background:#f8f9fa; border-radius:15px;">
                            <p class="small text-muted mb-1"><strong>BMR</strong> — Kalori yang dibakar tubuh saat istirahat total.</p>
                            <p class="small text-muted mb-0"><strong>TDEE</strong> — Total kalori yang kamu butuhkan per hari sesuai aktivitasmu.</p>
                        </div>

                        <hr class="divider">

                        <div class="text-center">
                            <a href="tracking.php" class="btn-start shadow">MULAI TRACKING</a>
                            <div class="mt-3">
                                <a href="personalmatriks.php?action=reset" class="text-muted small text-decoration-none">Hitung ulang</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>