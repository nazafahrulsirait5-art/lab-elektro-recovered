<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Lab Elektro - Knowledge Graph</title>
    <!-- Bootstrap CSS for basic styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Vis.js -->
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        #mynetwork {
            width: 100%;
            height: 700px;
            border: 1px solid #d1d8e0;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .legend-box {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Knowledge Graph: Relasi Peminjaman Lab</h2>
                <a href="/" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
            </div>
            
            <div class="card mb-3">
                <div class="card-body py-2">
                    <strong>Keterangan Status Pinjam (Garis):</strong> 
                    <span class="legend-box ms-3" style="background-color: #e74c3c;"></span> Dipinjam
                    <span class="legend-box ms-3" style="background-color: #f1c40f;"></span> Menunggu Persetujuan
                    <span class="legend-box ms-3" style="background-color: #2ecc71;"></span> Dikembalikan
                </div>
            </div>

            <div id="mynetwork">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2">Memuat Data Graph...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil data JSON dari GraphController
        fetch('/graph/data')
            .then(response => response.json())
            .then(data => {
                // Buat DataSets khusus agar kita bisa mengambil data node-nya nanti
                var nodesData = new vis.DataSet(data.nodes);
                var edgesData = new vis.DataSet(data.edges);
                
                var container = document.getElementById('mynetwork');
                // Hapus tulisan loading
                container.innerHTML = '';
                
                var options = {
                    nodes: {
                        borderWidth: 2,
                        size: 45,
                        font: { size: 14, multi: 'html', background: 'rgba(255,255,255,0.7)' },
                        shadow: true
                    },
                    edges: {
                        width: 2,
                        font: { size: 12, align: 'middle' },
                        smooth: { type: 'dynamic' },
                        shadow: true
                    },
                    layout: {
                        improvedLayout: true
                    },
                    physics: {
                        solver: 'forceAtlas2Based',
                        forceAtlas2Based: {
                            gravitationalConstant: -100,
                            centralGravity: 0.01,
                            springLength: 300,
                            springConstant: 0.08
                        },
                        maxVelocity: 50,
                        minVelocity: 0.1
                    },
                    interaction: {
                        hover: true,
                        tooltipDelay: 200
                    }
                };

                // Inisialisasi network
                var network = new vis.Network(container, { nodes: nodesData, edges: edgesData }, options);
                
                // Event click pada node
                network.on("click", function (params) {
                    if (params.nodes.length > 0) {
                        var nodeId = params.nodes[0];
                        // Jika yang diklik adalah Mahasiswa (id diawali 'u_')
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
                console.error("Error loading graph data:", error);
                document.getElementById('mynetwork').innerHTML = '<div class="alert alert-danger m-3">Gagal memuat data dari database. Pastikan server berjalan.</div>';
            });
    });
</script>

</body>
</html>
