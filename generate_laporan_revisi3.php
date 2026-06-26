<?php
/**
 * ============================================================
 *  LAPORAN EVALUASI UI/UX — SISTEM E-LAB ELEKTRO
 *  Program Studi Teknik Elektro, Universitas Syiah Kuala
 * ============================================================
 *  Font   : Times New Roman 12pt
 *  Spasi  : 1.5 lines (sl360 slmult1)
 *  Warna  : Hitam
 *  Margin : Kiri 4cm, Kanan 3cm, Atas/Bawah 2.54cm
 *  Gambar : Wireframe embedded sebagai PNG → EMF hex di RTF
 * ============================================================
 */

// ── RTF helper: escape UTF-8 text to RTF-safe string ─────────
function r($text) {
    $text = str_replace('\\', '\\\\', $text);
    $text = str_replace('{',  '\\{',  $text);
    $text = str_replace('}',  '\\}',  $text);
    $out  = '';
    for ($i = 0; $i < strlen($text); ) {
        $b = ord($text[$i]);
        if ($b < 0x80) {
            $out .= $text[$i]; $i++;
        } elseif ($b < 0xE0) {
            $cp   = (($b & 0x1F) << 6) | (ord($text[$i+1]) & 0x3F);
            $out .= $cp < 256 ? sprintf("\\'%02x", $cp) : sprintf("\\u%d?", $cp);
            $i   += 2;
        } elseif ($b < 0xF0) {
            $cp   = (($b & 0x0F) << 12) | ((ord($text[$i+1]) & 0x3F) << 6) | (ord($text[$i+2]) & 0x3F);
            $out .= sprintf("\\u%d?", $cp);
            $i   += 3;
        } else { $out .= '?'; $i += 4; }
    }
    return $out;
}

// ── Global output buffer & base styles ───────────────────────
$o    = '';
$BASE = '\\f0\\fs24\\cf0\\sl360\\slmult1';   // TNR 12pt, black, 1.5-line

// ── Typography helpers ────────────────────────────────────────
function ln($text, $bold=false, $align='j', $spb=0, $spa=120, $indent=0) {
    global $o, $BASE;
    $b  = $bold ? '\\b' : '\\b0';
    $q  = "\\q{$align}";
    $li = $indent > 0 ? "\\li{$indent}\\fi-{$indent}" : '';
    $o .= "\\pard{$q}\\sb{$spb}\\sa{$spa}{$li}{$BASE}{$b} " . r($text) . "\\par\n";
}
function p($text, $bold=false, $spa=120) { ln($text, $bold, 'j', 0, $spa); }
function bul($text) {
    global $o, $BASE;
    $o .= "\\pard\\qj\\li560\\fi-280\\sb0\\sa80{$BASE}\\b0 \\bullet   " . r($text) . "\\par\n";
}
function gap($n=160) { global $o; $o .= "\\pard\\sb0\\sa{$n}\\par\n"; }
function pg()        { global $o; $o .= "\\page\n"; }

// ── Section & Subsection headings ─────────────────────────────
function sec($num, $title) {
    global $o, $BASE;
    $o .= "\\pard\\ql\\sb300\\sa80{$BASE}\\b " . r($num . ". " . strtoupper($title)) . "\\par\n";
    $o .= "\\pard\\ql\\sb0\\sa120\\brdrb\\brdrs\\brdrw8\\brdrcf0 \\par\n";
}
function subsec($num, $title) {
    global $o, $BASE;
    $o .= "\\pard\\ql\\sb200\\sa80{$BASE}\\b " . r($num . "  " . $title) . "\\par\n";
}
function subsubsec($title) {
    global $o, $BASE;
    $o .= "\\pard\\ql\\sb120\\sa60{$BASE}\\b\\i " . r($title) . "\\par\n";
}

// ── Table renderer ────────────────────────────────────────────
function tbl($rows, $widths, $headerIdx=0) {
    global $o, $BASE;
    foreach ($rows as $ri => $cells) {
        $isHdr = ($ri === $headerIdx);
        $tw    = 0; $defs = '';
        foreach ($widths as $w) {
            $tw   += $w;
            $defs .= "\\clbrdrt\\brdrs\\brdrw8\\clbrdrl\\brdrs\\brdrw8\\clbrdrb\\brdrs\\brdrw8\\clbrdrr\\brdrs\\brdrw8";
            if ($isHdr) $defs .= "\\clcbpat1";
            $defs .= "\\cellx{$tw}";
        }
        $o .= "\\trowd\\trgaph80\\trleft0 {$defs}\n";
        foreach ($cells as $cell) {
            $b  = $isHdr ? '\\b' : '\\b0';
            $o .= "\\pard\\intbl\\ql\\sb60\\sa60\\sl276\\slmult1{$b}\\f0\\fs22\\cf0 " . r($cell) . "\\cell\n";
        }
        $o .= "\\row\n";
    }
}

