<!-- Di dalam view (misalnya manage-account.php) -->
<div class="container-fluid">
    <!-- Judul halaman -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Akun</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/manageaccount'); ?>" method="post">
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Cari akun" value="<?= $keywordAccount; ?>" name="keywordAccount" autocomplete="off" autofocus>
                            <div class="input-group-append">
                                <input class="btn btn-primary" type="submit" name="submit" value="Cari">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-gray-800 font-weight-bold">Data Akun</h2>
        <div>
            <button class="btn btn-danger my-2" data-toggle="modal" data-target="#modalDeleteMyAccount">
                <i class="fas fa-trash"></i> Hapus Akun Saya
            </button>
            <a href="<?= base_url('admin/createAccount'); ?>" class="btn btn-primary">
                <i class=" fas fa-plus-circle"></i> Buat Akun
            </a>
        </div>
    </div>

    <?= $this->session->flashdata('message'); ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Akun</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr align="center">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($akun)) : ?>
                            <tr>
                                <td colspan="15">
                                    <div class="alert alert-info" role="alert">
                                        Data tidak ditemukan
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($akun as $i => $a) : ?>
                            <tr align="center">
                                <td><?= ++$start; ?></td>
                                <td><?= $a['nama']; ?></td>
                                <td><?= $a['username']; ?></td>
                                <td><?= $a['role_name']; ?></td>
                                <td>
                                    <!-- Tombol untuk membuka modal edit -->
                                    <a href="#" class="btn btn-sm btn-outline-success mr-2" data-toggle="modal" data-target="#editModal<?= $a['user_id']; ?>">
                                        <i class="fas fa-edit"></i>Edit</a>
                                    <!-- Tombol untuk menghapus user -->
                                    <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalDeleteAccount<?= $a['user_id']; ?>">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?= $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>

    <!-- Modal Edit untuk setiap user -->
    <?php foreach ($akun as $a) : ?>
        <div class="modal fade" id="editModal<?= $a['user_id']; ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Akun</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('admin/editaccount'); ?>" method="post">
                        <div class="modal-body">
                            <!-- ID user yang akan diedit -->
                            <input type="hidden" name="user_id" value="<?= $a['user_id']; ?>">

                            <!-- Form untuk mengedit nama -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" value="<?= $a['nama']; ?>" required>
                            </div>

                            <!-- Form untuk mengedit username -->
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" value="<?= $a['username']; ?>" required>
                            </div>

                            <!-- Form untuk mengganti password (opsional) -->
                            <div class="form-group">
                                <label>Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <!-- Dropdown untuk memilih role -->
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role_id" class="form-control" required>
                                    <?php foreach ($role as $r) : ?>
                                        <option value="<?= $r['role_id']; ?>" <?= $r['role_id'] == $a['role_id'] ? 'selected' : ''; ?>><?= $r['role']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php foreach ($akun as $a) : ?>
        <!-- Modal Hapus Account -->
        <div class="modal fade" id="modalDeleteAccount<?= $a['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDeleteAccountLabel<?= $a['user_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeleteAccountLabel<?= $a['user_id']; ?>">Hapus Akun</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus akun dengan data<br>
                        nama: <?= $a['nama']; ?><br>
                        username "<?= $a['username']; ?>"
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="<?php echo base_url('admin/deleteAccount/' . $a['user_id']); ?>" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


    <!-- Modal Hapus MyAccount -->
    <div class="modal fade" id="modalDeleteMyAccount" tabindex="-1" role="dialog" aria-labelledby="modalDeleteMyAccountLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteMyAccount">Hapus Akun Saya</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin menghapus akun anda?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-danger" href="<?= base_url('admin/deleteMyAccount/' . $user['user_id']); ?>">Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>