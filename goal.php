<?php
session_start();

$nama = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Teman';
$jk = isset($_SESSION['tema_user']) ? $_SESSION['tema_user'] : 'netral';

$warna_bg = "linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%)";
if ($jk == 'Perempuan') {
    $warna_bg = "linear-gradient(135deg, #ffc0cb 0%, #ff9a9e 100%)";
    $box_shadow = "";
} elseif ($jk == 'Laki-Laki') {
    $warna_bg = "linear-gradient(135deg, #add8e6 0%, #74b9ff 100%)"; 
    $box_shadow = "#000000";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife-Goal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --text-dark: #2d3436;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: <?php echo $warna_bg; ?> !important;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            padding: 15px 50px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-brand { font-weight: 700; color: var(--text-dark) !important; }

        .auth-section { flex-grow: 1; padding: 40px 0; }

        .clear-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid white;
        }

        .goal-option { margin-bottom: 15px; position: relative; }
        .goal-option input[type="radio"] { display: none; }

        .goal-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 25px;
            background: #fff;
            border: 2px solid #eee;
            border-radius: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .goal-option input[type="radio"]:checked + .goal-label {
            border-color: <?php echo $box_shadow; ?> !important;
            background: rgba(255, 59, 196, 0.05);
        }

        .goal-option input[type="radio"]:checked + .goal-label span {
            font-weight: 600;
            color: <?php echo $box_shadow; ?> !important;
        }

        /* Button */
        .btn-next {
            background: <?php echo $warna_bg; ?> !important;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-next:hover {
            background: <?php echo $box_shadow; ?> !important;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 59, 196, 0.3);
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
                <div class="col-md-5">
                    <div class="clear-card p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Thanks, <?php echo htmlspecialchars($nama); ?>!</h2>
                            <p class="text-muted">Pilih target kesehatanmu:</p>
                        </div>
                        
                        <form action="save_goal.php" method="POST">
                            <div class="goal-selection">
                                <div class="goal-option">
                                    <input type="radio" name="goal" id="lose" value="lose_weight" required>
                                    <label class="goal-label" for="lose">
                                        <span>Menurunkan Berat</span>
                                        <span class="fs-4">📉</span>
                                    </label>
                                </div>
                                <div class="goal-option">
                                    <input type="radio" name="goal" id="maintain" value="maintain">
                                    <label class="goal-label" for="maintain">
                                        <span>Jaga Berat Badan</span>
                                        <span class="fs-4">⚖️</span>
                                    </label>
                                </div>
                                <div class="goal-option">
                                    <input type="radio" name="goal" id="gain" value="gain_weight">
                                    <label class="goal-label" for="gain">
                                        <span>Tambah Massa Otot</span>
                                        <span class="fs-4">💪</span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-4 align-items-center">
                                <a href="personalmatriks.php?action=reset" class="text-muted text-decoration-none small">Kembali</a>
                                <button type="submit" class="btn btn-dark btn-next flex-grow-1 text-white">LANJUTKAN</button>
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