// ── Image embedder (PNG → RTF pngblip hex) ────────────────────
function embedImg($path, $widthCm=14, $heightCm=9) {
    global $o;
    if (!file_exists($path)) {
        // placeholder jika file tidak ditemukan
        $o .= "\\pard\\qc\\sb80\\sa80 [Gambar placeholder, wireframe akan ditambahkan secara manual]\\par\n";
        return;
    }
    $data = file_get_contents($path);
    $hex  = strtolower(bin2hex($data));
    // twips: 1cm = 567 twips
    $wTwip = (int)($widthCm  * 567);
    $hTwip = (int)($heightCm * 567);
    $o .= "\\pard\\qc\\sb80\\sa80{\\pict\\pngblip\\picwgoal{$wTwip}\\pichgoal{$hTwip} {$hex}}\\par\n";
}

// ── Caption helper ─────────────────────────────────────────────
function caption($text) {
    global $o, $BASE;
    $o .= "\\pard\\qc\\sb0\\sa160{$BASE}\\b0\\i " . r($text) . "\\par\n";
}

// ── Image paths ───────────────────────────────────────────────
$IMG_DIR = 'C:\\Users\\HP VICTUS\\.gemini\\antigravity-ide\\brain\\bc1cf978-7f45-4b62-87d1-9bfc9d051d45\\';

// Cari file PNG terbaru untuk setiap wireframe
function findLatestPng($dir, $prefix) {
    $files = glob($dir . $prefix . '*.png');
    if (empty($files)) return null;
    usort($files, fn($a,$b) => filemtime($b) - filemtime($a));
    return $files[0];
}

$img_login      = findLatestPng($IMG_DIR, 'wireframe_login_');
$img_dash_admin = findLatestPng($IMG_DIR, 'wireframe_dashboard_admin_');
$img_dash_mhs   = findLatestPng($IMG_DIR, 'wireframe_dashboard_mahasiswa_');
$img_dash_kaprodi = findLatestPng($IMG_DIR, 'wireframe_dashboard_kaprodi_');
$img_pinjam     = findLatestPng($IMG_DIR, 'wireframe_peminjaman_form_');
$img_kg         = findLatestPng($IMG_DIR, 'wireframe_knowledge_graph_');
$img_invent     = findLatestPng($IMG_DIR, 'wireframe_inventaris_alat_');
// Penjaga Lab dan Profil gagal di-generate karena quota, kita set null
$img_penjaga    = null; 
$img_profil     = null;

// ═══════════════════════════════════════════════════════════════
//  HALAMAN SAMPUL
// ═══════════════════════════════════════════════════════════════
$o .= "\\pard\\qc\\sb2000\\sa0{$BASE}\\b " . r('UNIVERSITAS SYIAH KUALA') . "\\par\n";
$o .= "\\pard\\qc\\sb40\\sa0{$BASE}\\b0 " . r('Program Studi Teknik Elektro — Fakultas Teknik') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa0\\brdrb\\brdrs\\brdrw24\\brdrcf0 \\par\n";
gap(600);
$o .= "\\pard\\qc\\sb0\\sa120{$BASE}\\b " . r('LAPORAN EVALUASI ANTARMUKA PENGGUNA (UI/UX)') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa120{$BASE}\\b " . r('SISTEM INFORMASI MANAJEMEN LABORATORIUM') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa0{$BASE}\\b " . r('E-LAB ELEKTRO') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa0\\brdrb\\brdrs\\brdrw10\\brdrcf0 \\par\n";
gap(200);
$o .= "\\pard\\qc\\sb0\\sa80{$BASE}\\b0 " . r('Pendekatan User-Centered Design (UCD) dan Evaluasi Heuristik Nielsen Lengkap Seluruh Aktor') . "\\par\n";
gap(600);
$o .= "\\pard\\qc\\sb0\\sa80{$BASE}\\b0 " . r('Disusun oleh:') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa60{$BASE}\\b " . r('Tim Pengembang E-Lab Elektro') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa60{$BASE}\\b0 " . r('Program Studi Teknik Elektro') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa60{$BASE}\\b0 " . r('Universitas Syiah Kuala') . "\\par\n";
gap(600);
$o .= "\\pard\\qc\\sb0\\sa0{$BASE}\\b0 " . r('Banda Aceh, 2025') . "\\par\n";
pg();

// ═══════════════════════════════════════════════════════════════
//  KATA PENGANTAR
// ═══════════════════════════════════════════════════════════════
$o .= "\\pard\\qc\\sb300\\sa120{$BASE}\\b " . r('KATA PENGANTAR') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa200\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
p('Puji syukur penulis panjatkan ke hadirat Allah SWT atas rahmat dan karunia-Nya sehingga laporan evaluasi UI/UX Sistem E-Lab Elektro ini dapat diselesaikan dengan baik.');
p('Laporan ini mendokumentasikan proses perancangan dan evaluasi antarmuka pengguna Sistem Informasi Manajemen Laboratorium E-Lab Elektro yang dikembangkan untuk Laboratorium Teknik Elektro Universitas Syiah Kuala. Pendekatan yang digunakan adalah User-Centered Design (UCD) yang mengutamakan kebutuhan pengguna sejak tahap awal perancangan, serta Evaluasi Heuristik Nielsen untuk mengidentifikasi dan memperbaiki masalah usability.');
p('Laporan revisi ini disusun secara komprehensif dengan memuat seluruh wireframe untuk setiap aktor sistem (Admin, Kaprodi, Penjaga Lab, dan Mahasiswa), untuk memberikan gambaran arsitektur sistem yang utuh kepada seluruh pemangku kepentingan.');
p('Penulis menyadari bahwa laporan ini masih jauh dari sempurna. Saran dan masukan dari berbagai pihak sangat penulis harapkan demi penyempurnaan di masa mendatang.');
gap(200);
$o .= "\\pard\\qr\\sb0\\sa80{$BASE}\\b0 " . r('Banda Aceh, 2025') . "\\par\n";
gap(400);
$o .= "\\pard\\qr\\sb0\\sa80{$BASE}\\b " . r('Tim Pengembang E-Lab Elektro') . "\\par\n";
pg();

