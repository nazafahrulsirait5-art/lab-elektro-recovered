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

$o    = '';
$BASE = '\\f0\\fs24\\cf0\\sl360\\slmult1';   // TNR 12pt, black, 1.5-line

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

function embedImg($path, $widthCm=14, $heightCm=9) {
    global $o;
    if (!file_exists($path)) {
        $o .= "\\pard\\qc\\sb80\\sa80 [Gambar tidak ditemukan: " . r(basename($path)) . "]\\par\n";
        return;
    }
    $data = file_get_contents($path);
    $hex  = strtolower(bin2hex($data));
    $wTwip = (int)($widthCm  * 567);
    $hTwip = (int)($heightCm * 567);
    $o .= "\\pard\\qc\\sb80\\sa80{\\pict\\pngblip\\picwgoal{$wTwip}\\pichgoal{$hTwip} {$hex}}\\par\n";
}

function caption($text) {
    global $o, $BASE;
    $o .= "\\pard\\qc\\sb0\\sa160{$BASE}\\b0\\i " . r($text) . "\\par\n";
}

$IMG_DIR = 'C:\\Users\\HP VICTUS\\.gemini\\antigravity-ide\\brain\\bc1cf978-7f45-4b62-87d1-9bfc9d051d45\\';

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
$img_penjaga    = findLatestPng($IMG_DIR, 'wireframe_penjaga_lab_');
$img_pinjam     = findLatestPng($IMG_DIR, 'wireframe_peminjaman_form_');
$img_invent     = findLatestPng($IMG_DIR, 'wireframe_inventaris_alat_');
$img_profil     = findLatestPng($IMG_DIR, 'wireframe_profil_');
$img_kg         = findLatestPng($IMG_DIR, 'wireframe_knowledge_graph_');

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
p('Laporan final ini telah dilengkapi secara komprehensif, mencakup penjabaran fitur yang mendetail dalam bentuk tabel spesifikasi, serta seluruh wireframe (mockup visual) untuk semua tipe aktor dalam sistem, yaitu: Mahasiswa, Penjaga Laboratorium, Kepala Program Studi (Kaprodi), dan Administrator Utama.');
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
    ['2. Identifikasi Pengguna (User Persona)', '3'],
    ['3. Analisis Kebutuhan Pengguna', '6'],
    ['4. User Flow Diagram', '8'],
    ['5. Sitemap & Struktur Navigasi', '10'],
    ['6. Wireframe dan Mockup (Seluruh Role)', '12'],
    ['   6.1 Halaman Login', '12'],
    ['   6.2 Dashboard Admin', '13'],
    ['   6.3 Dashboard Kaprodi', '14'],
    ['   6.4 Dashboard Mahasiswa', '15'],
    ['   6.5 Meja Penjaga Lab (Kelola Transaksi)', '16'],
    ['   6.6 Halaman Peminjaman (Prototype Interaktif)', '17'],
    ['   6.7 Manajemen Inventaris Alat', '18'],
    ['   6.8 Profil Pengguna', '19'],
    ['   6.9 Knowledge Graph Visualization', '20'],
    ['7. Justifikasi Desain (Design Rationale)', '21'],
    ['8. Evaluasi Heuristik Nielsen', '23'],
    ['9. Rencana Pengujian System Usability Scale (SUS)', '25'],
    ['10. Prototype Interaktif', '27'],
    ['11. Knowledge Graph Visualization Design', '28'],
    ['12. Perbandingan Relational vs Knowledge Graph', '30'],
    ['13. Kesimpulan dan Saran', '31'],
    ['Daftar Pustaka', '33'],
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
bul('Mahasiswa tidak mengetahui ketersediaan alat sebelum datang secara langsung ke laboratorium, memicu kekecewaan dan pemborosan waktu jika alat ternyata sedang dipinjam atau dalam kondisi rusak.');
bul('Penjaga laboratorium harus menghitung durasi peminjaman dan denda keterlambatan secara manual yang sangat rentan terhadap kekeliruan pencatatan dan konflik antara penjaga lab dengan mahasiswa.');
bul('Kepala Program Studi (Kaprodi) tidak memiliki akses data analitik secara real-time mengenai kondisi kesehatan inventaris, menyulitkan pengambilan keputusan anggaran untuk perbaikan atau pembelian alat baru.');
bul('Tidak terdapat rekam jejak digital aktivitas peminjaman yang dapat digunakan untuk keperluan audit inventaris akhir tahun dan pelaporan akademis.');
gap(80);
p('Sistem E-Lab Elektro dikembangkan sebagai solusi berbasis web komprehensif menggunakan framework CodeIgniter 4 (PHP 8.3) dengan database MySQL 8.4. Sistem ini tidak sekadar melakukan digitalisasi formulir, tetapi mengintegrasikan dua paradigma pengelolaan data dalam satu antarmuka yang terpadu:');
bul('Relational Database (MySQL): digunakan untuk manajemen transaksi CRUD (Create, Read, Update, Delete) peminjaman alat secara terstruktur, efisien, dan mendukung integritas data (ACID compliance).');
bul('Knowledge Graph (Graf Semantik SVG): digunakan untuk eksplorasi relasi semantik antar entitas (Mahasiswa, Alat, Mata Kuliah, Dosen, Ruang Lab) secara visual dan interaktif, memberikan insight yang sulit didapatkan dari tabel biasa.');

subsec('1.2', 'Tujuan Pengembangan Sistem');
p('Tujuan utama pengembangan sistem E-Lab Elektro adalah sebagai berikut:');
bul('Menyediakan platform digital terintegrasi yang mampu mengelola seluruh siklus proses peminjaman alat laboratorium—mulai dari ketersediaan, pengajuan, persetujuan, hingga pengembalian—secara transparan dan akuntabel.');
bul('Mengurangi waktu operasional yang dihabiskan mahasiswa dan penjaga lab dalam administrasi peminjaman dari rata-rata 30 menit menjadi kurang dari 5 menit per transaksi.');
bul('Memberikan visualisasi data analitik dan fitur ekspor laporan yang komprehensif kepada Kaprodi untuk mendukung pengambilan keputusan berbasis data.');

subsec('1.3', 'Ruang Lingkup Laporan');
p('Laporan ini secara khusus mendokumentasikan hasil perancangan dan evaluasi UI/UX (User Interface / User Experience) sistem E-Lab Elektro yang mengacu pada dua metodologi ilmiah yang diakui secara akademis:');
bul('User-Centered Design (UCD): Pendekatan yang menempatkan kebutuhan pengguna di pusat pengembangan. Laporan ini menjabarkan identifikasi persona, pemetaan kebutuhan fitur, rancangan user flow, sitemap hierarkis, wireframe lengkap seluruh aktor, dan justifikasi desain (design rationale).');
bul('Evaluasi Heuristik Nielsen: Inspeksi mendalam terhadap 5 prinsip heuristik Nielsen yang relevan untuk mendeteksi masalah usability beserta resolusinya, serta merancang metode pengujian lapangan menggunakan System Usability Scale (SUS).');

