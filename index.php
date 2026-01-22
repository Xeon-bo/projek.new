<?php
require_once 'config.php';
require_once 'includes/functions.php';
$pageTitle = "GmailStore - Jual Beli Akun Gmail Premium";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-envelope"></i>
                    <span>GmailStore</span>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php" class="active">Beranda</a></li>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#pricing">Harga</a></li>
                    <li><a href="#testimonials">Testimoni</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <div class="auth-buttons">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php" class="btn btn-outline">Dashboard</a>
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline">Login</a>
                        <a href="register.php" class="btn btn-primary">Daftar</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Jual Beli Akun Gmail Premium</h1>
                <p>Temukan akun Gmail berkualitas tinggi untuk kebutuhan bisnis, pemasaran, atau penggunaan pribadi. Aman, terpercaya, dan bergaransi.</p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary btn-large">
                        <i class="fas fa-rocket"></i> Mulai Sekarang
                    </a>
                    <a href="#features" class="btn btn-outline btn-large">
                        <i class="fas fa-info-circle"></i> Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://cdn.pixabay.com/photo/2020/07/26/21/05/gmail-5441007_1280.png" alt="Gmail Illustration">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title">Mengapa Memilih GmailStore?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Semua akun telah diverifikasi dan dijamin keamanannya. Proteksi penuh untuk data Anda.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Proses Cepat</h3>
                    <p>Pembelian akun selesai dalam hitungan menit. Akses instan setelah pembayaran.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Dukungan 24/7</h3>
                    <p>Tim support siap membantu kapan saja. Respon cepat untuk semua pertanyaan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-guarantee"></i>
                    </div>
                    <h3>Garansi 30 Hari</h3>
                    <p>Garansi pengembalian uang jika akun bermasalah dalam 30 hari pertama.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing">
        <div class="container">
            <h2 class="section-title">Paket Akun Gmail</h2>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Basic</h3>
                        <div class="price">Rp 50.000</div>
                        <p>Per Akun</p>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Akun baru dibuat 2023</li>
                        <li><i class="fas fa-check"></i> Recovery email tersedia</li>
                        <li><i class="fas fa-check"></i> Garansi 15 hari</li>
                        <li><i class="fas fa-times"></i> Tidak termasuk Google Drive</li>
                    </ul>
                    <a href="register.php" class="btn btn-primary">Beli Sekarang</a>
                </div>
                <div class="pricing-card popular">
                    <div class="popular-badge">POPULAR</div>
                    <div class="pricing-header">
                        <h3>Premium</h3>
                        <div class="price">Rp 100.000</div>
                        <p>Per Akun</p>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Akun baru dibuat 2024</li>
                        <li><i class="fas fa-check"></i> Recovery email + nomor HP</li>
                        <li><i class="fas fa-check"></i> Garansi 30 hari</li>
                        <li><i class="fas fa-check"></i> Google Drive 15GB</li>
                    </ul>
                    <a href="register.php" class="btn btn-primary">Beli Sekarang</a>
                </div>
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Business</h3>
                        <div class="price">Rp 250.000</div>
                        <p>Per Akun</p>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Akun dengan nama custom</li>
                        <li><i class="fas fa-check"></i> Full recovery options</li>
                        <li><i class="fas fa-check"></i> Garansi 60 hari</li>
                        <li><i class="fas fa-check"></i> Google Workspace ready</li>
                    </ul>
                    <a href="register.php" class="btn btn-primary">Beli Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <h2 class="section-title">Apa Kata Pelanggan Kami?</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Akun Gmail yang saya beli berkualitas tinggi, proses cepat, dan support sangat responsif. Sangat direkomendasikan!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">A</div>
                        <div class="author-info">
                            <h4>Andi Wijaya</h4>
                            <p>Digital Marketer</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Sudah beli 5 akun untuk tim marketing, semua berjalan lancar. Garansinya juga benar-benar dihormati."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">B</div>
                        <div class="author-info">
                            <h4>Budi Santoso</h4>
                            <p>Pemilik Startup</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="logo">
                        <i class="fas fa-envelope"></i>
                        <span>GmailStore</span>
                    </div>
                    <p>Platform terpercaya untuk jual beli akun Gmail premium sejak 2020.</p>
                </div>
                <div class="footer-section">
                    <h3>Tautan Cepat</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Daftar</a></li>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
                            <li><a href="admin/index.php">Panel Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Kontak Kami</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope"></i> support@gmaillstore.com</li>
                        <li><i class="fas fa-phone"></i> +62 812-3456-7890</li>
                        <li><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 GmailStore. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>