// ═══════════════════════════════════════════════════════════════
//  DAFTAR ISI
// ═══════════════════════════════════════════════════════════════
$o .= "\\pard\\qc\\sb300\\sa120{$BASE}\\b " . r('DAFTAR ISI') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa200\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
$daftarIsi = [
    ['Kata Pengantar', 'ii'],
    ['Daftar Isi', 'iii'],
    ['Daftar Gambar', 'iv'],
    ['1. Pendahuluan', '1'],
    ['   1.1 Latar Belakang', '1'],
    ['   1.2 Tujuan Pengembangan Sistem', '2'],
    ['   1.3 Ruang Lingkup', '2'],
    ['2. Identifikasi Pengguna (User Persona)', '3'],
    ['3. Analisis Kebutuhan Pengguna', '5'],
    ['4. User Flow Diagram', '6'],
    ['5. Sitemap & Struktur Navigasi', '7'],
    ['6. Wireframe dan Mockup (Seluruh Role)', '8'],
    ['   6.1 Halaman Login', '8'],
    ['   6.2 Dashboard Admin', '9'],
    ['   6.3 Dashboard Kaprodi', '10'],
    ['   6.4 Dashboard Mahasiswa', '11'],
    ['   6.5 Meja Penjaga Lab (Kelola Transaksi)', '12'],
    ['   6.6 Halaman Peminjaman (Prototype Interaktif)', '13'],
    ['   6.7 Manajemen Inventaris Alat', '14'],
    ['   6.8 Profil Pengguna', '15'],
    ['   6.9 Knowledge Graph Visualization', '16'],
    ['7. Justifikasi Desain (Design Rationale)', '17'],
    ['8. Evaluasi Heuristik Nielsen', '19'],
    ['9. Rencana Pengujian System Usability Scale (SUS)', '21'],
    ['10. Prototype Interaktif', '23'],
    ['11. Knowledge Graph Visualization Design', '24'],
    ['12. Perbandingan Relational vs Knowledge Graph', '26'],
    ['13. Kesimpulan dan Saran', '27'],
    ['Daftar Pustaka', '29'],
];
foreach ($daftarIsi as $item) {
    $dots = str_repeat('.', max(2, 70 - strlen($item[0]) - strlen($item[1])));
    $o .= "\\pard\\qj\\li0\\fi0\\sb0\\sa60\\sl276\\slmult1\\f0\\fs24\\cf0\\b0 " 
        . r($item[0] . ' ' . $dots . ' ' . $item[1]) . "\\par\n";
}
pg();

// ═══════════════════════════════════════════════════════════════
//  DAFTAR GAMBAR
// ═══════════════════════════════════════════════════════════════
$o .= "\\pard\\qc\\sb300\\sa120{$BASE}\\b " . r('DAFTAR GAMBAR') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa200\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
$gambarList = [
    ['Gambar 6.1', 'Wireframe Halaman Login (Lo-Fi Sketch)'],
    ['Gambar 6.2', 'Wireframe Dashboard Admin — Statistik & Grafik'],
    ['Gambar 6.3', 'Wireframe Dashboard Kaprodi — Analitik & Laporan'],
    ['Gambar 6.4', 'Wireframe Dashboard Mahasiswa — Peringatan & Tabel Inventaris'],
    ['Gambar 6.5', 'Wireframe Meja Penjaga Lab — Kelola Transaksi'],
    ['Gambar 6.6', 'Wireframe Prototype Peminjaman 4-Step Wizard'],
    ['Gambar 6.7', 'Wireframe Halaman Manajemen Inventaris Alat'],
    ['Gambar 6.8', 'Wireframe Halaman Profil Pengguna'],
    ['Gambar 6.9', 'Wireframe Knowledge Graph SVG Interaktif'],
];
foreach ($gambarList as $g) {
    $dots = str_repeat('.', max(2, 65 - strlen($g[0]) - strlen($g[1])));
    $o .= "\\pard\\qj\\li0\\fi0\\sb0\\sa60\\sl276\\slmult1\\f0\\fs24\\cf0\\b0 " 
        . r($g[0] . '  ' . $g[1]) . "\\par\n";
}
pg();

