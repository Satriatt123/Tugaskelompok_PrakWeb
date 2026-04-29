<style>
    .custom-footer {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255, 255, 255, 0.4);
        padding: 60px 0 20px;
        color: #2d3436;
        margin-top: auto; 
    }

    .custom-footer .footer-brand {
        font-weight: 700;
        font-size: 1.8rem;
        color: #2d3436;
        letter-spacing: 1px;
    }

    .custom-footer .footer-brand span {
        /* Jika ada variabel $aksen dari session, gunakan itu. Jika tidak, gunakan ungu default */
        color: <?php echo isset($aksen) ? $aksen : 'var(--accent-purple, #a561ff)'; ?>; 
    }

    .footer-links a {
        color: #444;
        text-decoration: none;
        transition: 0.3s ease;
        font-weight: 500;
    }

    .footer-links a:hover {
        color: <?php echo isset($aksen) ? $aksen : 'var(--accent-purple, #a561ff)'; ?>;
        padding-left: 8px;
    }

    .footer-bottom {
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        padding-top: 20px;
        margin-top: 40px;
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }

    .social-icons a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        color: #2d3436;
        text-decoration: none;
        transition: 0.3s ease;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .social-icons a:hover {
        background: <?php echo isset($aksen) ? $aksen : 'var(--accent-purple, #a561ff)'; ?>;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(165, 97, 255, 0.3);
    }
</style>

<footer class="custom-footer mt-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5 col-md-6">
                <div class="footer-brand mb-3">RE<span>LIFE</span></div>
                <p class="small text-dark pe-lg-4" style="line-height: 1.8; opacity: 0.8;">
                    Platform tracking kesehatan dan aktivitas harian dengan antarmuka yang intuitif. Fokus pada target kalorimu tanpa ribet, karena setiap langkah sehatmu sangat berharga.
                </p>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-dark">Quick Links</h6>
                <ul class="list-unstyled footer-links small d-flex flex-column gap-3">
                    <li><a href="tracking.php?page=dashboard">Dashboard Tracking</a></li>
                    <li><a href="tracking.php?page=food">Cari Nutrisi Makanan</a></li>
                    <li><a href="tracking.php?page=activity">Kalkulator Aktivitas</a></li>
                    <li><a href="login.php">Masuk / Daftar</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <h6 class="fw-bold mb-3 text-dark">Connect With Us</h6>
                <p class="small text-dark mb-4" style="opacity: 0.8;">Tingkatkan kualitas hidupmu dengan inovasi teknologi kesehatan modern.</p>
                <div class="social-icons gap-3 d-flex">
                    <a href="#" title="Instagram">📸</a>
                    <a href="#" title="GitHub">💻</a>
                    <a href="#" title="Email">✉️</a>
                </div>
            </div>
        </div>

        <div class="text-center footer-bottom">
            <p class="mb-0">&copy; 2026 ReLife System. Crafted by Sistem Informasi UPN "Veteran" Yogyakarta.</p>
        </div>
    </div>
</footer>