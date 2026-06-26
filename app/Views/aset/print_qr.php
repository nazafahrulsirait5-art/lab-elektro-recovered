<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; background: #f5f5f5; }
        .card { border: 2px dashed #ccc; padding: 30px; display: inline-block; border-radius: 10px; background: #fff; }
        #qrcode { margin: 0 auto 20px auto; width: 250px; height: 250px; display: flex; align-items: center; justify-content: center; }
        #qrcode canvas, #qrcode img { width: 250px !important; height: 250px !important; }
        .alat-name { font-size: 24px; font-weight: bold; margin-bottom: 5px; color: #333; }
        .alat-merk { font-size: 16px; color: #666; margin-bottom: 15px; }
        .btn-print { background: #f36c21; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; text-decoration: none; margin-top: 20px; display: inline-block; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; background: white; }
            .card { border: none; padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin-top: 0; color: #f36c21;">USK E-Lab Elektro</h2>
        
        <!-- QR Code di-generate oleh JavaScript (tidak butuh internet) -->
        <div id="qrcode"></div>

        <div class="alat-name"><?= esc($alat['nama_alat']) ?></div>
        <div class="alat-merk"><?= esc($alat['merk']) ?></div>
        <div style="font-size: 12px; color: #999; margin-top: 10px;">ID Tanda Pengenal: ALAT-<?= sprintf('%04d', $alat['id']) ?></div>
        
        <div class="no-print" style="margin-top: 20px;">
            <button onclick="window.print()" class="btn-print">Cetak Stiker QR</button>
            <a href="<?= base_url('alat') ?>" class="btn-print" style="background: #6c757d; margin-left: 10px;">Kembali</a>
        </div>
    </div>

    <!-- QRCode.js library (di-load dari CDN, fallback ke qrserver API jika offline) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        var qrData = "ELAB-ALAT-<?= $alat['id'] ?>";
        
        try {
            // Generate QR Code menggunakan QRCode.js (tidak butuh server/internet)
            new QRCode(document.getElementById("qrcode"), {
                text: qrData,
                width: 250,
                height: 250,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        } catch(e) {
            // Fallback jika CDN tidak tersedia: tampilkan gambar dari API
            var img = document.createElement('img');
            img.src = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(qrData);
            img.alt = "QR Code";
            img.style.width = "250px";
            img.style.height = "250px";
            document.getElementById("qrcode").appendChild(img);
        }
    </script>
</body>
</html>