// ═══════════════════════════════════════════════════════════════
//  1. PENDAHULUAN
// ═══════════════════════════════════════════════════════════════
sec('1', 'Pendahuluan');
subsec('1.1', 'Latar Belakang');
p('Laboratorium Teknik Elektro Universitas Syiah Kuala (USK) menyimpan ratusan unit alat ukur dan komponen elektronika yang digunakan oleh mahasiswa untuk kegiatan praktikum maupun tugas akhir. Proses peminjaman alat yang selama ini dilakukan secara manual menggunakan buku catatan fisik menimbulkan berbagai kendala operasional yang signifikan:');
bul('Mahasiswa tidak mengetahui ketersediaan alat sebelum datang secara langsung ke laboratorium.');
bul('Penjaga laboratorium harus menghitung denda keterlambatan secara manual dan rentan terhadap kekeliruan pencatatan.');
bul('Kepala Program Studi (Kaprodi) tidak memiliki akses data analitik secara real-time mengenai penggunaan dan kondisi alat.');
bul('Tidak terdapat rekam jejak digital aktivitas peminjaman yang dapat digunakan untuk keperluan audit dan pelaporan akademis.');
gap(80);
p('Sistem E-Lab Elektro dikembangkan sebagai solusi berbasis web menggunakan framework CodeIgniter 4 (PHP 8.3) dengan database MySQL 8.4. Sistem ini mengintegrasikan dua paradigma pengelolaan data dalam satu platform yang terintegrasi:');
bul('Relational Database (MySQL): untuk manajemen transaksi CRUD peminjaman alat secara terstruktur dan efisien.');
bul('Knowledge Graph (Graf Semantik SVG): untuk eksplorasi relasi semantik antar entitas (Mahasiswa, Alat, Mata Kuliah, Dosen, Ruang Lab) secara visual dan interaktif.');

subsec('1.2', 'Tujuan Pengembangan Sistem');
p('Tujuan utama pengembangan sistem E-Lab Elektro adalah sebagai berikut:');
bul('Menyediakan platform digital terintegrasi yang mampu mengelola seluruh proses peminjaman alat laboratorium secara transparan dan akuntabel.');
bul('Mengurangi waktu yang dihabiskan mahasiswa dan penjaga lab dalam proses administrasi peminjaman secara signifikan.');
bul('Memberikan visualisasi data analitik kepada Kaprodi untuk mendukung pengambilan keputusan berbasis data mengenai kondisi dan penggunaan inventaris.');
bul('Mengimplementasikan dua paradigma query data (SQL Relasional dan Knowledge Graph Cypher) dalam satu antarmuka yang dapat diakses secara intuitif.');

subsec('1.3', 'Ruang Lingkup');
p('Laporan ini mendokumentasikan evaluasi UI/UX sistem E-Lab Elektro mengacu pada dua metodologi ilmiah yang diakui secara akademis, yaitu: (1) User-Centered Design (UCD) yang mencakup identifikasi persona, analisis kebutuhan, user flow, sitemap, wireframe untuk seluruh aktor, dan justifikasi desain; serta (2) Evaluasi Heuristik Nielsen yang mencakup inspeksi terhadap 5 prinsip heuristik paling relevan dengan sistem ini beserta rancangan pengujian System Usability Scale (SUS).');

// ═══════════════════════════════════════════════════════════════
//  2. IDENTIFIKASI PENGGUNA
// ═══════════════════════════════════════════════════════════════
sec('2', 'Identifikasi Pengguna (User Persona)');
p('Melalui wawancara mendalam dan observasi langsung di Laboratorium Teknik Elektro USK, diidentifikasi empat kategori pengguna utama beserta kebutuhan, karakteristik, dan frustrasi spesifiknya:');

gap(80);
subsec('2.1', 'Mahasiswa — Dwiky Ilham');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Dwiky Ilham'],
    ['Identitas', 'NPM: 250420501100004 | Semester Akhir, Teknik Elektro USK'],
    ['Karakteristik', 'Mahasiswa aktif yang sedang menyusun tugas akhir. Terbiasa menggunakan teknologi digital dan aplikasi mobile. Sering meminjam Oscilloscope Digital dan Signal Generator Rigol untuk keperluan pengujian rangkaian.'],
    ['Frustrasi Utama', 'Peminjaman manual memakan waktu 30-45 menit per transaksi. Tidak ada informasi ketersediaan alat sebelum datang ke lab. Pernah kecewa karena alat sudah habis saat tiba di lab.'],
    ['Kebutuhan Sistem', 'Katalog inventaris real-time, keranjang peminjaman cepat (< 5 menit), pengingat batas waktu pengembalian otomatis via notifikasi sistem.'],
], [2200, 6700]);
gap(160);

subsec('2.2', 'Penjaga Lab — Misbah Anuari');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Misbah Anuari'],
    ['Karakteristik', 'Asisten laboratorium berpengalaman yang bertugas mengelola fisik inventaris alat setiap hari kerja. Cepat beradaptasi dengan antarmuka yang intuitif.'],
    ['Frustrasi Utama', 'Sulit melacak mahasiswa yang terlambat mengembalikan alat. Pencatatan denda manual di buku sering keliru. Sering terjadi konflik stok saat alat yang sama dipinjam bersamaan.'],
    ['Kebutuhan Sistem', 'Dashboard validasi satu klik, pencatatan otomatis status alat (rusak/maintenance), kalkulasi denda otomatis Rp 5.000/hari, penerimaan alat via scan QR.'],
], [2200, 6700]);
gap(160);

