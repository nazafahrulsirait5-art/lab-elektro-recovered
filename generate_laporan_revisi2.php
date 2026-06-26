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
        $o .= "\\pard\\qc\\sb80\\sa80 [Gambar tidak ditemukan: " . r(basename($path)) . "]\\par\n";
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
$img_pinjam     = findLatestPng($IMG_DIR, 'wireframe_peminjaman_form_');
$img_kg         = findLatestPng($IMG_DIR, 'wireframe_knowledge_graph_');
$img_invent     = findLatestPng($IMG_DIR, 'wireframe_inventaris_alat_');

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
$o .= "\\pard\\qc\\sb0\\sa80{$BASE}\\b0 " . r('Pendekatan User-Centered Design (UCD) dan Evaluasi Heuristik Nielsen') . "\\par\n";
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
p('Laporan ini disusun sebagai bagian dari dokumentasi akademis pengembangan sistem dan diharapkan dapat memberikan gambaran komprehensif mengenai alur interaksi, struktur navigasi, wireframe antarmuka, dan hasil evaluasi usability kepada seluruh pemangku kepentingan.');
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
    ['6. Wireframe dan Mockup', '8'],
    ['   6.1 Halaman Login', '8'],
    ['   6.2 Dashboard Admin', '9'],
    ['   6.3 Dashboard Mahasiswa', '10'],
    ['   6.4 Halaman Peminjaman (Prototype Interaktif)', '11'],
    ['   6.5 Manajemen Inventaris Alat', '12'],
    ['   6.6 Knowledge Graph Visualization', '13'],
    ['7. Justifikasi Desain (Design Rationale)', '14'],
    ['8. Evaluasi Heuristik Nielsen', '16'],
    ['9. Rencana Pengujian System Usability Scale (SUS)', '18'],
    ['10. Prototype Interaktif', '20'],
    ['11. Knowledge Graph Visualization Design', '21'],
    ['12. Perbandingan Relational vs Knowledge Graph', '23'],
    ['13. Kesimpulan dan Saran', '24'],
    ['Daftar Pustaka', '26'],
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
    ['Gambar 6.3', 'Wireframe Dashboard Mahasiswa — Peringatan & Tabel Inventaris'],
    ['Gambar 6.4', 'Wireframe Prototype Peminjaman 4-Step Wizard'],
    ['Gambar 6.5', 'Wireframe Halaman Manajemen Inventaris Alat'],
    ['Gambar 6.6', 'Wireframe Knowledge Graph SVG Interaktif'],
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
p('Laporan ini mendokumentasikan evaluasi UI/UX sistem E-Lab Elektro mengacu pada dua metodologi ilmiah yang diakui secara akademis, yaitu: (1) User-Centered Design (UCD) yang mencakup identifikasi persona, analisis kebutuhan, user flow, sitemap, wireframe, dan justifikasi desain; serta (2) Evaluasi Heuristik Nielsen yang mencakup inspeksi terhadap 5 prinsip heuristik paling relevan dengan sistem ini beserta rancangan pengujian System Usability Scale (SUS).');

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
    ['Fitur Utama', 'Katalog Alat, Keranjang Peminjaman (Shopping Cart), Prototype Interaktif 4-Step, Riwayat Pinjam'],
], [2200, 6700]);
gap(160);

subsec('2.2', 'Penjaga Lab — Misbah Anuari');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Misbah Anuari (Dr. Misbah Anuari — Dosen Pembimbing di Knowledge Graph)'],
    ['Karakteristik', 'Asisten laboratorium berpengalaman yang bertugas mengelola fisik inventaris alat setiap hari kerja. Tidak terlalu fasih teknologi namun cepat beradaptasi dengan antarmuka yang intuitif.'],
    ['Frustrasi Utama', 'Sulit melacak mahasiswa yang terlambat mengembalikan alat. Pencatatan denda manual di buku sering keliru dan memakan waktu. Sering terjadi konflik stok saat alat yang sama dipinjam bersamaan oleh beberapa mahasiswa.'],
    ['Kebutuhan Sistem', 'Dashboard validasi satu klik, pencatatan otomatis status alat (rusak/maintenance), kalkulasi denda otomatis Rp 5.000/hari, notifikasi real-time keterlambatan.'],
    ['Fitur Utama', 'Dashboard Validasi, Manajemen Status Alat, Kalkulasi & Rekap Denda, Tabel Overdue'],
], [2200, 6700]);
gap(160);

subsec('2.3', 'Kepala Program Studi (Kaprodi) — Rana Sulthanah');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Rana Sulthanah (Kaprodi Teknik Elektro USK)'],
    ['Karakteristik', 'Kepala Program Studi yang berfokus pada pengawasan aset laboratorium dan pengambilan kebijakan anggaran. Membutuhkan laporan berkala untuk keperluan akreditasi dan evaluasi program studi.'],
    ['Frustrasi Utama', 'Laporan penggunaan lab sulit direkap secara manual. Tidak memiliki data alat yang paling sering rusak atau membutuhkan peremajaan. Rekap data untuk keperluan akreditasi sangat menyita waktu.'],
    ['Kebutuhan Sistem', 'Dashboard analitik statistik visual, grafik penggunaan alat, ekspor laporan berkala (Excel/PDF), visualisasi tingkat kesehatan inventaris secara komprehensif.'],
    ['Fitur Utama', 'Dashboard Analitik, Bar Chart Top 5 Alat, Donut Chart Kesehatan, Ekspor Excel/PDF, Laporan Overdue'],
], [2200, 6700]);
gap(160);

subsec('2.4', 'Administrator — Admin Utama');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Administrator (Admin Utama Sistem)'],
    ['Karakteristik', 'Pengelola teknis sistem yang bertanggung jawab atas kelancaran operasional platform E-Lab secara keseluruhan, termasuk manajemen akun, konfigurasi role, dan pemantauan keamanan sistem.'],
    ['Frustrasi Utama', 'Kehilangan rekam jejak aktivitas user jika terjadi manipulasi data ilegal (fraud) atau penghapusan transaksi yang tidak sah di dalam sistem.'],
    ['Kebutuhan Sistem', 'Audit log real-time (mencatat siapa melakukan apa dan kapan), manajemen akun pengguna, konfigurasi role dan hak akses, monitoring keamanan sistem.'],
    ['Fitur Utama', 'Audit Log, Manajemen Akun, Konfigurasi Role, Monitoring Sistem, Backup Data'],
], [2200, 6700]);

