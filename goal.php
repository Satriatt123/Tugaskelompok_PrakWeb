<?php
// Tangkap nama dari halaman sebelumnya
$nama = isset($_POST['nama']) ? $_POST['nama'] : 'Teman';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife - Pilih Target Anda</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">RE<span>LIFE</span></a>
        </div>
    </nav>

    <div class="auth-section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="glass-card p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-white">Thanks, <?php echo htmlspecialchars($nama); ?>!</h2>
                            <p class="text-light">Apa target kesehatan yang ingin Anda capai?</p>
                        </div>
                        
                        <form action="register.php" method="POST">
                            <input type="hidden" name="nama" value="<?php echo htmlspecialchars($nama); ?>">

                            <div class="mb-4">
                                <div class="goal-option mb-3">
                                    <input type="radio" name="goal" id="lose_weight" value="lose_weight" class="btn-check" required>
                                    <label class="btn btn-outline-light w-100 py-3 text-start px-4 d-flex align-items-center justify-content-between" for="lose_weight">
                                        <span>Menurunkan Berat Badan</span>
                                        <span class="fs-4">📉</span>
                                    </label>
                                </div>

                                <div class="goal-option mb-3">
                                    <input type="radio" name="goal" id="maintain" value="maintain" class="btn-check">
                                    <label class="btn btn-outline-light w-100 py-3 text-start px-4 d-flex align-items-center justify-content-between" for="maintain">
                                        <span>Menjaga Berat Badan (Sehat)</span>
                                        <span class="fs-4">⚖️</span>
                                    </label>
                                </div>

                                <div class="goal-option">
                                    <input type="radio" name="goal" id="gain_weight" value="gain_weight" class="btn-check">
                                    <label class="btn btn-outline-light w-100 py-3 text-start px-4 d-flex align-items-center justify-content-between" for="gain_weight">
                                        <span>Menambah Massa Otot / Berat</span>
                                        <span class="fs-4">💪</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                                <a href="name.php" class="text-info text-decoration-none small fw-bold">Kembali</a>
                                
                                <button type="submit" class="btn btn-info flex-grow-1 fw-bold text-white py-2 shadow">
                                    LANJUTKAN
                                </button>
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