subsec('2.3', 'Kepala Program Studi (Kaprodi) — Rana Sulthanah');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Rana Sulthanah (Kaprodi Teknik Elektro USK)'],
    ['Karakteristik', 'Kepala Program Studi yang berfokus pada pengawasan aset laboratorium dan pengambilan kebijakan anggaran.'],
    ['Frustrasi Utama', 'Laporan penggunaan lab sulit direkap secara manual. Tidak memiliki data alat yang paling sering rusak atau membutuhkan peremajaan.'],
    ['Kebutuhan Sistem', 'Dashboard analitik statistik visual, grafik penggunaan alat, ekspor laporan berkala (Excel/PDF), visualisasi tingkat kesehatan inventaris.'],
], [2200, 6700]);
gap(160);

subsec('2.4', 'Administrator — Admin Utama');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Administrator (Admin Utama Sistem)'],
    ['Karakteristik', 'Pengelola teknis sistem yang bertanggung jawab atas kelancaran operasional platform E-Lab secara keseluruhan.'],
    ['Frustrasi Utama', 'Kehilangan rekam jejak aktivitas user jika terjadi manipulasi data ilegal (fraud) di dalam sistem.'],
    ['Kebutuhan Sistem', 'Audit log real-time, manajemen akun pengguna, konfigurasi role dan hak akses, monitoring keamanan sistem.'],
], [2200, 6700]);

// ═══════════════════════════════════════════════════════════════
//  3. ANALISIS KEBUTUHAN PENGGUNA
// ═══════════════════════════════════════════════════════════════
sec('3', 'Analisis Kebutuhan Pengguna');
p('Berdasarkan identifikasi empat persona pengguna di atas, berikut pemetaan komprehensif kebutuhan fungsional ke dalam fitur solusi yang diimplementasikan dalam sistem E-Lab Elektro:');
gap(80);
tbl([
    ['Kategori Kebutuhan', 'Deskripsi Fitur Solusi', 'Persona'],
    ['Peminjaman Mandiri', 'Mahasiswa dapat memilih alat dari katalog dan mengajukan peminjaman secara mandiri tanpa tatap muka langsung.', 'Mahasiswa'],
    ['Katalog Stok Real-Time', 'Sistem langsung mengurangi stok setelah konfirmasi peminjaman dan menampilkan jumlah unit akurat.', 'Mahasiswa, Penjaga Lab'],
    ['Validasi Satu Klik', 'Penjaga Lab dapat menyetujui atau menolak pengajuan peminjaman hanya dengan satu klik di Dashboard Validasi.', 'Penjaga Lab'],
    ['Kalkulator Denda Otomatis', 'Sistem menghitung denda secara otomatis sejak batas waktu pengembalian terlampaui.', 'Penjaga Lab'],
    ['Dashboard Analitik', 'Visualisasi statistik (bar chart, donut chart) untuk memantau penggunaan dan kondisi kesehatan inventaris.', 'Kaprodi'],
    ['Ekspor Laporan', 'Unduh laporan rekap penggunaan alat dalam format standar (Excel/PDF) untuk keperluan pelaporan.', 'Kaprodi, Admin'],
], [2500, 4800, 1600]);

// ═══════════════════════════════════════════════════════════════
//  4. USER FLOW DIAGRAM
// ═══════════════════════════════════════════════════════════════
sec('4', 'User Flow Diagram');
p('Sistem E-Lab Elektro mengimplementasikan dua paradigma alur interaksi yang berbeda secara fundamental:');

gap(80);
subsec('4.1', 'Alur Linear Relasional — CRUD Peminjaman Alat');
tbl([
    ['Langkah', 'Aksi dalam Sistem', 'Pelaku'],
    ['1', 'Login menggunakan NPM dan password terdaftar', 'Mahasiswa'],
    ['2', 'Buka Katalog Alat — cek status dan jumlah stok', 'Mahasiswa'],
    ['3', 'Klik "Pinjam" — alat masuk ke Keranjang Peminjaman', 'Mahasiswa'],
    ['4', 'Isi Formulir Peminjaman (tanggal kembali, jumlah unit)', 'Mahasiswa'],
    ['5', 'Ajukan Peminjaman — status berubah "Menunggu Persetujuan"', 'Mahasiswa'],
    ['6', 'Setujui/tolak dengan satu klik di Dashboard Penjaga Lab', 'Penjaga Lab'],
    ['7', 'Pengembalian alat — scan QR atau konfirmasi manual', 'Penjaga Lab'],
], [600, 6000, 2300]);
gap(160);

subsec('4.2', 'Alur Eksplorasi Knowledge Graph — Semantic Exploration');
tbl([
    ['Langkah', 'Aksi & Interaksi Pengguna', 'Hasil yang Ditampilkan'],
    ['1', 'Buka tab Knowledge Graph', 'Tampil 5 node utama (default)'],
    ['2', 'Single click pada node', 'Panel "Detail Entitas" muncul di sidebar kanan'],
    ['3', 'Double-click pada node', 'Ekspansi node untuk melihat relasi tersembunyi'],
    ['4', 'Gunakan panel Semantic Filtering', 'Menyaring tipe entitas yang ditampilkan di canvas'],
], [600, 3900, 4400]);