// ═══════════════════════════════════════════════════════════════
//  3. ANALISIS KEBUTUHAN PENGGUNA
// ═══════════════════════════════════════════════════════════════
sec('3', 'Analisis Kebutuhan Pengguna');
p('Berdasarkan identifikasi empat persona pengguna di atas, berikut pemetaan komprehensif kebutuhan fungsional ke dalam fitur solusi yang diimplementasikan dalam sistem E-Lab Elektro:');
gap(80);
tbl([
    ['Kategori Kebutuhan', 'Deskripsi Fitur Solusi', 'Persona', 'Prioritas'],
    ['Peminjaman Mandiri (Paperless)', 'Mahasiswa dapat memilih alat dari katalog, memasukkan ke keranjang, dan mengajukan peminjaman secara mandiri tanpa tatap muka langsung dengan penjaga lab.', 'Mahasiswa (Dwiky)', 'Critical'],
    ['Katalog Stok Real-Time', 'Sistem langsung mengurangi stok setelah konfirmasi peminjaman dan menampilkan jumlah unit yang benar-benar tersedia di rak lab secara akurat.', 'Mahasiswa, Penjaga Lab', 'Critical'],
    ['Validasi Satu Klik', 'Penjaga Lab dapat menyetujui atau menolak pengajuan peminjaman hanya dengan satu klik di Dashboard Validasi tanpa proses tatap muka terlebih dahulu.', 'Penjaga Lab (Misbah)', 'Critical'],
    ['Kalkulator Denda Otomatis', 'Sistem menghitung denda secara presisi (Rp 5.000/hari terlambat) secara otomatis sejak batas waktu pengembalian terlampaui, mengurangi risiko kekeliruan manual.', 'Penjaga Lab (Misbah)', 'High'],
    ['Dashboard Analitik Visual', 'Visualisasi statistik (bar chart, donut chart) untuk Kaprodi dalam memantau penggunaan dan kondisi kesehatan inventaris laboratorium secara real-time.', 'Kaprodi (Rana)', 'High'],
    ['Ekspor Laporan (Excel/PDF)', 'Kaprodi dan Admin dapat mengunduh laporan rekap penggunaan alat dalam format standar untuk keperluan pelaporan dan akreditasi program studi.', 'Kaprodi, Admin', 'High'],
    ['Audit Log Keamanan', 'Mencatat setiap tindakan penting (manipulasi stok, penghapusan transaksi, perubahan role) dalam tabel audit log khusus untuk keamanan dan transparansi sistem.', 'Admin', 'Medium'],
    ['Knowledge Graph Semantik', 'Visualisasi relasi antar entitas (Mahasiswa, Alat, MK, Dosen, Lab) dalam graf interaktif SVG untuk eksplorasi data mendalam melampaui kemampuan tabel SQL.', 'Kaprodi, Admin', 'Medium'],
], [2500, 3800, 1600, 1000]);

// ═══════════════════════════════════════════════════════════════
//  4. USER FLOW DIAGRAM
// ═══════════════════════════════════════════════════════════════
sec('4', 'User Flow Diagram');
p('Sistem E-Lab Elektro mengimplementasikan dua paradigma alur interaksi yang berbeda secara fundamental, masing-masing dirancang untuk memenuhi kebutuhan pengguna yang berbeda:');

gap(80);
subsec('4.1', 'Alur Linear Relasional — CRUD Peminjaman Alat');
p('Alur ini mengikuti pola linear yang sederhana dan mudah diprediksi, dirancang untuk efisiensi transaksi peminjaman sehari-hari:');
gap(80);
tbl([
    ['Langkah', 'Aksi dalam Sistem', 'Pelaku', 'Output Sistem'],
    ['1', 'Login menggunakan NPM dan password yang telah terdaftar', 'Mahasiswa', 'Session aktif, redirect ke Dashboard'],
    ['2', 'Buka Katalog Alat — cek status dan jumlah stok tersedia di rak', 'Mahasiswa', 'Daftar alat dengan badge status (Tersedia/Terbatas/Habis)'],
    ['3', 'Klik "Pinjam" — alat masuk ke Keranjang Peminjaman', 'Mahasiswa', 'Item ditambahkan ke cart, counter keranjang bertambah'],
    ['4', 'Isi Formulir Peminjaman (tanggal kembali, jumlah unit)', 'Mahasiswa', 'Validasi frontend: cek stok & NPM; tombol Ajukan aktif/nonaktif'],
    ['5', 'Ajukan Peminjaman — status berubah "Menunggu Persetujuan"', 'Mahasiswa → Sistem', 'Notifikasi terkirim ke Penjaga Lab, stok berkurang sementara'],
    ['6', 'Penjaga Lab setujui/tolak dengan satu klik di Dashboard', 'Penjaga Lab (Misbah)', 'Status: "Dipinjam" atau "Ditolak"; stok dikonfirmasi/dikembalikan'],
    ['7', 'Pengembalian alat — Penjaga Lab konfirmasi di sistem', 'Penjaga Lab & Sistem', 'Status: "Dikembalikan"; denda kalkulasi otomatis jika terlambat'],
], [600, 3000, 1800, 3500]);
gap(160);

subsec('4.2', 'Alur Eksplorasi Knowledge Graph — Semantic Exploration');
p('Alur ini bersifat non-linear dan exploratory, memungkinkan pengguna berwenang untuk menemukan wawasan dari relasi antar data yang tidak dapat terlihat di tampilan tabel biasa:');
gap(80);
tbl([
    ['Langkah', 'Aksi & Interaksi Pengguna', 'Hasil yang Ditampilkan'],
    ['1', 'Buka tab Knowledge Graph — SVG graf dimuat dengan 5 node utama (default)', 'Tampil: Dwiky (N1), Oscilloscope (N2), Prak.Mikro (N3), Dr.Misbah (N4), Lab Tele (N5)'],
    ['2', 'Single click pada node — contoh klik node Dwiky (N1)', 'Panel "Detail Entitas Semantik" muncul di sidebar kanan dengan atribut lengkap node'],
    ['3', 'Double-click pada node N1 (Dwiky)', 'Node N7 (Naza Fahrul) muncul dengan edge berlabel REKAN_KLP berwarna biru'],
    ['4', 'Double-click pada node N2 (Oscilloscope)', 'Node N6 (Signal Generator Rigol) muncul dengan edge berlabel TERHUBUNG berwarna abu'],
    ['5', 'Gunakan panel Semantic Filtering — uncheck tipe entitas tertentu', '5 checkbox filter: Mahasiswa, Alat, Mata Kuliah, Dosen, Ruang Lab'],
    ['6', 'Klik tombol "Reset Grafik" di sudut kanan atas panel', 'Node N6 & N7 disembunyikan kembali, seluruh filter dikembalikan ke kondisi aktif (checked)'],
], [600, 3900, 4400]);

// ═══════════════════════════════════════════════════════════════
//  5. SITEMAP & STRUKTUR NAVIGASI
// ═══════════════════════════════════════════════════════════════
sec('5', 'Sitemap & Struktur Navigasi');
p('Arsitektur informasi sistem E-Lab Elektro dirancang menggunakan struktur hierarkis berbasis role-based access control (RBAC). Setiap modul memiliki batasan akses yang ketat sesuai peran pengguna yang telah ditetapkan:');
gap(80);
tbl([
    ['Modul Utama', 'Sub-Halaman / Fitur Tersedia', 'Role yang Dapat Mengakses'],
    ['Gerbang Masuk (Auth)', 'Login dengan CAPTCHA verifikasi; Registrasi Mahasiswa Baru; Lupa Sandi & Reset Token via Email', 'Publik (Semua Pengguna)'],
    ['Dashboard Utama', 'Widget Statistik Ringkas (4 kartu); Peringatan Keterlambatan (alert merah); Top 5 Alat Populer (Bar Chart); Donut Chart Kesehatan Inventaris; Tabel Current Inventory', 'Semua Role (konten berbeda per role)'],
    ['UCD Showcase & Demo', '6 Tab: Laporan UCD; Alur & Sitemap; Wireframe vs Mockup; Evaluasi Heuristik Nielsen; Knowledge Graph SVG; Prototype Interaktif 4-Step', 'Semua Role'],
    ['Manajemen Inventaris', 'Data Aset & CRUD (Tambah/Edit/Hapus); Cetak & Scan QR Code Label Alat; Filter Kategori & Pencarian Alat', 'Penjaga Lab, Admin'],
    ['Transaksi Peminjaman', 'Keranjang Belanja Alat (Shopping Cart); Formulir Pengajuan; Validasi & Persetujuan; Rekap Denda & Pengembalian; Riwayat Transaksi Lengkap', 'Mahasiswa, Penjaga Lab, Admin'],
    ['Laporan & Analitik', 'Dashboard Statistik Kaprodi; Rekap Kondisi Alat; Ekspor Excel/PDF; Grafik Penggunaan Historis', 'Kaprodi, Admin'],
    ['Manajemen Akun', 'Daftar Pengguna (User List); Edit Profil; Konfigurasi Role & Hak Akses; Audit Log Aktivitas Sistem', 'Admin Utama'],
    ['Profil Pengguna', 'Edit Data Diri; Ganti Password; Upload Foto Profil', 'Semua Role'],
], [2500, 4200, 2200]);

