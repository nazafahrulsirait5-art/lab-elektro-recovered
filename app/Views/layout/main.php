<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'E-Lab Elektro' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Vis.js -->
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom Assets -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>
<body class="bg-light">

<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="<?= base_url('assets/img/logo-usk.png') ?>" alt="USK" height="32">
            <div class="brand-divider"></div>
            <span>E-Lab Elektro</span>
        </div>
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (url_is('dashboard*') || url_is('/')) ? 'active' : '' ?>">
                    <i class="fas fa-home fa-fw"></i> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= base_url('alat') ?>" class="nav-link <?= (url_is('alat*')) ? 'active' : '' ?>">
                    <i class="fas fa-boxes fa-fw"></i> Inventaris
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#graphModal">
                    <i class="fas fa-project-diagram fa-fw"></i> Knowledge Graph
                </a>
            </div>
            <?php if(session()->get('role') == 'mahasiswa'): ?>
            <div class="nav-item">
                <a href="<?= base_url('booking') ?>" class="nav-link <?= (url_is('booking*')) ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt fa-fw" style="color: #0ea5e9;"></i> Booking Lab
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#cartModal">
                    <i class="fas fa-shopping-cart fa-fw" style="color: #ea580c;"></i> Keranjang Pinjam
                    <span id="cartBadgeCount" class="badge rounded-pill bg-danger ms-auto">0</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= base_url('peminjaman/riwayat') ?>" class="nav-link <?= (url_is('peminjaman/riwayat*')) ? 'active' : '' ?>">
                    <i class="far fa-clock fa-fw"></i> Riwayat & Denda
                </a>
            </div>
            <?php elseif(in_array(session()->get('role'), ['admin', 'penjaga_lab', 'penjaga'])): ?>
            <div class="nav-item">
                <a href="<?= base_url('booking/admin') ?>" class="nav-link <?= (url_is('booking*')) ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check fa-fw"></i> Booking Lab
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= base_url('peminjaman') ?>" class="nav-link <?= (url_is('peminjaman') || url_is('peminjaman/index')) ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart fa-fw"></i> Peminjaman
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= base_url('praktikum') ?>" class="nav-link <?= (url_is('praktikum*')) ? 'active' : '' ?>">
                    <i class="fas fa-file-pdf fa-fw"></i> Modul Praktikum
                </a>
            </div>
            <?php endif; ?>
            <?php if(in_array(session()->get('role'), ['admin', 'kaprodi'])): ?>
            <div class="nav-item">
                <a href="<?= base_url('laporan/analitik') ?>" class="nav-link <?= (url_is('laporan/analitik*')) ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie fa-fw"></i> Statistik Lab
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= base_url('laporan/inventaris') ?>" class="nav-link <?= (url_is('laporan/inventaris*')) ? 'active' : '' ?>">
                    <i class="fas fa-file-excel fa-fw"></i> Laporan & Rekap
                </a>
            </div>
            <?php endif; ?>
            <?php if(session()->get('role') == 'admin'): ?>
            <div class="nav-item">
                <a href="<?= base_url('users') ?>" class="nav-link <?= (url_is('users*')) ? 'active' : '' ?>">
                    <i class="fas fa-users fa-fw"></i> Pengaturan Akun
                </a>
            </div>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark me-4 d-none d-md-block" style="text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem;">Laboratory Inventory Dashboard</h5>
            </div>
            <div class="d-flex align-items-center gap-4">
                <a href="#" class="text-muted position-relative">
                    <i class="far fa-bell fa-lg"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
                
                <a href="<?= base_url('profil') ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                    <img src="<?= base_url('uploads/profil/' . (session()->get('foto_profil') ?? 'default.png')) ?>"
                         alt="Profil"
                         class="rounded-circle shadow-sm"
                         style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #e2e8f0;"
                         onerror="this.src='https://api.dicebear.com/9.x/initials/svg?seed=<?= urlencode(session()->get('nama_lengkap') ?? 'User') ?>&backgroundColor=f36c21&textColor=ffffff'">
                    <div class="d-none d-md-block text-end me-2">
                        <div class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1.2;"><?= session()->get('nama_lengkap') ?></div>
                        <div class="text-muted" style="font-size: 0.75rem;"><?= ucfirst(str_replace('_', ' ', session()->get('role'))) ?></div>
                    </div>
                </a>

                <a href="<?= base_url('logout') ?>" class="text-danger" title="Logout">
                    <i class="fas fa-sign-out-alt fa-lg"></i>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 flex-grow-1" style="padding-bottom: 100px !important;">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<!-- AI Assistant Trigger -->
<div class="position-fixed bottom-0 end-0 m-4 no-print" style="z-index: 1050;">
    <button class="btn btn-primary rounded-circle shadow-lg p-3" style="background: #f59e0b; border: none;" data-bs-toggle="modal" data-bs-target="#aiModal">
        <i class="fas fa-robot fa-2x"></i>
    </button>
</div>

