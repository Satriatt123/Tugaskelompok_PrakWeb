<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLife - Siapa Nama Anda?</title>
    
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
                <div class="col-md-5">
                    <div class="glass-card p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-white">Halo!</h2>
                            <p class="text-light small">Boleh kami tahu siapa nama Anda?</p>
                        </div>
                        
                        <form action="simpannama.php" method="POST">
    <div class="mb-4">
        <label class="form-label text-white small">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control custom-input" placeholder="Masukkan nama Anda" required>
    </div>
    
    <div class="d-flex align-items-center justify-content-between gap-3">
        <a href="welcome.php" class="btn btn-outline-light flex-grow-1 fw-bold py-2 shadow-sm" style="border-radius: 12px;">
            BACK
        </a>
        
        <button type="submit" class="btn btn-info flex-grow-1 fw-bold text-white py-2 shadow">
            NEXT
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