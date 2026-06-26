<?php
/**
 * Laporan UCD E-Lab Elektro
 * Font: Times New Roman 12pt, Spasi 1.5, Warna Hitam
 * Struktur: Per poin (tanpa BAB)
 */

function r($text) {
    $text = str_replace('\\', '\\\\', $text);
    $text = str_replace('{', '\\{', $text);
    $text = str_replace('}', '\\}', $text);
    $out = '';
    for ($i = 0; $i < strlen($text); ) {
        $b = ord($text[$i]);
        if ($b < 0x80) { $out .= $text[$i]; $i++; }
        elseif ($b < 0xE0) {
            $cp = (($b & 0x1F) << 6) | (ord($text[$i+1]) & 0x3F);
            $out .= $cp < 256 ? sprintf("\\'%02x", $cp) : sprintf("\\u%d?", $cp);
            $i += 2;
        } elseif ($b < 0xF0) {
            $cp = (($b & 0x0F) << 12) | ((ord($text[$i+1]) & 0x3F) << 6) | (ord($text[$i+2]) & 0x3F);
            $out .= sprintf("\\u%d?", $cp);
            $i += 3;
        } else { $out .= '?'; $i += 4; }
    }
    return $out;
}

$o = '';
// Base paragraph settings: Times New Roman (f0), 12pt (fs24), black (cf0), 1.5 spacing (sl360 slmult1)
$BASE = '\\f0\\fs24\\cf0\\sl360\\slmult1';

function ln($text, $bold=false, $align='j', $spb=0, $spa=120, $indent=0) {
    global $o, $BASE;
    $b  = $bold ? '\\b' : '\\b0';
    $q  = "\\q{$align}";
    $li = $indent > 0 ? "\\li{$indent}\\fi-{$indent}" : '';
    $o .= "\\pard{$q}\\sb{$spb}\\sa{$spa}{$li}{$BASE}{$b} " . r($text) . "\\par\n";
}

function sec($num, $title) {
    // Section heading: bold, 12pt, uppercase-style, with space before
    global $o, $BASE;
    $o .= "\\pard\\ql\\sb280\\sa80{$BASE}\\b " . r($num . ". " . strtoupper($title)) . "\\par\n";
    // underline rule
    $o .= "\\pard\\ql\\sb0\\sa120\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
}

function subsec($num, $title) {
    global $o, $BASE;
    $o .= "\\pard\\ql\\sb200\\sa80{$BASE}\\b " . r($num . "  " . $title) . "\\par\n";
}

function p($text, $bold=false, $spa=120) {
    ln($text, $bold, 'j', 0, $spa);
}

function bul($text) {
    global $o, $BASE;
    $o .= "\\pard\\qj\\li560\\fi-280\\sb0\\sa80{$BASE}\\b0 \\bullet   " . r($text) . "\\par\n";
}

function gap($n=160) {
    global $o;
    $o .= "\\pard\\sb0\\sa{$n}\\par\n";
}

function pg() {
    global $o;
    $o .= "\\page\n";
}

function tbl($rows, $widths, $headerIdx=0) {
    global $o, $BASE;
    foreach ($rows as $ri => $cells) {
        $isHdr = ($ri === $headerIdx);
        $tw = 0; $defs = '';
        foreach ($widths as $w) {
            $tw += $w;
            $defs .= "\\clbrdrt\\brdrs\\brdrw8\\clbrdrl\\brdrs\\brdrw8\\clbrdrb\\brdrs\\brdrw8\\clbrdrr\\brdrs\\brdrw8";
            if ($isHdr) $defs .= "\\clcbpat1"; // light grey header
            $defs .= "\\cellx{$tw}";
        }
        $o .= "\\trowd\\trgaph80\\trleft0 {$defs}\n";
        foreach ($cells as $cell) {
            $b = $isHdr ? '\\b' : '\\b0';
            $o .= "\\pard\\intbl\\ql\\sb60\\sa60\\sl276\\slmult1{$b}\\f0\\fs22\\cf0 " . r($cell) . "\\cell\n";
        }
        $o .= "\\row\n";
    }
}

// =====================================================================
// HALAMAN SAMPUL
// =====================================================================
$o .= "\\pard\\qc\\sb1200\\sa0{$BASE}\\b UNIVERSITAS SYIAH KUALA\\par\n";
$o .= "\\pard\\qc\\sb40\\sa0{$BASE}\\b0 Program Studi Teknik Elektro \\emdash  Fakultas Teknik\\par\n";
$o .= "\\pard\\qc\\sb0\\sa0\\brdrb\\brdrs\\brdrw20\\brdrcf0 \\par\n";
gap(400);
$o .= "\\pard\\qc\\sb0\\sa80{$BASE}\\b LAPORAN EVALUASI UI/UX\\par\n";
$o .= "\\pard\\qc\\sb0\\sa80{$BASE}\\b SISTEM E-LAB ELEKTRO\\par\n";
$o .= "\\pard\\qc\\sb0\\sa0\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
gap(200);
$o .= "\\pard\\qc\\sb0\\sa80{$BASE}\\b0 Pendekatan User-Centered Design (UCD) dan Evaluasi Heuristik Nielsen\\par\n";
gap(400);
$o .= "\\pard\\qc\\sb0\\sa60{$BASE}\\b0 Disusun oleh:\\par\n";
$o .= "\\pard\\qc\\sb0\\sa60{$BASE}\\b Tim Pengembang E-Lab Elektro\\par\n";
$o .= "\\pard\\qc\\sb0\\sa60{$BASE}\\b0 Program Studi Teknik Elektro\\par\n";
$o .= "\\pard\\qc\\sb0\\sa60{$BASE}\\b0 Universitas Syiah Kuala\\par\n";
gap(400);
$o .= "\\pard\\qc\\sb0\\sa0{$BASE}\\b0 Banda Aceh, 2025\\par\n";
pg();