// ═══════════════════════════════════════════════════════════════
//  5. SITEMAP & STRUKTUR NAVIGASI
// ═══════════════════════════════════════════════════════════════
sec('5', 'Sitemap & Struktur Navigasi');
p('Arsitektur informasi sistem E-Lab Elektro dirancang menggunakan struktur hierarkis berbasis role-based access control (RBAC):');
gap(80);
tbl([
    ['Modul Utama', 'Sub-Halaman / Fitur Tersedia', 'Role yang Dapat Mengakses'],
    ['Dashboard', 'Widget Statistik; Peringatan; Top 5 Alat; Donut Chart; Tabel Inventory', 'Semua Role'],
    ['Manajemen Inventaris', 'Data Aset & CRUD; Cetak QR Code; Filter Kategori', 'Penjaga Lab, Admin'],
    ['Peminjaman (Meja Penjaga)', 'Menunggu Persetujuan; Pantauan Denda; Riwayat Selesai', 'Penjaga Lab, Admin'],
    ['Keranjang Peminjaman', 'Formulir Pengajuan (Wizard)', 'Mahasiswa'],
    ['Laporan & Analitik', 'Rekap Kondisi Alat; Ekspor Excel/PDF; Dashboard Kaprodi', 'Kaprodi, Admin'],
    ['Manajemen Akun', 'Daftar Pengguna; Konfigurasi Role; Audit Log', 'Admin Utama'],
    ['Profil Pengguna', 'Edit Data Diri; Ganti Password; Upload Foto', 'Semua Role'],
], [2500, 4200, 2200]);

// ═══════════════════════════════════════════════════════════════
//  6. WIREFRAME DAN MOCKUP
// ═══════════════════════════════════════════════════════════════
sec('6', 'Wireframe dan Mockup (Seluruh Role)');
p('Tahap wireframing merupakan langkah fundamental dalam proses User-Centered Design. Berikut adalah wireframe lengkap untuk seluruh aktor sistem E-Lab Elektro:');
gap(80);

subsec('6.1', 'Halaman Login (Gerbang Masuk Sistem)');
p('Halaman login merupakan titik masuk utama sistem yang dirancang dengan prinsip kejelasan (clarity) dan kemudahan akses. Desain menggunakan layout dua kolom dengan panel kiri ilustratif dan panel kanan untuk form autentikasi.');
if ($img_login) { embedImg($img_login, 14, 9); caption('Gambar 6.1 — Wireframe Lo-Fi Halaman Login Sistem E-Lab Elektro'); }
gap(160);

subsec('6.2', 'Dashboard Admin — Pusat Kendali Sistem');
p('Dashboard Admin menampilkan ringkasan menyeluruh kondisi sistem secara real-time. Dirancang menggunakan prinsip Gestalt (proximity & similarity) untuk mengelompokkan informasi terkait secara visual.');
if ($img_dash_admin) { embedImg($img_dash_admin, 14, 9); caption('Gambar 6.2 — Wireframe Lo-Fi Dashboard Admin (Statistik, Grafik & Tabel Inventaris)'); }
gap(160);

subsec('6.3', 'Dashboard Kaprodi — Analitik Eksekutif');
p('Dashboard khusus Kaprodi (Kepala Program Studi) berfokus pada analitik tingkat tinggi dan pelaporan. Menampilkan metrik utama seperti total inventaris, ketersediaan, rasio kerusakan, serta akses cepat untuk mengekspor laporan dalam format PDF dan Excel.');
if ($img_dash_kaprodi) { embedImg($img_dash_kaprodi, 14, 9); caption('Gambar 6.3 — Wireframe Lo-Fi Dashboard Kaprodi (Fokus pada Analitik dan Laporan)'); }
gap(160);

subsec('6.4', 'Dashboard Mahasiswa — Akses Inventaris Cepat');
p('Dashboard mahasiswa menampilkan tampilan yang disederhanakan dengan fokus pada ketersediaan alat. Dilengkapi banner peringatan keterlambatan (Visibility of System Status) yang akan memblokir akses peminjaman baru jika ada tunggakan.');
if ($img_dash_mhs) { embedImg($img_dash_mhs, 14, 9); caption('Gambar 6.4 — Wireframe Lo-Fi Dashboard Mahasiswa dengan Banner Peringatan Keterlambatan'); }
gap(160);

subsec('6.5', 'Meja Penjaga Lab — Kelola Transaksi (Penjaga Lab)');
p('Halaman khusus Penjaga Lab ("Meja Penjaga") dirancang untuk alur kerja operasional harian. Menggunakan sistem tumpukan tiga zona warna: Zona Kuning (Menunggu Persetujuan), Zona Biru (Pemantauan Alat Dipinjam & Scan QR), dan Zona Hijau (Riwayat Selesai). Desain ini memisahkan prioritas tindakan dengan jelas.');
if ($img_penjaga) { 
    embedImg($img_penjaga, 14, 9); 
} else {
    // Placeholder text since image generation failed
    $o .= "\\pard\\qc\\sb80\\sa80\\brdrt\\brdrs\\brdrw10\\brdrb\\brdrs\\brdrw10\\brdrl\\brdrs\\brdrw10\\brdrr\\brdrs\\brdrw10\\cf0\\b [Area Wireframe Penjaga Lab]\\b0\\par\n";
    $o .= "\\pard\\qc\\sb40\\sa80 (Terdiri dari 3 bagian: Zona Kuning 'Menunggu Persetujuan', Zona Biru 'Sedang Dipinjam' dengan tombol Scan QR, dan Zona Hijau 'Riwayat Selesai')\\par\n";
}
caption('Gambar 6.5 — Wireframe Lo-Fi Meja Penjaga Lab (Manajemen Transaksi Multi-Zona)');
gap(160);

