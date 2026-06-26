<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Custom Premium CSS for UCD Showcase -->
<style>
    /* CSS Variables for Theme Consistency */
    :root {
        --primary-gold: #f59e0b;
        --primary-orange: #ea580c;
        --dark-slate: #0f172a;
        --light-slate: #f8fafc;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --neon-green: #10b981;
        --neon-blue: #3b82f6;
        --neon-purple: #8b5cf6;
    }

    /* Print Stylesheet integration */
    @media print {
        body {
            background: white !important;
            color: black !important;
            font-size: 12pt !important;
        }
        .sidebar, .topbar, .no-print, .btn, .nav-tabs, .tab-pane-controls, #chatWrapper, .ai-trigger {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
            page-break-inside: avoid !important;
            margin-bottom: 20px !important;
            background: transparent !important;
        }
        .tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        h1, h2, h3, h4, h5, h6 {
            color: black !important;
            page-break-after: avoid !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        .table th, .table td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
        }
    }

    /* Modern Aesthetics */
    .ucd-container {
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
    }
    
    .ucd-header {
        background: linear-gradient(135deg, var(--dark-slate) 0%, #1e293b 100%);
        border-radius: 1.5rem;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .ucd-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 60%);
        border-radius: 50%;
    }

    .custom-nav-tabs {
        border: none;
        background-color: #e2e8f0;
        padding: 0.35rem;
        border-radius: 1rem;
        gap: 0.25rem;
        margin-bottom: 2rem;
    }

    .custom-nav-tabs .nav-link {
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        color: var(--text-muted);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-nav-tabs .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.5);
        color: var(--text-dark);
    }

    .custom-nav-tabs .nav-link.active {
        background-color: white;
        color: var(--primary-orange);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
    }

    /* Persona Cards */
    .persona-card {
        border-radius: 1.25rem;
        border: 1px solid var(--border-color);
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .persona-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08);
    }

    .persona-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--border-color);
    }

    /* Sitemap and User Flow Visuals */
    .visual-tree {
        list-style-type: none;
        position: relative;
        padding-left: 1.5rem;
    }

    .visual-tree::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 2px;
        height: 100%;
        background-color: #cbd5e1;
    }

    .tree-node {
        position: relative;
        margin-bottom: 0.75rem;
        padding: 0.5rem 1rem;
        background: #f1f5f9;
        border-radius: 0.5rem;
        border-left: 4px solid var(--primary-gold);
    }

    .tree-node::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -1.5rem;
        width: 1.5rem;
        height: 2px;
        background-color: #cbd5e1;
    }

    /* Wireframe vs Mockup Toggle System */
    .wireframe-container {
        border: 1px solid var(--border-color);
        border-radius: 1.25rem;
        padding: 1.5rem;
        background: white;
    }

    /* Dynamic CSS class injection for Wireframe simulation */
    .ui-wireframe {
        font-family: "Courier New", Courier, monospace !important;
        background-color: #f1f5f9 !important;
        color: #475569 !important;
    }

    .ui-wireframe .card, 
    .ui-wireframe .stat-card-modern, 
    .ui-wireframe .modal-content {
        background: #f8fafc !important;
        border: 2px dashed #94a3b8 !important;
        box-shadow: none !important;
        color: #64748b !important;
        border-radius: 0px !important;
    }

    .ui-wireframe .btn {
        background: #e2e8f0 !important;
        border: 2px solid #94a3b8 !important;
        color: #475569 !important;
        box-shadow: none !important;
        border-radius: 0px !important;
        font-weight: bold;
    }

    .ui-wireframe h1, .ui-wireframe h2, .ui-wireframe h3, .ui-wireframe h4, .ui-wireframe h5, .ui-wireframe h6, .ui-wireframe p, .ui-wireframe td, .ui-wireframe th {
        color: #475569 !important;
        font-family: "Courier New", Courier, monospace !important;
    }

    .ui-wireframe .badge {
        background: #cbd5e1 !important;
        color: #475569 !important;
        border: 1px solid #94a3b8 !important;
        border-radius: 0px !important;
    }

    .ui-wireframe .bg-gradient, .ui-wireframe [style*="background"] {
        background: #e2e8f0 !important;
    }

    .ui-wireframe canvas, .ui-wireframe .chart-placeholder {
        display: none !important;
    }

    .ui-wireframe .wireframe-chart-placeholder {
        display: flex !important;
        height: 250px;
        border: 2px dashed #94a3b8;
        background: #f8fafc;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-weight: bold;
    }

    .wireframe-chart-placeholder {
        display: none;
    }

    /* Knowledge Graph Visualization Styles */
    .kg-canvas {
        background: #0f172a;
        border-radius: 1rem;
        height: 450px;
        width: 100%;
        position: relative;
        overflow: hidden;
        border: 1px solid #334155;
    }

    .kg-node {
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .kg-node:hover {
        filter: brightness(1.2);
        stroke-width: 4px;
    }

    .kg-node.selected {
        stroke: #fff !important;
        stroke-width: 4px;
        filter: drop-shadow(0 0 8px currentColor);
    }

    .kg-edge {
        stroke-dasharray: 4;
        animation: dash 30s linear infinite;
        transition: all 0.3s ease;
    }

    @keyframes dash {
        to {
            stroke-dashoffset: -1000;
        }
    }

    .kg-edge-label {
        font-size: 8px;
        fill: #94a3b8;
        font-weight: bold;
        pointer-events: none;
    }

    .filtered-out {
        opacity: 0.15 !important;
        pointer-events: none !important;
    }

    /* Interactive Prototype Wizard */
    .wizard-step {
        display: none;
        animation: fadeIn 0.4s ease forwards;
    }

    .wizard-step.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Code diff styles */
    .code-box {
        background-color: #1e293b;
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 0.75rem;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.85rem;
        overflow-x: auto;
        border-left: 4px solid var(--primary-gold);
    }
</style>

<div class="ucd-container container-fluid p-0">
    <!-- Header -->
    <div class="ucd-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div>
            <h2 class="fw-extrabold mb-1"><i class="fas fa-project-diagram me-2 text-warning"></i> UCD Evaluation & Final Prototype</h2>
            <p class="text-white-50 mb-0">E-Lab Elektro: Evaluasi Ilmiah Nielsen Heuristics, Analisis UCD & Simulasi Knowledge Graph</p>
        </div>
        <!-- html2pdf.js CDN library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        
        <div class="d-flex gap-2 no-print">
            <button class="btn btn-light rounded-pill px-4 fw-bold shadow-sm" onclick="window.print()">
                <i class="fas fa-print me-2 text-dark"></i> Cetak Laporan Browser
            </button>
            <button class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm text-white" style="background: #ea580c; border: none;" onclick="exportToPDF()">
                <i class="fas fa-file-pdf me-2"></i> Unduh PDF Laporan Resmi
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs custom-nav-tabs no-print" id="ucdTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                <i class="fas fa-info-circle me-1"></i> 1. User Persona (UCD)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="flow-tab" data-bs-toggle="tab" data-bs-target="#flow" type="button" role="tab">
                <i class="fas fa-route me-1"></i> 2. User Flow & Sitemap
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="toggle-tab" data-bs-toggle="tab" data-bs-target="#toggleUi" type="button" role="tab">
                <i class="fas fa-toggle-on me-1"></i> 3. Wireframe vs Mockup
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="heuristics-tab" data-bs-toggle="tab" data-bs-target="#heuristics" type="button" role="tab">
                <i class="fas fa-clipboard-list me-1"></i> 4. Evaluasi Heuristik & SUS
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="kg-tab" data-bs-toggle="tab" data-bs-target="#kg" type="button" role="tab">
                <i class="fas fa-network-wired me-1"></i> 5. Knowledge Graph
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="proto-tab" data-bs-toggle="tab" data-bs-target="#proto" type="button" role="tab">
                <i class="fas fa-gamepad me-1"></i> 6. Prototipe Interaktif
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="ucdTabsContent">
        
        <!-- Tab 1: User Persona -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3"><i class="fas fa-users text-orange me-2"></i> Fase UCD: Identifikasi Pengguna & Persona</h4>
                <p class="text-muted mb-4">Metode User-Centered Design (UCD) berfokus pada kebutuhan nyata pengguna akhir. Melalui wawancara dan observasi di Laboratorium Teknik Elektro, kami mengidentifikasi 4 persona utama sistem:</p>
                
                <div class="row g-4">
                    <!-- Mahasiswa -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="persona-card p-4">
                            <div class="text-center mb-3">
                                <img src="https://api.dicebear.com/9.x/initials/svg?seed=Dwiky&backgroundColor=f59e0b&textColor=ffffff" class="persona-avatar mb-2" alt="Dwiky">
                                <h6 class="fw-bold mb-0">Dwiky Ilham</h6>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Mahasiswa</span>
                            </div>
                            <hr>
                            <p class="small text-dark mb-2"><strong>Karakteristik:</strong> Mahasiswa semester akhir yang sedang menyusun tugas akhir praktikum.</p>
                            <p class="small text-danger mb-2"><strong>Frustrasi:</strong> Peminjaman manual memakan waktu lama; tidak ada kejelasan apakah alat tersedia sebelum datang ke lab.</p>
                            <p class="small text-success mb-0"><strong>Kebutuhan:</strong> Katalog inventaris real-time, keranjang peminjaman cepat, pengingat batas waktu kembali otomatis.</p>
                        </div>
                    </div>

                    <!-- Penjaga Lab -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="persona-card p-4">
                            <div class="text-center mb-3">
                                <img src="https://api.dicebear.com/9.x/initials/svg?seed=Misbah&backgroundColor=3b82f6&textColor=ffffff" class="persona-avatar mb-2" alt="Misbah">
                                <h6 class="fw-bold mb-0">Misbah Anuari</h6>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Penjaga Lab</span>
                            </div>
                            <hr>
                            <p class="small text-dark mb-2"><strong>Karakteristik:</strong> Asisten lab yang bertugas mengelola fisik inventaris dan memvalidasi transaksi harian.</p>
                            <p class="small text-danger mb-2"><strong>Frustrasi:</strong> Sulit melacak mahasiswa yang terlambat mengembalikan alat; pencatatan denda manual di buku sering keliru.</p>
                            <p class="small text-success mb-0"><strong>Kebutuhan:</strong> Dasbor validasi cepat sekali klik, pencatatan otomatis status alat (rusak/maintenance), sistem kalkulasi denda otomatis.</p>
                        </div>
                    </div>

                    <!-- Kepala Program Studi -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="persona-card p-4">
                            <div class="text-center mb-3">
                                <img src="https://api.dicebear.com/9.x/initials/svg?seed=Rana&backgroundColor=10b981&textColor=ffffff" class="persona-avatar mb-2" alt="Rana">
                                <h6 class="fw-bold mb-0">Rana Sulthanah</h6>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Kaprodi</span>
                            </div>
                            <hr>
                            <p class="small text-dark mb-2"><strong>Karakteristik:</strong> Kepala Program Studi yang berfokus pada pengawasan aset dan pengambilan kebijakan anggaran lab.</p>
                            <p class="small text-danger mb-2"><strong>Frustrasi:</strong> Laporan penggunaan lab sulit direkap; tidak tahu alat apa saja yang paling sering rusak dan butuh peremajaan.</p>
                            <p class="small text-success mb-0"><strong>Kebutuhan:</strong> Dasbor analitis statistik visual, ekspor laporan berkala otomatis (Excel/PDF), visualisasi tingkat kesehatan inventaris.</p>
                        </div>
                    </div>

                    <!-- Administrator Utama -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="persona-card p-4">
                            <div class="text-center mb-3">
                                <img src="https://api.dicebear.com/9.x/initials/svg?seed=Admin&backgroundColor=8b5cf6&textColor=ffffff" class="persona-avatar mb-2" alt="Admin">
                                <h6 class="fw-bold mb-0">Administrator</h6>
                                <span class="badge bg-purple-subtle text-purple border border-purple-subtle">Admin Utama</span>
                            </div>
                            <hr>
                            <p class="small text-dark mb-2"><strong>Karakteristik:</strong> Bertanggung jawab penuh terhadap kelancaran sistem e-learning/e-lab secara teknis.</p>
                            <p class="small text-danger mb-2"><strong>Frustrasi:</strong> Kehilangan rekam jejak aktivitas user jika terjadi manipulasi data ilegal (fraud).</p>
                            <p class="small text-success mb-0"><strong>Kebutuhan:</strong> Audit logs real-time untuk merekam seluruh aktivitas sistem (siapa, melakukan apa, kapan), konfigurasi akun.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-check-double text-success me-2"></i> Analisis Kebutuhan Pengguna (User Needs Analysis)</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori Kebutuhan</th>
                                <th>Deskripsi Fitur Solusi</th>
                                <th>Prioritas</th>
                            }
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Peminjaman Mandiri</strong></td>
                                <td>Mahasiswa dapat memilih alat dari katalog, memasukkan ke keranjang, dan mengajukan peminjaman secara mandiri secara paperless.</td>
                                <td><span class="badge bg-danger">Critical</span></td>
                            </tr>
                            <tr>
                                <td><strong>Katalog Stok Real-time</strong></td>
                                <td>Sistem langsung mengurangi stok barang yang dipinjam dan menampilkan stok yang benar-benar siap di lab secara akurat.</td>
                                <td><span class="badge bg-danger">Critical</span></td>
                            </tr>
                            <tr>
                                <td><strong>Persetujuan Fleksibel</strong></td>
                                <td>Penjaga lab dapat menyetujui peminjaman hanya dengan satu klik di dashboard tanpa tatap muka langsung terlebih dahulu.</td>
                                <td><span class="badge bg-danger">Critical</span></td>
                            </tr>
                            <tr>
                                <td><strong>Kalkulator Denda Otomatis</strong></td>
                                <td>Menghitung denda secara presisi (Rp 5.000 / hari terlambat) sejak batas waktu berakhir untuk menghemat waktu penjaga lab.</td>
                                <td><span class="badge bg-warning">High</span></td>
                            </tr>
                            <tr>
                                <td><strong>Audit Log Keamanan</strong></td>
                                <td>Mencatat setiap tindakan penting (seperti manipulasi stok atau penghapusan transaksi) dalam tabel audit khusus untuk keamanan sistem.</td>
                                <td><span class="badge bg-info">Medium</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: User Flow & Sitemap -->
        <div class="tab-pane fade" id="flow" role="tabpanel">
            <div class="row g-4">
                <!-- User Flow Diagram -->
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-3"><i class="fas fa-route text-orange me-2"></i> User Flow Diagram (Alur Pengguna)</h5>
                        <p class="text-muted small">Perbandingan Alur Pengguna: **Sistem Relasional (Linear CRUD)** vs **Sistem Knowledge Graph (Eksplorasi Semantik)**.</p>
                        
                        <!-- Inline SVG User Flow -->
                        <div class="p-3 bg-light rounded-4 text-center overflow-auto" style="min-width: 600px;">
                            <svg width="680" height="340" viewBox="0 0 680 340" class="mx-auto">
                                <!-- Marker definitions for arrows -->
                                <defs>
                                    <marker id="flow-arrow" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 0 0 L 10 5 L 0 10 z" fill="#ea580c"/>
                                    </marker>
                                    <marker id="flow-arrow-blue" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 0 0 L 10 5 L 0 10 z" fill="#3b82f6"/>
                                    </marker>
                                </defs>

                                <!-- Relational Flow (Top) -->
                                <text x="20" y="30" font-family="Inter" font-size="12" font-weight="bold" fill="#ea580c">ALUR LINIER RELASIONAL (CRUD Peminjaman)</text>
                                
                                <rect x="20" y="50" width="100" height="40" rx="20" fill="#fef2f2" stroke="#ea580c" stroke-width="2"/>
                                <text x="70" y="74" font-family="Inter" font-size="10" font-weight="bold" text-anchor="middle" fill="#ea580c">Mulai</text>

                                <line x1="120" y1="70" x2="160" y2="70" stroke="#ea580c" stroke-width="2" marker-end="url(#flow-arrow)"/>

                                <rect x="160" y="50" width="110" height="40" rx="5" fill="#fff" stroke="#ea580c" stroke-width="2"/>
                                <text x="215" y="74" font-family="Inter" font-size="9" text-anchor="middle" fill="#1e293b">Pilih Alat / Inventaris</text>

                                <line x1="270" y1="70" x2="310" y2="70" stroke="#ea580c" stroke-width="2" marker-end="url(#flow-arrow)"/>

                                <rect x="310" y="50" width="110" height="40" rx="5" fill="#fff" stroke="#ea580c" stroke-width="2"/>
                                <text x="365" y="74" font-family="Inter" font-size="9" text-anchor="middle" fill="#1e293b">Isi Formulir (Jumlah)</text>

                                <line x1="420" y1="70" x2="460" y2="70" stroke="#ea580c" stroke-width="2" marker-end="url(#flow-arrow)"/>

                                <rect x="460" y="50" width="100" height="40" rx="5" fill="#fff" stroke="#ea580c" stroke-width="2"/>
                                <text x="510" y="74" font-family="Inter" font-size="9" text-anchor="middle" fill="#1e293b">Validasi Sistem</text>

                                <line x1="560" y1="70" x2="600" y2="70" stroke="#ea580c" stroke-width="2" marker-end="url(#flow-arrow)"/>

                                <rect x="600" y="50" width="60" height="40" rx="20" fill="#fef2f2" stroke="#ea580c" stroke-width="2"/>
                                <text x="630" y="74" font-family="Inter" font-size="10" font-weight="bold" text-anchor="middle" fill="#ea580c">Selesai</text>

                                <!-- Knowledge Graph Flow (Bottom) -->
                                <text x="20" y="170" font-family="Inter" font-size="12" font-weight="bold" fill="#3b82f6">ALUR EKSPLORASI KNOWLEDGE GRAPH (Eksplorasi Relasi)</text>

                                <rect x="20" y="190" width="100" height="40" rx="20" fill="#eff6ff" stroke="#3b82f6" stroke-width="2"/>
                                <text x="70" y="214" font-family="Inter" font-size="10" font-weight="bold" text-anchor="middle" fill="#3b82f6">Mulai Eksplorasi</text>

                                <line x1="120" y1="210" x2="150" y2="210" stroke="#3b82f6" stroke-width="2" marker-end="url(#flow-arrow-blue)"/>

                                <rect x="150" y="190" width="120" height="40" rx="5" fill="#fff" stroke="#3b82f6" stroke-width="2"/>
                                <text x="210" y="214" font-family="Inter" font-size="9" text-anchor="middle" fill="#1e293b">Pilih Node (e.g. Alat)</text>

                                <path d="M 270 210 Q 310 180 350 210" fill="none" stroke="#3b82f6" stroke-width="2" marker-end="url(#flow-arrow-blue)"/>
                                <text x="310" y="188" font-family="Inter" font-size="8" text-anchor="middle" fill="#3b82f6">Double Click</text>

                                <rect x="350" y="190" width="130" height="40" rx="5" fill="#fff" stroke="#3b82f6" stroke-width="2"/>
                                <text x="415" y="214" font-family="Inter" font-size="9" text-anchor="middle" fill="#1e293b">Ekspansi Node Tetangga</text>

                                <line x1="480" y1="210" x2="510" y2="210" stroke="#3b82f6" stroke-width="2" marker-end="url(#flow-arrow-blue)"/>

                                <rect x="510" y="190" width="110" height="40" rx="5" fill="#fff" stroke="#3b82f6" stroke-width="2"/>
                                <text x="565" y="214" font-family="Inter" font-size="9" text-anchor="middle" fill="#1e293b">Filter Tipe Relasi</text>

                                <line x1="565" y1="230" x2="565" y2="270" stroke="#3b82f6" stroke-width="2" marker-end="url(#flow-arrow-blue)"/>
                                
                                <rect x="500" y="270" width="130" height="40" rx="5" fill="#fff" stroke="#3b82f6" stroke-width="2"/>
                                <text x="565" y="294" font-family="Inter" font-size="9" text-anchor="middle" fill="#1e293b">Lihat Detail Semantik</text>

                                <line x1="500" y1="290" x2="415" y2="290" stroke="#3b82f6" stroke-width="2"/>
                                <line x1="415" y1="290" x2="415" y2="230" stroke="#3b82f6" stroke-width="2" marker-end="url(#flow-arrow-blue)"/>
                                <text x="455" y="285" font-family="Inter" font-size="8" text-anchor="middle" fill="#3b82f6">Ulangi Eksplorasi</text>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sitemap Structure -->
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-3"><i class="fas fa-sitemap text-success me-2"></i> Struktur Navigasi (Sitemap)</h5>
                        <p class="text-muted small">Peta arsitektur informasi aplikasi E-Lab Elektro:</p>
                        
                        <ul class="visual-tree ps-2">
                            <li>
                                <div class="tree-node fw-bold">Gerbang Masuk (Auth)</div>
                                <ul class="list-unstyled ps-3 small text-muted">
                                    <li><i class="fas fa-caret-right me-1"></i> Login (Captcha & Validasi)</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Registrasi Mahasiswa</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Lupa Sandi & Reset Token</li>
                                </ul>
                            </li>
                            <li>
                                <div class="tree-node fw-bold">Dashboard (Utama)</div>
                                <ul class="list-unstyled ps-3 small text-muted">
                                    <li><i class="fas fa-caret-right me-1"></i> Widget Statistik Ringkas</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Peringatan Keterlambatan</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Top 5 Alat Populer & Donat Chart</li>
                                </ul>
                            </li>
                            <li>
                                <div class="tree-node fw-bold" style="border-left-color: var(--primary-orange);">UCD Showcase & Demo</div>
                                <ul class="list-unstyled ps-3 small text-muted">
                                    <li><i class="fas fa-caret-right me-1"></i> Laporan UCD & Evaluasi Heuristik</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Visualisasi Knowledge Graph (SVG)</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Simulasi Clickable Prototype</li>
                                </ul>
                            </li>
                            <li>
                                <div class="tree-node fw-bold">Manajemen Inventaris (Alat)</div>
                                <ul class="list-unstyled ps-3 small text-muted">
                                    <li><i class="fas fa-caret-right me-1"></i> Data Aset & CRUD</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Cetak QR Code Label</li>
                                </ul>
                            </li>
                            <li>
                                <div class="tree-node fw-bold">Transaksi Peminjaman</div>
                                <ul class="list-unstyled ps-3 small text-muted">
                                    <li><i class="fas fa-caret-right me-1"></i> Keranjang Belanja Alat</li>
                                    <li><i class="fas fa-caret-right me-1"></i> Validasi Denda & Pengembalian</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Wireframe vs Mockup Toggle -->
        <div class="tab-pane fade" id="toggleUi" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="fas fa-exchange-alt text-primary me-2"></i> Perbandingan Interaktif: Wireframe vs Mockup</h5>
                        <p class="text-muted mb-0 small">Aktifkan tombol toggle di bawah untuk melihat perbedaan Lo-Fi Wireframe dengan Hi-Fi Mockup secara instan.</p>
                    </div>
                    <div class="form-check form-switch no-print">
                        <input class="form-check-input" type="checkbox" role="switch" id="uiModeToggle" style="transform: scale(1.4); cursor: pointer; margin-right: 10px;">
                        <label class="form-check-label fw-bold text-dark" for="uiModeToggle" id="uiModeLabel">Mode: Mockup (High Fidelity)</label>
                    </div>
                </div>

                <!-- Simulation View Area -->
                <div id="simulationWrapper" class="wireframe-container">
                    
                    <!-- Simulating Dashboard Navigation and Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded bg-light border">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-flask fa-lg text-orange"></i>
                            <h6 class="mb-0 fw-bold">E-Lab Elektro Dashboard</h6>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success py-2 px-3 rounded-pill"><i class="fas fa-user-shield me-1"></i> Admin Utama</span>
                        </div>
                    </div>

                    <!-- Statistics Cards Row -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-light p-3 shadow-sm">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Total Alat</small>
                                <h3 class="fw-bold my-1 text-dark"><?= number_format($db_total_alat ?? 10) ?></h3>
                                <small class="text-success" style="font-size: 0.75rem;"><i class="fas fa-check me-1"></i> Terhubung DB</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-light p-3 shadow-sm">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Tersedia di Rak</small>
                                <h3 class="fw-bold my-1 text-dark"><?= number_format($db_total_tersedia ?? 8) ?></h3>
                                <small class="text-muted" style="font-size: 0.75rem;">Siap dipinjam</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-light p-3 shadow-sm">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Alat Rusak</small>
                                <h3 class="fw-bold my-1 text-danger"><?= number_format($db_total_rusak ?? 2) ?></h3>
                                <small class="text-danger" style="font-size: 0.75rem;">Butuh perbaikan</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-light p-3 shadow-sm">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Pinjaman Aktif</small>
                                <h3 class="fw-bold my-1 text-primary"><?= number_format($db_active_loans ?? 4) ?></h3>
                                <small class="text-primary" style="font-size: 0.75rem;">Sedang di luar</small>
                            </div>
                        </div>
                    </div>

                    <!-- Charts & Inventory Section -->
                    <div class="row g-4">
                        <!-- Chart Mock -->
                        <div class="col-12 col-md-6">
                            <div class="card border-0 bg-light p-3 h-100">
                                <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie text-success me-2"></i> Status Kesehatan Alat</h6>
                                
                                <!-- Mock High-fidelity Chart (Doughnut) -->
                                <div class="chart-placeholder text-center py-4">
                                    <div class="d-inline-block rounded-circle" style="width: 140px; height: 140px; border: 20px solid #10b981; border-top-color: #ef4444; position: relative;">
                                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 0.9rem;">
                                            Healthy
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center gap-3 mt-3 small">
                                        <span><i class="fas fa-circle text-success me-1"></i> Bagus (80%)</span>
                                        <span><i class="fas fa-circle text-danger me-1"></i> Rusak (20%)</span>
                                    </div>
                                </div>

                                <!-- Wireframe Chart placeholder -->
                                <div class="wireframe-chart-placeholder">
                                    [ LINGKARAN DIAGRAM KESEHATAN ALAT - PLACEHOLDER ]
                                </div>
                            </div>
                        </div>

                        <!-- Data List Mock -->
                        <div class="col-12 col-md-6">
                            <div class="card border-0 bg-light p-3 h-100">
                                <h6 class="fw-bold mb-3"><i class="fas fa-list text-primary me-2"></i> Aksi Cepat Inventaris</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle table-borderless">
                                        <thead>
                                            <tr class="border-bottom text-muted" style="font-size: 0.75rem;">
                                                <th>NAMA ALAT</th>
                                                <th class="text-center">STOK</th>
                                                <th class="text-end">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size: 0.85rem;">
                                            <tr class="border-bottom">
                                                <td><strong>Oscilloscope Tektronix</strong></td>
                                                <td class="text-center">12 Unit</td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-orange py-1 px-3 text-white" style="background: var(--primary-orange); border: none; border-radius: 20px; font-size: 0.75rem;">
                                                        <i class="fas fa-plus me-1"></i> Pinjam
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td><strong>Signal Generator Rigol</strong></td>
                                                <td class="text-center">5 Unit</td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-orange py-1 px-3 text-white" style="background: var(--primary-orange); border: none; border-radius: 20px; font-size: 0.75rem;">
                                                        <i class="fas fa-plus me-1"></i> Pinjam
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Design Rationale -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-brain text-warning me-2"></i> Justifikasi Desain & Landasan Teori UI/UX</h5>
                
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-orange mb-2"><i class="fas fa-arrows-alt me-2"></i> Hukum Fitts (Fitts's Law)</h6>
                            <p class="small text-muted mb-0">Hukum Fitts menyatakan bahwa waktu yang dibutuhkan untuk menjangkau target adalah fungsi dari jarak ke dan ukuran target. 
                            <strong>Penerapan:</strong> Tombol aksi utama seperti "Pinjam" atau "Ajukan Peminjaman" sengaja dirancang berukuran minimal 40px tinggi dengan border-radius rounded-pill yang lebar serta diletakkan di sisi kanan halaman (pola gerak alami jari user) untuk mempercepat interaksi dan meminimalkan kesalahan klik pengguna.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-primary mb-2"><i class="fas fa-puzzle-piece me-2"></i> Prinsip Gestalt (Gestalt Principles)</h6>
                            <p class="small text-muted mb-0">Menjelaskan bagaimana otak manusia mengelompokkan elemen visual secara alami.
                            <strong>Penerapan:</strong> Kami menerapkan <em>Law of Proximity</em> (Kedekatan) dan <em>Law of Similarity</em> (Kesamaan warna). Statistik inventaris (Total, Tersedia, Rusak) dikelompokkan secara rapat dengan layout kolom teratur dan diberi kode warna khusus. Status hijau menunjukkan ketersediaan langsung di rak fisik, memudahkan pengenalan pola kognitif seketika oleh mahasiswa tanpa harus membaca detail teks satu per satu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 4: Heuristic Evaluation & SUS -->
        <div class="tab-pane fade" id="heuristics" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-stethoscope text-danger me-2"></i> Tabel Evaluasi Heuristik Nielsen (Nielsen Heuristics Table)</h5>
                <p class="text-muted small">Mengevaluasi aspek usability sistem E-Lab Elektro menggunakan 10 Heuristik Usability Nielsen:</p>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th width="200">Prinsip Heuristik</th>
                                <th>Implementasi & Temuan Us usability</th>
                                <th class="text-center" width="100">Severity Rating</th>
                                <th>Desain Perbaikan (Solusi)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1. Visibility of system status</strong></td>
                                <td>User bingung saat memproses peminjaman berat karena layar terasa "hang". <br><small class="text-muted">Status loading tersembunyi.</small></td>
                                <td class="text-center"><span class="badge bg-danger">3 (Major)</span></td>
                                <td>Menambahkan indikator loading spinner yang menutupi layar saat pengajuan dilakukan, lengkap dengan progress bar keranjang peminjaman.</td>
                            </tr>
                            <tr>
                                <td><strong>2. Match between system & real world</strong></td>
                                <td>Menggunakan istilah teknis database seperti `id_alat`, `user_role` yang membingungkan mahasiswa awam.</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">2 (Minor)</span></td>
                                <td>Mengganti istilah menjadi istilah sehari-hari di kampus seperti "Nama Alat", "NPM", "Penjaga Lab", "Denda Telat".</td>
                            </tr>
                            <tr>
                                <td><strong>3. User control and freedom</strong></td>
                                <td>Pengguna tidak sengaja menambah alat ke keranjang tapi tidak ada opsi untuk membatalkan sebelum pengajuan dikirim.</td>
                                <td class="text-center"><span class="badge bg-danger">3 (Major)</span></td>
                                <td>Menyediakan tombol "Hapus" (tong sampah) di keranjang belanja, serta tombol "Kembali / Batal" di setiap modal formulir.</td>
                            </tr>
                            <tr>
                                <td><strong>4. Consistency & standards</strong></td>
                                <td>Tombol aksi menggunakan warna berbeda-beda di halaman yang berbeda (kadang biru, oranye, atau hijau untuk aksi simpan).</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">2 (Minor)</span></td>
                                <td>Menerapkan standarisasi warna tombol: Oranye/Emas (`#ea580c`) untuk aksi utama pengajuan/peminjaman, Abu-abu untuk batal/kembali, dan Merah untuk hapus/peringatan.</td>
                            </tr>
                            <tr>
                                <td><strong>5. Error prevention</strong></td>
                                <td>Mahasiswa dapat mengetik jumlah pinjam melebihi stok yang tersedia di rak fisik lab sehingga memicu error database.</td>
                                <td class="text-center"><span class="badge bg-danger">4 (Catastrophe)</span></td>
                                <td>Menambahkan validasi frontend secara real-time pada tombol input angka. Tombol "Pinjam" otomatis nonaktif jika stok alat bernilai 0.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SUS section -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-poll text-primary me-2"></i> Rencana & Simulator Pengujian Usability (System Usability Scale - SUS)</h5>
                
                <p class="small text-muted mb-4">Pengujian usability secara langsung kepada pengguna belum dilaksanakan. Bagian ini menyajikan instrumen kuesioner, skenario tugas yang dirancang, serta simulator visualisasi skor SUS untuk menguji tingkat kelayakan sistem di masa mendatang.</p>

                <div class="row g-4 align-items-center">
                    <div class="col-12 col-md-4 text-center">
                        <div class="p-4 bg-white rounded-4 border border-slate-200 shadow-sm" style="position: relative;">
                            <h6 class="text-muted mb-3 text-uppercase font-weight-bold" style="font-size: 0.8rem;">Visualisasi Skor SUS</h6>
                            
                            <div style="width: 100%; max-width: 220px; margin: 0 auto; position: relative;">
                                <!-- Gauge SVG -->
                                <svg viewBox="0 0 100 55" width="100%" height="100%">
                                    <!-- Background Arc -->
                                    <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#e2e8f0" stroke-width="8" stroke-linecap="round"/>
                                    
                                    <!-- Bad Arc (0-51) -> Red -->
                                    <path d="M 10 50 A 40 40 0 0 1 50.8 10.1" fill="none" stroke="#ef4444" stroke-width="8" stroke-linecap="butt"/>
                                    
                                    <!-- Marginal Arc (51-68) -> Orange -->
                                    <path d="M 50.8 10.1 A 40 40 0 0 1 71.3 21.3" fill="none" stroke="#f59e0b" stroke-width="8" stroke-linecap="butt"/>
                                    
                                    <!-- Acceptable Arc (68-100) -> Green -->
                                    <path d="M 71.3 21.3 A 40 40 0 0 1 90 50" fill="none" stroke="#10b981" stroke-width="8" stroke-linecap="round"/>

                                    <!-- Score and label -->
                                    <text x="50" y="44" font-family="Inter, sans-serif" font-size="11" font-weight="800" fill="#0f172a" text-anchor="middle" id="susScoreText">82.5</text>
                                    <text x="50" y="52" font-family="Inter, sans-serif" font-size="5" font-weight="bold" fill="#64748b" text-anchor="middle" id="susRatingText">EXCELLENT (GRADE B)</text>
                                    
                                    <!-- Pointer needle -->
                                    <g transform="translate(50, 50)">
                                        <line x1="0" y1="0" x2="-35" y2="0" stroke="#0f172a" stroke-width="2.5" stroke-linecap="round" id="susNeedle" style="transform: rotate(0deg); transform-origin: 0 0; transition: transform 2s ease-out;"/>
                                        <circle cx="0" cy="0" r="4" fill="#0f172a"/>
                                    </g>
                                </svg>
                            </div>
                            
                            <span class="badge bg-success-subtle text-success border border-success fs-7 py-1 px-3 rounded-pill mt-2 d-inline-block" id="susBadge"><i class="fas fa-check-circle me-1"></i> Highly Acceptable</span>
                            <p class="small text-muted mt-2 mb-0" style="font-size: 0.75rem;" id="susBadgeText">Sistem sangat mudah dipelajari & dioperasikan.</p>
                            
                            <!-- Interactive Simulator Slider -->
                            <div class="mt-3 p-2 bg-light rounded-3 border border-slate-200">
                                <label for="susSlider" class="form-label small fw-bold text-muted mb-1 d-block text-start">Simulator Skor SUS: <span id="susSliderVal" class="text-primary fw-extrabold">82.5</span></label>
                                <input type="range" class="form-range" id="susSlider" min="0" max="100" value="82.5" step="0.5">
                            </div>
                        </div>
                    </div>

                    <script>
                        // Animate & control SUS needle and simulator
                        document.addEventListener('DOMContentLoaded', function() {
                            const needle = document.getElementById('susNeedle');
                            if (needle) {
                                // 82.5% of 180 degrees = 148.5 degrees
                                setTimeout(function() {
                                    needle.style.transform = 'rotate(148.5deg)';
                                }, 300);
                            }

                            const slider = document.getElementById('susSlider');
                            if (slider) {
                                slider.addEventListener('input', function(e) {
                                    const val = parseFloat(e.target.value);
                                    const valSpan = document.getElementById('susSliderVal');
                                    if (valSpan) valSpan.textContent = val.toFixed(1);
                                    
                                    if (needle) {
                                        needle.style.transform = `rotate(${(val/100)*180}deg)`;
                                    }
                                    
                                    const scoreText = document.getElementById('susScoreText');
                                    if (scoreText) scoreText.textContent = val.toFixed(1);
                                    
                                    const ratingText = document.getElementById('susRatingText');
                                    let rating = '';
                                    if (val >= 85) rating = 'BEST IMAGINABLE (GRADE A)';
                                    else if (val >= 73) rating = 'EXCELLENT (GRADE B)';
                                    else if (val >= 68) rating = 'ACCEPTABLE (GRADE C)';
                                    else if (val >= 51) rating = 'MARGINAL (GRADE D)';
                                    else rating = 'NOT ACCEPTABLE (GRADE F)';
                                    if (ratingText) ratingText.textContent = rating;
                                    
                                    const badge = document.getElementById('susBadge');
                                    const badgeText = document.getElementById('susBadgeText');
                                    if (badge) {
                                        if (val >= 68) {
                                            badge.className = 'badge bg-success-subtle text-success border border-success fs-7 py-1 px-3 rounded-pill mt-2 d-inline-block';
                                            badge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Highly Acceptable';
                                            if (badgeText) badgeText.textContent = 'Sistem sangat mudah dipelajari & dioperasikan.';
                                        } else if (val >= 51) {
                                            badge.className = 'badge bg-warning-subtle text-warning border border-warning fs-7 py-1 px-3 rounded-pill mt-2 d-inline-block';
                                            badge.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Marginal';
                                            if (badgeText) badgeText.textContent = 'Usability sistem rata-rata, perlu perbaikan kecil.';
                                        } else {
                                            badge.className = 'badge bg-danger-subtle text-danger border border-danger fs-7 py-1 px-3 rounded-pill mt-2 d-inline-block';
                                            badge.innerHTML = '<i class="fas fa-times-circle me-1"></i> Not Acceptable';
                                            if (badgeText) badgeText.textContent = 'Usability buruk, wajib dilakukan perbaikan desain.';
                                        }
                                    }
                                });
                            }
                        });
                    </script>
                    <div class="col-12 col-md-8">
                        <h6 class="fw-bold mb-2">10 Pertanyaan Kuesioner Pengujian SUS</h6>
                        <p class="small text-muted mb-3">Instrumen kuesioner evaluasi standar yang akan diberikan kepada minimal 10 responden (mahasiswa, penjaga lab, kaprodi) setelah uji coba:</p>
                        
                        <div class="table-responsive border rounded-3 mb-2" style="max-height: 220px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 8%;" class="ps-2">No</th>
                                        <th>Pertanyaan (Bahasa Indonesia)</th>
                                        <th style="width: 20%;" class="text-end pe-2">Tipe Soal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="ps-2">Q1</td><td>Saya rasa saya akan sering menggunakan sistem E-Lab Elektro ini.</td><td class="text-end pe-2"><span class="badge bg-success-subtle text-success border border-success">Positif</span></td></tr>
                                    <tr><td class="ps-2">Q2</td><td>Saya merasa sistem ini terlalu rumit dan tidak perlu.</td><td class="text-end pe-2"><span class="badge bg-danger-subtle text-danger border border-danger">Negatif</span></td></tr>
                                    <tr><td class="ps-2">Q3</td><td>Saya rasa sistem ini mudah untuk digunakan.</td><td class="text-end pe-2"><span class="badge bg-success-subtle text-success border border-success">Positif</span></td></tr>
                                    <tr><td class="ps-2">Q4</td><td>Saya rasa saya membutuhkan bantuan teknisi untuk bisa menggunakan sistem ini.</td><td class="text-end pe-2"><span class="badge bg-danger-subtle text-danger border border-danger">Negatif</span></td></tr>
                                    <tr><td class="ps-2">Q5</td><td>Saya merasa berbagai fungsi dalam sistem ini terintegrasi dengan baik.</td><td class="text-end pe-2"><span class="badge bg-success-subtle text-success border border-success">Positif</span></td></tr>
                                    <tr><td class="ps-2">Q6</td><td>Saya merasa terlalu banyak inkonsistensi dalam sistem ini.</td><td class="text-end pe-2"><span class="badge bg-danger-subtle text-danger border border-danger">Negatif</span></td></tr>
                                    <tr><td class="ps-2">Q7</td><td>Saya rasa kebanyakan orang akan dapat mempelajari sistem ini dengan cepat.</td><td class="text-end pe-2"><span class="badge bg-success-subtle text-success border border-success">Positif</span></td></tr>
                                    <tr><td class="ps-2">Q8</td><td>Saya merasa sistem ini sangat sulit untuk digunakan.</td><td class="text-end pe-2"><span class="badge bg-danger-subtle text-danger border border-danger">Negatif</span></td></tr>
                                    <tr><td class="ps-2">Q9</td><td>Saya merasa sangat percaya diri saat menggunakan sistem ini.</td><td class="text-end pe-2"><span class="badge bg-success-subtle text-success border border-success">Positif</span></td></tr>
                                    <tr><td class="ps-2">Q10</td><td>Saya perlu belajar banyak hal sebelum bisa mulai menggunakan sistem ini.</td><td class="text-end pe-2"><span class="badge bg-danger-subtle text-danger border border-danger">Negatif</span></td></tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <hr class="my-3">
                        
                        <h6 class="fw-bold mb-3"><i class="fas fa-tasks text-warning me-2"></i> Skenario Tugas Evaluasi Usability (Usability Testing Scenarios)</h6>
                        <p class="small text-muted mb-3">10 responden diminta untuk menyelesaikan 3 skenario tugas utama berikut sebelum mengisi kuesioner SUS:</p>

                        
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="p-3 bg-light rounded-3 h-100 border-start border-warning border-4">
                                    <div class="fw-bold small text-dark mb-1"><span class="badge bg-warning text-dark me-2">Tugas 1</span> Pencarian & Ketersediaan</div>
                                    <p class="small text-muted mb-0" style="font-size: 0.8rem;">Mencari alat "Oscilloscope Digital" di katalog, mengecek jumlah ketersediaan unit yang tersisa di rak lab, dan memastikan statusnya "Tersedia".</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 bg-light rounded-3 h-100 border-start border-primary border-4">
                                    <div class="fw-bold small text-dark mb-1"><span class="badge bg-primary text-white me-2">Tugas 2</span> Proses Peminjaman</div>
                                    <p class="small text-muted mb-0" style="font-size: 0.8rem;">Menambahkan alat tersebut ke Keranjang Belanja, membuka keranjang, mengisi formulir dengan NPM yang valid, serta menekan tombol kirim pengajuan.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 bg-light rounded-3 h-100 border-start border-danger border-4">
                                    <div class="fw-bold small text-dark mb-1"><span class="badge bg-danger text-white me-2">Tugas 3</span> Pencegahan Kesalahan</div>
                                    <p class="small text-muted mb-0" style="font-size: 0.8rem;">Sengaja menginput jumlah peminjaman melebihi batas stok tersedia (misalnya meminjam 99 unit) untuk memverifikasi apakah validasi sistem berfungsi menolak input tersebut.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 5: Knowledge Graph Visualization -->
        <div class="tab-pane fade" id="kg" role="tabpanel">
            <div class="row g-4">
                <!-- SVG Visualizer -->
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><i class="fas fa-network-wired text-purple me-2"></i> Peta Relasi Semantik (Knowledge Graph)</h5>
                                <p class="text-muted small mb-0">Eksplorasi hubungan antar data: **Dosen, Mahasiswa, Mata Kuliah, Alat, Ruang Lab**.</p>
                            </div>
                            <button class="btn btn-outline-light text-dark border btn-sm rounded-pill px-3" onclick="resetGraph()">
                                <i class="fas fa-sync-alt me-1"></i> Reset Grafik
                            </button>
                        </div>

                        <!-- Graph Canvas -->
                        <div class="kg-canvas">
                            <svg width="100%" height="100%" id="kgSvg">
                                <!-- Marker for edge arrows -->
                                <defs>
                                    <marker id="kg-arrow" viewBox="0 0 10 10" refX="22" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 0 0 L 10 5 L 0 10 z" fill="#94a3b8"/>
                                    </marker>
                                    <marker id="arrow-meminjam" viewBox="0 0 10 10" refX="22" refY="5" markerWidth="6" markerHeight="6" orient="auto">
                                        <path d="M 0 0 L 10 5 L 0 10 z" fill="#ef4444"/>
                                    </marker>
                                    <marker id="arrow-mengajar" viewBox="0 0 10 10" refX="22" refY="5" markerWidth="6" markerHeight="6" orient="auto">
                                        <path d="M 0 0 L 10 5 L 0 10 z" fill="#8b5cf6"/>
                                    </marker>
                                    <marker id="arrow-digunakan" viewBox="0 0 10 10" refX="22" refY="5" markerWidth="6" markerHeight="6" orient="auto">
                                        <path d="M 0 0 L 10 5 L 0 10 z" fill="#f59e0b"/>
                                    </marker>
                                </defs>

                                <!-- Grid pattern background -->
                                <rect width="100%" height="100%" fill="none"/>

                                <!-- Relationships (Edges) -->
                                <!-- N1 to N2 (Dwiky -> Oscilloscope) -->
                                <line x1="150" y1="150" x2="350" y2="100" stroke="#ef4444" stroke-width="2" class="kg-edge" id="edge-n1-n2" marker-end="url(#arrow-meminjam)"/>
                                <text x="250" y="115" class="kg-edge-label" text-anchor="middle" id="label-n1-n2">MEMINJAM</text>

                                <!-- N4 to N3 (Dr. Misbah -> Praktikum Mikro) -->
                                <line x1="550" y1="250" x2="350" y2="250" stroke="#8b5cf6" stroke-width="2" class="kg-edge" id="edge-n4-n3" marker-end="url(#arrow-mengajar)"/>
                                <text x="450" y="242" class="kg-edge-label" text-anchor="middle" id="label-n4-n3">MENGAJAR</text>

                                <!-- N2 to N5 (Oscilloscope -> Lab Telekomunikasi) -->
                                <line x1="350" y1="100" x2="550" y2="100" stroke="#f59e0b" stroke-width="2" class="kg-edge" id="edge-n2-n5" marker-end="url(#arrow-digunakan)"/>
                                <text x="450" y="92" class="kg-edge-label" text-anchor="middle" id="label-n2-n5">TERLETAK_DI</text>

                                <!-- N3 to N5 (Praktikum Mikro -> Lab Telekomunikasi) -->
                                <line x1="350" y1="250" x2="550" y2="100" stroke="#f59e0b" stroke-width="1.5" class="kg-edge" id="edge-n3-n5" marker-end="url(#kg-arrow)"/>
                                <text x="450" y="170" class="kg-edge-label" text-anchor="middle" id="label-n3-n5">DILAKSANAKAN_DI</text>

                                <!-- N2 to N3 (Oscilloscope -> Praktikum Mikro) -->
                                <line x1="350" y1="100" x2="350" y2="250" stroke="#10b981" stroke-width="1.5" class="kg-edge" id="edge-n2-n3" marker-end="url(#kg-arrow)"/>
                                <text x="362" y="180" class="kg-edge-label" text-anchor="start" id="label-n2-n3">DIGUNAKAN_DI</text>

                                <!-- N4 to N1 (Dr. Misbah -> Dwiky) -->
                                <line x1="550" y1="250" x2="150" y2="150" stroke="#3b82f6" stroke-width="1.5" class="kg-edge" id="edge-n4-n1" marker-end="url(#kg-arrow)"/>
                                <text x="350" y="208" class="kg-edge-label" text-anchor="middle" id="label-n4-n1">MEMBIMBING</text>

                                <!-- Hidden Nodes relations (expanded later) -->
                                <!-- N6 to N2 (Signal Generator -> Oscilloscope) -->
                                <line x1="250" y1="50" x2="350" y2="100" stroke="#64748b" stroke-width="1" class="kg-edge filtered-out" id="edge-n6-n2" marker-end="url(#kg-arrow)" style="display: none;"/>
                                <text x="300" y="70" class="kg-edge-label filtered-out" text-anchor="middle" id="label-n6-n2" style="display: none;">TERKONEKSI</text>

                                <!-- N7 to N1 (Naza Fahrul -> Dwiky) -->
                                <line x1="100" y1="250" x2="150" y2="150" stroke="#3b82f6" stroke-width="1" class="kg-edge filtered-out" id="edge-n7-n1" marker-end="url(#kg-arrow)" style="display: none;"/>
                                <text x="120" y="200" class="kg-edge-label filtered-out" text-anchor="middle" id="label-n7-n1" style="display: none;">REKAN_KLP</text>


                                <!-- Nodes (Entities) -->
                                <!-- N1: Mahasiswa -->
                                <g class="kg-node" id="node-n1" onclick="selectNode('n1', 'Dwiky Ilham', 'Mahasiswa', 'NPM: 250420501100004. Sedang meminjam Oscilloscope untuk Tugas Akhir.')" ondblclick="expandNode('n1')">
                                    <circle cx="150" cy="150" r="20" fill="#10b981" stroke="#047857" stroke-width="2"/>
                                    <text x="150" y="154" font-family="Font Awesome 6 Free" font-size="10" font-weight="900" fill="#fff" text-anchor="middle">&#xf501;</text>
                                    <text x="150" y="185" font-family="Inter" font-size="10" font-weight="bold" fill="#e2e8f0" text-anchor="middle">Dwiky (Mhs)</text>
                                </g>

                                <!-- N2: Alat -->
                                <g class="kg-node" id="node-n2" onclick="selectNode('n2', 'Oscilloscope Tektronix', 'Alat Inventaris', 'Status: Dipinjam Dwiky. Terletak di Lab Telekomunikasi.')" ondblclick="expandNode('n2')">
                                    <circle cx="350" cy="100" r="20" fill="#f59e0b" stroke="#d97706" stroke-width="2"/>
                                    <text x="350" y="104" font-family="Font Awesome 6 Free" font-size="10" font-weight="900" fill="#fff" text-anchor="middle">&#xf0a0;</text>
                                    <text x="350" y="75" font-family="Inter" font-size="10" font-weight="bold" fill="#e2e8f0" text-anchor="middle">Oscilloscope</text>
                                </g>

                                <!-- N3: Mata Kuliah -->
                                <g class="kg-node" id="node-n3" onclick="selectNode('n3', 'Praktikum Mikroprosesor', 'Mata Kuliah', 'Kode: EL-302. Membutuhkan Oscilloscope dan Signal Generator.')" ondblclick="expandNode('n3')">
                                    <circle cx="350" cy="250" r="20" fill="#8b5cf6" stroke="#6d28d9" stroke-width="2"/>
                                    <text x="350" y="254" font-family="Font Awesome 6 Free" font-size="10" font-weight="900" fill="#fff" text-anchor="middle">&#xf518;</text>
                                    <text x="350" y="285" font-family="Inter" font-size="10" font-weight="bold" fill="#e2e8f0" text-anchor="middle">Prak. Mikro</text>
                                </g>

                                <!-- N4: Dosen -->
                                <g class="kg-node" id="node-n4" onclick="selectNode('n4', 'Dr. Misbah Anuari', 'Dosen Pembimbing / Pengajar', 'Mengajar Praktikum Mikroprosesor & membimbing Tugas Akhir Dwiky.')" ondblclick="expandNode('n4')">
                                    <circle cx="550" cy="250" r="20" fill="#3b82f6" stroke="#1d4ed8" stroke-width="2"/>
                                    <text x="550" y="254" font-family="Font Awesome 6 Free" font-size="10" font-weight="900" fill="#fff" text-anchor="middle">&#xf51c;</text>
                                    <text x="550" y="285" font-family="Inter" font-size="10" font-weight="bold" fill="#e2e8f0" text-anchor="middle">Dr. Misbah</text>
                                </g>

                                <!-- N5: Ruang Lab -->
                                <g class="kg-node" id="node-n5" onclick="selectNode('n5', 'Lab Telekomunikasi', 'Ruang Laboratorium', 'Lokasi penyimpanan utama alat ukur frekuensi tinggi.')" ondblclick="expandNode('n5')">
                                    <circle cx="550" cy="100" r="20" fill="#ec4899" stroke="#be185d" stroke-width="2"/>
                                    <text x="550" y="104" font-family="Font Awesome 6 Free" font-size="10" font-weight="900" fill="#fff" text-anchor="middle">&#xf1ad;</text>
                                    <text x="550" y="75" font-family="Inter" font-size="10" font-weight="bold" fill="#e2e8f0" text-anchor="middle">Lab Tele</text>
                                </g>

                                <!-- N6: Hidden Node (Signal Generator) -->
                                <g class="kg-node" id="node-n6" onclick="selectNode('n6', 'Signal Generator Rigol', 'Alat Inventaris', 'Merk: Rigol. Tersedia di rak.')" style="display: none;" ondblclick="expandNode('n6')">
                                    <circle cx="250" cy="50" r="16" fill="#f59e0b" stroke="#d97706" stroke-width="1.5"/>
                                    <text x="250" y="53" font-family="Font Awesome 6 Free" font-size="8" font-weight="900" fill="#fff" text-anchor="middle">&#xf0a0;</text>
                                    <text x="250" y="28" font-family="Inter" font-size="8" font-weight="bold" fill="#94a3b8" text-anchor="middle">Signal Gen</text>
                                </g>

                                <!-- N7: Hidden Node (Naza Fahrul) -->
                                <g class="kg-node" id="node-n7" onclick="selectNode('n7', 'Naza Fahrul Sirait', 'Mahasiswa Kelompok', 'NPM: 250420501100002. Rekan kelompok tugas akhir Dwiky.')" style="display: none;" ondblclick="expandNode('n7')">
                                    <circle cx="100" cy="250" r="16" fill="#10b981" stroke="#047857" stroke-width="1.5"/>
                                    <text x="100" y="253" font-family="Font Awesome 6 Free" font-size="8" font-weight="900" fill="#fff" text-anchor="middle">&#xf501;</text>
                                    <text x="100" y="280" font-family="Inter" font-size="8" font-weight="bold" fill="#94a3b8" text-anchor="middle">Naza (Mhs)</text>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Graph Sidebars & Settings -->
                <div class="col-12 col-lg-4">
                    <!-- Semantic Filters -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-filter text-orange me-2"></i> Semantic Filtering</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-cb" type="checkbox" value="Mahasiswa" id="cbMhs" checked onchange="applyFilters()">
                            <label class="form-check-label text-dark small" for="cbMhs">
                                <i class="fas fa-circle me-1" style="color: #10b981;"></i> Mahasiswa (Student)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-cb" type="checkbox" value="Alat Inventaris" id="cbAlat" checked onchange="applyFilters()">
                            <label class="form-check-label text-dark small" for="cbAlat">
                                <i class="fas fa-circle me-1" style="color: #f59e0b;"></i> Alat (Equipment)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-cb" type="checkbox" value="Mata Kuliah" id="cbMk" checked onchange="applyFilters()">
                            <label class="form-check-label text-dark small" for="cbMk">
                                <i class="fas fa-circle me-1" style="color: #8b5cf6;"></i> Mata Kuliah (Course)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-cb" type="checkbox" value="Dosen Pembimbing / Pengajar" id="cbDosen" checked onchange="applyFilters()">
                            <label class="form-check-label text-dark small" for="cbDosen">
                                <i class="fas fa-circle me-1" style="color: #3b82f6;"></i> Dosen (Lecturer)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-cb" type="checkbox" value="Ruang Laboratorium" id="cbLab" checked onchange="applyFilters()">
                            <label class="form-check-label text-dark small" for="cbLab">
                                <i class="fas fa-circle me-1" style="color: #ec4899;"></i> Ruang Lab (Lab Room)
                            </label>
                        </div>
                    </div>

                    <!-- Selected Node Info -->
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-info-circle text-primary me-2"></i> Detail Entitas Semantik</h6>
                        <div id="kgDetails">
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-hand-pointer fa-2x mb-2"></i>
                                <p class="mb-0 small">Klik salah satu node lingkaran di sebelah kiri untuk melihat atribut semantiknya.</p>
                                <p class="mb-0 small text-warning mt-2"><i class="fas fa-mouse me-1"></i> Double-click untuk ekspansi relasi baru!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Visual DB Schema Comparison -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-database text-purple me-2"></i> Perbandingan Visual Skema Data: SQL vs Knowledge Graph</h5>
                <p class="text-muted small">Representasi visual bagaimana data disimpan dan dihubungkan pada sistem relasional (tabular linier) vs graf semantik (network-based):</p>
                
                <div class="row g-4 mt-2">
                    <!-- Relational (SQL) Diagram -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded-4 h-100 border border-slate-200">
                            <h6 class="fw-bold text-danger mb-3 text-center"><i class="fas fa-table me-2"></i> Skema Relasional (Tabular & Foreign Key)</h6>
                            <div class="d-flex flex-column gap-3 align-items-center py-2">
                                <!-- Users Table -->
                                <div class="bg-white border rounded shadow-sm w-100" style="max-width: 280px; font-size: 0.8rem;">
                                    <div class="bg-danger text-white px-2 py-1 fw-bold rounded-top">tabel: users</div>
                                    <div class="p-2 family-monospace">
                                        <div class="text-primary font-weight-bold">PK | id (INT)</div>
                                        <div>&nbsp;&nbsp;&nbsp;&nbsp;username (VARCHAR)</div>
                                        <div>&nbsp;&nbsp;&nbsp;&nbsp;role (ENUM)</div>
                                    </div>
                                </div>
                                <!-- Arrow Down -->
                                <div class="text-danger"><i class="fas fa-long-arrow-alt-down fa-lg"></i></div>
                                <!-- Transaksi Table -->
                                <div class="bg-white border rounded shadow-sm w-100" style="max-width: 280px; font-size: 0.8rem; border-color: #ef4444 !important;">
                                    <div class="bg-danger text-white px-2 py-1 fw-bold rounded-top">tabel: transaksi</div>
                                    <div class="p-2 family-monospace">
                                        <div class="text-primary font-weight-bold">PK | id (INT)</div>
                                        <div class="text-warning">FK | username (VARCHAR)</div>
                                        <div class="text-warning">FK | id_alat (INT)</div>
                                        <div>&nbsp;&nbsp;&nbsp;&nbsp;tanggal_pinjam (DATE)</div>
                                    </div>
                                </div>
                                <!-- Arrow Up -->
                                <div class="text-danger"><i class="fas fa-long-arrow-alt-up fa-lg"></i></div>
                                <!-- Alat Table -->
                                <div class="bg-white border rounded shadow-sm w-100" style="max-width: 280px; font-size: 0.8rem;">
                                    <div class="bg-danger text-white px-2 py-1 fw-bold rounded-top">tabel: alat</div>
                                    <div class="p-2 family-monospace">
                                        <div class="text-primary font-weight-bold">PK | id (INT)</div>
                                        <div>&nbsp;&nbsp;&nbsp;&nbsp;nama_alat (VARCHAR)</div>
                                        <div>&nbsp;&nbsp;&nbsp;&nbsp;jumlah_tersedia (INT)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Knowledge Graph Diagram -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-dark rounded-4 h-100 border border-slate-700 text-white">
                            <h6 class="fw-bold text-success mb-3 text-center"><i class="fas fa-project-diagram me-2"></i> Skema Knowledge Graph (Node-Edge-Property)</h6>
                            <div class="d-flex justify-content-center align-items-center py-4">
                                <!-- Inline SVG schema representation -->
                                <svg width="280" height="240" viewBox="0 0 280 240">
                                    <!-- Node 1: Mahasiswa -->
                                    <circle cx="60" cy="50" r="22" fill="#10b981" stroke="#047857" stroke-width="2"/>
                                    <text x="60" y="54" font-family="Inter, sans-serif" font-size="6.5" font-weight="bold" fill="#fff" text-anchor="middle">Mahasiswa</text>
                                    
                                    <!-- Node 2: Alat -->
                                    <circle cx="220" cy="50" r="22" fill="#f59e0b" stroke="#d97706" stroke-width="2"/>
                                    <text x="220" y="54" font-family="Inter, sans-serif" font-size="7" font-weight="bold" fill="#fff" text-anchor="middle">Alat</text>
                                    
                                    <!-- Node 3: Lab -->
                                    <circle cx="140" cy="180" r="22" fill="#ec4899" stroke="#be185d" stroke-width="2"/>
                                    <text x="140" y="184" font-family="Inter, sans-serif" font-size="7" font-weight="bold" fill="#fff" text-anchor="middle">Lab Room</text>
                                    
                                    <!-- Edges (arrows) -->
                                    <!-- Mahasiswa -> MEMINJAM -> Alat -->
                                    <path d="M 82 50 L 198 50" stroke="#ef4444" stroke-width="2" marker-end="url(#arrow-meminjam)"/>
                                    <text x="140" y="44" font-family="Inter, sans-serif" font-size="6.5" font-weight="bold" fill="#ef4444" text-anchor="middle">MEMINJAM</text>
                                    
                                    <!-- Alat -> TERLETAK_DI -> Lab -->
                                    <path d="M 207 68 L 153 162" stroke="#f59e0b" stroke-width="1.5" marker-end="url(#kg-arrow)"/>
                                    <text x="195" y="120" font-family="Inter, sans-serif" font-size="6.5" font-weight="bold" fill="#f59e0b" text-anchor="middle">TERLETAK_DI</text>
                                    
                                    <!-- Mahasiswa -> PRAKTIKUM_DI -> Lab -->
                                    <path d="M 73 68 L 127 162" stroke="#10b981" stroke-width="1.5" marker-end="url(#kg-arrow)"/>
                                    <text x="85" y="120" font-family="Inter, sans-serif" font-size="6.5" font-weight="bold" fill="#10b981" text-anchor="middle">BELAJAR_DI</text>
                                </svg>
                            </div>
                            <p class="small text-white-50 text-center mb-0 px-2" style="font-size: 0.75rem;"><i class="fas fa-info-circle me-1"></i> Data dihubungkan secara natural sebagai entitas semantik, memangkas kebutuhan operasi tabel join yang lambat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SQL vs Cypher comparisons -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-code text-primary me-2"></i> Perbandingan Kode: SQL JOIN vs Cypher MATCH</h5>
                <p class="text-muted small">Bagaimana kedua sistem memproses pelacakan relasi: *"Mencari alat apa saja yang dipinjam oleh mahasiswa kelompok tugas akhir Dwiky Ilham."*</p>
                
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold text-danger mb-2">Relational System (SQL)</h6>
                        <pre class="code-box">
