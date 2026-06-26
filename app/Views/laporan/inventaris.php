<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mb-5">
    <!-- Header Banner -->
    <div class="mb-4 shadow" style="background: linear-gradient(135deg, #10b981, #047857); border-radius: 16px; padding: 24px; color: white; display: flex; justify-content: space-between; align-items: center;">
        <div class="d-flex align-items-center">
            <div class="bg-white bg-opacity-10 p-3 rounded-3 me-3">
                <i class="fas fa-file-excel fa-2x text-white"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">Laporan & Rekapitulasi Inventaris</h4>
                <div class="text-white-50 small">Dokumen Laporan Residu Aset Laboratorium Teknik Elektro</div>
            </div>
        </div>
        <div class="d-flex gap-2 printable-hide">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-light rounded-pill px-4" style="background: rgba(255,255,255,0.1); border: none;">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
            <button onclick="cetakLaporan()" class="btn btn-light text-success fw-bold rounded-pill px-4 shadow-sm" style="border: none;">
                <i class="fas fa-print me-2"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Header khusus Print (hanya muncul saat cetak) -->
    <div id="print-header" style="visibility:hidden;">
        <div style="text-align:center; margin-bottom:10px; border-bottom:2px solid #000; padding-bottom:8px;">
            <h4 style="margin:0; font-weight:bold; font-size:14pt;">LAPORAN REKAPITULASI INVENTARIS</h4>
            <p style="margin:2px 0; font-size:11pt;">E-Lab Laboratorium Teknik Elektro – Universitas Syiah Kuala</p>
            <p style="margin:0; font-size:9pt; color:#555;">Dicetak pada: <?= date('d F Y H:i:s') ?></p>
        </div>
    </div>

    <!-- Area yang dicetak -->
    <div id="laporan-print-area">
        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0" style="color: #047857;"><i class="fas fa-clipboard-list me-2"></i> Laporan Detail Kondisi Aset Lab</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table table-bordered table-striped align-middle mb-0 printable-table">
                    <colgroup>
                        <col class="col-no">
                        <col class="col-nama">
                        <col class="col-merk">
                        <col class="col-total">
                        <col class="col-baik">
                        <col class="col-maint">
                        <col class="col-rusak">
                    </colgroup>
                    <thead class="bg-light" style="font-size: 0.8rem; color: #1e293b;">
                        <tr>
                            <th class="text-center">NO</th>
                            <th>NAMA ALAT</th>
                            <th>MERK / SPESIFIKASI</th>
                            <th class="text-center">TOTAL UNIT</th>
                            <th class="text-center text-success">KONDISI BAIK</th>
                            <th class="text-center text-warning">MAINTENANCE</th>
                            <th class="text-center text-danger">RUSAK BERAT</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        <?php if(empty($alat)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                <h6 class="fw-bold text-secondary mb-1">Data Inventaris Kosong</h6>
                                <p class="small mb-0">Belum ada alat yang ditambahkan ke dalam sistem E-Lab Elektro.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php foreach($alat as $index => $item): ?>
                        <tr>
                            <td class="text-center text-muted"><?= $index + 1 ?></td>
                            <td class="fw-bold"><?= esc($item['nama_alat']) ?></td>
                            <td class="text-muted"><?= esc($item['merk']) ?></td>
                            <td class="text-center fw-bold bg-light"><?= $item['jumlah_total'] ?></td>
                            <td class="text-center text-success fw-bold print-success"><?= $item['jumlah_tersedia'] ?></td>
                            <td class="text-center text-warning fw-bold print-warning"><?= $item['jumlah_maintenance'] ?? 0 ?></td>
                            <td class="text-center text-danger fw-bold print-danger"><?= $item['jumlah_rusak'] ?? 0 ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center text-muted small">
                <div>Dicetak pada: <strong><?= date('d F Y H:i:s') ?></strong></div>
                <div>Dokumen Resmi USK E-Lab Elektro</div>
            </div>
        </div>

        <!-- Footer khusus Print -->
        <div class="print-footer-area">
            <span>Dicetak pada: <strong><?= date('d F Y H:i:s') ?></strong></span>
            <span>Dokumen Resmi USK E-Lab Elektro</span>
        </div>
    </div>
</div>

<style>
/* Sembunyikan footer di layar */
.print-footer-area { display: none; }
</style>

<script>
function cetakLaporan() {
    // Kumpulkan data tabel dari DOM
    var rows = document.querySelectorAll('.printable-table tbody tr');
    var tableRows = '';
    rows.forEach(function(tr) {
        var tds = tr.querySelectorAll('td');
        if (tds.length === 0) return;
        tableRows += '<tr>';
        tds.forEach(function(td, i) {
            var style = '';
            if (i === 4) style = 'color:#166534;font-weight:bold;';
            else if (i === 5) style = 'color:#92400e;font-weight:bold;';
            else if (i === 6) style = 'color:#991b1b;font-weight:bold;';
            else if (i === 0) style = 'text-align:center;color:#6b7280;';
            else if (i === 3) style = 'text-align:center;font-weight:bold;';
            tableRows += '<td style="border:1px solid #9ca3af;padding:4px 6px;' + style + '">' + td.innerText + '</td>';
        });
        tableRows += '</tr>';
    });

    var printWindow = window.open('', '_blank', 'width=1122,height=794');
    printWindow.document.write(`<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Inventaris</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 9pt; color: #000; background: #fff; }
        .header { text-align: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #000; }
        .header h3 { font-size: 13pt; font-weight: bold; margin-bottom: 3px; }
        .header p { font-size: 9.5pt; margin-bottom: 2px; }
        .header .tgl { font-size: 8.5pt; color: #444; }
        table { width: 100%; border-collapse: collapse; }
        thead { background-color: #d1fae5; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        th { border: 1px solid #374151; padding: 5px 4px; font-size: 8pt; text-align: center; }
        td { border: 1px solid #9ca3af; padding: 4px 6px; }
        .col-no    { width: 4%; text-align: center; }
        .col-nama  { width: 28%; }
        .col-merk  { width: 23%; }
        .col-num   { width: 9%; text-align: center; }
        .footer { margin-top: 10px; border-top: 1px solid #000; padding-top: 5px; display: flex; justify-content: space-between; font-size: 8pt; }
        @media print {
            @page { size: A4 landscape; margin: 10mm 12mm 10mm 12mm; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>LAPORAN REKAPITULASI INVENTARIS</h3>
        <p>E-Lab Laboratorium Teknik Elektro – Universitas Syiah Kuala</p>
        <p class="tgl">Dicetak pada: <?= date('d F Y H:i:s') ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th class="col-nama" style="text-align:left;">NAMA ALAT</th>
                <th class="col-merk" style="text-align:left;">MERK / SPESIFIKASI</th>
                <th class="col-num">TOTAL UNIT</th>
                <th class="col-num" style="color:#166534;">KONDISI BAIK</th>
                <th class="col-num" style="color:#92400e;">MAINTENANCE</th>
                <th class="col-num" style="color:#991b1b;">RUSAK BERAT</th>
            </tr>
        </thead>
        <tbody>` + tableRows + `</tbody>
    </table>
    <div class="footer">
        <span>Dicetak pada: <strong><?= date('d F Y H:i:s') ?></strong></span>
        <span>Dokumen Resmi USK E-Lab Elektro</span>
    </div>
    <script>
        window.onload = function() { window.print(); window.onafterprint = function() { window.close(); }; };
    <\/script>
</body>
</html>`);
    printWindow.document.close();
}
</script>
<?= $this->endSection() ?>