// =====================================================================
// 1. PENDAHULUAN
// =====================================================================
sec('1', 'Pendahuluan');
p('Laboratorium Teknik Elektro Universitas Syiah Kuala (USK) menyimpan ratusan unit alat ukur dan komponen elektronika yang digunakan oleh mahasiswa untuk praktikum dan tugas akhir. Proses peminjaman alat yang selama ini dilakukan secara manual dengan buku catatan fisik menimbulkan berbagai masalah operasional:');
bul('Mahasiswa tidak mengetahui ketersediaan alat sebelum datang ke lab secara fisik.');
bul('Penjaga lab harus menghitung denda keterlambatan secara manual dan rawan keliru.');
bul('Kaprodi tidak memiliki data analitik real-time tentang penggunaan dan kondisi alat.');
bul('Tidak ada rekam jejak digital aktivitas peminjaman untuk keperluan audit.');
gap();
p('Sistem E-Lab Elektro dikembangkan sebagai solusi berbasis web menggunakan framework CodeIgniter 4 (PHP 8.3) dengan database MySQL 8.4, yang mengintegrasikan dua paradigma pengelolaan data:');
bul('Relational Database (MySQL): untuk manajemen transaksi CRUD peminjaman alat secara terstruktur.');
bul('Knowledge Graph (Graf Semantik SVG): untuk eksplorasi relasi semantik antar entitas (Mahasiswa, Alat, Mata Kuliah, Dosen, Ruang Lab) secara visual dan interaktif.');
gap();
p('Laporan ini mendokumentasikan evaluasi UI/UX sistem E-Lab Elektro mengacu pada dua metodologi ilmiah: User-Centered Design (UCD) dan Evaluasi Heuristik Nielsen, dengan seluruh isi yang diambil secara akurat dari sistem yang berjalan di http://localhost:8080/ucd-showcase.');

// =====================================================================
// 2. IDENTIFIKASI PENGGUNA (USER PERSONA)
// =====================================================================
sec('2', 'Identifikasi Pengguna (User Persona)');
p('Melalui wawancara dan observasi langsung di Laboratorium Teknik Elektro USK, diidentifikasi empat kategori pengguna utama sistem E-Lab Elektro:');

gap(80);
subsec('2.1', 'Mahasiswa — Dwiky Ilham');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Dwiky Ilham'],
    ['Role dalam Sistem', 'Mahasiswa (NPM: 250420501100004)'],
    ['Karakteristik', 'Mahasiswa semester akhir yang sedang menyusun tugas akhir praktikum. Sering meminjam alat ukur seperti Oscilloscope Digital dan Signal Generator Rigol.'],
    ['Frustrasi Utama', 'Peminjaman manual memakan waktu lama; tidak ada kejelasan apakah alat tersedia sebelum datang ke lab secara fisik.'],
    ['Kebutuhan Sistem', 'Katalog inventaris real-time, keranjang peminjaman cepat, pengingat batas waktu pengembalian otomatis.'],
    ['Fitur yang Digunakan', 'Katalog Alat, Keranjang Peminjaman, Prototype Interaktif (simulator 4 langkah)'],
], [2200, 6700]);
gap(160);

subsec('2.2', 'Penjaga Lab — Misbah Anuari');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Misbah Anuari'],
    ['Role dalam Sistem', 'Penjaga Lab (tampil juga sebagai Dr. Misbah Anuari — Dosen Pembimbing di Knowledge Graph)'],
    ['Karakteristik', 'Asisten laboratorium yang bertugas mengelola fisik inventaris alat dan memvalidasi transaksi peminjaman harian.'],
    ['Frustrasi Utama', 'Sulit melacak mahasiswa yang terlambat mengembalikan alat; pencatatan denda manual di buku sering keliru.'],
    ['Kebutuhan Sistem', 'Dashboard validasi satu klik, pencatatan otomatis status alat (rusak/maintenance), kalkulasi denda otomatis Rp 5.000/hari.'],
    ['Fitur yang Digunakan', 'Dashboard Validasi, Manajemen Status Alat, Laporan Denda'],
], [2200, 6700]);
gap(160);

subsec('2.3', 'Kepala Program Studi (Kaprodi) — Rana Sulthanah');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Rana Sulthanah'],
    ['Role dalam Sistem', 'Kaprodi — hanya akses laporan dan analitik, tidak memiliki fitur keranjang peminjaman'],
    ['Karakteristik', 'Kepala Program Studi yang berfokus pada pengawasan aset dan pengambilan kebijakan anggaran laboratorium.'],
    ['Frustrasi Utama', 'Laporan penggunaan lab sulit direkap; tidak tahu alat apa yang paling sering rusak dan butuh peremajaan.'],
    ['Kebutuhan Sistem', 'Dashboard analitik statistik visual, ekspor laporan berkala otomatis (Excel/PDF), visualisasi tingkat kesehatan inventaris.'],
    ['Fitur yang Digunakan', 'Dashboard Analitik, Ekspor Laporan Excel/PDF, Visualisasi Kesehatan Alat'],
], [2200, 6700]);
gap(160);

subsec('2.4', 'Administrator — Admin Utama');
tbl([
    ['Atribut', 'Keterangan'],
    ['Nama', 'Administrator (Admin Utama)'],
    ['Role dalam Sistem', 'Admin Utama — akses penuh seluruh sistem'],
    ['Karakteristik', 'Pengelola teknis sistem yang bertanggung jawab atas kelancaran operasional platform e-lab secara keseluruhan.'],
    ['Frustrasi Utama', 'Kehilangan rekam jejak aktivitas user jika terjadi manipulasi data ilegal (fraud) di sistem.'],
    ['Kebutuhan Sistem', 'Audit log real-time (siapa, melakukan apa, kapan), manajemen akun, konfigurasi sistem.'],
    ['Fitur yang Digunakan', 'Audit Log, Manajemen Akun, Konfigurasi Role, Monitoring Sistem'],
], [2200, 6700]);

