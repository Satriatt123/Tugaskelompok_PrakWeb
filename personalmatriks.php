<?php
session_start();
$nama = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Teman';
$jk_user = isset($_SESSION['tema_user']) ? $_SESSION['tema_user'] : 'netral';

$warna_bg = "linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%)";
$aksen = "#a561ff"; 

if ($jk_user == 'Perempuan') {
    $warna_bg = "linear-gradient(135deg, #ffc0cb 0%, #ff9a9e 100%)";
    $aksen = "#ff3bc4";
} elseif ($jk_user == 'Laki-Laki') {
    $warna_bg = "linear-gradient(135deg, #add8e6 0%, #74b9ff 100%)"; 
    $aksen = "#0984e3";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife - Personal Metrics</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: <?php echo $warna_bg; ?>;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
        }

        .navbar-custom {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .navbar-brand { font-weight: 700; color: #2d3436 !important; font-size: 1.6rem; }
        .navbar-brand span { color: <?php echo $aksen; ?>; }

        .auth-section { padding: 40px 0; }

        .clear-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid white;
        }

        .form-label { font-weight: 600; color: #2d3436; }

        .custom-input, .custom-select {
            border: 2px solid #eee !important;
            border-radius: 12px !important;
            padding: 10px 15px !important;
        }

        .activity-option { margin-bottom: 12px; }
        .activity-option input[type="radio"] { display: none; }

        .activity-label {
            display: block;
            padding: 15px;
            background: #fff;
            border: 2px solid #eee;
            border-radius: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .activity-option input[type="radio"]:checked + .activity-label {
            border-color: <?php echo $aksen; ?>;
            background: rgba(165, 97, 255, 0.05);
        }

        .btn-next {
            background: #2d3436;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-next:hover {
            background: <?php echo $aksen; ?>;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">RE<span>LIFE</span></a>
        </div>
    </nav>

    <div class="auth-section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="clear-card p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Sedikit lagi, <?php echo htmlspecialchars($nama); ?>!</h2>
                            <p class="text-muted">Lengkapi data fisikmu untuk perhitungan kalori.</p>
                        </div>
                        
                        <form action="proseskalkulasi.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small">Jenis Kelamin</label>
                                <select name="jk" class="form-select custom-select" required>
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="form-label small">Usia</label>
                                    <input type="number" name="usia" class="form-control custom-input" placeholder="Thn" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label small">Tinggi</label>
                                    <input type="number" name="tinggi" class="form-control custom-input" placeholder="cm" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label small">Berat</label>
                                    <input type="number" name="berat" class="form-control custom-input" placeholder="kg" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small mb-2">Tingkat Aktivitas Harian</label>
                                
                                <div class="activity-option">
                                    <input type="radio" name="activity_level" id="low" value="1.2" required>
                                    <label class="activity-label" for="low">
                                        <strong>Sedikit Aktif</strong>
                                        <div class="small text-muted">Banyak duduk (kantoran), jarang olahraga.</div>
                                    </label>
                                </div>

                                <div class="activity-option">
                                    <input type="radio" name="activity_level" id="med" value="1.55">
                                    <label class="activity-label" for="med">
                                        <strong>Cukup Aktif</strong>
                                        <div class="small text-muted">Olahraga 3-5 kali seminggu.</div>
                                    </label>
                                </div>

                                <div class="activity-option">
                                    <input type="radio" name="activity_level" id="high" value="1.725">
                                    <label class="activity-label" for="high">
                                        <strong>Sangat Aktif</strong>
                                        <div class="small text-muted">Olahraga setiap hari / kerja fisik berat.</div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn-next shadow-sm">LANJUT KE TARGET</button>
                            <div class="text-center mt-3">
                                <a href="welcome.php" class="text-muted small text-decoration-none">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>