SELECT a.nama_alat, u.nama_lengkap 
FROM transaksi t
INNER JOIN users u ON t.username = u.username
INNER JOIN alat a ON t.id_alat = a.id
WHERE u.username IN (
    -- Subquery mencari rekan satu kelompok Dwiky
    SELECT kelompok_user FROM kelompok 
    WHERE nama_kelompok = (
        SELECT nama_kelompok FROM kelompok k
        INNER JOIN users us ON k.id_mahasiswa = us.id
        WHERE us.username = '250420501100004'
    )
);</pre>
                    </div>
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold text-success mb-2">Knowledge Graph System (Cypher Query)</h6>
                        <pre class="code-box" style="border-left-color: #10b981;">
MATCH (m1:Mahasiswa {username: '250420501100004'})
      -[:REKAN_KLP]-(m2:Mahasiswa)
      -[:MEMINJAM]->(a:Alat)
RETURN a.nama_alat, m2.nama_lengkap;</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 6: Clickable Interactive Prototype -->
        <div class="tab-pane fade" id="proto" role="tabpanel">
            <div class="row g-4">
                <!-- Prototype Simulator Window -->
                <div class="col-12 col-lg-8 mx-auto">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="border: 2px solid var(--primary-gold) !important;">
                        <!-- Browser Header bar -->
                        <div class="bg-dark p-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="rounded-circle bg-danger d-inline-block" style="width: 10px; height: 10px;"></span>
                                <span class="rounded-circle bg-warning d-inline-block" style="width: 10px; height: 10px;"></span>
                                <span class="rounded-circle bg-success d-inline-block" style="width: 10px; height: 10px;"></span>
                            </div>
                            <span class="badge bg-secondary text-white font-weight-bold" style="font-size: 0.75rem;">E-Lab Peminjaman Simulator (Interactive Prototype)</span>
                            <div></div>
                        </div>

                        <!-- Main Screen body -->
                        <div class="bg-white p-4" style="min-height: 380px; position: relative;">
                            
                            <!-- STEP 1: CATALOGUE SEARCH -->
                            <div id="step-1" class="wizard-step active">
                                <div class="alert bg-warning-subtle text-warning border-warning-subtle d-flex align-items-center mb-4">
                                    <i class="fas fa-info-circle fa-lg me-3"></i>
                                    <div class="small">Selamat Datang di Portal Mahasiswa. Pilih salah satu alat di bawah untuk mulai meminjam.</div>
                                </div>
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-search me-2 text-primary"></i> Pilih Alat Yang Ingin Dipinjam</h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="p-3 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">Oscilloscope Digital</h6>
                                                <small class="text-muted">Tersedia: <strong>12 Unit</strong></small>
                                            </div>
                                            <button class="btn btn-sm btn-orange text-white" style="background: var(--primary-orange); border: none;" onclick="goToStep2('Oscilloscope Digital', 12)">
                                                Pinjam <i class="fas fa-arrow-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="p-3 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">Signal Generator</h6>
                                                <small class="text-muted">Tersedia: <strong>5 Unit</strong></small>
                                            </div>
                                            <button class="btn btn-sm btn-orange text-white" style="background: var(--primary-orange); border: none;" onclick="goToStep2('Signal Generator', 5)">
                                                Pinjam <i class="fas fa-arrow-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="p-3 border rounded-3 bg-light d-flex justify-content-between align-items-center opacity-75">
                                            <div>
                                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">Mikroskop SEM</h6>
                                                <small class="text-muted">Tersedia: <strong class="text-danger">0 Unit (Habis)</strong></small>
                                            </div>
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                Habis
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 2: FORM INPUT & ERROR PREVENTION -->
                            <div id="step-2" class="wizard-step">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-edit me-2 text-primary"></i> Isi Form Peminjaman - <span id="selectedToolName" class="text-orange">Alat</span></h6>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nomor Pokok Mahasiswa (NPM)</label>
                                    <input type="text" class="form-control form-control-sm" id="protoNpm" placeholder="Masukkan NPM Anda (e.g. 250420501100004)">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Jumlah Pinjam (Stok tersedia: <span id="protoStokLabel">0</span>)</label>
                                    <input type="number" class="form-control form-control-sm" id="protoJumlah" value="1">
                                </div>
                                <!-- Error Prevention Warning placeholder -->
                                <div class="alert alert-danger p-2 small mb-4" id="protoErrorArea" style="display: none;">
                                    <i class="fas fa-times-circle me-2"></i> <span id="protoErrorMessage">Error</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- User Control & Freedom back button -->
                                    <button class="btn btn-sm btn-light border px-4" onclick="goToStep1()">
                                        <i class="fas fa-chevron-left me-1"></i> Kembali
                                    </button>
                                    <button class="btn btn-sm btn-orange text-white px-4" style="background: var(--primary-orange); border: none;" onclick="validateStep2()">
                                        Lanjut <i class="fas fa-chevron-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 3: DATE SELECTION -->
                            <div id="step-3" class="wizard-step">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-calendar-alt me-2 text-primary"></i> Atur Waktu Pengembalian</h6>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Tanggal Pinjam</label>
                                    <input type="date" class="form-control form-control-sm" id="protoTglPinjam" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Batas Waktu Pengembalian</label>
                                    <input type="date" class="form-control form-control-sm" id="protoTglBatas" readonly>
                                    <div class="form-text text-orange" style="font-size: 0.75rem;"><i class="fas fa-info-circle me-1"></i> Batas pengembalian maksimal 3 hari kerja otomatis dihitung oleh sistem.</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <button class="btn btn-sm btn-light border px-4" onclick="backToStep2()">
                                        <i class="fas fa-chevron-left me-1"></i> Kembali
                                    </button>
                                    <button class="btn btn-sm btn-orange text-white px-4" style="background: var(--primary-orange); border: none;" onclick="submitPeminjaman()">
                                        Ajukan Pengajuan <i class="fas fa-paper-plane ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 4: VISIBILITY OF SYSTEM STATUS (LOADING INDICATOR) -->
                            <div id="step-4" class="wizard-step">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mt-4">Memproses Pengajuan Peminjaman...</h6>
                                    <p class="text-muted small">Sistem sedang memverifikasi sisa kuota, status kepatuhan denda, dan mengamankan alokasi stok barang di database.</p>
                                    <div class="progress mx-auto mt-3" style="width: 80%; height: 8px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" id="protoProgressBar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 5: SUCCESS STATE -->
                            <div id="step-5" class="wizard-step">
                                <div class="text-center py-4">
                                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                                    <h5 class="fw-bold text-dark">Peminjaman Berhasil Diajukan!</h5>
                                    <p class="text-muted small px-4">Pengajuan Anda telah direkam di sistem. Status saat ini: <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Persetujuan</span>. Silakan temui asisten lab <strong>Misbah Anuari</strong> untuk penyerahan fisik alat.</p>
                                    
                                    <div class="card p-3 border-0 bg-light rounded-3 text-start mx-auto mb-4" style="max-width: 380px; font-size: 0.85rem;">
                                        <div><strong>Alat:</strong> <span id="successToolName">Oscilloscope</span></div>
                                        <div><strong>Jumlah:</strong> <span id="successJumlah">1</span> Unit</div>
                                        <div><strong>Tanggal Pinjam:</strong> <span id="successTglPinjam">28 May 2026</span></div>
                                        <div><strong>Batas Waktu:</strong> <span id="successTglBatas">31 May 2026</span></div>
                                    </div>

                                    <button class="btn btn-sm btn-orange text-white px-5 rounded-pill" style="background: var(--primary-orange); border: none;" onclick="resetWizard()">
                                        <i class="fas fa-home me-2"></i> Selesai & Kembali ke Katalog
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- AI/JS Simulation script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab preservation or switching trigger
        const hash = window.location.hash;
        if(hash) {
            const tabEl = document.querySelector(`button[data-bs-target="${hash}"]`);
            if(tabEl) {
                const tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }

        // 1. Wireframe vs Mockup Toggle Logic
        const toggle = document.getElementById('uiModeToggle');
        const label = document.getElementById('uiModeLabel');
        const wrapper = document.getElementById('simulationWrapper');

        if(toggle) {
            toggle.addEventListener('change', function() {
                if(this.checked) {
                    // Turn on Wireframe Mode
                    wrapper.classList.add('ui-wireframe');
                    label.textContent = "Mode: Wireframe (Low Fidelity)";
                } else {
                    // Turn off Wireframe Mode (Back to Mockup)
                    wrapper.classList.remove('ui-wireframe');
                    label.textContent = "Mode: Mockup (High Fidelity)";
                }
            });
        }
    });

    // 2. Knowledge Graph Script
    let activeSelectedNode = null;
    function selectNode(id, name, type, details) {
        // Reset old selections
        document.querySelectorAll('.kg-node').forEach(n => n.classList.remove('selected'));
        // Select new node
        const node = document.getElementById('node-' + id);
        if(node) {
            node.classList.add('selected');
            activeSelectedNode = id;
        }

        // Highlight paths
        highlightPathsForNode(id);

        // Update detail sidebar
        const detailContainer = document.getElementById('kgDetails');
        
        let typeBadgeColor = 'bg-secondary';
        if (type.includes('Mahasiswa')) typeBadgeColor = 'bg-success';
        if (type.includes('Alat')) typeBadgeColor = 'bg-warning text-dark';
        if (type.includes('Mata Kuliah')) typeBadgeColor = 'bg-purple';
        if (type.includes('Dosen')) typeBadgeColor = 'bg-primary';
        if (type.includes('Laboratorium')) typeBadgeColor = 'bg-pink';

        detailContainer.innerHTML = `
            <div class="p-3 bg-slate-900 border border-slate-700 rounded-3 text-light">
                <span class="badge ${typeBadgeColor} mb-2">${type}</span>
                <h6 class="fw-bold text-white mb-2">${name}</h6>
                <p class="small text-white-50 mb-0">${details}</p>
            </div>
        `;
    }

    function highlightPathsForNode(nodeId) {
        // Fade all edges and labels
        document.querySelectorAll('.kg-edge, .kg-edge-label').forEach(el => {
            el.classList.add('filtered-out');
        });

        // Show matching edges
        document.querySelectorAll('.kg-edge').forEach(edge => {
            const idAttr = edge.id;
            if(idAttr.includes(nodeId)) {
                edge.classList.remove('filtered-out');
                const label = document.getElementById('label-' + idAttr.replace('edge-', ''));
                if(label) label.classList.remove('filtered-out');
            }
        });
    }

    function expandNode(nodeId) {
        // Simulate Node Expansion interaction (double click)
        // Show N6 and N7 nodes & edges
        if(nodeId === 'n2') {
            // Expand N6 (Signal Generator)
            document.getElementById('node-n6').style.display = 'block';
            document.getElementById('edge-n6-n2').style.display = 'block';
            document.getElementById('label-n6-n2').style.display = 'block';

            // remove filtered-out style on N6
            setTimeout(() => {
                document.getElementById('node-n6').classList.remove('filtered-out');
                document.getElementById('edge-n6-n2').classList.remove('filtered-out');
                document.getElementById('label-n6-n2').classList.remove('filtered-out');
            }, 50);

            // Toast feedback
            Toast.fire({
                icon: 'info',
                title: 'Node Expanded: Menampilkan hubungan Signal Generator ke Oscilloscope'
            });
        }
        if(nodeId === 'n1') {
            // Expand N7 (Naza Fahrul)
            document.getElementById('node-n7').style.display = 'block';
            document.getElementById('edge-n7-n1').style.display = 'block';
            document.getElementById('label-n7-n1').style.display = 'block';

            // remove filtered-out style on N7
            setTimeout(() => {
                document.getElementById('node-n7').classList.remove('filtered-out');
                document.getElementById('edge-n7-n1').classList.remove('filtered-out');
                document.getElementById('label-n7-n1').classList.remove('filtered-out');
            }, 50);

            // Toast feedback
            Toast.fire({
                icon: 'info',
                title: 'Node Expanded: Menampilkan kelompok belajar Dwiky & Naza'
            });
        }
    }

    function resetGraph() {
        // Hide N6 and N7
        document.getElementById('node-n6').style.display = 'none';
        document.getElementById('edge-n6-n2').style.display = 'none';
        document.getElementById('label-n6-n2').style.display = 'none';

        document.getElementById('node-n7').style.display = 'none';
        document.getElementById('edge-n7-n1').style.display = 'none';
        document.getElementById('label-n7-n1').style.display = 'none';

        // Unfade all primary nodes/edges
        document.querySelectorAll('.kg-node, .kg-edge, .kg-edge-label').forEach(el => {
            el.classList.remove('filtered-out');
        });
        document.querySelectorAll('.kg-node').forEach(n => n.classList.remove('selected'));
        
        // Reset checkboxes
        document.querySelectorAll('.filter-cb').forEach(cb => cb.checked = true);

        // Reset detail panel
        document.getElementById('kgDetails').innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-hand-pointer fa-2x mb-2"></i>
                <p class="mb-0 small">Klik salah satu node lingkaran di sebelah kiri untuk melihat atribut semantiknya.</p>
                <p class="mb-0 small text-warning mt-2"><i class="fas fa-mouse me-1"></i> Double-click untuk ekspansi relasi baru!</p>
            </div>
        `;

        Toast.fire({
            icon: 'success',
            title: 'Knowledge Graph direset ke struktur utama.'
        });
    }

    function applyFilters() {
        // Collect checked categories
        const activeCategories = [];
        document.querySelectorAll('.filter-cb').forEach(cb => {
            if(cb.checked) activeCategories.push(cb.value);
        });

        // Filter nodes
        // N1: Mahasiswa, N2: Alat, N3: Mata Kuliah, N4: Dosen, N5: Lab, N6: Alat, N7: Mahasiswa
        filterNodeHelper('n1', 'Mahasiswa', activeCategories);
        filterNodeHelper('n2', 'Alat Inventaris', activeCategories);
        filterNodeHelper('n3', 'Mata Kuliah', activeCategories);
        filterNodeHelper('n4', 'Dosen Pembimbing / Pengajar', activeCategories);
        filterNodeHelper('n5', 'Ruang Laboratorium', activeCategories);
        filterNodeHelper('n6', 'Alat Inventaris', activeCategories);
        filterNodeHelper('n7', 'Mahasiswa', activeCategories);

        // Filter Edges: if source or target node is filtered out, fade the edge
        document.querySelectorAll('.kg-edge').forEach(edge => {
            const idAttr = edge.id;
            const parts = idAttr.replace('edge-', '').split('-');
            const sourceNode = document.getElementById('node-' + parts[0]);
            const targetNode = document.getElementById('node-' + parts[1]);

            const isSourceFiltered = sourceNode.classList.contains('filtered-out');
            const isTargetFiltered = targetNode.classList.contains('filtered-out');

            const edgeLabel = document.getElementById('label-' + idAttr.replace('edge-', ''));

            if(isSourceFiltered || isTargetFiltered) {
                edge.classList.add('filtered-out');
                if(edgeLabel) edgeLabel.classList.add('filtered-out');
            } else {
                edge.classList.remove('filtered-out');
                if(edgeLabel) edgeLabel.classList.remove('filtered-out');
            }
        });
    }

    function filterNodeHelper(nodeId, category, activeCategories) {
        const node = document.getElementById('node-' + nodeId);
        if(!activeCategories.includes(category)) {
            node.classList.add('filtered-out');
        } else {
            node.classList.remove('filtered-out');
        }
    }


    // 3. Interactive Prototype (Wizard Simulation)
    let selectedTool = "";
    let toolMaxStock = 0;

    function showWizardStep(stepNum) {
        document.querySelectorAll('.wizard-step').forEach(step => {
            step.classList.remove('active');
        });
        document.getElementById('step-' + stepNum).classList.add('active');
    }

    function goToStep1() {
        showWizardStep(1);
    }

    function goToStep2(toolName, stock) {
        selectedTool = toolName;
        toolMaxStock = stock;
        
        document.getElementById('selectedToolName').textContent = toolName;
        document.getElementById('protoStokLabel').textContent = stock;
        document.getElementById('protoErrorArea').style.display = 'none';
        
        showWizardStep(2);
    }

    function validateStep2() {
        const npm = document.getElementById('protoNpm').value.trim();
        const jumlah = parseInt(document.getElementById('protoJumlah').value) || 0;
        const errorArea = document.getElementById('protoErrorArea');
        const errorMessage = document.getElementById('protoErrorMessage');

        errorArea.style.display = 'none';

        // Error Prevention Heuristic Sim
        if(!npm) {
            errorMessage.textContent = "NPM wajib diisi! Silakan masukkan NPM Anda.";
            errorArea.style.display = 'block';
            return;
        }

        if(npm.length < 10) {
            errorMessage.textContent = "Format NPM tidak valid! Masukkan NPM lengkap (minimal 10 digit).";
            errorArea.style.display = 'block';
            return;
        }

        if(jumlah <= 0) {
            errorMessage.textContent = "Jumlah peminjaman minimal harus 1 Unit!";
            errorArea.style.display = 'block';
            return;
        }

        if(jumlah > toolMaxStock) {
            errorMessage.textContent = `Error Prevention: Jumlah (${jumlah} unit) melebihi stok yang tersedia (${toolMaxStock} unit) di laboratorium!`;
            errorArea.style.display = 'block';
            return;
        }

        // Set dates
        const today = new Date();
        const returnDate = new Date();
        returnDate.setDate(today.getDate() + 3); // max 3 days loan limit

        document.getElementById('protoTglPinjam').value = today.toISOString().split('T')[0];
        document.getElementById('protoTglBatas').value = returnDate.toISOString().split('T')[0];

        showWizardStep(3);
    }

    function backToStep2() {
        showWizardStep(2);
    }

    function submitPeminjaman() {
        showWizardStep(4);
        
        // Simulate System Status Loading bar animation
        let width = 0;
        const bar = document.getElementById('protoProgressBar');
        const interval = setInterval(() => {
            if(width >= 100) {
                clearInterval(interval);
                
                // Show success screen
                document.getElementById('successToolName').textContent = selectedTool;
                document.getElementById('successJumlah').textContent = document.getElementById('protoJumlah').value;
                document.getElementById('successTglPinjam').textContent = document.getElementById('protoTglPinjam').value;
                document.getElementById('successTglBatas').textContent = document.getElementById('protoTglBatas').value;

                showWizardStep(5);
            } else {
                width += 20;
                bar.style.width = width + '%';
            }
        }, 300);
    }

    function resetWizard() {
        document.getElementById('protoNpm').value = '';
        document.getElementById('protoJumlah').value = '1';
        selectedTool = '';
        toolMaxStock = 0;
        showWizardStep(1);
    }

    function exportToPDF() {
        // Ensure the graph is reset to show all nodes before copying its SVG
        if (typeof resetGraph === 'function') {
            resetGraph();
        }

        // Show loading alert using SweetAlert2
        Swal.fire({
            title: 'Mempersiapkan PDF...',
            text: 'Mengkompilasi halaman laporan UCD E-Lab Elektro.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Create a temporary element specifically for PDF generation
        const element = document.createElement('div');
        element.innerHTML = `
            <div style="padding: 40px; font-family: 'Inter', sans-serif; background: white; color: #1e293b;">
                <!-- Cover Page -->
                <div style="height: 270mm; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; border: 4px double #ea580c; padding: 40px; box-sizing: border-box;">
                    <h1 style="font-size: 26pt; color: #0f172a; font-weight: 800; margin-bottom: 20px; line-height: 1.3;">LAPORAN EVALUASI UCD & PROTOTIPE AKHIR</h1>
                    <h3 style="font-size: 15pt; color: #ea580c; font-weight: 600; margin-bottom: 50px;">Sistem Informasi Manajemen Laboratorium Terpadu (E-Lab Elektro)</h3>
                    
                    <div style="margin-bottom: 60px;">
                        <img src="<?= base_url('assets/img/logo-usk.png') ?>" height="150" alt="Logo USK" style="object-fit: contain;">
                    </div>
                    
                    <p style="font-size: 13pt; margin-bottom: 5px; font-weight: 600; color: #334155;">Mata Kuliah: Interaksi Manusia & Komputer</p>
                    <p style="font-size: 12pt; color: #475569; margin-bottom: 40px;">Program Studi Teknik Elektro - Fakultas Teknik<br>Universitas Syiah Kuala</p>
                    
                    <hr style="width: 100px; border-top: 3px solid #ea580c; margin-bottom: 30px; margin-left: auto; margin-right: auto;">
                    <p style="font-size: 9pt; color: #64748b;">Laporan Otomatis Sistem E-Lab Elektro &bull; ${new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                </div>
                
                <div class="html2pdf__page-break"></div>

                <!-- Page 1: User Persona & Needs -->
                <div style="padding-top: 10px;">
                    <h2 style="font-size: 18pt; border-bottom: 2px solid #ea580c; padding-bottom: 8px; color: #0f172a; margin-bottom: 20px;">1. User Persona & Analisis Kebutuhan (UCD)</h2>
                    <p style="font-size: 10pt; line-height: 1.6; color: #334155;">Berikut adalah identifikasi kelompok pengguna utama dan kebutuhan fitur solusi yang telah dianalisis untuk sistem E-Lab Elektro:</p>
                    
                    <div style="margin-top: 20px;">
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 9pt;">
                            <thead>
                                <tr style="background-color: #f1f5f9;">
                                    <th style="border: 1px solid #cbd5e1; padding: 10px; text-align: left;">Persona</th>
                                    <th style="border: 1px solid #cbd5e1; padding: 10px; text-align: left;">Karakteristik & Frustrasi</th>
                                    <th style="border: 1px solid #cbd5e1; padding: 10px; text-align: left;">Kebutuhan Fitur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px; font-weight: bold; width: 25%;">Dwiky Ilham (Mahasiswa)</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Penyusunan tugas akhir. Birokrasi peminjaman manual memakan waktu lama dan stok di rak tidak real-time.</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Katalog stok real-time, keranjang belanja peminjaman praktis, pengingat otomatis.</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px; font-weight: bold;">Misbah Anuari (Penjaga Lab)</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Mengecek kondisi fisik alat. Kesulitan melacak pengembalian terlambat dan denda sering keliru.</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Dasbor approval sekali klik, kalkulasi denda otomatis, pencatatan status alat (rusak/maintenance).</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px; font-weight: bold;">Rana Sulthanah (Kaprodi)</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Pengawasan anggaran & aset. Tidak ada rekap data peminjaman tahunan untuk justifikasi pengadaan barang baru.</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Analitik grafik top 5 alat terpopuler dan tingkat kesehatan aset.</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px; font-weight: bold;">Admin Utama (Admin)</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Pengelolaan keamanan & database. Risiko manipulasi data transaksi tanpa jejak audit.</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 10px;">Audit logs sistem terintegrasi yang mencatat waktu, aksi, dan aktor.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="html2pdf__page-break"></div>

                <!-- Page 2: User Flow & Sitemap -->
                <div style="padding-top: 10px;">
                    <h2 style="font-size: 18pt; border-bottom: 2px solid #ea580c; padding-bottom: 8px; color: #0f172a; margin-bottom: 20px;">2. User Flow & Struktur Navigasi (Sitemap)</h2>
                    <p style="font-size: 10pt; line-height: 1.6; color: #334155; margin-bottom: 20px;">Alur interaksi pengguna dibedakan menjadi dua pendekatan utama: Alur CRUD Peminjaman Relasional (Linier) dan Alur Eksplorasi Knowledge Graph Semantik (Eksploratif):</p>
                    <div style="text-align: center; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px; margin-bottom: 30px;">
                        ${document.querySelector('#flow svg').outerHTML}
                    </div>
                    <h4 style="font-size: 12pt; color: #0f172a; font-weight: bold; margin-bottom: 10px;">Struktur Navigasi Sitemap Utama:</h4>
                    <div style="font-size: 9.5pt; color: #334155; line-height: 1.7;">
                        <strong>&bull; Halaman Publik:</strong> Login (Captcha) &bull; Registrasi Akun &bull; Lupa Sandi & Reset Token<br>
                        <strong>&bull; Dashboard:</strong> Widget Statistik &bull; Chart Kesehatan &bull; Grafik Top 5 Alat Populer<br>
                        <strong>&bull; Inventaris Alat:</strong> CRUD Data Alat &bull; Cetak Barcode / QR Label<br>
                        <strong>&bull; Peminjaman Alat:</strong> Keranjang Belanja &bull; Validasi Denda &bull; Persetujuan Admin<br>
                        <strong>&bull; Laporan & Analitik:</strong> Ekspor Excel &bull; Cetak Surat Bebas Laboratorium
                    </div>
                </div>

                <div class="html2pdf__page-break"></div>

                <!-- Page 3: Wireframe vs Mockup -->
                <div style="padding-top: 10px;">
                    <h2 style="font-size: 18pt; border-bottom: 2px solid #ea580c; padding-bottom: 8px; color: #0f172a; margin-bottom: 20px;">3. Perancangan UI (Wireframe vs Mockup)</h2>
                    <p style="font-size: 10pt; line-height: 1.6; color: #334155; margin-bottom: 25px;">Transisi desain dilakukan dari rancangan kasar (Lo-Fi Wireframe) menuju implementasi antarmuka akhir (Hi-Fi Mockup). Desain diselaraskan berdasarkan teori psikologi desain berikut:</p>
                    
                    <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                        <h5 style="font-size: 11pt; font-weight: bold; color: #ea580c; margin-bottom: 5px;">Hukum Fitts (Fitts's Law)</h5>
                        <p style="font-size: 9.5pt; line-height: 1.5; color: #475569; margin-bottom: 0;">Tombol utama seperti "Pinjam" atau "Ajukan" dirancang berukuran minimal 40px tinggi dengan warna kontras kontemporer (Oranye) dan diletakkan di sisi kanan bawah agar meminimalkan tingkat kegagalan dan waktu jangkauan jempol/kursor.</p>
                    </div>

                    <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                        <h5 style="font-size: 11pt; font-weight: bold; color: #3b82f6; margin-bottom: 5px;">Prinsip Gestalt (Kedekatan & Kesamaan)</h5>
                        <p style="font-size: 9.5pt; line-height: 1.5; color: #475569; margin-bottom: 0;">Data statistik inventaris dikelompokkan secara rapat dengan layout kolom teratur (*Proximity*). Kode warna status ketersediaan (Hijau untuk tersedia, Merah untuk habis/rusak) diseragamkan di seluruh halaman (*Similarity*) untuk memfasilitasi penarikan kesimpulan instan oleh pengguna.</p>
                    </div>
                </div>

                <div class="html2pdf__page-break"></div>

                <!-- Page 4: Heuristic Evaluation & SUS -->
                <div style="padding-top: 10px;">
                    <h2 style="font-size: 18pt; border-bottom: 2px solid #ea580c; padding-bottom: 8px; color: #0f172a; margin-bottom: 20px;">4. Evaluasi Usability (Heuristic & SUS)</h2>
                    
                    <h4 style="font-size: 11pt; color: #ea580c; font-weight: bold; margin-bottom: 10px;">Tabel Evaluasi Heuristik Nielsen:</h4>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 8.5pt; line-height: 1.4;">
                        <thead>
                            <tr style="background-color: #f1f5f9;">
                                <th style="border: 1px solid #cbd5e1; padding: 8px; text-align: left; width: 25%;">Prinsip Heuristik</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; text-align: left;">Temuan Masalah Usability</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; width: 10%;">Severity</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; text-align: left; width: 35%;">Solusi Desain Perbaikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold;">1. Visibility of system status</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Layar membeku saat memproses data transaksi tanpa indikasi loading.</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; color: #ef4444; font-weight: bold;">3</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Menambahkan loading progress bar dan SweetAlert toast konfirmasi setelah transaksi.</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold;">2. Match between system & real world</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Label form menggunakan nama field database teknis (`id_alat`, `batas_waktu_ts`).</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; color: #f59e0b; font-weight: bold;">2</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Mengubah teks label menjadi: "Nama Alat", "Jabatan", dan "Batas Waktu Kembali".</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold;">3. User control & freedom</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Mahasiswa tidak dapat membatalkan item keranjang belanja tanpa merestart browser.</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; color: #ef4444; font-weight: bold;">3</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Menyediakan ikon hapus (tong sampah merah) di keranjang dan tombol Batal di modal dialog.</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold;">4. Consistency & standards</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Warna tombol aksi primer tidak seragam di berbagai menu (ungu, hijau, biru).</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; color: #f59e0b; font-weight: bold;">2</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Standardisasi: Oranye untuk tombol primer, Abu-abu untuk sekunder, Merah untuk bahaya/denda.</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold;">5. Error prevention</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Bisa menginput jumlah pinjam melebihi sisa stok di rak, memicu error SQL.</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; color: #b91c1c; font-weight: bold;">4</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px;">Menerapkan validasi otomatis di frontend (*max="stok_tersedia"*). Menolak submit jika melebihi batas.</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 style="font-size: 11pt; color: #ea580c; font-weight: bold; margin-bottom: 10px;">Rencana Pengujian Usability (SUS):</h4>
                    <p style="font-size: 10pt; line-height: 1.5; color: #334155; margin-bottom: 0;">Pengujian usability secara empiris direncanakan menggunakan instrumen kuesioner **System Usability Scale (SUS)** standar (10 pertanyaan dengan skala Likert 1-5) dan 3 skenario tugas utama yang diuji pada minimal 10 responden (mahasiswa, penjaga lab, dan kaprodi). Hasil pengujian nantinya akan dipetakan langsung pada visualisasi gauge meter di dashboard UCD showcase.</p>
                </div>

                <div class="html2pdf__page-break"></div>

                <!-- Page 5: Knowledge Graph System & Query -->
                <div style="padding-top: 10px;">
                    <h2 style="font-size: 18pt; border-bottom: 2px solid #ea580c; padding-bottom: 8px; color: #0f172a; margin-bottom: 20px;">5. Visualisasi Knowledge Graph & Komparasi Kueri</h2>
                    <p style="font-size: 10pt; line-height: 1.6; color: #334155; margin-bottom: 20px;">Visualisasi peta hubungan semantik dinamis menggambarkan relasi nyata di laboratorium:</p>
                    
                    <div style="text-align: center; background: #0f172a; border: 1px solid #334155; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                        ${document.getElementById('kgSvg').outerHTML}
                    </div>
                    
                    <h4 style="font-size: 11pt; color: #0f172a; font-weight: bold; margin-bottom: 10px;">Perbandingan Kueri SQL vs Cypher:</h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 8.5pt;">
                        <thead>
                            <tr style="background-color: #f1f5f9;">
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 50%;">SQL (Sistem Relasional)</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 50%;">Cypher (Sistem Knowledge Graph)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-family: monospace; vertical-align: top; background-color: #fafafa;">
                                    SELECT a.nama_alat, u.nama_lengkap<br>
                                    FROM transaksi t<br>
                                    JOIN users u ON t.username = u.username<br>
                                    JOIN alat a ON t.id_alat = a.id<br>
                                    WHERE u.username IN (<br>
                                    &nbsp;&nbsp;SELECT kelompok_user FROM kelompok<br>
                                    &nbsp;&nbsp;WHERE nama_kelompok = (<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;SELECT nama_kelompok FROM kelompok k<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;JOIN users us ON k.id_mahasiswa = us.id<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;WHERE us.username = '250420501100004'<br>
                                    &nbsp;&nbsp;)<br>
                                    );
                                </td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-family: monospace; vertical-align: top; background-color: #fafafa; color: #0f766e;">
                                    MATCH (m1:Mahasiswa {username: '250420501100004'})<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-[:REKAN_KLP]-(m2:Mahasiswa)<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-[:MEMINJAM]->(a:Alat)<br>
                                    RETURN a.nama_alat, m2.nama_lengkap;
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `;

        // Options for html2pdf
        const opt = {
            margin:       10,
            filename:     'Laporan_UCD_dan_Prototype_ELab.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Run export
        html2pdf().set(opt).from(element).save().then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Ekspor Berhasil!',
                text: 'Dokumen PDF Laporan Resmi telah diunduh.',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Ekspor Gagal!',
                text: 'Terjadi kesalahan saat memproses ekspor PDF.'
            });
        });
    }
</script>

<?= $this->endSection() ?>