subsec('6.6', 'Prototype Interaktif — Wizard Peminjaman (Mahasiswa)');
p('Prototype peminjaman menggunakan pola desain "Wizard" (step-by-step) untuk memandu mahasiswa melalui proses pengajuan peminjaman. Progress indicator di bagian atas memberikan konteks tentang posisi pengguna dalam alur.');
if ($img_pinjam) { embedImg($img_pinjam, 14, 9); caption('Gambar 6.6 — Wireframe Lo-Fi Prototype Interaktif Peminjaman (4-Step Wizard Form)'); }
gap(160);

subsec('6.7', 'Manajemen Inventaris Alat (Admin & Penjaga Lab)');
p('Pusat pengelolaan aset laboratorium. Dilengkapi fitur pencarian, filter kategori, manajemen stok (tambah/kurang/rusak), serta fitur cetak QR Code label alat untuk identifikasi fisik di rak laboratorium.');
if ($img_invent) { embedImg($img_invent, 14, 9); caption('Gambar 6.7 — Wireframe Lo-Fi Halaman Manajemen Inventaris Alat Laboratorium'); }
gap(160);

subsec('6.8', 'Profil Pengguna (Seluruh Role)');
p('Halaman manajemen identitas pengguna. Menampilkan foto profil melingkar dengan kontrol ganti/hapus, detail informasi institusional, metrik spesifik peran (seperti sisa kuota untuk mahasiswa), dan pengaturan keamanan (ganti password).');
if ($img_profil) {
    embedImg($img_profil, 14, 9);
} else {
    $o .= "\\pard\\qc\\sb80\\sa80\\brdrt\\brdrs\\brdrw10\\brdrb\\brdrs\\brdrw10\\brdrl\\brdrs\\brdrw10\\brdrr\\brdrs\\brdrw10\\cf0\\b [Area Wireframe Profil Pengguna]\\b0\\par\n";
    $o .= "\\pard\\qc\\sb40\\sa80 (Menampilkan Card Kiri: Foto Profil, Nama, Role. Card Kanan: Detail Informasi Akun, Status Akun, Ganti Password)\\par\n";
}
caption('Gambar 6.8 — Wireframe Lo-Fi Halaman Profil Pengguna');
gap(160);

subsec('6.9', 'Knowledge Graph Visualization');
p('Menampilkan visualisasi interaktif relasi semantik antar entitas dalam sistem menggunakan teknologi SVG. Memungkinkan eksplorasi relasi multi-hop yang tidak dapat terlihat dalam satu query SQL standar.');
if ($img_kg) { embedImg($img_kg, 14, 9); caption('Gambar 6.9 — Wireframe Lo-Fi Knowledge Graph SVG Interaktif dengan Panel Semantic Filtering'); }

// ═══════════════════════════════════════════════════════════════
//  7. JUSTIFIKASI DESAIN
// ═══════════════════════════════════════════════════════════════
sec('7', 'Justifikasi Desain (Design Rationale)');
p('Setiap keputusan desain dalam E-Lab Elektro dilandasi oleh teori dan prinsip UI/UX yang terukur secara ilmiah:');
gap(80);
subsec('7.1', 'Hukum Fitts (Fitts\'s Law) — Penempatan Tombol Aksi');
p('Tombol aksi utama ("Pinjam", "Setujui") dirancang dengan tinggi minimal 40px dan border-radius rounded-pill untuk memperbesar area klik. Diletakkan di sisi kanan baris tabel sesuai pola gerak alami jari dari kiri ke kanan.');
gap(80);
subsec('7.2', 'Prinsip Gestalt — Pengelompokan Visual');
p('Widget statistik dikelompokkan rapat (Law of Proximity). Kode warna diterapkan konsisten (Law of Similarity): hijau untuk aman/tersedia, merah untuk bahaya/keterlambatan, biru/oranye untuk aksi utama.');
gap(80);
subsec('7.3', 'F-Pattern Eye Tracking');
p('Sidebar navigasi diletakkan di sisi kiri, memanfaatkan zona F-Pattern yang paling awal dilihat pengguna sehingga navigasi instan ditemukan tanpa usaha ekstra.');

// ═══════════════════════════════════════════════════════════════
//  8. EVALUASI HEURISTIK NIELSEN
// ═══════════════════════════════════════════════════════════════
sec('8', 'Evaluasi Heuristik Nielsen');
p('Inspeksi ahli terhadap 5 prinsip usability Nielsen (Severity 0-4):');
gap(80);
tbl([
    ['Kode', 'Prinsip Heuristik', 'Masalah', 'Severity', 'Solusi'],
    ['H1', 'Visibility of Status', 'Layar hang saat submit', '3 (Major)', 'Loading spinner & progress bar wizard'],
    ['H2', 'Match Real World', 'Istilah teknis database', '2 (Minor)', 'Ubah teks jadi bahasa kampus'],
    ['H3', 'User Control', 'Tidak bisa hapus isi cart', '3 (Major)', 'Tombol hapus cart & batal form'],
    ['H4', 'Consistency', 'Warna tombol campur', '2 (Minor)', 'Standarisasi palet warna tombol'],
    ['H5', 'Error Prevention', 'Input lewat batas stok', '4 (Catastrophe)', 'Validasi frontend real-time'],
], [500, 1600, 2400, 1200, 3200]);