// ═══════════════════════════════════════════════════════════════
//  6. WIREFRAME DAN MOCKUP
// ═══════════════════════════════════════════════════════════════
sec('6', 'Wireframe dan Mockup');
p('Tahap wireframing merupakan langkah fundamental dalam proses User-Centered Design untuk mengkomunikasikan layout, hierarki informasi, dan alur navigasi kepada seluruh pemangku kepentingan sebelum implementasi antarmuka final dilakukan. Sistem E-Lab Elektro menggunakan pendekatan Lo-Fi Wireframe (sketsa hitam-putih) sebagai dasar perancangan, yang kemudian diterjemahkan ke dalam Hi-Fi Mockup (desain penuh warna dengan aset visual nyata).');
gap(80);

subsec('6.1', 'Halaman Login (Gerbang Masuk Sistem)');
p('Halaman login merupakan titik masuk utama sistem yang dirancang dengan prinsip kejelasan (clarity) dan kemudahan akses. Desain menggunakan layout dua kolom: panel kiri berisi ilustrasi dekoratif universitas, panel kanan berisi form autentikasi. Fitur CAPTCHA diterapkan sebagai lapisan keamanan tambahan untuk mencegah serangan brute-force.');
gap(80);

if ($img_login) {
    embedImg($img_login, 14, 9);
    caption('Gambar 6.1 — Wireframe Lo-Fi Halaman Login Sistem E-Lab Elektro');
}
gap(80);

tbl([
    ['Komponen UI', 'Deskripsi & Justifikasi Desain'],
    ['Logo & Judul Sistem', 'Ditempatkan di bagian atas card untuk membangun identitas merek sistem yang kuat (Brand Identity)'],
    ['Input NPM/Username', 'Label jelas di atas field, placeholder teks panduan, border dengan focus state yang terlihat jelas'],
    ['Input Password', 'Tombol show/hide mata di sisi kanan untuk meningkatkan kenyamanan pengguna saat memasukkan sandi'],
    ['CAPTCHA Verifikasi', 'Kotak verifikasi dengan tombol refresh, mencegah serangan brute-force pada sistem autentikasi'],
    ['Tombol "Masuk"', 'Full-width, pill shape, warna oranye (#ea580c) — sesuai standar aksi primer sistem'],
    ['Link Bantu', '"Lupa Password?" dan "Belum punya akun? Daftar" — mengurangi hambatan akses bagi pengguna baru'],
], [2500, 6400]);
gap(160);

subsec('6.2', 'Dashboard Admin — Statistik & Analitik');
p('Dashboard Admin merupakan halaman utama setelah login yang menampilkan ringkasan menyeluruh kondisi sistem secara real-time. Dirancang menggunakan prinsip Gestalt (proximity & similarity) untuk mengelompokkan informasi terkait secara visual.');
gap(80);

if ($img_dash_admin) {
    embedImg($img_dash_admin, 14, 9);
    caption('Gambar 6.2 — Wireframe Lo-Fi Dashboard Admin (Statistik, Grafik & Tabel Inventaris)');
}
gap(80);

tbl([
    ['Area Dashboard', 'Konten & Data Sumber'],
    ['Header Navigasi (Topbar)', 'Logo "E-Lab Elektro", tombol toggle sidebar, nama & role pengguna, tombol notifikasi, avatar foto profil'],
    ['Sidebar Kiri (250px)', 'Menu: Dashboard, Inventaris Alat, Peminjaman, Laporan, Manajemen Akun, Profil — dengan ikon Font Awesome 6'],
    ['Baris Stat Card (4 kartu)', 'Total Inventaris (kotak ikon oranye), Menunggu Persetujuan (segitiga warning merah), Peminjaman Aktif (cart oranye), Total Mahasiswa (flask oranye) — data langsung dari database MySQL'],
    ['Bar Chart (75% lebar)', '"Top 5 Alat Paling Sering Dipinjam" menggunakan Chart.js, warna biru (#3b82f6), animasi smooth loading'],
    ['Donut Chart (25% lebar)', '"Kesehatan Inventaris" — Bagus (hijau emerald #10b981) vs Rusak (merah #ef4444), angka total di tengah'],
    ['Tabel Overdue (Admin)', 'Daftar mahasiswa terlambat: Nama, Alat, Tanggal Seharusnya, Lama Telat, Estimasi Denda — header merah'],
    ['Tabel Current Inventory', 'Semua alat dengan kolom: Item Name, Manufacturer, Qty, Status Badge, tombol Kelola — infinite scroll'],
], [2800, 6100]);
gap(160);

subsec('6.3', 'Dashboard Mahasiswa — Peminjaman & Peringatan');
p('Dashboard mahasiswa menampilkan tampilan yang disederhanakan dengan fokus pada ketersediaan alat dan tombol aksi peminjaman yang mudah dijangkau. Peringatan keterlambatan ditampilkan dengan visual menonjol di bagian paling atas konten untuk memastikan mahasiswa tidak melewatkannya (prinsip Visibility of System Status).');
gap(80);

if ($img_dash_mhs) {
    embedImg($img_dash_mhs, 14, 9);
    caption('Gambar 6.3 — Wireframe Lo-Fi Dashboard Mahasiswa dengan Banner Peringatan Keterlambatan');
}
gap(80);

tbl([
    ['Komponen Eksklusif Mahasiswa', 'Deskripsi'],
    ['Banner Peringatan Merah', 'Tampil otomatis jika ada alat yang terlambat dikembalikan. Gradien merah gelap, ikon segitiga dengan animasi pulse, teks peringatan, tombol "Cek Riwayat & Denda" berwarna kuning'],
    ['Ikon Keranjang (Cart Badge)', 'Di sudut kanan topbar — menampilkan jumlah item di keranjang secara real-time. Klik untuk buka panel keranjang'],
    ['Tombol "Pinjam" per Alat', 'Tombol pill oranye di kolom Actions setiap baris alat. Otomatis berubah menjadi "Dibekukan" (disabled, abu) saat ada tunggakan keterlambatan aktif'],
    ['Filter Status Alat', 'Badge Tersedia (hijau), Terbatas (kuning), Habis (merah) — visual langsung di kolom Status tabel inventaris'],
], [3000, 5900]);
gap(160);

subsec('6.4', 'Prototype Interaktif — Wizard Peminjaman 4 Langkah');
p('Prototype peminjaman menggunakan pola desain "Wizard" (step-by-step) untuk memandu mahasiswa melalui proses pengajuan peminjaman yang kompleks menjadi lebih mudah dan tidak membingungkan. Progress indicator di bagian atas memberikan konteks tentang posisi pengguna dalam alur (prinsip Visibility of System Status — H1 Nielsen).');
gap(80);

