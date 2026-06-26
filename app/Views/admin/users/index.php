<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-users me-2" style="color:#f36c21;"></i> Manajemen Pengguna</h4>
        <button class="btn btn-primary rounded-pill px-4" style="background:#f36c21; border:none;" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus me-2"></i> Tambah User Baru
        </button>
    </div>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>User & Role</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $index => $u): ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $index + 1 ?></td>
                        <td>
                            <div class="fw-bold"><?= $u['username'] ?></div>
                            <div class="badge rounded-pill text-white small" style="background:#f36c21; font-size:0.65rem;"><?= strtoupper($u['role']) ?></div>
                        </td>
                        <td><?= $u['nama_lengkap'] ?></td>
                        <td>
                            <?php if(!empty($u['email'])): ?>
                                <span class="text-muted small"><i class="fas fa-envelope me-1"></i><?= $u['email'] ?></span>
                            <?php else: ?>
                                <span class="text-danger small fst-italic">Belum diatur</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?= $u['status_akun'] ?></span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light text-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal"
                                    data-id="<?= $u['id'] ?>"
                                    data-nama="<?= htmlspecialchars($u['nama_lengkap']) ?>"
                                    data-email="<?= htmlspecialchars($u['email'] ?? '') ?>"
                                    data-role="<?= $u['role'] ?>"
                                    data-status="<?= $u['status_akun'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?= base_url('users/delete/' . $u['id']) ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Hapus user ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:1.5rem; overflow:hidden;">
            <div class="p-4 text-white" style="background: linear-gradient(135deg, #f36c21, #ea580c);">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-plus me-2"></i> Tambah Pengguna Baru</h5>
            </div>
            <form action="<?= base_url('users/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Username (NIM/NIP)</label>
                        <input type="text" name="username" class="form-control rounded-pill bg-light border-0" placeholder="200410501xxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control rounded-pill bg-light border-0" placeholder="Nama Mahasiswa/Dosen" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Email Aktif</label>
                        <input type="email" name="email" class="form-control rounded-pill bg-light border-0" placeholder="nama@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control rounded-pill bg-light border-0" placeholder="••••••••" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Role</label>
                        <select name="role" class="form-select rounded-pill bg-light border-0" required>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="penjaga_lab">Penjaga Lab</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="submit" class="btn rounded-pill w-100 fw-bold text-white" style="background:#f36c21; border:none;">Daftarkan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:1.5rem; overflow:hidden;">
            <div class="p-4 text-white" style="background: linear-gradient(135deg, #1e293b, #334155);">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-edit me-2"></i> Edit Data Pengguna</h5>
            </div>
            <form id="editUserForm" action="" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit_nama" class="form-control rounded-pill bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Email Aktif</label>
                        <input type="email" name="email" id="edit_email" class="form-control rounded-pill bg-light border-0" placeholder="nama@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Role</label>
                        <select name="role" id="edit_role" class="form-select rounded-pill bg-light border-0" required>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="penjaga_lab">Penjaga Lab</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Status Akun</label>
                        <select name="status_akun" id="edit_status" class="form-select rounded-pill bg-light border-0">
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="form-control rounded-pill bg-light border-0" placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 gap-2">
                    <button type="submit" class="btn rounded-pill w-100 fw-bold text-white" style="background:#1e293b; border:none;">Simpan Perubahan</button>
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-bold border" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Populate Edit Modal fields from data attributes
document.getElementById('editUserModal').addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const id      = btn.getAttribute('data-id');
    const nama    = btn.getAttribute('data-nama');
    const email   = btn.getAttribute('data-email');
    const role    = btn.getAttribute('data-role');
    const status  = btn.getAttribute('data-status');

    document.getElementById('edit_nama').value   = nama;
    document.getElementById('edit_email').value  = email;
    document.getElementById('edit_role').value   = role;
    document.getElementById('edit_status').value = status;

    // Set form action dynamically
    document.getElementById('editUserForm').action = '<?= base_url('users/update/') ?>' + id;
});
</script>

<?= $this->endSection() ?>