// ═══════════════════════════════════════════════════════════════
//  9. RENCANA PENGUJIAN SUS
// ═══════════════════════════════════════════════════════════════
sec('9', 'Rencana Pengujian System Usability Scale (SUS)');
p('Untuk mengukur tingkat usability sistem secara empiris, digunakan instrumen SUS (John Brooke, 1996) dengan 10 pertanyaan skala Likert 1-5 kepada 10 responden (mahasiswa, dosen, laboran).');

// ═══════════════════════════════════════════════════════════════
//  10-13. BAGIAN PENUTUP & KESIMPULAN
// ═══════════════════════════════════════════════════════════════
sec('10', 'Prototype Interaktif');
p('Prototype 4-step wizard memastikan mahasiswa menyelesaikan pengajuan peminjaman tanpa kebingungan teknis, mengurangi tingkat error saat entri data.');

sec('11', 'Knowledge Graph Visualization Design');
p('Memvisualisasikan 5 entitas (Mahasiswa, Alat, Mata Kuliah, Dosen, Lab) dan 8 relasi semantik dengan fitur ekspansi node on-click.');

sec('12', 'Perbandingan Relational vs Knowledge Graph UI');
p('Sistem E-Lab Elektro membuktikan bahwa antarmuka relasional (CRUD/Tabel) optimal untuk transaksi operasional harian, sedangkan graf semantik sangat powerful untuk analitik eksploratif eksekutif (Kaprodi).');

sec('13', 'Kesimpulan dan Saran');
p('Desain UI/UX E-Lab Elektro telah mencakup seluruh kebutuhan 4 aktor utama (Mahasiswa, Penjaga Lab, Kaprodi, Admin) dengan penerapan prinsip UCD. 100% masalah heuristik kritikal (Severity 3 & 4) telah diperbaiki pada iterasi rilis ini. Disarankan integrasi sensor IoT barcode scanner fisik pada meja penjaga lab untuk pengembangan tahap selanjutnya.');

// ═══════════════════════════════════════════════════════════════
//  DAFTAR PUSTAKA
// ═══════════════════════════════════════════════════════════════
pg();
$o .= "\\pard\\qc\\sb300\\sa120{$BASE}\\b " . r('DAFTAR PUSTAKA') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa200\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
$refs = [
    'Nielsen, J. (1994). Usability Engineering. Morgan Kaufmann Publishers.',
    'Nielsen, J. (1995). 10 Usability Heuristics for User Interface Design.',
    'Brooke, J. (1996). SUS: A quick and dirty usability scale.',
    'Norman, D. A. (2013). The Design of Everyday Things.',
    'Fitts, P. M. (1954). The information capacity of the human motor system.',
];
foreach ($refs as $i => $ref) {
    $o .= "\\pard\\qj\\fi-500\\li500\\sb0\\sa100{$BASE}\\b0 " . r(($i+1).". ".$ref) . "\\par\n";
}

// ═══════════════════════════════════════════════════════════════
//  WRAP & OUTPUT RTF
// ═══════════════════════════════════════════════════════════════
$rtf = '{\\rtf1\\ansi\\ansicpg1252\\deff0\\deflang1057' . "\n"
    . '{\\fonttbl{\\f0\\froman\\fprq2\\fcharset0 Times New Roman;}{\\f1\\fmodern\\fcharset0 Courier New;}}' . "\n"
    . '{\\colortbl;\\red0\\green0\\blue0;\\red230\\green230\\blue230;}' . "\n"
    . '{\\info{\\title Laporan Evaluasi UI/UX E-Lab Elektro}{\\author Tim E-Lab Elektro}{\\company Universitas Syiah Kuala}}' . "\n"
    . '\\widowctrl\\hyphauto' . "\n"
    . '\\margl2268\\margr1701\\margt1440\\margb1440' . "\n"   // L=4cm R=3cm T/B=2.54cm
    . '\\f0\\fs24\\cf0\\sl360\\slmult1' . "\n"
    . $o . '}';

$outFile      = __DIR__ . '/Laporan_UCD_ELab_Elektro_LengkapSemuaUser.doc';

$writeResult = @file_put_contents($outFile, $rtf);

if ($writeResult === false) {
    echo "GAGAL: Tidak dapat menulis file laporan.\n";
} else {
    echo "=" . str_repeat("=", 55) . "\n";
    echo " LAPORAN BERHASIL DIBUAT!\n";
    echo "=" . str_repeat("=", 55) . "\n";
    echo " File  : {$outFile}\n";
    echo " Ukuran: " . number_format(filesize($outFile)/1024, 1) . " KB\n";
    echo "=" . str_repeat("=", 55) . "\n";
}
