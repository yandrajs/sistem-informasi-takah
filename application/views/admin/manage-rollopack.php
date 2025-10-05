    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Rollopack</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/rollopack'); ?>" method="post">
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Cari rollopack" value="<?= $keywordRollopack; ?>" name="keywordRollopack" autocomplete="off" autofocus>
                                <div class="input-group-append shadow-sm">
                                    <input class="btn btn-primary" type="submit" name="submit" value="Cari">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 text-gray-800 font-weight-bold">Data Rollopack</h2>
            <a href="<?php echo site_url('admin/createrollopack'); ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Rollopack Baru
            </a>
        </div>

        <?= $this->session->flashdata('message'); ?>

        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Rollopack</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>#</th>
                                <th>Nama Ruangan</th>
                                <th>Nomor Rollopack</th>
                                <th>Instansi</th>
                                <th>Jumlah lemari</th>
                                <th>Jumlah rak</th>
                                <th>Kapasitas rak</th>
                                <th>No Panggil Awal</th>
                                <th>No Panggil Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rollopack)) : ?>
                                <tr>
                                    <td colspan="15">
                                        <div class="alert alert-info" role="alert">
                                            Data tidak ditemukan
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($rollopack as $r): ?>
                                <tr align="center">
                                    <td><?= ++$start ?></td>
                                    <td><?= $r['nama_ruangan']; ?></td>
                                    <td><?= $r['nomor_rollopack']; ?></td>
                                    <td><?= $r['instansi']; ?></td>
                                    <td><?= $r['jumlah_lemari_per_rollopack']; ?></td>
                                    <td><?= $r['jumlah_rak_per_lemari']; ?></td>
                                    <td><?= $r['kapasitas_per_rak']; ?></td>
                                    <td><?= $r['no_panggil_awal']; ?></td>
                                    <td><?= $r['no_panggil_akhir']; ?></td>
                                    <td>
                                        <a href="<?php echo site_url('admin/editrollopack/' . $r['rollopack_id']); ?>" class="btn btn-sm btn-outline-success mr-2 mb-2">
                                            <i class="fas fa-edit"></i>Edit</a>
                                        <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalHapusRollopack<?= $r['rollopack_id']; ?>">
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

        <?php foreach ($rollopack as $r) : ?>
            <!-- Modal Hapus Rollopack -->
            <div class="modal fade" id="modalHapusRollopack<?= $r['rollopack_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusRollopackLabel<?= $r['rollopack_id']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalHapusRollopackLabel<?= $r['rollopack_id']; ?>">Hapus <?= $r['nomor_rollopack']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus rollopack <?= $r['nomor_rollopack']; ?> di <?= $r['nama_ruangan']; ?> dengan kode instansi <?= $r['instansi']; ?>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <a href="<?php echo site_url('admin/deleterollopack/' . $r['rollopack_id']); ?>" class="btn btn-danger">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->