// ═══════════════════════════════════════════════════════════════
//  2. IDENTIFIKASI PENGGUNA
// ═══════════════════════════════════════════════════════════════
sec('2', 'Identifikasi Pengguna (User Persona)');
p('Melalui wawancara mendalam, studi dokumen laboratorium, dan observasi langsung terhadap alur peminjaman fisik di Laboratorium Teknik Elektro USK, diidentifikasi empat kategori aktor pengguna (persona) dengan kebutuhan fungsional dan tingkat literasi teknologi yang berbeda-berbeda. Tabel-tabel berikut menyajikan pemetaan persona yang sangat detail:');

gap(80);
subsec('2.1', 'Aktor 1: Mahasiswa — Dwiky Ilham');
tbl([
    ['Atribut', 'Spesifikasi & Keterangan'],
    ['Nama Persona', 'Dwiky Ilham'],
    ['Identitas Akademis', 'NPM: 250420501100004 | Mahasiswa Semester Akhir, Teknik Elektro USK'],
    ['Karakteristik & Skill', 'Sangat fasih menggunakan teknologi (digital native), sering menggunakan aplikasi e-commerce. Lebih suka antarmuka visual yang cepat (self-service) daripada proses birokrasi tatap muka.'],
    ['Aktivitas Utama', 'Melakukan penelitian Tugas Akhir. Sering meminjam alat ukur frekuensi tinggi seperti Oscilloscope Digital dan Signal Generator Rigol untuk pengujian di luar jam operasional lab.'],
    ['Frustrasi Utama (Pain Points)', '1. Datang ke lab fisik hanya untuk mendapati alat incaran sedang dipinjam orang lain. 2. Formulir peminjaman kertas sering hilang atau terselip. 3. Tidak tahu berapa total denda jika telat mengembalikan.'],
    ['Kebutuhan Sistem (Needs)', 'Katalog inventaris real-time yang bisa diakses dari HP. Fitur "Keranjang Belanja" (shopping cart) untuk meminjam banyak alat sekaligus. Pengingat otomatis jadwal pengembalian.'],
    ['Fitur Prioritas (Halaman)', 'Katalog Alat, Keranjang Peminjaman 4-Step Wizard, Dashboard Mahasiswa, Riwayat Pinjam'],
], [1900, 7000]);
gap(160);

subsec('2.2', 'Aktor 2: Penjaga Lab — Misbah Anuari');
tbl([
    ['Atribut', 'Spesifikasi & Keterangan'],
    ['Nama Persona', 'Misbah Anuari (Tampil juga sebagai Dr. Misbah Anuari di Knowledge Graph)'],
    ['Identitas Akademis', 'Penjaga Laboratorium / Asisten Lab Senior Teknik Elektro'],
    ['Karakteristik & Skill', 'Cukup terbiasa dengan komputer dasar (Excel/Word) namun bukan programmer. Mengelola lalu lintas fisik ratusan inventaris alat masuk dan keluar setiap hari kerja (Senin-Jumat).'],
    ['Aktivitas Utama', 'Menerima mahasiswa yang datang membawa alat, mengecek kondisi fisik alat saat dikembalikan, memvalidasi form peminjaman, mencatat status alat yang rusak untuk dilaporkan.'],
    ['Frustrasi Utama (Pain Points)', '1. Konflik penjadwalan saat satu alat diperebutkan beberapa mahasiswa. 2. Menghitung denda keterlambatan dengan kalender meja sangat menyita waktu dan sering diwarnai perdebatan dengan mahasiswa. 3. Susah mencari siapa yang sedang menahan alat A.'],
    ['Kebutuhan Sistem (Needs)', 'Dashboard "Satu Layar" yang menampilkan daftar tunggu persetujuan dan daftar alat yang sedang di luar. Kalkulator denda otomatis yang mutlak. Penerimaan alat kilat menggunakan Scan Barcode/QR Code.'],
    ['Fitur Prioritas (Halaman)', 'Meja Penjaga (Manajemen Transaksi), Modul Scan QR Code Pengembalian, Modul Inventaris Alat'],
], [1900, 7000]);
gap(160);

subsec('2.3', 'Aktor 3: Kepala Program Studi (Kaprodi) — Rana Sulthanah');
tbl([
    ['Atribut', 'Spesifikasi & Keterangan'],
    ['Nama Persona', 'Rana Sulthanah'],
    ['Identitas Akademis', 'Kepala Program Studi (Kaprodi) Teknik Elektro USK'],
    ['Karakteristik & Skill', 'Pengambil kebijakan strategis. Tidak terlibat langsung dalam operasional peminjaman harian, sangat sibuk, membutuhkan data yang sudah diolah menjadi informasi visual, bukan tumpukan baris tabel.'],
    ['Aktivitas Utama', 'Merencanakan anggaran belanja modal (CAPEX) program studi untuk semester depan, termasuk pengadaan alat laboratorium baru atau perbaikan aset yang rusak. Menyiapkan dokumen akreditasi.'],
    ['Frustrasi Utama (Pain Points)', '1. Saat ditanya Dekanat mengenai utilitas lab, data sangat sulit dikompilasi. 2. Tidak tahu pasti alat mana yang menjadi favorit mahasiswa (tingkat pemakaian tinggi) dan alat mana yang sering rusak.'],
    ['Kebutuhan Sistem (Needs)', 'Dashboard analitik yang menyajikan grafik batang dan pie chart secara instan. Fitur unduh laporan sekali klik dalam format PDF (untuk rapat) dan Excel (untuk diolah staf).'],
    ['Fitur Prioritas (Halaman)', 'Dashboard Kaprodi (Statistik Eksekutif), Laporan & Analitik, Tombol Ekspor PDF/Excel'],
], [1900, 7000]);
gap(160);

subsec('2.4', 'Aktor 4: Administrator — Admin Utama');
tbl([
    ['Atribut', 'Spesifikasi & Keterangan'],
    ['Nama Persona', 'Administrator (Admin Utama Sistem)'],
    ['Identitas Akademis', 'Tim IT / Pengelola Teknis Sistem E-Lab Elektro'],
    ['Karakteristik & Skill', 'Sangat teknikal, memahami struktur database relasional dan keamanan sistem informasi. Bertanggung jawab atas stabilitas dan transparansi platform.'],
    ['Aktivitas Utama', 'Mendaftarkan akun staf baru, memberikan peran (role) kepada pengguna, memantau error sistem, melakukan mitigasi jika terjadi manipulasi data.'],
    ['Frustrasi Utama (Pain Points)', 'Kehilangan rekam jejak (traceability) jika ada Penjaga Lab yang tidak sengaja menghapus transaksi peminjaman besar, atau jika mahasiswa mencoba melakukan fraud kuota peminjaman.'],
    ['Kebutuhan Sistem (Needs)', 'Tabel Audit Log yang mencatat event secara immutable (tidak bisa dihapus) beserta timestamp, aktor, aksi, dan IP address. Modul manajemen hak akses pengguna (Role-Based Access Control).'],
    ['Fitur Prioritas (Halaman)', 'Manajemen Akun Pengguna, Konfigurasi Role, Audit Log Aktivitas Sistem'],
], [1900, 7000]);

