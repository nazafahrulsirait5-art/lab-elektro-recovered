<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Lab Elektro | Universitas Syiah Kuala</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom Assets -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Open Sans', sans-serif; overflow-x: hidden; background-color: #0f172a; }
        h1, h2, h3, h4, h5, h6, .btn { font-family: 'Inter', sans-serif; }
        
        /* Landing Navbar - Premium Light Glass */
        .landing-navbar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 12px 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .nav-menu-link {
            color: #1e293b;
            font-weight: 600;
            text-decoration: none;
            margin: 0 12px;
            font-size: 0.9rem;
            transition: color 0.2s;
            opacity: 0.85;
        }
        .nav-menu-link:hover { color: #f59e0b; opacity: 1; }
        
        .btn-register-nav {
            background-color: #f59e0b;
            color: white;
            border-radius: 50px;
            padding: 8px 24px;
            font-weight: 700;
            border: none;
            transition: background 0.2s;
        }
        .btn-register-nav:hover { background-color: #d97706; color: white; }
        
        .btn-login-nav {
            color: #1e293b;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            padding: 6px 22px;
            font-weight: 700;
            background: transparent;
            transition: all 0.2s;
        }
        .btn-login-nav:hover { background-color: rgba(0,0,0,0.05); color: #1e293b; border-color: #cbd5e1; }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            background: url('<?= base_url('bakgroundUSK1.jpg') ?>') center/cover no-repeat;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            /* Subtle dark overlay so white text is always readable regardless of background image */
            background: rgba(15, 23, 42, 0.75); 
        }
        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 800px;
            padding: 40px 15px;
        }
        .hero-title-main {
            font-size: 4rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.1;
            margin-bottom: 20px;
        }
        .hero-title-highlight {
            color: #f59e0b; /* USK Gold */
        }
        .hero-description {
            font-size: 1.15rem;
            color: #e2e8f0;
            margin-bottom: 35px;
            font-weight: 400;
            line-height: 1.7;
            max-width: 650px;
        }

        /* Modal Styles */
        .modal-content-custom {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        .modal-header-usk {
            background-color: #f59e0b;
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-bottom: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .modal-header-usk .btn-close {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .form-control-custom {
            border-radius: 10px;
            padding: 12px 15px;
            background-color: #f1f5f9;
            border: 1px solid transparent;
            font-weight: 500;
        }
        .form-control-custom:focus {
            background-color: white;
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="landing-navbar">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <img src="<?= base_url('assets/img/logo-usk.png') ?>" alt="USK Logo" height="40">
                <div style="width: 2px; height: 24px; background-color: #cbd5e1; border-radius: 2px;"></div>
                <span class="fw-bold fs-5" style="color: #f59e0b; font-family: 'Inter', sans-serif; letter-spacing: 0.5px;">E-Lab Elektro</span>
            </div>
            
            <div class="d-none d-xl-flex align-items-center justify-content-center flex-grow-1">
                <a href="#tentang" class="nav-menu-link">Tentang</a>
                <a href="#fakultas" class="nav-menu-link">Fakultas Teknik</a>
                <a href="#elektro" class="nav-menu-link">Teknik Elektro</a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown me-2 d-none d-md-block">
                    <a href="#" class="text-dark text-decoration-none dropdown-toggle" style="font-weight: 600; font-size: 0.9rem;" data-bs-toggle="dropdown">
                        <img src="https://flagcdn.com/w20/id.png" alt="ID" width="20" class="me-1 border rounded-1"> ID
                    </a>
                </div>
                <button class="btn btn-login-nav" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                <button class="btn btn-register-nav" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title-main">
                    Sistem Manajemen<br>
                    <span class="hero-title-highlight">Laboratorium Terpadu.</span>
                </h1>
                <div class="d-flex gap-3 mt-4">
                    <button class="btn btn-register-nav px-5 py-3 fs-5" data-bs-toggle="modal" data-bs-target="#registerModal">
                        Daftar Sekarang <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="py-5 bg-white">
        <div class="container py-5 text-center">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 class="fw-bold mb-4" style="color: #0f172a; font-size: 2.5rem;">Tentang <span style="color: #f59e0b;">Sistem</span></h2>
                    <p class="text-muted fs-5 mb-4" style="line-height: 1.8;">
                        Sistem Manajemen Laboratorium Terpadu (E-Lab Elektro) mengintegrasikan pengelolaan inventaris, otomatisasi peminjaman, dan analitik data laboratorium untuk mendukung ekosistem akademik yang efisien, transparan, dan modern di Fakultas Teknik.
                    </p>
                    <p class="text-muted fs-5" style="line-height: 1.8;">
                        Platform ini dirancang khusus untuk mempermudah mahasiswa, dosen, dan laboran dalam menjalankan kegiatan praktikum dan penelitian, sekaligus menjaga akuntabilitas penggunaan aset laboratorium.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fakultas Teknik Section -->
    <section id="fakultas" class="py-5" style="background-color: #f8fafc;">
        <div class="container py-5">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-4" style="color: #0f172a; font-size: 2.5rem;">Fakultas <span style="color: #f59e0b;">Teknik</span></h2>
                    <p class="text-muted fs-5 mb-4" style="line-height: 1.8;">
                        Fakultas Teknik Universitas Syiah Kuala (USK) merupakan salah satu fakultas teknik terkemuka di Indonesia yang berdiri sejak tahun 1963. Kami berkomitmen menghasilkan lulusan yang berkualitas, inovatif, dan berdaya saing global dalam bidang rekayasa dan teknologi.
                    </p>
                    <p class="text-muted fs-5" style="line-height: 1.8;">
                        Dengan visi menjadi pusat unggulan (center of excellence) pendidikan dan riset keteknikan di tingkat internasional, Fakultas Teknik USK terus mengembangkan fasilitas, kurikulum, dan kemitraan strategis dengan industri.
                    </p>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="img-fluid rounded-4 shadow-lg w-100" style="object-fit: cover; height: 400px;" alt="Fakultas Teknik">
                </div>
            </div>
        </div>
    </section>

    <!-- Teknik Elektro Section -->
    <section id="elektro" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-4" style="color: #0f172a; font-size: 2.5rem;">Teknik <span style="color: #f59e0b;">Elektro</span></h2>
                    <p class="text-muted fs-5 mb-4" style="line-height: 1.8;">
                        Jurusan Teknik Elektro membekali mahasiswa dengan pengetahuan mendalam dan keterampilan praktis di bidang sistem tenaga, telekomunikasi, elektronika, kendali, dan komputer. 
                    </p>
                    <p class="text-muted fs-5" style="line-height: 1.8;">
                        Didukung oleh fasilitas laboratorium modern seperti E-Lab, jurusan kami dirancang untuk mendukung penelitian mutakhir dan praktikum yang relevan dengan kebutuhan standar industri modern 4.0.
                    </p>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1555664424-778a1e5e1b48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="img-fluid rounded-4 shadow-lg w-100" style="object-fit: cover; height: 400px;" alt="Teknik Elektro">
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Messages -->
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 2000; margin-top: 80px;">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="border-radius: 12px; font-weight: 500;">
                <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();
                });
            </script>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style="border-radius: 12px; font-weight: 500;">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header-usk">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img src="<?= base_url('assets/img/logo-usk.png') ?>" alt="USK" height="50" class="mb-2">
                    <h5 class="fw-bold mb-0">Selamat Datang</h5>
                    <small style="opacity: 0.9;">Masukkan akun Anda untuk mengakses sistem</small>
                </div>
                <div class="modal-body p-4 p-md-5">
                    <form action="<?= base_url('login') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Username / NIM</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control form-control-custom" placeholder="admin" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                            </div>
                            <div class="text-end mt-2">
                                <a href="<?= base_url('lupa-password') ?>" class="text-decoration-none small fw-bold" style="color: #f59e0b;">Lupa Password?</a>
                            </div>
                        </div>

                        <div class="mb-4 p-3 bg-light rounded-3 border border-warning border-opacity-25">
                            <label class="form-label fw-bold text-muted small mb-2 d-block">Verifikasi Keamanan (Captcha)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white rounded px-3 py-2 fw-bold text-dark shadow-sm border text-center" style="font-size: 1.2rem; min-width: 100px;">
                                    <?= $captcha_question ?>
                                </div>
                                <input type="number" name="captcha" class="form-control form-control-custom" placeholder="Hasil..." required>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold py-3 rounded-pill text-white" style="background-color: #f59e0b; border: none;">
                            <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Dashboard
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Register -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header border-0 bg-light p-4 text-center d-flex flex-column align-items-center position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 60px; height: 60px; background-color: #f59e0b; color: white;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #0f172a;">Pendaftaran Mahasiswa</h4>
                    <p class="text-muted small mb-0">Buat akun untuk menggunakan fasilitas laboratorium</p>
                </div>
                <div class="modal-body p-4 p-md-5 pt-0 bg-light">
                    <form action="<?= base_url('auth/register') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">NPM / NIM</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text" name="username" class="form-control form-control-custom bg-white" placeholder="Contoh: 2104105010001" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0"><i class="fas fa-font text-muted"></i></span>
                                <input type="text" name="nama_lengkap" class="form-control form-control-custom bg-white" placeholder="Nama Lengkap Anda" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control form-control-custom bg-white" placeholder="Minimal 6 Karakter" required minlength="6">
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold py-3 rounded-pill text-white" style="background-color: #f59e0b;">
                            Daftar Akun Sekarang
                        </button>
                        <div class="text-center mt-3">
                            <small class="text-muted">Sudah punya akun? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="fw-bold" style="color: #f59e0b;">Login di sini</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