<?php if(session()->get('role') == 'mahasiswa'): ?>
<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem; overflow: hidden;">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-shopping-cart me-2" style="color: #ea580c;"></i> Keranjang Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4" style="background-color: #f8fafc;">
                <!-- Table cart items -->
                <div class="table-responsive bg-white rounded-4 shadow-sm p-2 mb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-muted small">
                            <tr>
                                <th>NAMA ALAT</th>
                                <th class="text-center" width="150">JUMLAH</th>
                                <th class="text-center" width="100">STOK</th>
                                <th class="text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-3 text-light"></i>
                                    <p class="mb-0">Keranjang masih kosong</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert mb-0" style="background-color: #fffaf5; border: 1px dashed #fdba74; border-radius: 10px; color: #c2410c;">
                    <i class="fas fa-info-circle me-2"></i> Peminjaman maksimal <strong>3 hari kerja</strong> berdasarkan aturan laboratorium.
                </div>
            </div>

            <div class="modal-footer border-0 p-4 bg-white">
                <form action="<?= base_url('peminjaman/pinjam') ?>" method="POST" id="formCheckoutCart" class="w-100 m-0">
                    <div id="cartHiddenInputs"></div>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold" style="background: #f59e0b; border: none;" id="btnCheckout" disabled>
                            <i class="fas fa-paper-plane me-2"></i> Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- AI Assistant Modal -->
<div class="modal fade" id="aiModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 bg-light" style="border-radius: 1.5rem 1.5rem 0 0;">
                <h5 class="modal-title fw-bold"><i class="fas fa-robot me-2 text-primary"></i> Lab Assistant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="chatWrapper" style="height: 350px; overflow-y: auto;">
                <div class="d-flex mb-3">
                    <div class="bg-light p-3 rounded-4" style="max-width: 85%;">
                        <div class="small fw-bold text-primary mb-1">Virtual Assistant</div>
                        Halo! Saya asisten virtual lab. Bagaimana saya bisa membantu Anda hari ini?
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <form id="aiChatForm" class="w-100 d-flex">
                    <input type="text" id="aiMessage" class="form-control rounded-pill me-2 border-primary-subtle" placeholder="Tanya sesuatu..." required>
                    <button type="submit" class="btn btn-primary rounded-circle"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Graph Modal -->
<div class="modal fade" id="graphModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 bg-light" style="border-radius: 1.5rem 1.5rem 0 0;">
                <h5 class="modal-title fw-bold"><i class="fas fa-project-diagram me-2 text-primary"></i> Knowledge Graph: Relasi Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="graphModalNetwork" style="width: 100%; height: 600px; background-color: #f8f9fa; border-radius: 0 0 1.5rem 1.5rem;">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<?php if(session()->getFlashdata('clear_cart')): ?>
<script>
    if (localStorage.getItem('lab_cart_items')) {
        localStorage.removeItem('lab_cart_items');
    }
</script>
<?php endif; ?>

<script>
    // Global SweetAlert Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    <?php if(session()->getFlashdata('success')): ?>
        Toast.fire({
            icon: 'success',
            title: '<?= session()->getFlashdata('success') ?>'
        });
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        Toast.fire({
            icon: 'error',
            title: '<?= session()->getFlashdata('error') ?>'
        });
    <?php endif; ?>

    <?php if(session()->getFlashdata('warning')): ?>
        Toast.fire({
            icon: 'warning',
            title: '<?= session()->getFlashdata('warning') ?>'
        });
    <?php endif; ?>

    // Logic for Graph Modal
    document.getElementById('graphModal').addEventListener('shown.bs.modal', function () {
        var container = document.getElementById('graphModalNetwork');
        if (container.dataset.loaded == 'true') return; // Load only once
        
        fetch('/graph/data')
            .then(response => response.json())
            .then(data => {
                var nodesData = new vis.DataSet(data.nodes);
                var edgesData = new vis.DataSet(data.edges);
                container.innerHTML = '';
                
                var options = {
                    nodes: {
                        borderWidth: 3,
                        size: 55,
                        font: { multi: 'html', size: 15 },
                        shadow: { enabled: true, color: 'rgba(0,0,0,0.2)', size: 5, x: 2, y: 2 }
                    },
                    edges: {
                        width: 3,
                        font: { size: 13, align: 'horizontal', background: 'white', multi: 'html' },
                        smooth: { type: 'cubicBezier', forceDirection: 'vertical', roundness: 0.4 },
                        shadow: { enabled: true, color: 'rgba(0,0,0,0.1)', size: 3, x: 1, y: 1 }
                    },
                    layout: { 
                        hierarchical: {
                            enabled: true,
                            direction: 'UD', // Up-Down (Users top, Alat bottom)
                            sortMethod: 'directed',
                            nodeSpacing: 250,
                            levelSeparation: 200
                        }
                    },
                    physics: {
                        enabled: false // Matikan physics dinamis agar posisinya tetap rapi sesuai hierarki
                    },
                    interaction: { hover: true, tooltipDelay: 200 }
                };

                var network = new vis.Network(container, { nodes: nodesData, edges: edgesData }, options);
                container.dataset.loaded = 'true';

                network.on("click", function (params) {
                    if (params.nodes.length > 0) {
                        var nodeId = params.nodes[0];
                        if (typeof nodeId === 'string' && nodeId.startsWith('u_')) {
                            var nodeInfo = nodesData.get(nodeId);
                            if (nodeInfo.no_hp) {
                                Swal.fire({
                                    title: 'Hubungi Peminjam',
                                    html: 'Kirim pesan ke <strong>' + nodeInfo.nama_lengkap + '</strong> untuk menanyakan alat?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: '<i class="fab fa-whatsapp"></i> Chat via WhatsApp',
                                    confirmButtonColor: '#25D366',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.open('https://wa.me/' + nodeInfo.no_hp, '_blank');
                                    }
                                });
                            } else {
                                Swal.fire('Informasi', 'Nomor WhatsApp pengguna ini belum tersedia.', 'info');
                            }
                        }
                    }
                });
            })
            .catch(error => {
                container.innerHTML = '<div class="alert alert-danger m-3">Gagal memuat data dari database.</div>';
            });
    });
</script>

</body>
</html>