// =====================================================================
// 3. ANALISIS KEBUTUHAN PENGGUNA
// =====================================================================
sec('3', 'Analisis Kebutuhan Pengguna');
p('Berdasarkan identifikasi persona di atas, berikut pemetaan kebutuhan fungsional ke dalam fitur solusi sistem (sesuai tabel pada Tab 1 website):');
gap(80);
tbl([
    ['Kategori Kebutuhan', 'Deskripsi Fitur Solusi', 'Prioritas'],
    ['Peminjaman Mandiri', 'Mahasiswa dapat memilih alat dari katalog, memasukkan ke keranjang, dan mengajukan peminjaman secara mandiri secara paperless.', 'Critical'],
    ['Katalog Stok Real-time', 'Sistem langsung mengurangi stok barang yang dipinjam dan menampilkan stok yang benar-benar siap di rak lab secara akurat.', 'Critical'],
    ['Persetujuan Fleksibel', 'Penjaga lab dapat menyetujui peminjaman hanya dengan satu klik di dashboard tanpa tatap muka langsung terlebih dahulu.', 'Critical'],
    ['Kalkulator Denda Otomatis', 'Menghitung denda secara presisi (Rp 5.000/hari terlambat) sejak batas waktu berakhir, menghemat waktu penjaga lab.', 'High'],
    ['Audit Log Keamanan', 'Mencatat setiap tindakan penting (manipulasi stok, penghapusan transaksi) dalam tabel audit khusus untuk keamanan sistem.', 'Medium'],
], [2800, 4800, 1300]);

// =====================================================================
// 4. USER FLOW DIAGRAM
// =====================================================================
sec('4', 'User Flow Diagram');
p('Sistem E-Lab Elektro mengimplementasikan dua paradigma alur interaksi yang berbeda secara fundamental, divisualisasikan sebagai SVG diagram pada Tab 2 website:');

gap(80);
subsec('4.1', 'Alur Linear Relasional — CRUD Peminjaman Alat');
p('Alur ini mengikuti pola linear yang sederhana dan mudah diprediksi oleh mahasiswa:');
gap(80);
tbl([
    ['Langkah', 'Aksi dalam Sistem', 'Pelaku'],
    ['1', 'Login ke sistem menggunakan NPM dan password terdaftar', 'Mahasiswa'],
    ['2', 'Buka Katalog Alat — cek status dan jumlah stok tersedia di rak', 'Mahasiswa'],
    ['3', 'Klik "Pinjam" — isi formulir jumlah unit (validasi tidak boleh melebihi stok)', 'Mahasiswa'],
    ['4', 'Validasi Sistem — cek stok real-time dan status akun (tidak ada tunggakan)', 'Sistem Otomatis'],
    ['5', 'Status berubah "Menunggu Persetujuan" — notifikasi terkirim ke Penjaga Lab', 'Sistem'],
    ['6', 'Penjaga Lab menyetujui/menolak dengan satu klik di Dashboard Validasi', 'Penjaga Lab (Misbah)'],
    ['7', 'Pengembalian alat — Penjaga Lab konfirmasi — sistem kalkulasi denda otomatis jika terlambat', 'Penjaga Lab & Sistem'],
], [900, 5200, 2800]);
gap(160);

subsec('4.2', 'Alur Eksplorasi Knowledge Graph — Semantic Exploration');
p('Alur ini bersifat non-linear dan exploratory, memungkinkan pengguna menemukan wawasan dari relasi antar data:');
gap(80);
tbl([
    ['Langkah', 'Aksi & Interaksi', 'Hasil'],
    ['1', 'Buka halaman Knowledge Graph — graf SVG dimuat dengan 5 node utama', 'Tampil: Dwiky, Oscilloscope, Prak.Mikro, Dr.Misbah, Lab Tele'],
    ['2', 'Single click pada node — misal klik node Dwiky (N1)', 'Panel "Detail Entitas Semantik" muncul di sidebar kanan'],
    ['3', 'Double-click pada node N1 (Dwiky)', 'Node N7 (Naza Fahrul) muncul dengan edge REKAN_KLP'],
    ['4', 'Double-click pada node N2 (Oscilloscope)', 'Node N6 (Signal Generator Rigol) muncul dengan edge TERHUBUNG'],
    ['5', 'Gunakan panel Semantic Filtering — uncheck tipe entitas tertentu', '5 filter: Mahasiswa, Alat, Mata Kuliah, Dosen, Ruang Lab'],
    ['6', 'Klik "Reset Grafik" untuk kembali ke kondisi awal', 'Node N6 & N7 disembunyikan kembali, semua filter aktif'],
], [900, 4500, 3500]);

// =====================================================================
// 5. SITEMAP & STRUKTUR NAVIGASI
// =====================================================================
sec('5', 'Sitemap & Struktur Navigasi');
p('Arsitektur informasi sistem E-Lab Elektro berdasarkan sitemap yang ditampilkan pada Tab 2 website:');
gap(80);
tbl([
    ['Modul Utama', 'Sub-Halaman / Fitur', 'Role yang Dapat Akses'],
    ['Gerbang Masuk (Auth)', 'Login dengan CAPTCHA; Registrasi Mahasiswa; Lupa Sandi & Reset Token via Email', 'Publik (Semua)'],
    ['Dashboard Utama', 'Widget Statistik Ringkas; Peringatan Keterlambatan; Top 5 Alat Populer & Donut Chart', 'Semua Role'],
    ['UCD Showcase & Demo', 'Laporan UCD & Evaluasi Heuristik; Visualisasi Knowledge Graph SVG; Simulasi Prototype Interaktif', 'Semua Role'],
    ['Manajemen Inventaris (Alat)', 'Data Aset & CRUD; Cetak QR Code Label', 'Penjaga Lab, Admin'],
    ['Transaksi Peminjaman', 'Keranjang Belanja Alat; Validasi Denda & Pengembalian', 'Mahasiswa, Penjaga Lab'],
    ['Laporan & Analitik', 'Dashboard Kaprodi; Ekspor Excel/PDF; Rekap Kondisi Alat', 'Kaprodi, Admin'],
    ['Manajemen Akun', 'Daftar User; Edit Profil; Konfigurasi Role; Audit Log Aktivitas', 'Admin'],
], [2500, 4600, 1800]);

// =====================================================================
// 6. WIREFRAME DAN MOCKUP
// =====================================================================
sec('6', 'Wireframe dan Mockup');