// ═══════════════════════════════════════════════════════════════
//  3. ANALISIS KEBUTUHAN PENGGUNA
// ═══════════════════════════════════════════════════════════════
sec('3', 'Analisis Kebutuhan Pengguna Berdasarkan Persona');
p('Berdasarkan permasalahan (pain points) dari empat persona yang telah diidentifikasi, tim pengembang merumuskan tabel matriks spesifikasi kebutuhan fungsional sistem yang komprehensif. Fitur-fitur ini menjadi landasan dalam pembuatan UI/UX wireframe.');
gap(80);
tbl([
    ['Kategori Fitur / Kebutuhan', 'Deskripsi Fungsionalitas Sistem (Solusi UCD)', 'Target Persona', 'Prioritas Development'],
    ['Katalog Inventaris Real-Time', 'Sistem menampilkan daftar seluruh alat dengan indikator ketersediaan aktual (Tersedia, Terbatas, Habis). Stok otomatis terkunci sementara saat ada yang mengajukan.', 'Mahasiswa, Penjaga Lab', 'P1 (Critical)'],
    ['Peminjaman Pola E-Commerce', 'Penerapan konsep "Shopping Cart" di mana mahasiswa dapat memilih beberapa jenis alat sekaligus, menyesuaikan quantity, lalu checkout via form peminjaman 4-Langkah (Wizard).', 'Mahasiswa', 'P1 (Critical)'],
    ['Manajemen Transaksi 3-Zona', 'Dashboard penjaga lab memecah antrean jadi 3 zona warna: Kuning (Menunggu Persetujuan), Biru (Sedang Dipinjam), Hijau (Selesai). Persetujuan dan penolakan dapat dilakukan 1 kali klik.', 'Penjaga Lab', 'P1 (Critical)'],
    ['Penerimaan via Scan QR Code', 'Sistem menggunakan library html5-qrcode. Penjaga Lab cukup mengarahkan kamera HP/Webcam ke stiker QR di alat, sistem otomatis mencocokkan id transaksi dan menyelesaikan pengembalian.', 'Penjaga Lab', 'P2 (High)'],
    ['Mesin Kalkulasi Denda (Penalty Engine)', 'Fungsi cron-job atau kalkulasi on-the-fly yang menghitung: (Hari Terlambat * Rp 5.000). Hasil ditampilkan sebagai badge merah yang tidak bisa diubah secara manual tanpa otorisasi.', 'Penjaga Lab, Mahasiswa', 'P2 (High)'],
    ['Pembekuan Akun Otomatis', 'Jika mahasiswa memiliki tagihan denda belum dibayar atau alat yang terlambat belum dikembalikan, tombol "Pinjam" di katalog akan otomatis menjadi "Disabled" (Abu-abu) dan tidak bisa diklik.', 'Admin, Penjaga Lab', 'P2 (High)'],
    ['Visualisasi Data Kaprodi', 'Implementasi pustaka Chart.js untuk menggambar Bar Chart (Top 5 Alat Populer) dan Donut Chart (Rasio Kesehatan Alat). Data ditarik via query agregat SUM dan COUNT dari database secara live.', 'Kaprodi', 'P3 (Medium)'],
    ['Ekspor Laporan Akademis', 'Tombol untuk men-generate file PDF berformat kop surat universitas atau file Excel spreadsheet berisi seluruh rekam jejak transaksi semester terkait untuk keperluan asesmen.', 'Kaprodi, Admin', 'P3 (Medium)'],
    ['Eksplorasi Knowledge Graph', 'Modul UI SVG interaktif yang memetakan hubungan antar Entitas (Mahasiswa X meminjam Alat Y yang terhubung dengan MK Z) yang tidak bisa divisualisasikan dalam tabel biasa.', 'Admin, Kaprodi', 'P3 (Medium)'],
], [1900, 3900, 1600, 1500]);

// ═══════════════════════════════════════════════════════════════
//  4. USER FLOW DIAGRAM
// ═══════════════════════════════════════════════════════════════
sec('4', 'User Flow Diagram (Alur Pengguna)');
p('Sistem E-Lab Elektro mengimplementasikan dua arsitektur alur interaksi yang berbeda secara fundamental. Alur pertama ditujukan untuk efisiensi transaksi, sementara alur kedua ditujukan untuk eksplorasi analitik mendalam.');

gap(80);
subsec('4.1', 'Alur Linear Relasional — Siklus Transaksi Peminjaman (CRUD)');
p('Alur ini dirancang sangat linear dan preskriptif, memastikan pengguna tidak tersesat dalam proses administrasi. Alur ini melibatkan dua aktor (Mahasiswa dan Penjaga Lab) secara asinkron.');
gap(80);
tbl([
    ['No', 'Titik Interaksi', 'Tindakan Pengguna', 'Respon Sistem & Validasi Backend'],
    ['1', 'Halaman Login', 'Mahasiswa memasukkan NPM, password, dan verifikasi CAPTCHA.', 'Autentikasi. Jika berhasil, buat Session dan arahkan ke Dashboard Mahasiswa.'],
    ['2', 'Dashboard / Katalog', 'Mencari "Oscilloscope" dan menekan tombol pill "Pinjam".', 'Validasi stok > 0. Validasi status akun (tidak ada tunggakan). Alat ditambahkan ke tabel session Cart.'],
    ['3', 'Keranjang Belanja', 'Mahasiswa mengecek isi keranjang dan menekan "Lanjut ke Form".', 'Memulai UI Wizard 4 Langkah (Prototype Interaktif).'],
    ['4', 'Formulir Peminjaman', 'Mengatur "Jumlah Unit" dan memilih "Tanggal Kembali" (DatePicker).', 'Tombol "Ajukan" disabled otomatis jika input unit > stok tersedia fisik di database.'],
    ['5', 'Konfirmasi Akhir', 'Menyetujui syarat denda dan menekan "Ajukan Peminjaman".', 'Data di-insert ke tabel transaksi. Status="Menunggu Persetujuan". Stok dikurangi.'],
    ['6', 'Meja Penjaga Lab', 'Penjaga Lab melihat notifikasi di Zona Kuning, lalu menekan "Setujui".', 'Status di-update menjadi "Dipinjam". Data dipindah ke tabel Zona Biru.'],
    ['7', 'Pengembalian Alat', 'Penjaga Lab menekan "Scan QR" dan mengarahkan ke barcode alat.', 'Sistem mendeteksi ID transaksi. Cek tanggal kembali. Jika telat > hitung denda.'],
    ['8', 'Penyelesaian', 'Penjaga memverifikasi kondisi alat bagus, klik "Terima".', 'Status="Dikembalikan". Data pindah ke Zona Hijau (Arsip). Stok alat dikembalikan.'],
], [600, 1800, 3100, 3400]);
gap(160);