if ($img_pinjam) {
    embedImg($img_pinjam, 14, 9);
    caption('Gambar 6.4 — Wireframe Lo-Fi Prototype Interaktif Peminjaman (4-Step Wizard Form)');
}
gap(80);

tbl([
    ['Step', 'Nama Layar', 'Elemen UI Utama', 'Validasi yang Diterapkan'],
    ['Step 1', 'Pilih Alat', 'Alert info sambutan, daftar alat dengan nama, stok, tombol "Pinjam →" orange per baris', 'Tombol nonaktif jika stok = 0; badge "Habis" merah'],
    ['Step 2', 'Isi Formulir', 'NPM (pre-filled, readonly), Tanggal Kembali (date picker min=today), Jumlah Unit (number, max=stok)', 'Tombol "Ajukan" disabled jika jumlah > stok atau field kosong'],
    ['Step 3', 'Konfirmasi', 'Ringkasan: nama alat, jumlah, tanggal kembali, catatan denda Rp5.000/hari. Alert warning kuning', 'User harus klik "Konfirmasi & Kirim" — tidak bisa bypass'],
    ['Step 4', 'Berhasil', 'Centang hijau besar animasi, "Menunggu Konfirmasi Penjaga Lab", nomor referensi REF-2025-XXXX', 'Tombol: "Kembali ke Katalog" (oranye) & "Cek Status Pinjam"'],
], [600, 1500, 3200, 3600]);
gap(160);

subsec('6.5', 'Manajemen Inventaris Alat');
p('Halaman manajemen inventaris merupakan pusat pengelolaan aset laboratorium yang hanya dapat diakses oleh Penjaga Lab dan Admin. Dilengkapi fitur pencarian, filter kategori, dan cetak QR Code label alat untuk memudahkan identifikasi fisik di rak laboratorium.');
gap(80);

if ($img_invent) {
    embedImg($img_invent, 14, 9);
    caption('Gambar 6.5 — Wireframe Lo-Fi Halaman Manajemen Inventaris Alat Laboratorium');
}
gap(80);

tbl([
    ['Komponen', 'Deskripsi'],
    ['Stat Mini (3 kartu)', 'Total Alat, Tersedia, Rusak/Maintenance — ringkasan cepat kondisi inventaris di bagian atas'],
    ['Pencarian & Filter', 'Search bar full-text + dropdown filter kategori (Alat Ukur, Komponen, Sumber Daya, dll.)'],
    ['Tabel DataTable', 'Kolom: No, Nama Alat, Merk/Model, Kategori, Jumlah Total, Tersedia, Rusak, Status, Actions'],
    ['Aksi per Baris', 'Ikon Edit (pensil biru), Ikon QR Code (ungu/cetak label), Ikon Hapus (tong merah) — dengan konfirmasi modal'],
    ['Modal Tambah/Edit', 'Form: Nama Alat, Merk, Kategori, Jumlah Total, Keterangan, Status awal — dengan validasi server-side'],
    ['Fitur QR Code', 'Generate dan cetak label QR Code per alat — memudahkan scan identifikasi fisik di rak lab'],
], [2500, 6400]);
gap(160);

subsec('6.6', 'Knowledge Graph Visualization');
p('Halaman Knowledge Graph menampilkan visualisasi interaktif relasi semantik antar entitas dalam sistem menggunakan teknologi SVG (Scalable Vector Graphics). Berbeda dengan tampilan tabel SQL konvensional, Knowledge Graph memungkinkan eksplorasi relasi multi-hop yang tidak dapat terlihat dalam satu query SQL standar.');
gap(80);

if ($img_kg) {
    embedImg($img_kg, 14, 9);
    caption('Gambar 6.6 — Wireframe Lo-Fi Knowledge Graph SVG Interaktif dengan Panel Semantic Filtering');
}
gap(80);

tbl([
    ['Komponen Graf', 'Spesifikasi & Interaksi'],
    ['Canvas SVG (Area Utama)', 'Lebar penuh dengan padding 40px. Background putih/gelap (dark mode). Node draggable (klik tahan geser)'],
    ['Node Entitas (5 default)', 'Lingkaran berwarna per tipe: Mahasiswa (hijau), Alat (emas), Mata Kuliah (ungu), Dosen (biru), Ruang Lab (pink)'],
    ['Edge/Relasi Berlabel', '8 relasi semantik dengan warna berbeda: MEMINJAM (merah), MENGAJAR (ungu), MEMBIMBING (biru), TERHUBUNG (abu)'],
    ['Single Click Node', 'Membuka panel "Detail Entitas Semantik" di sidebar kanan — menampilkan atribut lengkap node yang diklik'],
    ['Double-Click Node', 'Mengekspansi node: klik N1 (Dwiky) memunculkan N7 (Naza), klik N2 (Oscilloscope) memunculkan N6 (Signal Gen)'],
    ['Panel Semantic Filtering', '5 checkbox filter tipe entitas — real-time toggle tampilan node dan edge terkait tanpa reload halaman'],
    ['Tombol Reset Grafik', 'Menyembunyikan node ekspansi (N6, N7), mengembalikan semua filter ke kondisi aktif (checked)'],
], [2500, 6400]);

// ═══════════════════════════════════════════════════════════════
//  7. JUSTIFIKASI DESAIN
// ═══════════════════════════════════════════════════════════════
sec('7', 'Justifikasi Desain (Design Rationale)');
p('Setiap keputusan desain dalam E-Lab Elektro dilandasi oleh teori dan prinsip UI/UX yang terukur secara ilmiah. Berikut justifikasi empat keputusan desain utama yang paling berdampak pada pengalaman pengguna:');

gap(80);
subsec('7.1', 'Hukum Fitts (Fitts\'s Law) — Penempatan Tombol Aksi');
p('Hukum Fitts (1954) menyatakan bahwa waktu yang dibutuhkan untuk menjangkau target adalah fungsi dari jarak ke target dan ukuran target: T = a + b × log₂(2D/W). Semakin besar ukuran tombol (W) dan semakin dekat posisinya (D), semakin cepat pengguna dapat mengaksesnya.');
p('Penerapan di E-Lab Elektro: Tombol aksi utama seperti "Pinjam" dan "Ajukan Peminjaman" dirancang dengan tinggi minimal 40px dan menggunakan border-radius rounded-pill yang lebar untuk memperbesar area klik yang efektif. Tombol "Pinjam" diletakkan di sisi paling kanan setiap baris tabel inventaris, sesuai dengan pola gerak alami jari pengguna yang bergerak dari kiri ke kanan saat membaca, sehingga mempercepat interaksi dan meminimalkan klik yang tidak disengaja.');

gap(80);
subsec('7.2', 'Prinsip Gestalt — Pengelompokan Visual Statistik');
p('Prinsip Gestalt menjelaskan cara otak manusia secara natural mengelompokkan elemen visual tanpa perlu diarahkan secara eksplisit. Dua prinsip Gestalt utama diterapkan dalam desain dashboard E-Lab Elektro:');
bul('Law of Proximity (Kedekatan): Widget statistik Total Alat, Tersedia di Rak, Alat Rusak, dan Pinjaman Aktif dikelompokkan rapat dalam satu baris grid dengan gutter yang konsisten, sehingga otak secara otomatis mempersepsikannya sebagai satu kelompok informasi yang saling berkaitan.');
bul('Law of Similarity (Kesamaan): Kode warna diterapkan secara konsisten — hijau emerald (#10b981) untuk kondisi baik/tersedia, merah (#ef4444) untuk masalah/rusak/keterlambatan, biru (#3b82f6) untuk aktivitas aktif/sedang berjalan. Mahasiswa dapat langsung mengenali pola status tanpa harus membaca teks detail setiap komponen.');