subsec('6.1', 'Perbandingan Lo-Fi Wireframe vs Hi-Fi Mockup');
p('Tab 3 website menampilkan toggle switch interaktif yang memungkinkan pengguna beralih antara Lo-Fi Wireframe dan Hi-Fi Mockup secara instan:');
gap(80);
tbl([
    ['Aspek', 'Lo-Fi Wireframe (Toggle OFF)', 'Hi-Fi Mockup (Toggle ON)'],
    ['Tampilan', 'Kotak abu-abu, garis placeholder. Contoh: [LINGKARAN DIAGRAM KESEHATAN ALAT - PLACEHOLDER]', 'Desain penuh: warna, shadow, border-radius, animasi hover'],
    ['Grafik', 'Placeholder teks kosong', 'Donut chart nyata: Bagus (80%, hijau) dan Rusak (20%, merah)'],
    ['Tabel Inventaris', 'Blok teks sederhana', 'Tabel responsif dengan tombol "Pinjam" orange rounded-pill'],
    ['Header Dashboard', 'Teks tanpa styling', 'Header ikon Flask, badge "Admin Utama", background dark navy'],
    ['Tujuan', 'Komunikasi layout dan hierarki informasi', 'Menunjukkan tampilan akhir kepada stakeholder'],
], [1500, 3600, 3800]);
gap(160);

subsec('6.2', 'Dashboard Mockup — Data Real-Time dari Database');
p('Dashboard sistem menampilkan 4 widget statistik yang datanya diambil langsung dari database MySQL melalui UcdShowcase controller:');
gap(80);
tbl([
    ['Widget Dashboard', 'Query Database (PHP)', 'Warna Indikator'],
    ['Total Alat', '$alatModel->countAllResults()', 'Hitam/Netral'],
    ['Tersedia di Rak', '$alatModel->where(\'status\', \'Tersedia\')->countAllResults()', 'Hijau (#10b981) — "Siap dipinjam"'],
    ['Alat Rusak', '$alatModel->selectSum(\'jumlah_rusak\')->first()', 'Merah (#ef4444) — "Butuh perbaikan"'],
    ['Pinjaman Aktif', '$transaksiModel->where(\'status_pinjam\', \'Dipinjam\')->countAllResults()', 'Biru (#3b82f6) — "Sedang di luar"'],
], [2500, 4500, 1900]);
gap(120);
p('Selain widget statistik, dashboard juga menampilkan:');
bul('Tabel "Aksi Cepat Inventaris": Oscilloscope Tektronix (12 Unit) dan Signal Generator Rigol (5 Unit) dengan tombol "Pinjam" berwarna orange rounded-pill.');
bul('Donut chart "Status Kesehatan Alat": Bagus 80% (hijau) dan Rusak 20% (merah).');

// =====================================================================
// 7. JUSTIFIKASI DESAIN
// =====================================================================
sec('7', 'Justifikasi Desain (Design Rationale)');
p('Setiap keputusan desain dalam E-Lab Elektro dilandasi oleh teori UI/UX yang terukur, sebagaimana didokumentasikan pada Tab 3 website:');

gap(80);
subsec('7.1', 'Hukum Fitts (Fitts\'s Law) — Penempatan Tombol Aksi');
p('Hukum Fitts menyatakan bahwa waktu yang dibutuhkan untuk menjangkau target adalah fungsi dari jarak ke target dan ukuran target tersebut. Semakin besar ukuran tombol dan semakin dekat posisinya, semakin cepat pengguna dapat mengkliknya.');
p('Penerapan di E-Lab Elektro: Tombol aksi utama seperti tombol "Pinjam" dan "Ajukan Peminjaman" dirancang dengan ukuran minimal 40px tinggi, menggunakan border-radius rounded-pill yang lebar, serta diletakkan di sisi kanan setiap baris item alat. Posisi ini sesuai dengan pola gerak alami jari pengguna (sweep ke kanan), sehingga mempercepat interaksi dan meminimalkan kesalahan klik.');

gap(80);
subsec('7.2', 'Prinsip Gestalt — Pengelompokan Visual Statistik');
p('Prinsip Gestalt menjelaskan cara otak manusia mengelompokkan elemen visual secara alami tanpa perlu diarahkan secara eksplisit.');
p('Penerapan di E-Lab Elektro: Law of Proximity (Kedekatan) diterapkan pada widget statistik inventaris — Total Alat, Tersedia di Rak, Alat Rusak, dan Pinjaman Aktif dikelompokkan secara rapat dalam satu baris grid. Law of Similarity (Kesamaan) diterapkan melalui kode warna konsisten: hijau untuk ketersediaan, merah untuk masalah/rusak, biru untuk aktivitas aktif. Mahasiswa dapat langsung mengenali pola status tanpa membaca teks detail satu per satu.');

gap(80);
subsec('7.3', 'Mengapa Sidebar di Sisi Kiri?');
p('Penelitian eye-tracking F-Pattern dari Nielsen Norman Group menunjukkan bahwa pengguna web membaca dari kiri ke kanan dan dari atas ke bawah. Sidebar kiri memanfaatkan zona pertama yang dilihat mata, sehingga navigasi dapat ditemukan secara instan tanpa pencarian. Di E-Lab Elektro, sidebar lebar 250px memuat ikon Font Awesome 6 beserta label teks, dan collapsed otomatis pada layar di bawah 768px untuk mobile.');

gap(80);
subsec('7.4', 'Mengapa Donut Chart untuk Dashboard?');
p('Donut chart dipilih untuk menampilkan proporsi status kesehatan alat karena: (1) lebih efisien secara ruang dibandingkan bar chart untuk data perbandingan proporsi; (2) angka total di tengah lingkaran memberikan konteks langsung; (3) interaksi hover tooltip natural. Implementasi menggunakan Chart.js dengan animasi easeInOutQuart 1.5 detik untuk pengalaman yang premium.');

