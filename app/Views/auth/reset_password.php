<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setel Ulang Password | E-Lab Elektro</title>
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
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .input-group-custom { position: relative; margin-bottom: 15px; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #9ca3af; z-index: 5;
        }

        .btn-submit {
            background: #10b981;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            transition: 0.2s;
            margin-top: 10px;
        }
        .btn-submit:hover { background: #059669; }
        
        .alert-error {
            background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626;
            padding: 15px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="glass-card text-center">
        <div class="card-header-logo">
            <img src="<?= base_url('assets/img/logo-usk.png') ?>" alt="Logo USK">
        </div>
        <h3 class="title">Bikin Sandi Baru</h3>
        <p class="subtitle">Silakan ketik password baru untuk akun Anda. Jangan sampai lupa lagi ya!</p>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert-error text-start">
                <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('reset-password/' . $token) ?>" method="POST">
            <div class="input-group-custom text-start">
                <label class="form-label fw-bold text-dark mb-2" style="font-size:0.85rem;">Password Baru</label>
                <div style="position: relative;">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                </div>
            </div>
            
            <div class="input-group-custom text-start">
                <label class="form-label fw-bold text-dark mb-2" style="font-size:0.85rem;">Konfirmasi Password Baru</label>
                <div style="position: relative;">
                    <i class="fas fa-shield-alt input-icon"></i>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="Ketik ulang password baru">
                </div>
            </div>
            
            <button type="submit" class="btn-submit">Ubah Password & Login</button>
        </form>
    </div>
</body>
</html>