subsec('4.2', 'Alur Eksplorasi Knowledge Graph — Semantic Exploration');
p('Berbeda dengan tabel CRUD, alur ini bersifat eksploratif (non-linear). Pengguna dibebaskan menjelajahi hubungan antar-node tanpa urutan langkah yang baku. Sangat berguna untuk audit investigasi atau mencari pola penggunaan lab.');
gap(80);
tbl([
    ['Aktivitas Interaksi', 'Gesture UI', 'Hasil Interaksi Visual SVG'],
    ['Inisialisasi', 'Buka Menu Menu Graph', 'Sistem merender kanvas SVG dengan 5 Node Default (Dwiky, Oscilloscope, Prak.Mikro, Lab Tele, Dr.Misbah).'],
    ['Inspeksi Node', 'Single-Click Node (Kiri)', 'Sidebar kanan bergeser masuk (slide-in) menampilkan Panel "Detail Entitas Semantik" lengkap dengan meta-atribut.'],
    ['Ekspansi Relasi', 'Double-Click Node (Kiri)', 'Memicu query sub-graph. Node tersembunyi seperti Naza (Teman Kelompok Dwiky) atau Signal Generator akan muncul beranimasi dengan garis relasi baru.'],
    ['Penyaringan Visual', 'Klik Checkbox Panel Kanan', 'Semantic Filtering. Contoh: menghilangkan centang "Mahasiswa" akan secara instan menghapus semua node mahasiswa (hijau) dan relasinya dari kanvas.'],
    ['Manipulasi Posisi', 'Click-Hold & Drag', 'Pengguna menarik node untuk merapikan visual graf (implementasi layout force-directed physics dasar).'],
    ['Reset Keadaan', 'Klik Tombol "Reset Grafik"', 'Semua filter di-check kembali, node tambahan disembunyikan, kembali ke kondisi awal 5 Node Default.'],
], [1900, 2000, 5000]);

// ═══════════════════════════════════════════════════════════════
//  5. SITEMAP & STRUKTUR NAVIGASI
// ═══════════════════════════════════════════════════════════════
sec('5', 'Sitemap & Struktur Navigasi (Arsitektur Informasi)');
p('Sistem navigasi E-Lab Elektro menggunakan model sidebar adaptif (collapsed pada layar mobile < 768px). Modul aplikasi dibatasi ketat menggunakan middlewares Role-Based Access Control (RBAC). Tabel ini memetakan seluruh rute URL dan hak aksesnya.');
gap(80);
tbl([
    ['Kategori Modul Utama', 'Path (Rute URL)', 'Sub-Fitur / Halaman Spesifik', 'Hak Akses (Role)'],
    ['Autentikasi & Publik', '/auth', 'Halaman Login Utama, Form Lupa Password, Form Registrasi Mahasiswa Baru', 'Public (Guest)'],
    ['Dashboard (Beranda)', '/dashboard', 'Widget Statistik Personal, Banner Peringatan, Grafik Analitik (Kaprodi)', 'Semua Role'],
    ['Transaksi Operasional', '/peminjaman', 'Meja Penjaga (Kelola Transaksi), Riwayat Peminjaman, Scan QR Pengembalian', 'Penjaga Lab, Admin'],
    ['Portal Mahasiswa', '/peminjaman/form', 'Katalog Inventaris, Keranjang Belanja, Wizard Peminjaman, Cek Status', 'Mahasiswa'],
    ['Manajemen Inventaris', '/alat', 'Tabel Daftar Aset, Form Tambah/Edit Alat, Konfigurasi Stok, Cetak QR Code', 'Penjaga Lab, Admin'],
    ['Laporan Akademis', '/laporan', 'Dashboard Analitik Eksekutif, Cetak Laporan PDF/Excel, Laporan Alat Rusak', 'Kaprodi, Admin'],
    ['Knowledge Graph', '/ucd-showcase/graph', 'Visualisasi Graf SVG, Eksplorasi Node Relasi, Semantic Filtering', 'Kaprodi, Admin'],
    ['Pengaturan Keamanan', '/admin/users', 'Daftar Akun Pengguna, Ubah Hak Akses (Role), Tabel Log Audit Sistem', 'Admin Utama'],
    ['Profil Personal', '/profil', 'Tampilan Identitas, Ganti Password, Upload Crop Foto Profil', 'Semua Role'],
], [1800, 1600, 3600, 1900]);

// ═══════════════════════════════════════════════════════════════
//  6. WIREFRAME DAN MOCKUP
// ═══════════════════════════════════════════════════════════════
sec('6', 'Wireframe dan Mockup Antarmuka (Lengkap Seluruh Role)');
p('Tahap wireframing hitam-putih (Lo-Fi) dilakukan sebelum fase koding untuk menyelaraskan hierarki informasi, layout, dan elemen fungsional tanpa terdistraksi oleh estetika warna. Bagian ini menyajikan rancangan layar untuk seluruh tipe aktor pengguna dalam sistem.');
gap(80);

// -- 6.1 LOGIN --
subsec('6.1', 'Halaman Login (Gerbang Akses Utama)');
p('Desain menggunakan layout dua kolom asimetris (Split-Screen). Panel kiri difungsikan untuk penguatan branding institusi dengan ilustrasi gedung kampus atau logo Universitas Syiah Kuala yang berukuran besar. Panel kanan berfokus penuh pada konversi form login.');
if ($img_login) { embedImg($img_login, 14, 9); caption('Gambar 6.1 — Wireframe Lo-Fi Halaman Login Sistem E-Lab Elektro'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Input NPM/Username & Sandi', 'Label selalu di luar field agar tidak hilang saat diketik. Ikon mata (show/hide) ditambahkan untuk mengurangi error pengetikan sandi di layar sentuh.'],
    ['Widget CAPTCHA', 'Ditempatkan tepat di atas tombol submit. Wajib untuk melindungi endpoint login dari serangan brute-force otomatis.'],
    ['Tombol Aksi (Call to Action)', 'Tombol "Masuk" dibuat Full-Width dan menggunakan sudut melengkung (rounded-pill) warna oranye untuk menegaskan ini adalah langkah paling krusial.'],
    ['Tautan Navigasi Darurat', '"Lupa Password?" dan "Belum punya akun?" meminimalisir jalan buntu (dead-end) bagi pengguna yang bermasalah dengan kredensialnya.'],
], [2500, 6400]);
gap(160);

// -- 6.2 ADMIN --
subsec('6.2', 'Dashboard Admin (Pusat Kendali Operasional)');
p('Tampilan ini memprioritaskan penyajian makro metrik sistem. Dirancang khusus untuk memonitor lalu lintas data dan kesehatan aplikasi secara agregat (keseluruhan).');
if ($img_dash_admin) { embedImg($img_dash_admin, 14, 9); caption('Gambar 6.2 — Wireframe Lo-Fi Dashboard Admin (Statistik Agregat)'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['4 Kartu Statistik Atas', 'Menerapkan Hukum Gestalt (Proximity). Menyajikan data terpenting: Total Inventaris (Box), Persetujuan Tertunda (Warning), Peminjaman Aktif (Cart), dan Total Mahasiswa (Flask).'],
    ['Grafik Distribusi Data', 'Bar chart (Top 5 Alat) di kiri dan Donut chart (Bagus vs Rusak) di kanan memberikan insight kuantitatif sekilas tanpa harus membaca baris tabel panjang.'],
    ['Tabel Current Inventory', 'Tabel di bagian bawah menggunakan scroll vertikal internal (infinite scroll) untuk memastikan struktur dashboard atas tidak terdorong ke bawah oleh data yang ratusan baris.'],
], [2500, 6400]);
gap(160);

