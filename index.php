<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELIFE - Premium Tracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ffc0cb 0%, #add8e6 100%);
            --glass-white: rgba(255, 255, 255, 0.85);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--primary-gradient);
            background-attachment: fixed;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar {
            width: 100%;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: #444;
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: #555;
            font-weight: 400;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #a561ff;
        }

        header {
            margin-top: 60px;
            text-align: center;
            max-width: 800px;
        }

        header h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #444;
            line-height: 1.2;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .main-card {
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            background: var(--glass-white);
            max-width: 1100px;
            width: 90%;
            margin: 40px 0;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            /* Animasi Muncul */
            animation: slideUp 1s ease forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .text-section {
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .badge {
            background: #f0f0f0;
            color: #888;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            width: fit-content;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .text-section h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: #222;
        }

        .text-section p {
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }

        .btn-changeyourlife {
            text-decoration: none;
            background: #222; 
            color: white;
            padding: 16px 35px;
            border-radius: 15px;
            font-weight: 600;
            display: inline-block;
            width: fit-content;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn-changeyourlife:hover {
            transform: scale(1.05) translateY(-5px);
            background: linear-gradient(135deg, #a561ff 0%, #6c5ce7 100%);
            box-shadow: 0 15px 30px rgba(255, 59, 196, 0.3);
        }

        .video-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .video-box {
            height: 500px;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.6s;
        }

        .video-box:hover video {
            transform: scale(1.1);
        }

        footer {
            margin-top: auto;
            padding: 40px;
            color: #777;
            font-size: 0.85rem;
        }

        @media (max-width: 900px) {
            .main-card { grid-template-columns: 1fr; }
            .navbar { padding: 20px; }
            .nav-links { display: none; }
            .text-section { padding: 40px; }
            .video-section { height: 600px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-logo">RELIFE.</div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#">Features</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </div>
    </nav>

    <header>
        <h1>Food & Activity <br> Tracking System</h1>
    </header>

    <main class="main-card">
        <div class="text-section">
            <div class="badge">Innovation 2026</div>
            <h2>Relive Your Health.</h2>
            <p>Kelola pola makan dan aktivitas harian Anda dengan antarmuka yang intuitif. Fokus pada kesehatan tanpa ribet, karena setiap langkah Anda berharga.</p>
            
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

    <footer>
        <p>&copy; 2026 Creative Lab. Crafted for a Better Lifestyle.</p>
    </footer>

</body>
</html>