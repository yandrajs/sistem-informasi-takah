<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?= $this->session->flashdata('message'); ?>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-door-open mr-2"></i>Ruangan</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($ruangan)) : ?>
                        <tr>
                            <td colspan="15">
                                <div class="alert alert-info" role="alert">
                                    Data tidak tersedia
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($ruangan as $r) : ?>
                            <li class="list-group-item"><?= $r['nama_ruangan']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Rollopack</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('user/ruanganRollopack'); ?>" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari rollopack" value="<?= $keywordRR; ?>" name="keywordRR" autocomplete="off" autofocus>
                            <div class="input-group-append">
                                <input class="btn btn-primary" type="submit" name="submit" value="Cari">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?= $this->pagination->create_links(); ?>
        </div>
    </div>
</div>
</div>
<!-- /.container-fluid -->