gap(80);
subsec('7.3', 'F-Pattern Eye Tracking — Posisi Sidebar Navigasi');
p('Penelitian eye-tracking F-Pattern dari Nielsen Norman Group (Pernice, 2017) menunjukkan bahwa pengguna web secara natural membaca dari kiri ke kanan dan dari atas ke bawah, membentuk pola huruf "F". Area paling atas dan paling kiri adalah zona pertama yang dilihat mata pengguna.');
p('Penerapan di E-Lab Elektro: Sidebar navigasi diletakkan di sisi kiri dengan lebar 250px (collapsed ke 70px di mobile < 768px), memanfaatkan zona F-Pattern yang paling awal dilihat sehingga pengguna dapat menemukan navigasi secara instan tanpa perlu mencari. Setiap item menu dilengkapi ikon Font Awesome 6 beserta label teks untuk memastikan keterbacaan pada kedua mode (expanded dan collapsed).');

gap(80);
subsec('7.4', 'Pemilihan Donut Chart vs Bar Chart');
p('Dashboard menggunakan dua jenis visualisasi data yang berbeda secara strategis berdasarkan jenis informasi yang ingin disampaikan:');
bul('Donut Chart (Kesehatan Inventaris): Dipilih karena superior untuk menampilkan data proporsi dua kategori (Bagus vs Rusak). Angka total di tengah lingkaran memberikan konteks kuantitatif langsung. Implementasi menggunakan Chart.js dengan animasi easeInOutQuart 1.5 detik untuk pengalaman visual yang premium.');
bul('Bar Chart (Top 5 Alat): Dipilih karena lebih efektif untuk perbandingan nilai numerik antar kategori (ranking alat berdasarkan frekuensi peminjaman). Label nama alat di sumbu X memberikan identifikasi langsung tanpa tooltip. Warna biru (#3b82f6) dipilih untuk membedakan visual dari donut chart yang berdampingan.');

// ═══════════════════════════════════════════════════════════════
//  8. EVALUASI HEURISTIK NIELSEN
// ═══════════════════════════════════════════════════════════════
sec('8', 'Evaluasi Heuristik Nielsen');
p('Evaluasi Heuristik dilakukan mengacu pada 10 Prinsip Usability yang dirumuskan oleh Jakob Nielsen (1994). Lima prinsip yang paling relevan dievaluasi secara mendalam terhadap sistem E-Lab Elektro melalui metode inspeksi ahli (expert inspection), menggunakan skala Severity Rating 0–4 yang telah distandardisasi.');
gap(40);
p('Keterangan Skala Severity Rating Nielsen:', true);
bul('0 = Bukan masalah usability — tidak perlu tindakan');
bul('1 = Cosmetic — perbaiki jika ada waktu sisa pengembangan');
bul('2 = Minor — prioritas rendah, jadwalkan untuk siklus iterasi berikutnya');
bul('3 = Major — perlu segera diperbaiki sebelum pengujian pengguna');
bul('4 = Catastrophe — wajib diperbaiki sebelum rilis ke pengguna nyata');
gap(120);

tbl([
    ['Kode', 'Prinsip Heuristik Nielsen', 'Temuan Masalah di E-Lab Elektro', 'Severity', 'Solusi Desain yang Diterapkan'],
    ['H1', 'Visibility of System Status', 'Pengguna tidak mendapat umpan balik visual saat sistem sedang memproses pengajuan peminjaman ke server. Layar terasa "hang" dan pengguna cenderung mengklik tombol berulang kali.', '3 — Major', 'Implementasi loading spinner full-screen dengan overlay semi-transparan saat pengajuan dikirim. Progress bar multi-step pada wizard peminjaman untuk menunjukkan tahapan proses yang berjalan.'],
    ['H2', 'Match Between System & Real World', 'Sistem awalnya menggunakan istilah teknis database seperti "id_alat", "user_role", dan "tgl_kembali" yang tidak familiar bagi mahasiswa awam yang tidak berlatar belakang IT.', '2 — Minor', 'Seluruh istilah teknis diganti dengan bahasa sehari-hari kampus: "Nama Alat" (bukan id_alat), "NPM" (bukan username), "Penjaga Lab" (bukan admin_level2), "Denda Terlambat" (bukan penalty_amount).'],
    ['H3', 'User Control and Freedom', 'Pengguna tidak sengaja menambahkan alat yang salah ke keranjang namun tidak tersedia opsi untuk menghapus atau membatalkan item sebelum pengajuan dikirimkan ke server.', '3 — Major', 'Setiap item di keranjang dilengkapi tombol "Hapus" (ikon tong sampah merah) yang dapat diklik kapan saja. Tombol "Batal / Kembali" tersedia di setiap langkah wizard. Modal konfirmasi sebelum penghapusan permanen.'],
    ['H4', 'Consistency & Standards', 'Tombol aksi menggunakan warna yang berbeda-beda di halaman berbeda: tombol "Simpan" kadang biru, kadang oranye, kadang hijau untuk fungsi yang pada dasarnya sama (menyimpan data).', '2 — Minor', 'Standardisasi palet warna tombol di seluruh sistem: Oranye (#ea580c) untuk aksi utama/pengajuan, Abu-abu (#6b7280) untuk batal/kembali, Merah (#ef4444) untuk hapus/peringatan, Hijau (#10b981) untuk konfirmasi berhasil.'],
    ['H5', 'Error Prevention', 'Mahasiswa dapat mengetikkan jumlah pinjam yang melebihi stok yang tersedia di rak fisik, sehingga memicu error constraint database dan transaksi tidak dapat dilanjutkan sama sekali.', '4 — Catastrophe', 'Validasi frontend real-time: atribut max pada input number otomatis dibatasi sesuai stok tersedia. Tombol "Pinjam" dan "Ajukan" otomatis disabled (tidak dapat diklik) jika stok = 0 atau jumlah input melebihi stok. Pesan error inline langsung di bawah field.'],
], [500, 1600, 2800, 900, 3100]);
gap(120);

p('Rangkuman hasil evaluasi heuristik: dari 5 prinsip yang diinspeksi, ditemukan 1 masalah Catastrophe (H5 — sudah diperbaiki), 2 masalah Major (H1 dan H3 — sudah diperbaiki), dan 2 masalah Minor (H2 dan H4 — sudah distandarisasi). Tidak ditemukan masalah dengan Severity Rating 4 yang belum ditangani pada versi sistem saat ini.');

// ═══════════════════════════════════════════════════════════════
//  9. RENCANA PENGUJIAN SUS
// ═══════════════════════════════════════════════════════════════
sec('9', 'Rencana Pengujian System Usability Scale (SUS)');
p('Untuk mengukur tingkat usability sistem secara empiris dan kuantitatif, dirancang pengujian menggunakan instrumen System Usability Scale (SUS) yang dikembangkan oleh John Brooke (1996). Instrumen SUS merupakan standar industri untuk pengukuran usability yang telah divalidasi secara luas dalam lebih dari 500 publikasi ilmiah.');

