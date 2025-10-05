<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
        <a href="<?php echo site_url('admin/createinstansi'); ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>Tambah Instansi Baru</a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?= $this->session->flashdata('message'); ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Tabel Instansi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Instansi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($instansi as $in) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $in['instansi']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-success mr-2" data-toggle="modal" data-target="#modalEditInstansi<?= $in['instansi_id']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalDeleteInstansi<?= $in['instansi_id']; ?>">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Instansi -->
    <?php foreach ($instansi as $in) : ?>
        <div class="modal fade" id="modalDeleteInstansi<?= $in['instansi_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDeleteInstansiLabel<?= $in['instansi_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeleteInstansiLabel<?= $in['instansi_id']; ?>">Hapus Katalog</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p> Apakah anda yakin ingin menghapus instansi <?= $in['instansi']; ?>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="<?php echo site_url('admin/deleteinstansi/' . $in['instansi_id']); ?>" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal Edit Instansi-->
    <?php foreach ($instansi as $in) : ?>
        <div class="modal fade" id="modalEditInstansi<?= $in['instansi_id']; ?>" tabindex="-1" aria-labelledby="modalEditInstansi" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditInstansiLabel<?= $in['instansi_id']; ?>">Edit Instansi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('admin/editinstansi'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="instansi_id" value="<?= $in['instansi_id']; ?>">
                            <div class="form-group">
                                <label for="role">Edit Instansi</label>
                                <input type="text" class="form-control" id="instansi" name="instansi" placeholder="Instansi" value="<?= $in['instansi']; ?>">
                                <?= form_error('role', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <label for="role">Edit Tabel Instansi</label>
                                <input type="text" class="form-control" id="nama_tabel" name="nama_tabel" placeholder="Nama Tabel" value="<?= $in['nama_tabel']; ?>">
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

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->