<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | E-Lab Elektro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
        }

        .glass-card {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .card-header-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f36c21 0%, #ea580c 100%);
            margin: -3rem -3rem 1.75rem -3rem;
            padding: 2.5rem 1rem 3.5rem 1rem;
            border-radius: 24px 24px 0 0;
            position: relative;
        }

        .card-header-logo::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(180deg, transparent 0%, white 100%);
        }

        .card-header-logo img {
            height: 65px;
            position: relative;
            z-index: 2;
        }

        .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .form-control {
            padding: 0.75rem 1rem 0.75rem 2.65rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
        }

        .input-group-custom { position: relative; margin-bottom: 20px; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #9ca3af; z-index: 5;
        }

        .btn-submit {
            background: #f97316;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            transition: 0.2s;
        }
        .btn-submit:hover { background: #ea580c; }
        
        .alert-success {
            background: #ecfdf5; border: 1px solid #6ee7b7; color: #059669;
            padding: 15px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="glass-card text-center">
        <div class="card-header-logo">
            <img src="<?= base_url('assets/img/logo-usk.png') ?>" alt="Logo USK">
        </div>
        <h3 class="title">Lupa Password?</h3>
        <p class="subtitle">Masukkan alamat email yang terdaftar pada akun Anda. Kami akan mengirimkan instruksi pemulihan sandi.</p>

        <?php if(session()->getFlashdata('simulate_email')): ?>
            <div class="alert-success text-start">
                <i class="fas fa-paper-plane me-2"></i> 
                <?= session()->getFlashdata('simulate_email') ?>
            </div>
        <?php else: ?>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('lupa-password/kirim') ?>" method="POST">
                <div class="input-group-custom text-start">
                    <label class="form-label fw-bold text-dark mb-2" style="font-size:0.85rem;">Alamat Email</label>
                    <div style="position: relative;">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" required placeholder="contoh@usk.ac.id">
                    </div>
                </div>
                <button type="submit" class="btn-submit">Kirim Tautan Reset</button>
            </form>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="<?= base_url('login') ?>" class="text-decoration-none text-muted" style="font-size: 0.85rem; font-weight: 500;">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>