gap(80);
subsec('9.1', 'Rancangan Metodologi Pengujian');
tbl([
    ['Aspek Metodologi', 'Rencana Detail'],
    ['Instrumen Pengujian', 'System Usability Scale (SUS) — 10 pertanyaan standar dengan skala Likert 1-5 (Sangat Tidak Setuju s.d. Sangat Setuju)'],
    ['Target Responden', 'Minimal 10 responden: mahasiswa aktif semester 4-8 (6 orang), penjaga lab (2 orang), dosen Teknik Elektro USK (2 orang)'],
    ['Kriteria Inklusif', 'Pernah menggunakan sistem laboratorium (manual atau digital) dan mampu mengoperasikan browser web dengan mandiri'],
    ['Waktu & Tempat', 'Sesi pengujian 45-60 menit per responden di Laboratorium Teknik Elektro USK dengan akun demo yang telah disiapkan'],
    ['Prosedur Pengujian', '(1) Penjelasan tujuan 5 menit; (2) Responden diberikan akun demo; (3) Selesaikan 3 skenario tugas mandiri; (4) Isi kuesioner SUS; (5) Wawancara singkat (5 menit)'],
    ['Formula Skor SUS', 'Skor = [(Σ skor ganjil - 5) + (25 - Σ skor genap)] × 2.5 — menghasilkan skor 0-100'],
    ['Interpretasi Target', '0-50: Not Acceptable | 51-68: Marginal (batas bawah) | 68-80: Good | 80-90: Excellent | >90: Best Imaginable (target: ≥ 68)'],
    ['Visualisasi Hasil', 'Gauge meter speedometer SVG telah disiapkan di Tab 4 website untuk menampilkan skor hasil pengujian SUS secara real-time'],
], [3000, 5900]);
gap(120);

subsec('9.2', 'Daftar 10 Pertanyaan Kuesioner SUS');
tbl([
    ['No.', 'Pertanyaan SUS (Bahasa Indonesia)', 'Skala'],
    ['Q1', 'Saya rasa saya akan sering menggunakan sistem E-Lab Elektro ini dalam aktivitas perkuliahan.', '1-5'],
    ['Q2', 'Saya merasa sistem ini terlalu rumit dan kompleks tanpa alasan yang jelas.', '1-5'],
    ['Q3', 'Saya rasa sistem ini mudah untuk digunakan tanpa memerlukan panduan khusus.', '1-5'],
    ['Q4', 'Saya rasa saya membutuhkan bantuan teknisi atau orang ahli untuk bisa menggunakan sistem ini.', '1-5'],
    ['Q5', 'Saya merasa berbagai fungsi dalam sistem ini terintegrasi dengan baik satu sama lain.', '1-5'],
    ['Q6', 'Saya merasa terlalu banyak inkonsistensi (ketidakkonsistenan) dalam desain sistem ini.', '1-5'],
    ['Q7', 'Saya rasa kebanyakan orang akan dapat mempelajari cara menggunakan sistem ini dengan cepat.', '1-5'],
    ['Q8', 'Saya merasa sistem ini sangat sulit untuk digunakan secara efektif.', '1-5'],
    ['Q9', 'Saya merasa sangat percaya diri dan nyaman saat menggunakan sistem ini.', '1-5'],
    ['Q10', 'Saya perlu belajar banyak hal terlebih dahulu sebelum saya bisa mulai menggunakan sistem ini.', '1-5'],
], [400, 6200, 2300]);
gap(120);

subsec('9.3', 'Skenario Tugas Usability Testing');
tbl([
    ['No.', 'Nama Tugas', 'Instruksi kepada Responden', 'Heuristik yang Diuji'],
    ['T1', 'Pencarian & Ketersediaan Alat', 'Gunakan sistem untuk mencari alat "Oscilloscope Digital", periksa jumlah unit yang tersisa di rak laboratorium, dan pastikan statusnya "Tersedia" sebelum mengajukan peminjaman.', 'H1 (Visibility) & H2 (Real World Match)'],
    ['T2', 'Proses Peminjaman Lengkap', 'Tambahkan alat "Signal Generator Rigol" ke Keranjang, buka keranjang, isi formulir dengan NPM yang valid dan tanggal kembali 7 hari ke depan, lalu kirimkan pengajuan peminjaman.', 'H3 (User Control) & H4 (Consistency)'],
    ['T3', 'Pencegahan Kesalahan Input', 'Coba masukkan jumlah peminjaman sebesar 99 unit (melebihi stok yang tersedia) dan verifikasi apakah sistem menolak input tersebut secara otomatis tanpa harus mengirim form.', 'H5 (Error Prevention) — Severity 4'],
], [300, 1500, 4500, 2600]);

// ═══════════════════════════════════════════════════════════════
//  10. PROTOTYPE INTERAKTIF
// ═══════════════════════════════════════════════════════════════
sec('10', 'Prototype Interaktif');
p('Prototype interaktif E-Lab Elektro disimulasikan dalam format clickable wizard yang mensimulasikan alur peminjaman mahasiswa secara penuh dalam 4 langkah berurutan. Prototype ditampilkan dalam frame browser palsu untuk memberikan kesan yang realistis dan memudahkan stakeholder memahami tampilan akhir sistem.');
gap(80);
tbl([
    ['Step', 'Nama Layar', 'Konten & Elemen Interaktif', 'Prinsip Heuristik'],
    ['Step 1/4', 'Pilih Alat dari Katalog', 'Alert info sambutan "Selamat Datang". Daftar 3 alat: Oscilloscope Digital (12 Unit), Signal Generator Rigol (5 Unit), Multimeter Fluke (8 Unit). Tombol "Pinjam →" oranye di kanan setiap baris. Badge status warna.', 'H1: Progress indicator 4 step; H5: Tombol nonaktif jika stok habis'],
    ['Step 2/4', 'Isi Formulir Peminjaman', 'NPM (pre-filled: 250420501100004, readonly), nama alat yang dipilih (readonly), Tanggal Kembali (date picker, min=hari ini), Jumlah Unit (number, 1 s.d. maks stok). Validasi real-time.', 'H3: Tombol Kembali tersedia; H5: Tombol Ajukan disabled jika tidak valid'],
    ['Step 3/4', 'Konfirmasi Pengajuan', 'Ringkasan detail: nama alat terpilih, jumlah, tanggal kembali, nama peminjam. Alert warning kuning: "Pastikan data sudah benar — denda Rp 5.000/hari jika terlambat". Tombol Konfirmasi & Batal.', 'H4: Visual konsisten; H2: Bahasa sehari-hari (denda, bukan penalty)'],
    ['Step 4/4', 'Pengajuan Berhasil', 'Animasi centang hijau besar. Pesan: "Menunggu Konfirmasi Penjaga Lab". Nomor Referensi: REF-2025-XXXX. Tombol: "Kembali ke Katalog" (oranye) dan "Cek Status Pinjam" (outline).', 'H1: Status jelas; H3: Tombol kembali tersedia'],
], [700, 1600, 3900, 2700]);

// ═══════════════════════════════════════════════════════════════
//  11. KNOWLEDGE GRAPH DESIGN
// ═══════════════════════════════════════════════════════════════
sec('11', 'Knowledge Graph Visualization Design');
p('Knowledge Graph E-Lab Elektro mengimplementasikan visualisasi graf semantik interaktif menggunakan teknologi SVG dengan 7 node entitas (5 default + 2 tersembunyi yang muncul saat ekspansi), 8 relasi semantik berlabel, panel Semantic Filtering dengan 5 kategori, dan panel Detail Entitas Semantik.');

