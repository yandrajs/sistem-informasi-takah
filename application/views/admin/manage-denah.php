<!-- Begin Page Content -->
<div class="container-fluid py-4">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 font-weight-bold"><?= $title; ?></h1>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Denah</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/searchDenah'); ?>" method="post">
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Cari denah" value="<?= $keywordDenah; ?>" name="keywordDenah" autocomplete="off" autofocus>
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
        <h2 class="h4 text-gray-800 font-weight-bold">Data Denah</h2>
        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahDenah">
            <i class="fas fa-plus-circle mr-2"></i>Tambah Denah
        </button>
    </div>

    <?= form_error('nama', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
    <?= $this->session->flashdata('message'); ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Denah</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr align="center">
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($denah)) : ?>
                            <tr>
                                <td colspan="15">
                                    <div class="alert alert-info" role="alert">
                                        Data tidak ditemukan
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($denah as $d) : ?>
                            <tr align="center">
                                <th scope="row"><?= ++$start; ?></th>
                                <td><?= $d['nama']; ?></td>
                                <td><img src="<?= base_url('assets/img/denah/' . $d['gambar']); ?>" alt="<?= $d['nama']; ?>" class="img-thumbnail" width="100"></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success mr-2" data-toggle="modal" data-target="#editDenahModal<?= $d['denah_id']; ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalHapusDenah<?= $d['denah_id']; ?>">
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

    <div class="d-flex justify-content-center">
        <?= $this->pagination->create_links(); ?>
    </div>

    <!-- Modal Tambah Denah -->
    <div class="modal fade" id="modalTambahDenah" tabindex="-1" aria-labelledby="modalTambahDenah" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDenahLabel">Tambah Denah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('admin/addDenah'); ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Denah</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Ruang">
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar Denah</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar">
                                <label class="custom-file-label" for="gambar">Pilih file</label>
                            </div>
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

    <!-- Edit Denah Modal -->
    <?php foreach ($denah as $d) : ?>
        <div class="modal fade" id="editDenahModal<?= $d['denah_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editDenahModalLabel<?= $d['denah_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDenahModalLabel<?= $d['denah_id']; ?>">Edit Denah</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('admin/editDenah/' . $d['denah_id']); ?>" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama">Nama Denah</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?= $d['nama']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="gambar">Gambar Denah</label>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <img src="<?= base_url('assets/img/denah/' . $d['gambar']); ?>" class="img-thumbnail">
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="gambar" name="gambar">
                                            <label class="custom-file-label" for="gambar">Pilih File</label>
                                            <input type="hidden" name="old_image" value="<?= $d['gambar']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php foreach ($denah as $d) : ?>
        <!-- Modal Hapus Denah -->
        <div class="modal fade" id="modalHapusDenah<?= $d['denah_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusDenahLabel<?= $d['denah_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusDenahLabel<?= $d['denah_id']; ?>">Hapus Denah <?= $d['nama']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus denah "<?= $d['nama']; ?>"?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="<?php echo site_url('admin/deletedenah/' . $d['denah_id']); ?>" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>
</div>
<!-- /.container-fluid -->