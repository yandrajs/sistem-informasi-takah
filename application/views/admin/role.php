    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Role</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/search_role'); ?>" method="post">
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Cari role" value="<?= $keywordRole; ?>" name="keywordRole" autocomplete="off" autofocus>
                                <div class="input-group-append">
                                    <button class="btn btn-primary shadow-sm" type="submit" name="submit">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 text-gray-800 font-weight-bold">Data Role</h2>
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahRole">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Role
            </button>
        </div>

        <?= form_error('role', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
        <?= $this->session->flashdata('message'); ?>

        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Role</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th scope="col">#</th>
                                <th scope="col">Role</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($role)) : ?>
                                <tr>
                                    <td colspan="15">
                                        <div class="alert alert-info" role="alert">
                                            Data tidak ditemukan
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($role as $r) : ?>
                                <tr align="center">
                                    <th scope="row"><?= ++$start; ?></th>
                                    <td><?= $r['role']; ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/roleaccess/') . $r['role_id']; ?>" class="btn btn-sm btn-outline-warning mr-2">
                                            <i class="fas fa-key mr-1"></i> Akses
                                        </a>
                                        <button class="btn btn-sm btn-outline-success mr-2" data-toggle="modal" data-target="#modalEditRole<?= $r['role_id']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalHapusRole<?= $r['role_id']; ?>">
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


        <!-- Modal Tambah Role -->
        <div class="modal fade" id="modalTambahRole" tabindex="-1" aria-labelledby="modalTambahRole" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahRoleLabel">Tambah Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('admin/add_role'); ?>" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="role">Tambah Role</label>
                                <input type="text" class="form-control" id="role" name="role" placeholder="Role">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Role-->
        <?php foreach ($role as $r) : ?>
            <div class="modal fade" id="modalEditRole<?= $r['role_id']; ?>" tabindex="-1" aria-labelledby="modalEditRole" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditRoleLabel<?= $r['role_id']; ?>">Edit Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?= base_url('admin/editrole'); ?>" method="post">
                            <div class="modal-body">
                                <input type="hidden" name="role_id" value="<?= $r['role_id']; ?>">
                                <div class="form-group">
                                    <label for="role">Edit Role</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder="Role" value="<?= $r['role']; ?>">
                                    <?= form_error('role', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Modal Hapus Role -->
        <?php foreach ($role as $r) : ?>
            <div class="modal fade" id="modalHapusRole<?= $r['role_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusRoleLabel<?= $r['role_id']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalHapusRoleLabel<?= $r['role_id']; ?>">Hapus <?= $r['role']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus role <?= $r['role']; ?>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <a href="<?php echo site_url('admin/deleterole/' . $r['role_id']); ?>" class="btn btn-danger">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->