gap(80);
subsec('11.1', 'Spesifikasi Node Entitas (Simpul Graf)');
tbl([
    ['ID', 'Nama Entitas', 'Tipe', 'Warna', 'Data Semantik Utama'],
    ['N1', 'Dwiky Ilham', 'Mahasiswa', '#10b981 Hijau', 'NPM: 250420501100004. Sedang meminjam Oscilloscope untuk keperluan Tugas Akhir.'],
    ['N2', 'Oscilloscope Tektronix', 'Alat Inventaris', '#f59e0b Emas', 'Status: Dipinjam oleh Dwiky. Terletak di Lab Telekomunikasi lantai 2.'],
    ['N3', 'Praktikum Mikroprosesor', 'Mata Kuliah', '#8b5cf6 Ungu', 'Kode: EL-302. Membutuhkan Oscilloscope dan Signal Generator sebagai alat praktikum utama.'],
    ['N4', 'Dr. Misbah Anuari', 'Dosen Pembimbing', '#3b82f6 Biru', 'Mengajar Praktikum Mikroprosesor (EL-302) & membimbing Tugas Akhir Dwiky.'],
    ['N5', 'Lab Telekomunikasi', 'Ruang Laboratorium', '#ec4899 Pink', 'Lokasi penyimpanan utama alat ukur frekuensi tinggi. Kapasitas 30 mahasiswa.'],
    ['N6*', 'Signal Generator Rigol', 'Alat Inventaris', '#f59e0b Emas', 'Status: Tersedia di rak (5 unit). *Muncul setelah double-click N2 (Oscilloscope).'],
    ['N7*', 'Naza Fahrul Sirait', 'Mahasiswa Kelompok', '#10b981 Hijau', 'NPM: 250420501100002. Rekan kelompok TA Dwiky. *Muncul setelah double-click N1 (Dwiky).'],
], [500, 2000, 1600, 1400, 3400]);
gap(160);

subsec('11.2', 'Spesifikasi Edge (Relasi Semantik Antar Node)');
tbl([
    ['Dari Node', 'Label Relasi', 'Ke Node', 'Warna Edge', 'Visibilitas'],
    ['Dwiky (N1)', 'MEMINJAM', 'Oscilloscope (N2)', '#ef4444 Merah', 'Default (selalu terlihat)'],
    ['Dr. Misbah (N4)', 'MENGAJAR', 'Prak. Mikro (N3)', '#8b5cf6 Ungu', 'Default (selalu terlihat)'],
    ['Oscilloscope (N2)', 'TERLETAK_DI', 'Lab Tele (N5)', '#f59e0b Emas', 'Default (selalu terlihat)'],
    ['Prak. Mikro (N3)', 'DILAKSANAKAN_DI', 'Lab Tele (N5)', '#f59e0b Emas', 'Default (selalu terlihat)'],
    ['Oscilloscope (N2)', 'DIGUNAKAN_DI', 'Prak. Mikro (N3)', '#10b981 Hijau', 'Default (selalu terlihat)'],
    ['Dr. Misbah (N4)', 'MEMBIMBING', 'Dwiky (N1)', '#3b82f6 Biru', 'Default (selalu terlihat)'],
    ['Signal Gen (N6)*', 'TERHUBUNG', 'Oscilloscope (N2)', '#64748b Abu', 'Hidden awal — muncul setelah expand N2'],
    ['Naza (N7)*', 'REKAN_KLP', 'Dwiky (N1)', '#3b82f6 Biru', 'Hidden awal — muncul setelah expand N1'],
], [1800, 1600, 1800, 1400, 2300]);

// ═══════════════════════════════════════════════════════════════
//  12. PERBANDINGAN RELATIONAL vs KNOWLEDGE GRAPH
// ═══════════════════════════════════════════════════════════════
sec('12', 'Perbandingan Relational vs Knowledge Graph UI');
p('Sistem E-Lab Elektro mengimplementasikan dua paradigma pengelolaan dan visualisasi data dalam satu platform yang terintegrasi. Berikut perbandingan komprehensif dari berbagai dimensi UI/UX:');
gap(80);
tbl([
    ['Dimensi UI/UX', 'Relational System (CRUD MySQL)', 'Knowledge Graph System (SVG)'],
    ['Paradigma Interaksi', 'Linear & prosedural — pengguna mengikuti alur form yang sudah ditentukan sistem secara kaku', 'Eksploratori & non-linear — pengguna bebas menjelajahi relasi antar entitas secara organik'],
    ['Komponen UI Utama', 'Form input, tabel DataTable, modal konfirmasi, dropdown filter, pagination, breadcrumb', 'Canvas SVG, panel node detail, checkbox semantic filter, tombol expand node, tombol reset'],
    ['Jenis Data yang Ditampilkan', 'Data terstruktur tabular: baris & kolom dengan tipe data primitif (angka, teks, tanggal)', 'Data relasional semantik: node bertipe, edge berlabel, atribut multi-nilai per entitas'],
    ['Navigasi', 'Sidebar hierarkis ke halaman terpisah per fitur, URL berubah per navigasi, breadcrumb linear', 'Single-page exploration — semua relasi terlihat sekaligus, navigasi via klik node di canvas'],
    ['Target Pengguna Utama', 'Mahasiswa & Penjaga Lab — kebutuhan CRUD cepat, efisiensi transaksi sehari-hari', 'Kaprodi & Admin — analitik mendalam, eksplorasi relasi multi-hop, insight strategis'],
    ['Learning Curve', 'Rendah — familiar dengan form web & tabel yang umum digunakan di web manapun', 'Sedang s.d. Tinggi — perlu familiarisasi konsep graf, gesture double-click, dan filter semantik'],
    ['Contoh Query Data', 'SELECT * FROM transaksi WHERE status = "Dipinjam" (3 token SQL)', 'MATCH (m)-[:MEMINJAM]->(a:Alat) RETURN m,a (2 baris Cypher — lebih ekspresif)'],
], [2000, 3500, 3500]);
gap(120);

subsec('12.1', 'Studi Kasus: Perbandingan Query SQL vs Cypher');
p('Kasus: Temukan semua alat yang pernah dipinjam oleh mahasiswa satu kelompok Tugas Akhir dengan Dwiky Ilham (NPM: 250420501100004):');
gap(80);
tbl([
    ['Sistem', 'Query yang Diperlukan', 'Jumlah Baris', 'Kompleksitas'],
    ['Relational SQL (MySQL)', 'SELECT a.nama_alat, u.nama_lengkap FROM transaksi t INNER JOIN users u ON t.username=u.username INNER JOIN alat a ON t.id_alat=a.id WHERE u.username IN (SELECT k.kelompok_user FROM kelompok k WHERE k.nama_kelompok = (SELECT k2.nama_kelompok FROM kelompok k2 INNER JOIN users u2 ON k2.id_mahasiswa=u2.id WHERE u2.username="250420501100004"))', '12 baris', 'Tinggi — nested subquery 3 level'],
    ['Knowledge Graph (Cypher)', 'MATCH (m1:Mahasiswa {username:"250420501100004"}) -[:REKAN_KLP]-(m2) -[:MEMINJAM]->(a:Alat) RETURN a.nama_alat, m2.nama_lengkap', '3 baris', 'Rendah — pattern matching ekspresif'],
], [1400, 4600, 1200, 1700]);

// ═══════════════════════════════════════════════════════════════
//  13. KESIMPULAN DAN SARAN
// ═══════════════════════════════════════════════════════════════
sec('13', 'Kesimpulan dan Saran');

