<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari katalog</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('user/katalogBUP'); ?>" method="post">
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Cari katalog" value="<?= $keywordKatalogBUP; ?>" name="keywordKatalogBUP" autocomplete="off" autofocus>
                            <div class="input-group-append">
                                <input class="btn btn-primary" type="submit" name="submit" value="Cari">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filter</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('user/katalogBUP'); ?>" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter_instansi">Instansi:</label>
                                    <select class="form-control" id="filter_instansi" name="filter_instansi">
                                        <option value="">Pilih Instansi</option>
                                        <?php foreach ($instansi as $i) : ?>
                                            <option value="<?= $i['instansi']; ?>" <?= $filter_instansi == $i['instansi'] ? 'selected' : ''; ?>><?= $i['instansi']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter_ruangan">Ruangan:</label>
                                    <select class="form-control" id="filter_ruangan" name="filter_ruangan">
                                        <option value="">Semua Ruangan</option>
                                        <?php foreach ($ruangan as $r) : ?>
                                            <option value="<?= $r; ?>" <?= $filter_ruangan == $r ? 'selected' : ''; ?>><?= $r; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort_by">Urutkan Berdasarkan:</label>
                                    <select class="form-control" id="sort_by" name="sort_by">
                                        <option value="Kode_instansi" <?= $sort_by == 'Kode_instansi' ? 'selected' : ''; ?>>Kode Instansi</option>
                                        <option value="nama_ruangan" <?= $sort_by == 'nama_ruangan' ? 'selected' : ''; ?>>Ruangan</option>
                                        <option value="No_Panggil" <?= $sort_by == 'No_Panggil' ? 'selected' : ''; ?>>No Panggil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sort_order">Arah Urutan:</label>
                                    <select class="form-control" id="sort_order" name="sort_order">
                                        <option value="ASC" <?= $sort_order == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                                        <option value="DESC" <?= $sort_order == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="mx-3">
                                <button type="submit" class="btn btn-primary btn-block">Terapkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?= $this->session->flashdata('message'); ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Katalog</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto">
                <h5>Jumlah : <?= $total_rows; ?></h5>
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr align="center">
                            <th scope="col">#</th>
                            <th scope="col">No Panggil</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Instansi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($takah)) : ?>
                            <tr>
                                <td colspan="15">
                                    <div class="alert alert-info" role="alert">
                                        Data tidak ditemukan
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($takah as $t) : ?>
                            <tr align="center">
                                <th scope="row"><?= ++$start; ?></th>
                                <td><?= $t['No_Panggil']; ?></td>
                                <td><?= $t['NIP']; ?></td>
                                <td><?= $t['Nama']; ?></td>
                                <td><?= $t['Kode_instansi']; ?></td>
                                <td>
                                    <a href="<?= base_url(); ?>user/detailDataPegawaiBUP/<?= $t['takah_bup_id']; ?>" class="btn btn-sm btn-outline-info mr-2 ">
                                        <i class="fas fa-edit"></i>Detail
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalDeleteKatalogBUP<?= $t['takah_bup_id']; ?>">
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

    <?php foreach ($takah as $t) : ?>
        <!-- Modal Hapus Katalog -->
        <div class="modal fade" id="modalDeleteKatalogBUP<?= $t['takah_bup_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDeleteKatalogBUPLabel<?= $t['takah_bup_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeleteKatalogBUPLabel<?= $t['takah_bup_id']; ?>">Hapus Katalog</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p> Apakah anda yakin ingin menghapus katalog dengan:</p>
                        <strong>NIP: <?= $t['NIP']; ?></strong><br>
                        <strong>Nama: <?= $t['Nama']; ?></strong><br>
                        <strong>Kode instansi: <?= $t['Kode_instansi']; ?></strong><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="<?php echo site_url('user/deleteDataKatalogBUP/' . $t['takah_bup_id']); ?>" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->