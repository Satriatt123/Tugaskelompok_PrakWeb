<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELIFE - Premium Tracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%);
            --glass-white: rgba(255, 255, 255, 0.85);
            --accent-purple: #a561ff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--primary-gradient);
            background-attachment: fixed;
            color: #333;
            width: 100%;
            min-height: 200px;
            display: flex; 
            flex-direction: column;
        }

        .navbar-custom {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: #2d3436 !important;
        }

        .navbar-brand span { color: var(--accent-purple); }

        .nav-link {
            color: #444 !important;
            font-weight: 500;
            margin-left: 20px;
            transition: 0.3s;
        }

        .nav-link:hover { color: var(--accent-purple) !important; }

        header {
            margin-top: 50px;
            text-align: center;
        }

        header h1 {
            font-size: clamp(2rem, 5vw, 3rem); 
            font-weight: 700;
            color: #444;
            text-transform: uppercase;
        }

        .main-card {
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            background: var(--glass-white);
            max-width: 1100px;
            width: 90%;
            margin: 40px auto; 
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            animation: slideUp 1s ease forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .text-section {
            padding: 50px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .badge-custom {
            background: #f0f0f0;
            color: #888;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            width: fit-content;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .btn-changeyourlife {
            text-decoration: none;
            background: #2d3436; 
            color: white !important;
            padding: 16px 35px;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.4s ease;
            display: inline-block;
            width: fit-content;
        }

        .btn-changeyourlife:hover {
            background: var(--accent-purple);
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 10px 20px rgba(165, 97, 255, 0.3);
        }

        .video-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .video-box {
            height: 450px;
            overflow: hidden;
            border-radius: 20px;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media (max-width: 991px) {
            .main-card { grid-template-columns: 1fr; }
            .video-section { height: auto; }
            .nav-link { margin-left: 0; margin-top: 10px; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">RE<span>LIFE</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item">
                        <a class="btn-changeyourlife ms-lg-4 px-4 py-2 mt-2 mt-lg-0" href="login.php" style="font-size: 0.9rem;">LOGIN</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="container">
        <h1>Food & Activity <br> Tracking System</h1>
    </header>

    <main class="main-card">
        <div class="text-section">
            <div class="badge-custom">INNOVATION 2026</div>
            <h2 class="display-5 fw-bold mb-4">Relive Your Health.</h2>
            <p class="mb-4">Kelola pola makan dan aktivitas harian Anda dengan antarmuka yang intuitif. Fokus pada kesehatan tanpa ribet, karena setiap langkah Anda berharga.</p>
            <a href="pendaftaran.php" class="btn-changeyourlife">CHANGE YOUR LIFE</a>
        </div>

        <div class="video-section">
            <div class="video-box">
                <video autoplay loop playsinline muted>
                    <source src="asset/82636-580974567.mp4" type="video/mp4">
                </video>
            </div>
            <div class="video-box">
                <video autoplay loop playsinline muted>
                    <source src="asset/11654896-uhd_3840_2160_50fps.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </main> 
<?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>