subsec('13.1', 'Kesimpulan');
p('Evaluasi UI/UX sistem E-Lab Elektro menggunakan pendekatan User-Centered Design (UCD) dan Evaluasi Heuristik Nielsen menghasilkan temuan dan pencapaian sebagai berikut:');
bul('Empat persona pengguna utama (Dwiky — Mahasiswa, Misbah — Penjaga Lab, Rana — Kaprodi, Admin Utama) telah diidentifikasi secara spesifik dengan kebutuhan masing-masing yang diterjemahkan ke dalam fitur nyata dan terukur dalam sistem.');
bul('Alur interaksi dual-paradigma berhasil diimplementasikan: CRUD linear untuk efisiensi peminjaman sehari-hari, dan eksplorasi Knowledge Graph non-linear untuk analitik strategis — keduanya terintegrasi dalam satu platform yang terpadu.');
bul('Enam wireframe Lo-Fi telah disusun dan didokumentasikan untuk seluruh halaman utama: Login, Dashboard Admin, Dashboard Mahasiswa, Prototype Peminjaman 4-Step, Manajemen Inventaris, dan Knowledge Graph.');
bul('Dari 5 prinsip heuristik Nielsen yang diinspeksi: 1 masalah Catastrophe (H5 — validasi stok realtime), 2 masalah Major (H1 loading spinner, H3 tombol hapus keranjang) seluruhnya sudah diperbaiki; 2 masalah Minor (H2 istilah teknis, H4 konsistensi warna tombol) sudah distandarisasi.');
bul('Knowledge Graph berhasil memvisualisasikan 5 tipe entitas dan 8 relasi semantik dalam satu canvas SVG interaktif dengan kemampuan ekspansi node dan semantic filtering dinamis yang melampaui kemampuan tabel SQL konvensional.');
bul('Instrumen pengujian SUS (10 pertanyaan standar Brooke, 1996) beserta 3 skenario tugas usability testing telah dirancang lengkap dan siap dilaksanakan untuk memperoleh skor usability empiris yang tervalidasi secara ilmiah.');

gap(80);
subsec('13.2', 'Saran Pengembangan Ke Depan');
tbl([
    ['Prioritas', 'Saran Pengembangan', 'Justifikasi & Dampak yang Diharapkan'],
    ['P1 — Tinggi', 'Integrasikan node Knowledge Graph dengan query database MySQL real-time (data masih hardcoded)', 'Graf akan menampilkan data transaksi nyata secara dinamis, bukan demo statis'],
    ['P1 — Tinggi', 'Tambahkan trigger node expansion pada seluruh node (saat ini hanya N1 dan N2)', 'Double-click N5 (Lab Tele) akan menampilkan semua alat yang tersimpan di lab tersebut'],
    ['P2 — Sedang', 'Laksanakan pengujian SUS dengan minimal 10 responden nyata', 'Mendapatkan skor usability empiris untuk validasi ilmiah (target skor SUS ≥ 68)'],
    ['P2 — Sedang', 'Implementasi notifikasi push/email otomatis untuk pengingat batas waktu pengembalian', 'Mengurangi angka keterlambatan dan denda secara signifikan'],
    ['P3 — Rendah', 'Ganti library SVG static dengan vis.js Network untuk physics simulation', 'Node dapat di-drag bebas, layout otomatis, animasi spring yang lebih realistis'],
    ['P3 — Rendah', 'Tambahkan fitur peminjaman berulang (recurring borrow) untuk mata kuliah dengan jadwal tetap', 'Mengurangi friction pengajuan peminjaman untuk kegiatan praktikum rutin mingguan'],
], [1200, 3800, 3900]);

// ═══════════════════════════════════════════════════════════════
//  DAFTAR PUSTAKA
// ═══════════════════════════════════════════════════════════════
pg();
$o .= "\\pard\\qc\\sb300\\sa120{$BASE}\\b " . r('DAFTAR PUSTAKA') . "\\par\n";
$o .= "\\pard\\qc\\sb0\\sa200\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
$refs = [
    'Nielsen, J. (1994). Usability Engineering. Morgan Kaufmann Publishers. San Francisco, CA.',
    'Nielsen, J. (1995). 10 Usability Heuristics for User Interface Design. Nielsen Norman Group. https://www.nngroup.com/articles/ten-usability-heuristics/',
    'Brooke, J. (1996). SUS: A quick and dirty usability scale. In P. W. Jordan, B. Thomas, B. A. Weerdmeester, & I. L. McClelland (Eds.), Usability evaluation in industry (pp. 189-194). Taylor and Francis, London.',
    'Norman, D. A. (2013). The Design of Everyday Things: Revised and Expanded Edition. Basic Books. New York.',
    'Fitts, P. M. (1954). The information capacity of the human motor system in controlling the amplitude of movement. Journal of Experimental Psychology, 47(6), 381-391.',
    'Wertheimer, M. (1923). Laws of Organization in Perceptual Forms (W. Ellis, Trans.). London: Kegan Paul, Trench, Trubner & Company.',
    'Pernice, K. (2017). F-Shaped Pattern of Reading on the Web: Misunderstood, But Still Relevant. Nielsen Norman Group. https://www.nngroup.com/articles/f-shaped-pattern-reading-web-content/',
    'ISO 9241-210:2019. Ergonomics of human-system interaction — Part 210: Human-centred design for interactive systems. Geneva: International Organization for Standardization.',
    'W3C. (2023). Web Content Accessibility Guidelines (WCAG) 2.1. https://www.w3.org/WAI/WCAG21/quickref/',
    'Bootstrap Team. (2024). Bootstrap 5.3 Documentation. https://getbootstrap.com/docs/5.3/',
    'CodeIgniter Foundation. (2024). CodeIgniter 4.x User Guide. https://codeigniter.com/user_guide/',
    'Chart.js Contributors. (2024). Chart.js Documentation v4.x. https://www.chartjs.org/docs/latest/',
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

$outFile      = __DIR__ . '/Laporan_UCD_ELab_Elektro_Revisi2.doc';
$fallbackFile = __DIR__ . '/Laporan_UCD_ELab_Elektro_Revisi2_v2.doc';

$writeResult = @file_put_contents($outFile, $rtf);

if ($writeResult === false) {
    $fallbackResult = @file_put_contents($fallbackFile, $rtf);
    if ($fallbackResult === false) {
        echo "GAGAL: Tidak dapat menulis file laporan.\n";
    } else {
        echo "PERINGATAN: File utama sedang dikunci (mungkin terbuka di Microsoft Word).\n";
        echo "Laporan berhasil ditulis ke file cadangan:\n";
        echo "File: {$fallbackFile}\n";
        echo "Ukuran: " . number_format(filesize($fallbackFile)/1024, 1) . " KB\n";
    }
} else {
    echo "=" . str_repeat("=", 55) . "\n";
    echo " LAPORAN BERHASIL DIBUAT!\n";
    echo "=" . str_repeat("=", 55) . "\n";
    echo " File  : {$outFile}\n";
    echo " Ukuran: " . number_format(filesize($outFile)/1024, 1) . " KB\n";
    echo "=" . str_repeat("=", 55) . "\n";
    echo "\n ISI LAPORAN:\n";
    echo " - Halaman Sampul\n";
    echo " - Kata Pengantar\n";
    echo " - Daftar Isi\n";
    echo " - Daftar Gambar\n";
    echo " - 13 Bab Konten (Pendahuluan s.d. Kesimpulan)\n";
    echo " - 6 Gambar Wireframe Embedded\n";
    echo " - 12 Referensi Ilmiah\n";
    echo "=" . str_repeat("=", 55) . "\n";
    @file_put_contents($fallbackFile, $rtf);
}