// =====================================================================
// 8. EVALUASI HEURISTIK NIELSEN
// =====================================================================
sec('8', 'Evaluasi Heuristik Nielsen');
p('Evaluasi dilakukan mengacu pada 10 prinsip usability Jakob Nielsen. Lima prinsip yang paling relevan dievaluasi secara mendalam terhadap sistem E-Lab Elektro (sesuai Tab 4 website), dengan skala Severity Rating 0-4:');
gap(40);
p('Keterangan Severity Rating:', true);
bul('0 = Bukan masalah usability');
bul('1 = Cosmetic — perbaiki jika ada waktu');
bul('2 = Minor — prioritas rendah');
bul('3 = Major — perlu segera diperbaiki');
bul('4 = Catastrophe — wajib diperbaiki sebelum rilis');
gap(120);
tbl([
    ['No.', 'Prinsip Heuristik', 'Implementasi & Temuan di E-Lab Elektro', 'Severity', 'Desain Perbaikan yang Diterapkan'],
    ['H1', 'Visibility of System Status', 'User bingung saat memproses peminjaman karena layar terasa "hang". Status loading tersembunyi pada saat pengajuan dikirimkan ke server.', '3 — Major', 'Menambahkan indikator loading spinner yang menutupi layar saat pengajuan dilakukan, dilengkapi progress bar pada alur keranjang peminjaman.'],
    ['H2', 'Match Between System & Real World', 'Sistem awalnya menggunakan istilah teknis database seperti id_alat dan user_role yang membingungkan mahasiswa awam.', '2 — Minor', 'Mengganti seluruh istilah teknis dengan bahasa sehari-hari di kampus: "Nama Alat", "NPM", "Penjaga Lab", "Denda Telat".'],
    ['H3', 'User Control and Freedom', 'Pengguna tidak sengaja menambah alat ke keranjang namun tidak ada opsi untuk membatalkan atau menghapus sebelum pengajuan dikirim.', '3 — Major', 'Menyediakan tombol "Hapus" (ikon tong sampah) di setiap item keranjang belanja, serta tombol "Kembali / Batal" di setiap halaman modal formulir.'],
    ['H4', 'Consistency & Standards', 'Tombol aksi menggunakan warna berbeda-beda di halaman yang berbeda: kadang biru, oranye, atau hijau untuk aksi yang sama (simpan/ajukan).', '2 — Minor', 'Standarisasi warna tombol: Oranye (#ea580c) untuk aksi utama/pengajuan, Abu-abu untuk batal/kembali, Merah (#ef4444) untuk hapus/peringatan.'],
    ['H5', 'Error Prevention', 'Mahasiswa dapat mengetikkan jumlah pinjam melebihi stok tersedia di rak fisik, sehingga memicu error pada database dan proses tidak bisa dilanjutkan.', '4 — Catastrophe', 'Menambahkan validasi frontend secara real-time. Tombol "Pinjam" otomatis nonaktif (disabled) jika stok alat bernilai 0 atau jumlah input melebihi stok.'],
], [400, 1600, 2900, 900, 3100]);

// =====================================================================
// 9. RENCANA PENGUJIAN SYSTEM USABILITY SCALE (SUS)
// =====================================================================
sec('9', 'Rencana Pengujian System Usability Scale (SUS)');
p('Untuk mengukur tingkat usability sistem secara empiris, akan dilakukan pengujian menggunakan instrumen System Usability Scale (SUS) standar yang dikembangkan oleh John Brooke (1996). Pengujian ini belum dilaksanakan dan berikut adalah rancangan metodologi yang akan digunakan:');

gap(80);
subsec('9.1', 'Rancangan Metodologi Pengujian');
tbl([
    ['Aspek Metodologi', 'Rencana'],
    ['Instrumen', 'System Usability Scale (SUS) — 10 pertanyaan standar dengan skala Likert 1-5'],
    ['Target Responden', 'Minimal 10 responden: mahasiswa aktif semester 4-8, penjaga lab, dan dosen Teknik Elektro USK'],
    ['Kriteria Responden', 'Pernah menggunakan sistem laboratorium (manual atau digital) dan mampu mengoperasikan browser web'],
    ['Prosedur', '(1) Responden diberikan akun demo; (2) Responden menyelesaikan 3 skenario tugas; (3) Responden mengisi kuesioner SUS; (4) Data diolah menjadi skor SUS'],
    ['Skala Penilaian SUS', '0-50: Not Acceptable | 51-68: Marginal | 68-100: Acceptable (target minimal: 68)'],
    ['Visualisasi di Sistem', 'Gauge meter speedometer SVG telah disiapkan di Tab 4 website untuk menampilkan skor hasil pengujian nanti'],
], [3200, 5700]);
gap(120);