// -- 6.3 KAPRODI --
subsec('6.3', 'Dashboard Kaprodi (Analitik dan Pelaporan Eksekutif)');
p('Tampilan ini dioptimalkan untuk pengambil keputusan. Sangat mirip dengan dashboard Admin namun mengurangi fungsi operasional (tidak ada tombol kelola alat) dan menambahkan fungsi ekspor data historis untuk keperluan rapat struktural.');
if ($img_dash_kaprodi) { embedImg($img_dash_kaprodi, 14, 9); caption('Gambar 6.3 — Wireframe Lo-Fi Dashboard Kaprodi (Fokus Laporan & Analitik)'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Tombol Ekspor Laporan', 'Ditempatkan secara strategis di sudut kanan atas tabel utama (Download PDF / Download Excel) untuk memenuhi kebutuhan dokumentasi akreditasi prodi.'],
    ['Tabel Overdue (Keterlambatan)', 'Ditandai dengan border warna merah menyala (Law of Similarity) agar Kaprodi menyadari adanya mahasiswa yang melanggar batas waktu dalam jumlah signifikan.'],
    ['Proporsi Grafik', 'Lebar grafik bar chart dibuat lebih dominan (70% viewport) karena label nama alat membutuhkan ruang horizontal yang panjang agar tidak terpotong (truncated).'],
], [2500, 6400]);
gap(160);

// -- 6.4 MAHASISWA --
subsec('6.4', 'Dashboard Mahasiswa (Portal Peminjaman)');
p('Antarmuka untuk mahasiswa sangat disederhanakan. Fokus utama difokuskan pada ketersediaan stok fisik alat dan peringatan (alert) terhadap kewajiban mahasiswa yang tertunda (denda/tunggakan).');
if ($img_dash_mhs) { embedImg($img_dash_mhs, 14, 9); caption('Gambar 6.4 — Wireframe Lo-Fi Dashboard Mahasiswa dengan Sistem Peringatan Aktif'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Banner Peringatan (Alert Merah)', 'Contoh penerapan Heuristik (Visibility of System Status). Jika mahasiswa telat mengembalikan alat, banner ini otomatis muncul paling atas, memblokir interaksi form lain sampai diselesaikan.'],
    ['Tombol Pinjam (Aksi Dinamis)', 'Tombol pill di kanan tabel. Sesuai Hukum Fitts (Target besar mudah diklik). Jika ada banner merah menyala, tombol ini otomatis dinonaktifkan (disable) menjadi warna abu-abu (Error Prevention).'],
    ['Ikon Keranjang (Cart Badge)', 'Terletak di pojok kanan navbar, familiar bagi generasi milenial yang sering berbelanja e-commerce. Terdapat angka (badge) notifikasi jumlah alat di dalamnya.'],
], [2500, 6400]);
gap(160);

// -- 6.5 PENJAGA LAB --
subsec('6.5', 'Meja Penjaga Lab (Manajemen Lalu Lintas Transaksi)');
p('Ini merupakan antarmuka paling kritikal untuk operasional harian. Layar ini memisahkan prioritas pekerjaan penjaga lab menjadi 3 zona warna yang berurutan secara vertikal untuk mengurangi beban kognitif (cognitive load).');
if ($img_penjaga) { embedImg($img_penjaga, 14, 9); caption('Gambar 6.5 — Wireframe Lo-Fi Meja Penjaga Lab (Manajemen Transaksi Multi-Zona)'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Zona 1 (Kuning): Validasi Baru', 'Antrean teratas. Mahasiswa yang sedang berdiri di depan lab menunggu persetujuan alat. Penjaga cukup menekan 1 tombol hijau "Setujui" tanpa membuka halaman baru.'],
    ['Zona 2 (Biru): Sedang Dipinjam', 'Memantau siapa yang sedang menahan alat. Jika kolom batas waktu sudah terlewati, kolom "Denda Aktif" akan otomatis menampilkan nominal (misal: Rp 15.000) dengan badge merah.'],
    ['Tombol "Scan QR Pengembalian"', 'Terletak di Header Zona 2. Membuka modal kamera (webcam/HP). Mempercepat proses ketimbang harus mencari nama mahasiswa satu per satu di tabel pencarian.'],
    ['Zona 3 (Hijau): Riwayat Selesai', 'Arsip pasif di bagian paling bawah. Digunakan hanya untuk verifikasi perselisihan historis.'],
], [2500, 6400]);
gap(160);

// -- 6.6 PROTOTYPE PEMINJAMAN --
subsec('6.6', 'Prototype Form Peminjaman (4-Step Wizard)');
p('Menggantikan formulir HTML panjang yang membosankan menjadi 4 tahapan kecil yang interaktif. Hal ini terbukti secara riset UI mengurangi angka pentalan (bounce rate) atau pembatalan pengisian form.');
if ($img_pinjam) { embedImg($img_pinjam, 14, 9); caption('Gambar 6.6 — Wireframe Lo-Fi Prototype Interaktif Peminjaman (Wizard Form)'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Progress Indicator (Navigasi Atas)', 'Menerapkan Heuristik Nielsen #1. Pengguna selalu tahu sedang di langkah 2 dari 4. Titik indikator terhubung oleh garis progress bar.'],
    ['Validasi Stok Real-Time', 'Di Step 2, input kotak "Jumlah Unit" dikunci atribut max-nya. Mahasiswa tidak bisa mengetik angka 10 jika stok alat hanya sisa 5 di rak lab.'],
    ['Ringkasan Konfirmasi (Step 3)', 'Penerapan Heuristik #5 (Error Prevention). Memaksa mahasiswa mereview kembali pilihan alat, durasi, dan peringatan besaran denda (Teks Kuning) sebelum data masuk database.'],
], [2500, 6400]);
gap(160);

// -- 6.7 MANAJEMEN INVENTARIS --
subsec('6.7', 'Manajemen Inventaris Alat (Database Aset)');
p('Halaman master data untuk mengelola informasi fisik barang. Hanya dapat diubah (mutasi data) oleh Penjaga Lab dan Admin Utama.');
if ($img_invent) { embedImg($img_invent, 14, 9); caption('Gambar 6.7 — Wireframe Lo-Fi Halaman Manajemen Inventaris Alat Laboratorium'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Input Pencarian & Filter Cepat', 'Kotak pencarian teks bebas berdampingan dengan Dropdown Kategori (Cth: Alat Ukur, Komponen, Modul) mempercepat navigasi untuk ribuan baris data aset.'],
    ['Badge Status Kondisional', 'Kolom status menggunakan shape badge dengan warna psikologis: Hijau (Tersedia), Kuning (Terbatas <= 2 unit), Merah (Habis / Rusak) — identifikasi sekilas mata.'],
    ['Aksi Generate QR Code', 'Tersedia tombol ikon cetak di setiap baris alat untuk men-generate gambar QRCode berformat PDF yang bisa diprint di kertas stiker dan ditempel pada fisik osiloskop/multimeter.'],
], [2500, 6400]);
gap(160);

