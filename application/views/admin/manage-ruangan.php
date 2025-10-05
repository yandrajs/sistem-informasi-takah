    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Ruangan</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/ruangan'); ?>" method="post">
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Cari ruangan" value="<?= $keywordRuangan; ?>" name="keywordRuangan" autocomplete="off" autofocus>
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
            <h2 class="h4 text-gray-800 font-weight-bold">Data Ruangan</h2>
            <a href="<?php echo site_url('admin/createruangan'); ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Ruangan Baru</a>
        </div>

        <?php if ($this->session->flashdata('message')): ?>
            <?= $this->session->flashdata('message'); ?>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Ruangan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>#</th>
                                <th>Nama Ruangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ruangan)) : ?>
                                <tr>
                                    <td colspan="15">
                                        <div class="alert alert-info" role="alert">
                                            Data tidak ditemukan
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($ruangan as $r) : ?>
                                <tr align="center">
                                    <td><?= ++$start; ?></td>
                                    <td><?= $r['nama_ruangan']; ?></td>
                                    <td>
                                        <a href="<?php echo site_url('admin/editruangan/' . $r['ruangan_id']); ?>" class="btn btn-sm btn-outline-success mr-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalHapusRuangan<?= $r['ruangan_id']; ?>">
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

        <?php foreach ($ruangan as $r) : ?>
            <!-- Modal Hapus Ruangan -->
            <div class="modal fade" id="modalHapusRuangan<?= $r['ruangan_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusRuanganLabel<?= $r['ruangan_id']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalHapusRuanganLabel<?= $r['ruangan_id']; ?>">Hapus <?= $r['nama_ruangan']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus ruangan "<?= $r['nama_ruangan']; ?>"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <a href="<?php echo site_url('admin/deleteruangan/' . $r['ruangan_id']); ?>" class="btn btn-danger">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>


    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->