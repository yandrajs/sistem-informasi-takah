<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <!-- Form Input Data -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cari Tata Letak</h6>
                </div>
                <div class="card-body">
                    <?= $this->session->flashdata('info'); ?>
                    <form action="<?= base_url('user/index'); ?>" method="post">
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="number" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" value="<?= isset($selected_nip) ? $selected_nip : set_value('nip'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="search" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" value="<?= isset($selected_nama) ? $selected_nama : set_value('nama'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="instansi">Pilih Instansi:</label>
                            <select name="instansi" id="instansi" class="form-control">
                                <option value="">Pilih Instansi</option>
                                <?php foreach ($instansi as $i) : ?>
                                    <option value="<?= $i['nama_tabel']; ?>" <?= (isset($selected_instansi) && $selected_instansi == $i['nama_tabel']) ? 'selected' : ''; ?>><?= $i['instansi']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row justify-content-end">
                            <a href="<?php echo site_url('user/'); ?>" class="btn btn-secondary">Batal</a>
                            <div class="mx-2">
                                <button type="submit" name="cari" value="1" class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($search_performed && $show_result): ?>
            <div class="col-lg-6">
                <div class="card shadow-sm border-left-success">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Hasil Pencarian</h6>
                    </div>
                    <div class="card-body">
                        <?php if (isset($result) && !empty($result)) : ?>
                            <?php foreach ($result as $r) : ?>
                                <div class="result-item mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>NIP:</strong> <?= $r['NIP']; ?></p>
                                            <p><strong>Nama:</strong> <?= $r['Nama']; ?></p>
                                            <p><strong>Instansi:</strong> <?= $r['Instansi']; ?></p>
                                            <p><strong>Status:</strong> <?= $r['status_pensiun']; ?></p>
                                            <p><strong>Dokumen:</strong>
                                                <?= $r['D2NIP'] ? 'D2NIP, ' : ''; ?>
                                                <?= $r['Ijazah'] ? 'Ijazah, ' : ''; ?>
                                                <?= $r['DRH'] ? 'DRH, ' : ''; ?>
                                                <?= $r['SKCPNS'] ? 'SKCPNS, ' : ''; ?>
                                                <?= $r['SKPNS'] ? 'SKPNS, ' : ''; ?>
                                                <?= $r['SK_Perubahan_Data'] ? 'SK Perubahan Data, ' : ''; ?>
                                                <?= $r['SK_Jabatan'] ? 'SK Jabatan, ' : ''; ?>
                                                <?= $r['SK_Pemberhentian'] ? 'SK Pemberhentian, ' : ''; ?>
                                                <?= $r['SK_Pensiun'] ? 'SK Pensiun' : ''; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Ruang:</strong> <?= $r['nama_ruangan']; ?></p>
                                            <p><strong>No. Rollopack:</strong> <?= $r['no_rollopack']; ?></p>
                                            <p><strong>No. Lemari:</strong> <?= $r['no_lemari']; ?></p>
                                            <p><strong>No. Rak:</strong> <?= $r['no_rak']; ?></p>
                                            <p><strong>No. Urut/Panggil:</strong> <?= $r['No_Panggil']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Tidak ada hasil ditemukan.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($search_performed && $show_table): ?>
        <div class="card shadow-sm border-left-success mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Hasil Pencarian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Instansi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($table_data as $t) : ?>
                                <tr align="center">
                                    <td><?= $i++; ?></td>
                                    <td><?= $t['NIP']; ?></td>
                                    <td><?= $t['Nama']; ?></td>
                                    <td><?= $t['Instansi']; ?></td>
                                    <td>
                                        <button onclick="fillForm('<?= $t['NIP']; ?>', '<?= $t['Nama']; ?>', '<?= $t['Instansi']; ?>')" class="btn btn-primary btn-sm btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-search"></i>
                                            </span>
                                            <span class="text">Cari</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>
    function fillForm(nip, nama, instansi) {
        document.getElementById('nip').value = nip;
        document.getElementById('nama').value = nama;
        document.getElementById('instansi').value = instansi;
        document.querySelector('form').submit();
    }
</script>