// -- 6.8 PROFIL PENGGUNA --
subsec('6.8', 'Halaman Profil dan Pengaturan Keamanan Akun');
p('Ruang personalisasi bagi pengguna untuk memvalidasi identitas, mengelola metadata mereka, dan melakukan pembaharuan kata sandi sistem secara mandiri.');
if ($img_profil) { embedImg($img_profil, 14, 9); caption('Gambar 6.8 — Wireframe Lo-Fi Halaman Profil Pengguna'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Avatar & Upload Interaktif', 'Foto profil lingkaran (Circle Avatar) dengan overlay tombol kamera transparant. Menerapkan pustaka Cropper.js untuk zoom dan potong foto persegi langsung di browser sebelum simpan.'],
    ['Pemilahan Layout 2 Kolom', 'Kolom Kiri difokuskan pada manipulasi profil (Aksi Utama: Ubah Identitas, Ganti Sandi). Kolom Kanan difokuskan pada metadata readonly institusi (Universitas, Prodi, Status Akun).'],
    ['Mini Stats Mahasiswa', 'Bagi mahasiswa, kolom kiri menempelkan 2 kotak info krusial: "Total Peminjaman Historis" dan "Sisa Kuota Peminjaman Aktif".'],
], [2500, 6400]);
gap(160);

// -- 6.9 KNOWLEDGE GRAPH --
subsec('6.9', 'Knowledge Graph Visualization (Grafik Semantik SVG)');
p('Merupakan antarmuka non-relasional tercanggih dalam sistem. Menggantikan query tabular yang kaku menjadi visualisasi jaringan node yang bisa disentuh, ditarik, dan disaring secara dinamis.');
if ($img_kg) { embedImg($img_kg, 14, 9); caption('Gambar 6.9 — Wireframe Lo-Fi Knowledge Graph SVG Interaktif dengan Semantic Filter'); }
gap(80);
tbl([
    ['Komponen UI Kritis', 'Justifikasi Desain Berpusat Pengguna (UCD)'],
    ['Kanvas SVG Interaktif', 'Menggunakan ruang layar luas. Node direpresentasikan sebagai bentuk geometris warna-warni berlabel. Edge direpresentasikan sebagai panah tegak (directional arrow) dengan teks relasi.'],
    ['Panel Semantic Filtering (Kanan)', 'Terdapat 5 kotak centang (Checkbox) sesuai 5 tipe entitas. Uncheck kotak "Dosen", maka seluruh node biru beserta relasinya akan menghilang (fade out) seketika tanpa refresh web.'],
    ['Interaksi Drill-Down (Double Click)', 'Untuk mencegah UI berantakan oleh ratusan node, graf hanya memuat 5 node utama (default). Node tersembunyi baru akan di-expand dari server jika pengguna melakukan double-click pada node spesifik.'],
], [2500, 6400]);

// ═══════════════════════════════════════════════════════════════
//  7. JUSTIFIKASI DESAIN
// ═══════════════════════════════════════════════════════════════
sec('7', 'Justifikasi Desain (Design Rationale)');
p('Seluruh perancangan UI/UX dalam sistem E-Lab Elektro ini berakar dari teori interaksi manusia-komputer (Human-Computer Interaction / HCI) dan psikologi kognitif untuk memastikan antarmuka tidak membebani pemikiran pengguna secara berlebih (low cognitive load).');

gap(80);
subsec('7.1', 'Hukum Fitts (Fitts\'s Law) — Penempatan Tombol Akselerasi');
p('Teori: Paul Fitts (1954) merumuskan bahwa waktu untuk menjangkau target berbanding lurus dengan jarak ke target dan berbanding terbalik dengan ukuran target. Secara logis, tombol yang kecil dan tersembunyi akan memperlambat operasional.');
p('Implementasi: Di E-Lab Elektro, seluruh tombol aksi utama (CTA) seperti "Pinjam Alat" dan "Setujui Transaksi" didesain berbentuk kapsul melengkung (rounded-pill) dengan tinggi minimal 42px (ukuran tap-friendly untuk layar sentuh). Tombol ini disejajarkan di sisi paling kanan dari tabel, bertepatan dengan titik henti (stopping point) mata saat membaca baris dari kiri ke kanan.');

gap(80);
subsec('7.2', 'Hukum Gestalt (Gestalt Principles of Grouping)');
p('Teori: Manusia menganggap elemen visual yang berdekatan atau memiliki karakteristik serupa sebagai satu kesatuan logis.');
p('Implementasi Law of Proximity (Kedekatan): Pada Dashboard, 4 widget statistik dikelompokkan rapat di baris paling atas (above the fold) tanpa ada elemen pengganggu di sela-selanya.');
p('Implementasi Law of Similarity (Kesamaan Warna): Sistem menggunakan kode palet yang sangat ketat di seluruh halaman. Warna Merah (#ef4444) selalu merepresentasikan bahaya, rusak, terlambat, denda, hapus. Warna Hijau (#10b981) selalu merepresentasikan tersedia, aman, selesai, setujui. Pengguna dengan cepat memahami konteks tanpa membaca teks label.');

gap(80);
subsec('7.3', 'F-Pattern Eye Tracking');
p('Teori: Studi Nielsen Norman Group menyimpulkan mata pengguna memindai web membentuk pola huruf F (Atas horizontal, turun sedikit, tengah horizontal pendek, lalu vertikal ke bawah di sisi kiri).');
p('Implementasi: Sidebar navigasi utama E-Lab ditempatkan secara permanen (fixed) di sebelah kiri layar (zona F pertama). Informasi paling krusial seperti "Peringatan Keterlambatan" diletakkan di baris atas halaman. Sementara pojok kanan bawah dialokasikan untuk tombol sekunder seperti export atau pagination.');

// ═══════════════════════════════════════════════════════════════
//  8. EVALUASI HEURISTIK NIELSEN
// ═══════════════════════════════════════════════════════════════
sec('8', 'Evaluasi Heuristik Nielsen (Usability Inspection)');
p('Tahap verifikasi usability antarmuka dilakukan oleh desainer ahli (Expert Review) menggunakan matriks 10 Prinsip Heuristik Jakob Nielsen (1995). Skala penilaian tingkat keparahan (Severity Rating) berkisar antara 0 (bukan masalah) hingga 4 (Bencana / Catastrophe). Berikut 5 temuan teratas dan resolusinya:');
gap(80);

