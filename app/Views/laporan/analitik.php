<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .stat-card {
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        background: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        border: 1px solid #f1f5f9;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #1e293b;
    }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .analytical-header {
        background: linear-gradient(135deg, #2b3954, #1a2333);
        border-radius: 16px;
        padding: 24px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .highlight-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 15px;
    }
</style>

<div class="container-fluid mb-5">
    <!-- Header Banner -->
    <div class="analytical-header mb-4 shadow">
        <div class="d-flex align-items-center">
            <div class="bg-white bg-opacity-10 p-3 rounded-3 me-3">
                <i class="fas fa-chart-line fa-2x text-white"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white">Laporan & Analitik</h4>
                <div class="text-light small">Rekapitulasi Peminjaman Inventaris Lab Elektro</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-light rounded-pill px-4" style="background: rgba(255,255,255,0.1); border: none;">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
            <button onclick="exportCSV()" class="btn btn-success rounded-pill px-4 shadow-sm" style="background: #10b981; border: none;">
                <i class="fas fa-file-excel me-2"></i> Export CSV
            </button>
            <button onclick="cetakAnalitik()" class="btn btn-primary rounded-pill px-4 shadow-sm" style="background: #6366f1; border: none;">
                <i class="fas fa-print me-2"></i> Cetak
            </button>
        </div>
    </div>

    <!-- 4 Statistics Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0e7ff; color: #4f46e5;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <h3 class="stat-value"><?= $total_transaksi ?></h3>
                    <div class="stat-label">TOTAL TRANSAKSI</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-box-open"></i>
                </div>
                <div>
                    <h3 class="stat-value"><?= $sedang_dipinjam ?></h3>
                    <div class="stat-label">SEDANG DIPINJAM</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="stat-value"><?= $dikembalikan ?></h3>
                    <div class="stat-label">DIKEMBALIKAN</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h3 class="stat-value">Rp <?= number_format($total_denda, 0, ',', '.') ?></h3>
                    <div class="stat-label">TOTAL DENDA</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Highlight Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="highlight-card" style="background: #eff6ff;">
                <div class="stat-icon" style="background: white; color: #ef4444; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <i class="fas fa-trophy"></i>
                </div>
                <div>
                    <div class="stat-label text-primary mb-1">ALAT TERPOPULER</div>
                    <div class="fw-bold" style="color: #1e293b; font-size: 1.1rem;"><?= $alat_populer ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="highlight-card" style="background: #f5f3ff;">
                <div class="stat-icon" style="background: white; color: #eab308; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <div class="stat-label text-primary mb-1">PEMINJAM TERAKTIF</div>
                    <div class="fw-bold" style="color: #1e293b; font-size: 1.1rem;"><?= $peminjam_aktif ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section (Dashboard Data Teknik) -->
    <div class="row g-4 mb-5">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-chart-line me-2"></i> Tren Denda Keterlambatan (6 Bulan Terakhir)</h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="trendDendaChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(145deg, #1e293b, #0f172a); color: white;">
                <div class="card-header border-0 pt-4 pb-0 px-4 text-center" style="background: transparent;">
                    <h6 class="fw-bold mb-0 text-white"><i class="fas fa-chart-pie me-2 text-warning"></i> Distribusi Kondisi Alat</h6>
                </div>
                <div class="card-body p-4 d-flex justify-content-center align-items-center">
                    <canvas id="kondisiAlatChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data for Tren Denda
            const trendCtx = document.getElementById('trendDendaChart').getContext('2d');
            
            // Create gradient for line chart
            let gradientBlue = trendCtx.createLinearGradient(0, 0, 0, 400);
            gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
            gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: <?= $chart_bulan ?>,
                    datasets: [{
                        label: 'Total Denda (Rp)',
                        data: <?= $chart_denda ?>,
                        borderColor: '#3b82f6',
                        backgroundColor: gradientBlue,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], color: '#e2e8f0' },
                            ticks: { callback: function(value) { return 'Rp ' + value; } }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Data for Distribusi Kondisi Alat
            const kondisiCtx = document.getElementById('kondisiAlatChart').getContext('2d');
            new Chart(kondisiCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Maintenance', 'Rusak'],
                    datasets: [{
                        data: [
                            <?= $chart_alat['tersedia'] ?>, 
                            <?= $chart_alat['maintenance'] ?>, 
                            <?= $chart_alat['rusak'] ?>
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#cbd5e1', padding: 20, font: { size: 12 } }
                        }
                    }
                }
            });
        });
    </script>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url('laporan/analitik') ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="<?= isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '' ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" class="form-control" value="<?= isset($_GET['tgl_sampai']) ? $_GET['tgl_sampai'] : '' ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="Semua Status">Semua Status</option>
                        <option value="Menunggu Persetujuan" <?= (isset($_GET['status']) && $_GET['status'] == 'Menunggu Persetujuan') ? 'selected' : '' ?>>Menunggu Persetujuan</option>
                        <option value="Dipinjam" <?= (isset($_GET['status']) && $_GET['status'] == 'Dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                        <option value="Dikembalikan" <?= (isset($_GET['status']) && $_GET['status'] == 'Dikembalikan') ? 'selected' : '' ?>>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">Cari Nama / Alat / NIM</label>
                    <input type="text" name="keyword" class="form-control" placeholder="Ketik kata kunci..." value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn text-white w-100 fw-bold" style="background: #6366f1;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="<?= base_url('laporan/analitik') ?>" class="btn btn-light border w-50">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-table me-2"></i> Detail Transaksi</h6>
            <span class="badge bg-light text-primary border"><?= count($transaksi) ?> data</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light" style="font-size: 0.75rem; color: #64748b; letter-spacing: 1px;">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>NIM</th>
                        <th>NAMA PEMINJAM</th>
                        <th>NAMA ALAT</th>
                        <th class="text-center">JML</th>
                        <th>TGL PINJAM</th>
                        <th>TGL KEMBALI</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-end">DENDA</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    <?php if(empty($transaksi)): ?>
                    <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data transaksi ditemukan</td></tr>
                    <?php endif; ?>
                    <?php foreach($transaksi as $t): ?>
                    <tr>
                        <td class="ps-4"><span class="badge bg-light text-secondary border">#TRX-<?= $t['id'] ?></span></td>
                        <td class="text-secondary"><?= $t['username'] ?></td>
                        <td class="fw-bold"><?= $t['nama_lengkap'] ?></td>
                        <td><?= $t['nama_alat'] ?></td>
                        <td class="text-center fw-medium"><?= $t['jumlah_pinjam'] ?></td>
                        <td><?= date('d/m/Y', strtotime($t['tanggal_pinjam'])) ?></td>
                        <td><?= $t['tanggal_kembali'] ? date('d/m/Y', strtotime($t['tanggal_kembali'])) : '-' ?></td>
                        <td class="text-center">
                            <?php if($t['status_pinjam'] == 'Menunggu Persetujuan'): ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">MENUNGGU</span>
                            <?php elseif($t['status_pinjam'] == 'Dipinjam'): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">DIPINJAM</span>
                            <?php elseif($t['status_pinjam'] == 'Ditolak'): ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">DITOLAK</span>
                            <?php else: ?>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">DIKEMBALIKAN</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold <?= $t['denda'] > 0 ? 'text-danger' : 'text-muted' ?>">
                            <?= $t['denda'] > 0 ? 'Rp ' . number_format($t['denda'], 0, ',', '.') : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// ========== EXPORT CSV ==========
function exportCSV() {
    var rows = [
        ['ID', 'NIM', 'NAMA PEMINJAM', 'NAMA ALAT', 'JML PINJAM', 'TGL PINJAM', 'TGL KEMBALI', 'STATUS', 'DENDA']
    ];

    document.querySelectorAll('.table tbody tr').forEach(function(tr) {
        var tds = tr.querySelectorAll('td');
        if (tds.length < 9) return;
        rows.push([
            tds[0].innerText.trim(),
            tds[1].innerText.trim(),
            tds[2].innerText.trim(),
            tds[3].innerText.trim(),
            tds[4].innerText.trim(),
            tds[5].innerText.trim(),
            tds[6].innerText.trim(),
            tds[7].innerText.trim(),
            tds[8].innerText.trim()
        ]);
    });

    var csvContent = rows.map(function(r) {
        return r.map(function(cell) {
            // Wrap in quotes jika ada koma
            return '"' + String(cell).replace(/"/g, '""') + '"';
        }).join(',');
    }).join('\r\n');

    var BOM = '\uFEFF'; // UTF-8 BOM agar Excel bisa baca karakter Indonesia
    var blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
    var url = URL.createObjectURL(blob);
    var link = document.createElement('a');
    link.href = url;
    link.download = 'laporan-analitik-elab-<?= date('Ymd') ?>.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

// ========== CETAK ANALITIK ==========
function cetakAnalitik() {
    var rows = '';
    document.querySelectorAll('.table tbody tr').forEach(function(tr) {
        var tds = tr.querySelectorAll('td');
        if (tds.length < 9) return;
        var denda = tds[8].innerText.trim();
        var dendaStyle = denda !== '-' ? 'color:#991b1b;font-weight:bold;' : 'color:#94a3b8;';
        rows += '<tr>';
        for (var i = 0; i < tds.length; i++) {
            var s = 'border:1px solid #9ca3af;padding:3px 5px;font-size:7.5pt;';
            if (i === 0) s += 'color:#6b7280;text-align:center;';
            if (i === 4) s += 'text-align:center;';
            if (i === 7) s += 'text-align:center;';
            if (i === 8) s += 'text-align:right;' + dendaStyle;
            rows += '<td style="' + s + '">' + tds[i].innerText.trim() + '</td>';
        }
        rows += '</tr>';
    });

    var stats = {
        total: document.querySelectorAll('.stat-value')[0]?.innerText ?? '-',
        dipinjam: document.querySelectorAll('.stat-value')[1]?.innerText ?? '-',
        kembali: document.querySelectorAll('.stat-value')[2]?.innerText ?? '-',
        denda: document.querySelectorAll('.stat-value')[3]?.innerText ?? '-',
    };

    var pw = window.open('', '_blank', 'width=1122,height=794');
    pw.document.write(`<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<title>Laporan Analitik E-Lab Elektro</title>
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { font-family:Arial,sans-serif; font-size:9pt; color:#000; background:#fff; }
  .header { text-align:center; border-bottom:2px solid #000; padding-bottom:8px; margin-bottom:10px; }
  .header h3 { font-size:13pt; font-weight:bold; }
  .header p { font-size:9pt; }
  .stats { display:flex; gap:10px; margin-bottom:10px; }
  .stat-box { flex:1; border:1px solid #e2e8f0; border-radius:6px; padding:8px 10px; text-align:center; }
  .stat-box .val { font-size:12pt; font-weight:bold; color:#1e293b; }
  .stat-box .lbl { font-size:7pt; color:#64748b; text-transform:uppercase; }
  table { width:100%; border-collapse:collapse; }
  th { border:1px solid #374151; padding:4px 5px; font-size:7.5pt; text-align:center; background:#e0e7ff; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
  .footer { margin-top:10px; border-top:1px solid #000; padding-top:5px; display:flex; justify-content:space-between; font-size:8pt; }
  @media print {
    @page { size:A4 landscape; margin:8mm 10mm 8mm 10mm; }
    thead { display:table-header-group; }
    tr { page-break-inside:avoid; }
  }
</style>
</head><body>
  <div class="header">
    <h3>LAPORAN ANALITIK PEMINJAMAN INVENTARIS</h3>
    <p>E-Lab Laboratorium Teknik Elektro – Universitas Syiah Kuala</p>
    <p style="font-size:8pt;color:#555;">Dicetak pada: <?= date('d F Y H:i:s') ?></p>
  </div>
  <div class="stats">
    <div class="stat-box"><div class="val">${stats.total}</div><div class="lbl">Total Transaksi</div></div>
    <div class="stat-box"><div class="val">${stats.dipinjam}</div><div class="lbl">Sedang Dipinjam</div></div>
    <div class="stat-box"><div class="val">${stats.kembali}</div><div class="lbl">Dikembalikan</div></div>
    <div class="stat-box"><div class="val">${stats.denda}</div><div class="lbl">Total Denda</div></div>
  </div>
  <table>
    <thead><tr>
      <th>ID</th><th>NIM</th><th>NAMA PEMINJAM</th><th>NAMA ALAT</th>
      <th>JML</th><th>TGL PINJAM</th><th>TGL KEMBALI</th><th>STATUS</th><th>DENDA</th>
    </tr></thead>
    <tbody>${rows}</tbody>
  </table>
  <div class="footer">
    <span>Dicetak pada: <strong><?= date('d F Y H:i:s') ?></strong></span>
    <span>Dokumen Resmi USK E-Lab Elektro</span>
  </div>
  <script>window.onload=function(){window.print();window.onafterprint=function(){window.close();};};<\/script>
</body></html>`);
    pw.document.close();
}
</script>

<?= $this->endSection() ?>