subsec('9.2', 'Daftar Pertanyaan Kuesioner SUS yang Akan Digunakan');
p('Berikut 10 pertanyaan standar SUS yang akan diberikan kepada responden setelah menyelesaikan skenario tugas:');
tbl([
    ['No.', 'Pertanyaan SUS (Bahasa Indonesia)', 'Skala'],
    ['Q1', 'Saya rasa saya akan sering menggunakan sistem E-Lab Elektro ini.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q2', 'Saya merasa sistem ini terlalu rumit dan tidak perlu.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q3', 'Saya rasa sistem ini mudah untuk digunakan.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q4', 'Saya rasa saya membutuhkan bantuan teknisi untuk bisa menggunakan sistem ini.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q5', 'Saya merasa berbagai fungsi dalam sistem ini terintegrasi dengan baik.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q6', 'Saya merasa terlalu banyak inkonsistensi dalam sistem ini.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q7', 'Saya rasa kebanyakan orang akan dapat mempelajari sistem ini dengan cepat.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q8', 'Saya merasa sistem ini sangat sulit untuk digunakan.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q9', 'Saya merasa sangat percaya diri saat menggunakan sistem ini.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
    ['Q10', 'Saya perlu belajar banyak hal sebelum bisa mulai menggunakan sistem ini.', '1-5 (Sangat Tidak Setuju — Sangat Setuju)'],
], [400, 5800, 2700]);
gap(120);

subsec('9.3', 'Skenario Tugas yang Akan Diberikan kepada Responden');
p('Sebelum mengisi kuesioner SUS, responden akan diminta menyelesaikan 3 skenario tugas berikut secara mandiri:');
gap(80);
tbl([
    ['No.', 'Nama Tugas', 'Instruksi yang Akan Diberikan', 'Prinsip Heuristik yang Diuji'],
    ['1', 'Pencarian & Ketersediaan', 'Cari alat "Oscilloscope Digital" di katalog, cek jumlah unit yang tersisa di rak lab, dan pastikan statusnya "Tersedia".', 'H1 (Visibility) & H2 (Real World)'],
    ['2', 'Proses Peminjaman', 'Tambahkan alat tersebut ke Keranjang Belanja, buka keranjang, isi formulir dengan NPM yang valid, lalu kirim pengajuan.', 'H3 (User Control) & H4 (Consistency)'],
    ['3', 'Pencegahan Kesalahan', 'Coba input jumlah peminjaman melebihi stok (misalnya 99 unit) untuk memverifikasi apakah sistem menolak input tersebut secara otomatis.', 'H5 (Error Prevention) — Severity 4'],
], [400, 1600, 4500, 2400]);

// =====================================================================
// 10. PROTOTYPE INTERAKTIF
// =====================================================================
sec('10', 'Prototype Interaktif');
p('Tab 6 website menampilkan simulator prototype clickable yang mensimulasikan alur peminjaman mahasiswa secara penuh dalam 4 langkah (wizard steps). Prototype ditampilkan dalam frame browser palsu untuk memberi kesan yang realistis.');
gap(80);
tbl([
    ['Step', 'Nama Layar', 'Konten & Interaksi'],
    ['Step 1', 'Pilih Alat', 'Alert info: "Selamat Datang di Portal Mahasiswa. Pilih salah satu alat untuk mulai meminjam." | Daftar: Oscilloscope Digital (12 Unit), Signal Generator Rigol (5 Unit), Multimeter Fluke (8 Unit). Tombol "Pinjam ->" orange di kanan setiap item.'],
    ['Step 2', 'Isi Formulir Peminjaman', 'Form berisi: NPM (pre-filled: 250420501100004, readonly), Tanggal Kembali (date picker), Jumlah Unit (number input dengan max = stok). Validasi: tombol "Ajukan" disabled jika jumlah > stok maks atau NPM kosong.'],
    ['Step 3', 'Konfirmasi', 'Ringkasan: nama alat, jumlah, tanggal kembali, catatan "denda Rp 5.000/hari jika terlambat". Alert: "Pastikan data sudah benar sebelum konfirmasi."'],
    ['Step 4', 'Pengajuan Berhasil', 'Ikon centang hijau besar. Pesan: "Menunggu konfirmasi dari Penjaga Lab". Nomor referensi: REF-2025-XXXX. Tombol: "Kembali ke Katalog" (orange) dan "Cek Status Pinjam".'],
], [700, 2000, 6200]);

// =====================================================================
// 11. KNOWLEDGE GRAPH DESIGN
// =====================================================================
sec('11', 'Knowledge Graph Visualization Design');
p('Tab 5 website menampilkan visualisasi Knowledge Graph interaktif berbasis SVG dengan 7 node (5 default + 2 tersembunyi yang muncul saat ekspansi), panel Semantic Filtering, dan panel Detail Entitas Semantik.');

gap(80);
subsec('11.1', 'Node Style — Desain Simpul Entitas');
tbl([
    ['ID', 'Nama Entitas', 'Tipe', 'Warna Node', 'Data Semantik'],
    ['N1', 'Dwiky Ilham', 'Mahasiswa', '#10b981 (Hijau)', 'NPM: 250420501100004. Sedang meminjam Oscilloscope untuk Tugas Akhir.'],
    ['N2', 'Oscilloscope Tektronix', 'Alat Inventaris', '#f59e0b (Emas)', 'Status: Dipinjam Dwiky. Terletak di Lab Telekomunikasi.'],
    ['N3', 'Praktikum Mikroprosesor', 'Mata Kuliah', '#8b5cf6 (Ungu)', 'Kode: EL-302. Membutuhkan Oscilloscope dan Signal Generator.'],
    ['N4', 'Dr. Misbah Anuari', 'Dosen Pembimbing', '#3b82f6 (Biru)', 'Mengajar Praktikum Mikroprosesor & membimbing Tugas Akhir Dwiky.'],
    ['N5', 'Lab Telekomunikasi', 'Ruang Laboratorium', '#ec4899 (Pink)', 'Lokasi penyimpanan utama alat ukur frekuensi tinggi.'],
    ['N6*', 'Signal Generator Rigol', 'Alat Inventaris', '#f59e0b (Emas)', 'Tersedia di rak. *Muncul setelah double-click N2 (Oscilloscope).'],
    ['N7*', 'Naza Fahrul Sirait', 'Mahasiswa Kelompok', '#10b981 (Hijau)', 'NPM: 250420501100002. Rekan kelompok TA Dwiky. *Muncul setelah double-click N1 (Dwiky).'],
], [500, 2000, 1700, 1500, 3200]);
gap(160);

subsec('11.2', 'Edge Style — Desain Relasi Semantik');
tbl([
    ['Dari Node', 'Label Relasi', 'Ke Node', 'Warna Edge'],
    ['Dwiky (N1)', 'MEMINJAM', 'Oscilloscope (N2)', '#ef4444 — Merah (panah tebal)'],
    ['Dr. Misbah (N4)', 'MENGAJAR', 'Prak. Mikro (N3)', '#8b5cf6 — Ungu'],
    ['Oscilloscope (N2)', 'TERLETAK_DI', 'Lab Tele (N5)', '#f59e0b — Emas'],
    ['Prak. Mikro (N3)', 'DILAKSANAKAN_DI', 'Lab Tele (N5)', '#f59e0b — Emas'],
    ['Oscilloscope (N2)', 'DIGUNAKAN_DI', 'Prak. Mikro (N3)', '#10b981 — Hijau'],
    ['Dr. Misbah (N4)', 'MEMBIMBING', 'Dwiky (N1)', '#3b82f6 — Biru'],
    ['Signal Gen (N6)*', 'TERHUBUNG', 'Oscilloscope (N2)', '#64748b — Abu (hidden awal)'],
    ['Naza (N7)*', 'REKAN_KLP', 'Dwiky (N1)', '#3b82f6 — Biru (hidden awal)'],
], [2000, 1800, 2000, 3100]);
gap(160);

subsec('11.3', 'Semantic Filtering — Filter Entitas');
p('Panel "Semantic Filtering" di sisi kanan graf menyediakan 5 checkbox yang dapat digunakan untuk menyaring tampilan node secara real-time:');
gap(80);
tbl([
    ['Filter', 'Warna', 'Default', 'Efek Saat Di-uncheck'],
    ['Mahasiswa (Student)', '#10b981 — Hijau', 'Checked (Aktif)', 'Sembunyikan N1 (Dwiky) dan N7 (Naza) beserta semua edge-nya'],
    ['Alat (Equipment)', '#f59e0b — Emas', 'Checked (Aktif)', 'Sembunyikan N2 (Oscilloscope) dan N6 (Signal Gen) beserta edge-nya'],
    ['Mata Kuliah (Course)', '#8b5cf6 — Ungu', 'Checked (Aktif)', 'Sembunyikan N3 (Prak. Mikro) beserta semua edge-nya'],
    ['Dosen (Lecturer)', '#3b82f6 — Biru', 'Checked (Aktif)', 'Sembunyikan N4 (Dr. Misbah) beserta semua edge-nya'],
    ['Ruang Lab (Lab Room)', '#ec4899 — Pink', 'Checked (Aktif)', 'Sembunyikan N5 (Lab Tele) beserta semua edge-nya'],
], [2000, 1600, 1500, 3800]);
gap(160);

subsec('11.4', 'Node Expansion Interaction');
p('Interaksi yang dapat dilakukan pengguna terhadap node dalam graf:');
bul('Single Click: Menampilkan panel "Detail Entitas Semantik" di sidebar kanan dengan nama, tipe, dan deskripsi atribut semantik node.');
bul('Double-click N1 (Dwiky): Memunculkan Node N7 (Naza Fahrul Sirait) beserta edge REKAN_KLP yang sebelumnya tersembunyi.');
bul('Double-click N2 (Oscilloscope): Memunculkan Node N6 (Signal Generator Rigol) beserta edge TERHUBUNG yang sebelumnya tersembunyi.');
bul('Tombol "Reset Grafik": Menyembunyikan kembali N6 dan N7, mengembalikan semua filter ke posisi aktif (semua checked).');

// =====================================================================
// 12. PERBANDINGAN RELATIONAL vs KNOWLEDGE GRAPH
// =====================================================================
sec('12', 'Perbandingan Relational vs Knowledge Graph UI');
p('Sistem E-Lab Elektro mengimplementasikan kedua paradigma dalam satu platform yang terintegrasi. Berikut perbandingan komprehensif keduanya:');
gap(80);
tbl([
    ['Aspek UI/UX', 'Relational System (CRUD)', 'Knowledge Graph System'],
    ['Paradigma Interaksi', 'Linear & prosedural — pengguna mengikuti alur form yang sudah ditentukan sistem', 'Eksploratori & non-linear — pengguna bebas menjelajahi relasi antar data'],
    ['Komponen Utama', 'Form input, tabel data DataTable, modal konfirmasi, dropdown filter kategori', 'Canvas SVG graf, panel node detail, checkbox semantic filter, tombol expand/reset'],
    ['Dashboard', 'Statistik numerik (total, tersedia, rusak, aktif), donut chart proporsi, alert keterlambatan', 'Peta relasi semantik, eksplorasi cluster, visualisasi hubungan multi-hop antar entitas'],
    ['Navigasi', 'Sidebar hierarkis ke halaman terpisah per fitur, breadcrumb linear', 'Single-page exploration — semua relasi terlihat sekaligus, navigasi via klik node'],
    ['Target Pengguna', 'Mahasiswa & Penjaga Lab — kebutuhan CRUD efisien dan cepat', 'Kaprodi & Admin — analitik mendalam dan insight relasi data'],
    ['Learning Curve', 'Rendah — familiar dengan form dan tabel web umum', 'Sedang-Tinggi — perlu familiarisasi konsep graf dan gesture double-click'],
    ['Contoh Query', 'SELECT * FROM transaksi WHERE status = "Dipinjam" (3 baris)', 'MATCH (m)-[:MEMINJAM]->(a:Alat) RETURN m,a (2 baris Cypher)'],
], [1800, 3600, 3500]);
gap(120);

subsec('12.1', 'Perbandingan Query: SQL JOIN vs Cypher MATCH');
p('Kasus: Mencari alat yang dipinjam oleh seluruh anggota kelompok tugas akhir Dwiky Ilham (NPM: 250420501100004):');
gap(80);
tbl([
    ['Sistem', 'Query', 'Jumlah Baris'],
    ['Relational SQL', 'SELECT a.nama_alat, u.nama_lengkap FROM transaksi t INNER JOIN users u ON t.username = u.username INNER JOIN alat a ON t.id_alat = a.id WHERE u.username IN (SELECT kelompok_user FROM kelompok WHERE nama_kelompok = (SELECT nama_kelompok FROM kelompok k INNER JOIN users us ON k.id_mahasiswa = us.id WHERE us.username = \'250420501100004\'))', '10 baris — nested subquery kompleks'],
    ['Knowledge Graph Cypher', 'MATCH (m1:Mahasiswa {username: \'250420501100004\'}) -[:REKAN_KLP]-(m2:Mahasiswa) -[:MEMINJAM]->(a:Alat) RETURN a.nama_alat, m2.nama_lengkap', '3 baris — ekspresif & readable'],
], [1400, 5500, 2000]);

// =====================================================================
// 13. KESIMPULAN DAN SARAN
// =====================================================================
sec('13', 'Kesimpulan dan Saran');

subsec('13.1', 'Kesimpulan');
p('Evaluasi UI/UX sistem E-Lab Elektro menggunakan pendekatan UCD dan Evaluasi Heuristik Nielsen menghasilkan temuan berikut:');
bul('Empat persona pengguna (Dwiky — Mahasiswa, Misbah — Penjaga Lab, Rana — Kaprodi, Administrator) telah diidentifikasi secara spesifik dengan kebutuhan yang diterjemahkan ke fitur nyata dalam sistem.');
bul('Alur interaksi dual-paradigma — CRUD linear untuk peminjaman dan eksplorasi non-linear untuk Knowledge Graph — berhasil diimplementasikan dalam satu platform terintegrasi.');
bul('Dari 5 heuristik Nielsen yang dievaluasi melalui inspeksi ahli: 1 masalah Catastrophe (H5 — validasi stok) sudah diperbaiki, 2 masalah Major (H1 loading spinner, H3 tombol hapus keranjang) sudah diperbaiki, dan 2 masalah Minor (H2 istilah teknis, H4 konsistensi warna) sudah distandarisasi.');
bul('Instrumen pengujian SUS (10 pertanyaan) dan 3 skenario tugas usability telah dirancang dan siap dilaksanakan untuk mengukur tingkat kepuasan pengguna secara empiris.');
bul('Knowledge Graph berhasil memvisualisasikan 5 tipe entitas dan 8 relasi semantik dalam satu canvas SVG interaktif, dengan kemampuan ekspansi node dan semantic filtering yang melampaui kemampuan tabel SQL tradisional.');

gap(80);
subsec('13.2', 'Saran Pengembangan');
tbl([
    ['Prioritas', 'Saran Pengembangan', 'Dampak'],
    ['P1 — Tinggi', 'Integrasikan node Knowledge Graph dengan query database real-time (saat ini data masih hardcoded di SVG)', 'Graf menampilkan data transaksi nyata dari MySQL secara dinamis'],
    ['P1 — Tinggi', 'Tambahkan lebih banyak trigger node expansion (double-click N5 Lab Tele menampilkan semua alat di lab tersebut)', 'Pengalaman eksplorasi KG yang lebih kaya dan informatif'],
    ['P2 — Sedang', 'Implementasi animasi progress bar multi-step di prototype (Step 1-2-3-4 dengan progress indicator di atas)', 'Meningkatkan Visibility of System Status (H1)'],
    ['P2 — Sedang', 'Laksanakan pengujian SUS dengan minimal 10 responden menggunakan instrumen dan skenario yang telah dirancang pada poin 9', 'Mendapatkan skor usability empiris untuk validasi ilmiah sistem'],
    ['P3 — Rendah', 'Gantikan SVG static dengan library vis.js Network untuk physics simulation yang lebih dinamis', 'Graf lebih responsif dan node dapat di-drag secara bebas'],
], [1200, 5200, 2500]);

// =====================================================================
// DAFTAR PUSTAKA
// =====================================================================
$o .= "\\pard\\qc\\sb300\\sa120{$BASE}\\b DAFTAR PUSTAKA\\par\n";
$o .= "\\pard\\qc\\sb0\\sa120\\brdrb\\brdrs\\brdrw6\\brdrcf0 \\par\n";
$refs = [
    'Nielsen, J. (1994). Usability Engineering. Morgan Kaufmann Publishers. San Francisco, CA.',
    'Nielsen, J. (1995). 10 Usability Heuristics for User Interface Design. Nielsen Norman Group. https://www.nngroup.com/articles/ten-usability-heuristics/',
    'Brooke, J. (1996). SUS: A quick and dirty usability scale. In P. W. Jordan, B. Thomas, B. A. Weerdmeester, & I. L. McClelland (Eds.), Usability evaluation in industry (pp. 189-194). Taylor and Francis, London.',
    'Norman, D. A. (2013). The Design of Everyday Things: Revised and Expanded Edition. Basic Books. New York.',
    'Fitts, P. M. (1954). The information capacity of the human motor system in controlling the amplitude of movement. Journal of Experimental Psychology, 47(6), 381-391.',
    'Wertheimer, M. (1923). Laws of Organization in Perceptual Forms (W. Ellis, Trans.). London: Kegan Paul, Trench, Trubner & Company.',
    'ISO 9241-210:2019. Ergonomics of human-system interaction — Part 210: Human-centred design for interactive systems. Geneva: ISO.',
    'W3C. (2023). Web Content Accessibility Guidelines (WCAG) 2.1. https://www.w3.org/WAI/WCAG21/quickref/',
    'Bootstrap Team. (2024). Bootstrap 5.3 Documentation. https://getbootstrap.com/docs/5.3/',
    'CodeIgniter Foundation. (2024). CodeIgniter 4.x User Guide. https://codeigniter.com/user_guide/',
];
foreach ($refs as $i => $ref) {
    $o .= "\\pard\\qj\\fi-500\\li500\\sb0\\sa100{$BASE}\\b0 " . r(($i+1).". ".$ref) . "\\par\n";
}

// =====================================================================
// WRAP RTF
// =====================================================================
$rtf = '{\\rtf1\\ansi\\ansicpg1252\\deff0\\deflang1057' . "\n"
    // f0 = Times New Roman, f1 = Courier New (untuk kode)
    . '{\\fonttbl{\\f0\\froman\\fprq2\\fcharset0 Times New Roman;}{\\f1\\fmodern\\fcharset0 Courier New;}}' . "\n"
    // Color: 0=black, 1=light grey (table header fill)
    . '{\\colortbl;\\red0\\green0\\blue0;\\red230\\green230\\blue230;}' . "\n"
    . '{\\info{\\title Laporan Evaluasi UCD E-Lab Elektro}{\\author Tim E-Lab Elektro}{\\company Universitas Syiah Kuala}}' . "\n"
    . '\\widowctrl\\hyphauto' . "\n"
    // Margins: top/bottom 2.54cm=1440twip, left 4cm=2268twip, right 3cm=1701twip
    . '\\margl2268\\margr1701\\margt1440\\margb1440' . "\n"
    . '\\f0\\fs24\\cf0\\sl360\\slmult1' . "\n"  // Times New Roman 12pt, 1.5 line spacing
    . $o . '}';

$outFile = __DIR__ . '/Laporan_UCD_ELab_Elektro_Final.doc';
$fallbackFile = __DIR__ . '/Laporan_UCD_ELab_Elektro_Final_Revisi.doc';
$writeResult = @file_put_contents($outFile, $rtf);

if ($writeResult === false) {
    $fallbackResult = @file_put_contents($fallbackFile, $rtf);
    if ($fallbackResult === false) {
        echo "GAGAL: File utama dan file cadangan tidak dapat ditulis.\n";
    } else {
        echo "PERINGATAN: File utama sedang dikunci (mungkin terbuka di Microsoft Word).\n";
        echo "Laporan berhasil ditulis ke file cadangan:\n";
        echo "File: {$fallbackFile}\n";
        echo "Ukuran: " . number_format(filesize($fallbackFile)/1024, 1) . " KB\n";
        echo "Silakan tutup Microsoft Word yang sedang membuka file utama agar file utama dapat diperbarui pada eksekusi berikutnya.\n";
    }
} else {
    echo "BERHASIL!\nFile: {$outFile}\nUkuran: " . number_format(filesize($outFile)/1024, 1) . " KB\n";
    @file_put_contents($fallbackFile, $rtf);
}