tbl([
    ['Kode Heuristik', 'Prinsip', 'Temuan Masalah di E-Lab Elektro (Pra-Revisi)', 'Skala Severity', 'Solusi Perbaikan (Desain Revisi Final)'],
    ['H1', 'Visibility of System Status', 'Pengguna tidak mendapat umpan balik saat sistem sedang mengirim pengajuan peminjaman ke server. Layar "freeze" 2 detik dan pengguna panik mengklik tombol berulang kali.', '3 (Major)', 'Implementasi loading spinner (animasi putar) dengan overlay semi-transparan menutupi layar. Progress bar dipasang pada bagian atas modul Wizard Peminjaman.'],
    ['H2', 'Match Between System & Real World', 'Antarmuka awal menggunakan istilah teknis database murni seperti "id_alat", "user_role_level", dan "tgl_kembali" yang membingungkan mahasiswa awam.', '2 (Minor)', 'Seluruh teks label diganti ke bahasa komunikasi kampus sehari-hari: "Nama Alat" (id_alat), "NPM" (username), "Penjaga Lab" (admin_level2), "Denda Terlambat" (penalty_amt).'],
    ['H3', 'User Control and Freedom', 'Mahasiswa yang tidak sengaja memasukkan alat yang salah ke dalam Keranjang Belanja tidak menemukan cara untuk menghapus item tersebut sebelum check-out.', '3 (Major)', 'Penyediaan "Pintu Darurat". Setiap baris di keranjang belanja ditambahkan tombol tong sampah (Hapus Item). Semua modal konfirmasi diberikan tombol Batal/Kembali (Abu-abu) yang besar.'],
    ['H4', 'Consistency and Standards', 'Warna tombol aksi bercampur aduk. Tombol simpan di halaman profil berwarna biru, sedangkan di formulir peminjaman berwarna hijau.', '2 (Minor)', 'Pembuatan pedoman Design System UI Component standar. Oranye (#ea580c) mutlak untuk tombol Submit/Ajukan primer. Hijau hanya untuk konfirmasi sukses/terima.'],
    ['H5', 'Error Prevention', 'Mahasiswa dapat dengan bebas mengetik angka "999" pada input jumlah pinjam mesin Osiloskop meskipun di rak lab hanya tersedia 5 unit. Mengakibatkan error fatal di database.', '4 (Catastrophe - Wajib Diperbaiki)', 'Implementasi Validasi Frontend agresif. Atribut max="" pada elemen input di-bind dengan variabel $stok PHP. Jika mengetik lebih dari max, tombol Checkout otomatis didisable (diabu-abukan) dan muncul teks merah peringatan.'],
], [400, 1500, 2600, 1000, 3400]);
gap(120);

// ═══════════════════════════════════════════════════════════════
//  9. PENGUJIAN SUS
// ═══════════════════════════════════════════════════════════════
sec('9', 'Rencana Pengujian Kuantitatif: System Usability Scale (SUS)');
p('Walaupun masalah usability major telah dibersihkan via Evaluasi Heuristik, E-Lab Elektro tetap merencanakan validasi empiris dari pengguna sesungguhnya (User Acceptance Testing) menggunakan kuesioner baku SUS (Brooke, 1996). Instrumen ini diakui secara akademis mampu menghasilkan skor metrik tunggal (0-100) mengenai kelayakan sistem.');

gap(80);
subsec('9.1', 'Metodologi & Parameter Pelaksanaan');
tbl([
    ['Parameter', 'Rincian Pelaksanaan Rencana'],
    ['Instrumen Data', 'Kuesioner tertutup 10 pertanyaan standar dengan skala Likert 1-5 (Sangat Tidak Setuju hingga Sangat Setuju).'],
    ['Kriteria Demografi Responden', 'Melibatkan total 12 responden perwakilan nyata: 8 Mahasiswa tingkat akhir (skripsi), 2 Laboran/Asisten Lab, dan 2 Dosen Pembimbing (Prodi).'],
    ['Lingkungan Pengujian', 'Laboratorium komputer kampus dengan koneksi intranet. Responden diberikan Akun Demo (dummy user) dengan role spesifik tanpa campur tangan data asli.'],
    ['Prosedur (Workflow)', '1. Briefing singkat (5 mnt). 2. Responden menyelesaikan 3 skenario tugas terstruktur (15 mnt). 3. Pengisian form SUS Google Form (5 mnt).'],
    ['Rumus Kalkulasi Skor SUS', 'Rumus Baku: [(Skor Pertanyaan Ganjil - 1) + (5 - Skor Pertanyaan Genap)] x 2.5'],
    ['Target Indikator Keberhasilan', 'Sistem dinyatakan Go-Live jika skor rata-rata SUS komposit mencapai angka minimal 68 (Kategori "Acceptable" / Grade C). Jika di atas 80 (Grade A / Excellent).'],
], [2800, 6100]);
gap(160);

subsec('9.2', 'Kuesioner SUS dan Skenario Tugas (Task Scenario)');
p('Skenario simulasi yang wajib diselesaikan responden mahasiswa sebelum pengisian SUS:');
bul('Skenario 1 (Pencarian & Observasi): Silakan masuk ke aplikasi, cari alat bernama "Multimeter Fluke", dan laporkan berapa unit yang benar-benar siap dipinjam (Tersedia) di layar Anda.');
bul('Skenario 2 (Transaksi Peminjaman): Silakan masukkan alat "Signal Generator" ke keranjang, tentukan peminjaman selama 3 hari, dan ajukan sampai Anda mendapat nomor tiket Referensi warna hijau.');
gap(80);
p('Setelah Skenario selesai, ke-10 pertanyaan standar berikut (telah dilokalisasi ke Bahasa Indonesia) diberikan:');
tbl([
    ['ID', 'Butir Pertanyaan Kuesioner SUS'],
    ['Q1', 'Saya berpikir bahwa saya akan sering menggunakan sistem E-Lab Elektro ini.'],
    ['Q2', 'Saya merasa sistem ini terlalu rumit dan kompleks padahal bisa dibuat lebih sederhana.'],
    ['Q3', 'Saya merasa sistem ini mudah untuk digunakan (user-friendly).'],
    ['Q4', 'Saya pikir saya akan membutuhkan bantuan dari staf IT atau panduan khusus untuk dapat menggunakannya.'],
    ['Q5', 'Saya menemukan bahwa berbagai macam fungsi dan menu dalam sistem ini terintegrasi dengan sangat baik.'],
    ['Q6', 'Saya merasa banyak sekali inkonsistensi (hal yang tidak standar) dari tampilan sistem ini.'],
    ['Q7', 'Saya membayangkan bahwa kebanyakan orang akan dapat mempelajari sistem ini dengan sangat cepat.'],
    ['Q8', 'Saya mendapati sistem ini sangat canggung/kaku (cumbersome) untuk digunakan.'],
    ['Q9', 'Saya merasa sangat percaya diri saat mengoperasikan sistem ini.'],
    ['Q10', 'Saya merasa perlu belajar banyak hal baru sebelum bisa memulai memanfaatkan sistem ini.'],
], [500, 8400]);

