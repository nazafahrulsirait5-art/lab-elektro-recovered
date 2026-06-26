<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            padding: 50px;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 14px; }
        .content {
            line-height: 1.6;
            margin-bottom: 50px;
        }
        .status-box {
            border: 2px solid #000;
            padding: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            margin: 30px 0;
        }
        .status-bebas { color: green; }
        .status-tanggungan { color: red; }
        .footer {
            margin-top: 100px;
            float: right;
            text-align: center;
            width: 250px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print" style="background: #f8f9fa; padding: 15px; margin-bottom: 30px; border-radius: 8px; border: 1px solid #ddd;">
    <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Cetak Sekarang</button>
    <button onclick="window.history.back()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">Kembali</button>
</div>

<div class="header">
    <h2>Laboratorium Teknik Elektro</h2>
    <h2>Universitas Kebangsaan</h2>
    <p>Jl. Pendidikan No. 123, Kampus Utama | Telp: (021) 12345678</p>
</div>

<div class="content">
    <h3 style="text-align: center; text-decoration: underline;">SURAT KETERANGAN BEBAS LABORATORIUM</h3>
    <br>
    <p>Diterangkan dengan sebenarnya bahwa mahasiswa di bawah ini:</p>
    
    <table style="width: 100%; margin-left: 20px;">
        <tr>
            <td width="150">Nama Lengkap</td>
            <td width="20">:</td>
            <td style="font-weight: bold;"><?= $user['nama_lengkap'] ?></td>
        </tr>
        <tr>
            <td>NIM / Username</td>
            <td>:</td>
            <td><?= $user['username'] ?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td>Teknik Elektro</td>
        </tr>
    </table>

    <?php if ($is_bebas): ?>
        <div class="status-box status-bebas">
            DINYATAKAN BEBAS <br>
            DARI SELURUH TANGGUNGAN ALAT LABORATORIUM
        </div>
        <p>Demikian surat keterangan ini diberikan untuk dapat dipergunakan sebagaimana mestinya, terutama dalam pengurusan Yudisium atau Pengambilan Ijazah.</p>
    <?php else: ?>
        <div class="status-box status-tanggungan">
            MEMILIKI TANGGUNGAN <br>
            (BELUM DAPAT DINYATAKAN BEBAS LAB)
        </div>
        <p>Mahasiswa yang bersangkutan saat ini masih memiliki tanggungan peminjaman alat sebagai berikut:</p>
        <ul>
            <?php foreach($tanggungan as $t): ?>
                <li>1 unit - Transaksi #<?= $t['id'] ?> (Tgl: <?= $t['tanggal_pinjam'] ?>)</li>
            <?php endforeach; ?>
        </ul>
        <p>Mohon segera mengembalikan alat tersebut untuk mendapatkan Surat Bebas Lab.</p>
    <?php endif; ?>
</div>

<div class="footer">
    <p>Banda Aceh, <?= $date_now ?></p>
    <p>Kepala Laboratorium,</p>
    <br><br><br>
    <p><strong>( ________________________ )</strong></p>
    <p>NIP. 19800101 200501 1 001</p>
</div>

</body>
</html>