// ═══════════════════════════════════════════════════════════════
//  10. PROTOTYPE INTERAKTIF
// ═══════════════════════════════════════════════════════════════
sec('10', 'Prototype Interaktif Peminjaman (4-Step Wizard)');
p('Inovasi utama E-Lab Elektro untuk mahasiswa adalah merombak formulir panjang yang mengintimidasi menjadi pola interaktif 4 tahapan ringkas (Wizard). Pola ini mengurangi beban memori kerja otak manusia secara drastis.');
tbl([
    ['Tahap', 'Konteks UI', 'Aksi Interaktif & Validasi Sistem'],
    ['Step 1', 'Pilih dari Katalog Aset', 'Mahasiswa hanya fokus mencari barang. Tidak ada input teks, cukup tekan tombol "Pinjam ->". Validasi: Alat yang stok = 0 tidak akan bisa di-klik sama sekali (tombol mati/disable).'],
    ['Step 2', 'Lengkapi Metadata Form', 'Menampilkan panel tanggal DatePicker modern. Sistem mencegah mahasiswa memilih tanggal masa lalu. Mahasiswa mengetik unit jumlah, script frontend memantau agar tidak mengetik melampaui sisa unit.'],
    ['Step 3', 'Review & Konfirmasi', 'Fase kritis pencegahan human-error. Menampilkan panel kuning besar berisi peringatan tertulis mengenai denda Rp 5.000/hari dan menuntut pengguna meng-klik konfirmasi sadar.'],
    ['Step 4', 'Penerbitan Tiket (Sukses)', 'Feedback akhir yang melegakan. Gambar centang animasi. Menyediakan tombol pintas (shortcut) untuk kembali mengeksplorasi katalog alat lain atau memantau status persetujuan di dashboard.'],
], [800, 2200, 5900]);

// ═══════════════════════════════════════════════════════════════
//  11-13. KNOWLEDGE GRAPH & KESIMPULAN
// ═══════════════════════════════════════════════════════════════
sec('11', 'Knowledge Graph Visualization Design (Desain UI Semantik)');
p('Sebagai pembeda revolusioner dari sistem akademik tradisional, E-Lab Elektro mengemas teknologi Knowledge Graph dalam kanvas interaktif Scalable Vector Graphics (SVG) yang diprogram dengan JavaScript untuk merespon interaksi hover, klik, dan filter kategori.');
tbl([
    ['Meta-Elemen Graf', 'Parameter Visual UI', 'Deskripsi Atribut Semantik Representatif'],
    ['Node (Mahasiswa)', 'Lingkaran, Radius 30px, Warna: #10b981 (Hijau Emerald)', 'Berisi Property: NIM, Nama, Semester, IPK, Riwayat Transaksi'],
    ['Node (Alat Inventaris)', 'Lingkaran/Kotak, Warna: #f59e0b (Emas Solid)', 'Berisi Property: Kode Aset, Merek, Lokasi Rak Fisik di Laboratorium'],
    ['Node (Mata Kuliah)', 'Bentuk Wajik (Diamond), Warna: #8b5cf6 (Ungu)', 'Berisi Property: Kode MK (Misal: EL-302), SKS, Deskripsi Silabus'],
    ['Edge (Relasi MEMINJAM)', 'Garis Tebal 3px, Animasi Mengalir, Warna: Merah Panah', 'Menghubungkan Node Mahasiswa (Kiri) ke Node Alat (Kanan) secara direct'],
    ['Edge (Relasi MENGAJAR)', 'Garis Titik-Titik (Dashed), Panah Biru Muda', 'Menghubungkan Node Dosen Pembimbing ke Node Praktikum Mata Kuliah'],
], [1900, 2800, 4200]);

sec('12', 'Perbandingan Paradigma UI: Relational System vs Knowledge Graph');
p('Sistem E-Lab Elektro mengkomodir kebutuhan dua ekstrem dunia database. Tabel komparasi berikut membedah kapan sebuah antarmuka digunakan dalam konteks aplikasi.');
tbl([
    ['Parameter Komparasi', 'Antarmuka CRUD Tabel Biasa (Relasional)', 'Antarmuka Visual Knowledge Graph SVG'],
    ['Metode Presentasi', 'Struktur Grid tabular (Baris x Kolom kaku)', 'Topologi Jaringan Fleksibel (Node x Edge)'],
    ['Pola Pencarian (Mental Model)', '"Saya tahu pasti apa yang saya cari di kotak pencarian" (Deterministik)', '"Saya ingin melihat hubungan antar data yang tidak terduga" (Eksploratif / Serendipity)'],
    ['Pengguna Terbaik', 'Penjaga Lab, Mahasiswa (Butuh transaksi kilat/cegat)', 'Kaprodi, Tim Audit Eksekutif (Butuh insight makro)'],
    ['Penanganan Data Relasi Jauh', 'Sangat buruk. SQL Join > 3 tabel akan menghasilkan antarmuka yang sangat padat, duplikatif dan lambat.', 'Sangat cemerlang. Relasi teman ke teman ke dosen divisualisasikan dengan garis lompatan (multi-hop traversal).'],
], [1800, 3500, 3600]);

sec('13', 'Kesimpulan Utama dan Saran Tindak Lanjut');
p('Laporan komprehensif (Lengkap Seluruh Aktor) mengenai evaluasi perancangan User Interface (UI) dan User Experience (UX) Sistem E-Lab Elektro menghasilkan kesimpulan akhir:');
bul('Matriks Kebutuhan Terselesaikan: Desain UI/UX telah berhasil mengakomodasi kebutuhan unik dari 4 aktor utama (Mahasiswa, Penjaga Lab, Kaprodi, Admin). Tidak ada aktor yang diforsir untuk menggunakan antarmuka campuran yang membingungkan.');
bul('Penyembuhan Usability Kritikal: 100% dari masalah Severity 3 (Major) dan Severity 4 (Catastrophe)—seperti absennya validasi stok real-time, layar freeze tanpa feedback, dan ketiadaan tombol batal checkout—telah dikoreksi total pada revisi wireframe final ini.');
bul('Integrasi Dual-Paradigm: Pembuktian bahwa tabel relasional untuk efisiensi operasional dan Kanvas Graf SVG interaktif untuk kepentingan intelijen data (business intelligence) dapat disatukan dalam satu ekosistem web tanpa merusak hirarki navigasi situs.');
gap(80);
p('Berdasarkan pencapaian tersebut, tim perancang UX memberikan rekomendasi (saran) tindak lanjut teknis untuk tahap production (live deploy):');
bul('Prioritas 1: Segera laksanakan sesi pengujian SUS lapangan terhadap 12 responden riil dan targetkan pencapaian skor System Usability Scale (SUS) minimum di angka 75 (Good/Acceptable).');
bul('Prioritas 2: Kembangkan modul tambahan integrasi Internet of Things (IoT), di mana antarmuka "Meja Penjaga Lab" dapat langsung menerima sinyal dari pemindai Barcode Gun fisik via WebSocket tanpa harus memencet tombol kamera manual.');

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

$outFile      = __DIR__ . '/Laporan_UCD_ELab_Elektro_LengkapSemuaUser.doc';

$writeResult = @file_put_contents($outFile, $rtf);

if ($writeResult === false) {
    echo "GAGAL: Tidak dapat menulis file laporan.\n";
} else {
    echo "=" . str_repeat("=", 55) . "\n";
    echo " LAPORAN BERHASIL DIBUAT (VERSI TABEL TERLENGKAP)!\n";
    echo "=" . str_repeat("=", 55) . "\n";
    echo " File  : {$outFile}\n";
    echo " Ukuran: " . number_format(filesize($outFile)/1024, 1) . " KB\n";
    echo "=" . str_repeat("=", 55) . "\n";
}
