<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800"><?= $subtitle; ?></h1>
        <div class="justify-content-md-end">
            <button class="btn btn-md btn-warning mr-2" data-toggle="modal" data-target="#modalKosongkanKatalog<?= $takah['takah_id']; ?>">
                <i class="fas fa-edit"></i>Kosongkan
            </button>
            <a href="<?= base_url(); ?>user/editDataPegawai/<?= $takah['takah_id']; ?>" class="btn btn-md btn-success mr-2">
                <i class="fas fa-edit"></i>Edit
            </a>
            <a href="<?= base_url(); ?>user/katalog" class="btn btn-md btn-primary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Form Input Data -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Data Pegawai <?= $takah['Nama']; ?></h6>
                </div>
                <div class="card-body">
                    <div class="row m-1">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <?php if (!empty($takah['NIP'])) : ?>
                                    <label>NIP</label>
                                    <input type="text" class="form-control" name="nip" value="<?= $takah['NIP']; ?>" readonly>
                                <?php else: ?>
                                    <label>NIP</label>
                                    <input type="text" class="form-control" name="nip" value="-" readonly>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <?php if (!empty($takah['Nama'])) : ?>
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="nama" value="<?= $takah['Nama']; ?>" readonly>
                                <?php else : ?>
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="nama" value="-" readonly>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label>Instansi</label>
                                <input type="text" class="form-control" name="instansi" value="<?= $takah['Instansi']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <?php if (!empty($takah['NIP'])) : ?>
                                    <label>Usia</label>
                                    <input type="text" class="form-control" name="usia" value="<?= $takah['usia']; ?>" readonly>
                                <?php else : ?>
                                    <label>Usia</label>
                                    <input type="text" class="form-control" name="usia" value="-" readonly>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <?php if (!empty($takah['NIP'])) : ?>
                                    <label>Status</label>
                                    <input type="text" class="form-control" name="status" value="<?= $takah['status_pensiun']; ?>" readonly>
                                <?php else : ?>
                                    <label>Status</label>
                                    <input type="text" class="form-control" name="status" value="-" readonly>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <?php if (!empty($takah['NIP'])) : ?>
                                    <label>Tanggal BUP</label>
                                    <input type="text" class="form-control" name="tanggal_bup" value="<?= $takah['tanggal_bup']; ?>" readonly>
                                <?php else : ?>
                                    <label>Tanggal BUP</label>
                                    <input type="text" class="form-control" name="tanggal_bup" value="-" readonly>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>No Panggil</label>
                                <input type="number" class="form-control" name="no_panggil" value="<?= $takah['No_Panggil']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nama Ruangan</label>
                                <input type="text" class="form-control" name="nama_ruangan" value="<?= $takah['nama_ruangan']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>No Lemari</label>
                                <input type="number" class="form-control" name="no_lemari" value="<?= $takah['no_lemari']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>No Rollopack</label>
                                <input type="number" class="form-control" name="no_rollopack" value="<?= $takah['no_rollopack']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>No Rak</label>
                                <input type="number" class="form-control" name="no_rak" value="<?= $takah['no_rak']; ?>" readonly>
                            </div>
                            <div class="mt-5 d-md-flex justify-content-md-end">
                                <?php if ($takah['status_pensiun'] === 'BUP/Pensiun') : ?>
                                    <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#modalNonaktifKatalog<?= $takah['takah_id']; ?>">
                                        <i class="fa-fw fas fa-regular fa-eye-slash"></i> Nonaktif
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-md btn-secondary " data-toggle="modal" data-target="#modalNonaktifKatalog<?= $takah['takah_id']; ?>" disabled>
                                        <i class="fa-fw fas fa-regular fa-eye-slash"></i> Nonaktif
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dokumen Checklist</h6>
                </div>
                <div class="card-body">
                    <div class="row m-1">
                        <div class="col">
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['D2NIP'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">D2NIP</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['Ijazah'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">Ijazah</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['DRH'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">DRH</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['SKCPNS'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SKCPNS</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['SKPNS'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SKPNS</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['SK_Perubahan_Data'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Perubahan Data</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['SK_Jabatan'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Jabatan</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['SK_Pemberhentian'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Pemberhentian</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah['SK_Pensiun'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Pensiun</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nonaktif Katalog -->
    <div class="modal fade" id="modalNonaktifKatalog<?= $takah['takah_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalNonaktifKatalogLabel<?= $takah['takah_id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNonaktifKatalogLabel<?= $takah['takah_id']; ?>">Nonaktif Katalog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> Apakah anda yakin ingin menonaktifkan katalog dengan:</p>
                    <h5 style="font-weight:bold;">Status: <?= $takah['status_pensiun']; ?></h5>
                    <strong>NIP: <?= $takah['NIP']; ?></strong><br>
                    <strong>Nama: <?= $takah['Nama']; ?></strong><br>
                    <strong>Kode instansi: <?= $takah['Instansi']; ?></strong><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="<?php echo site_url('user/nonaktifKatalog/' . $takah['takah_id']); ?>" class="btn btn-danger">Nonaktif</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Kosongkan Katalog -->
    <div class="modal fade" id="modalKosongkanKatalog<?= $takah['takah_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalKosongkanKatalogLabel<?= $takah['takah_id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKosongkanKatalogLabel<?= $takah['takah_id']; ?>">Kosongkan Katalog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> Apakah anda yakin ingin mengosongkan dengan katalog:</p>
                    <h5 style="font-weight:bold;">Status: <?= $takah['status_pensiun']; ?></h5>
                    <strong>NIP: <?= $takah['NIP']; ?></strong><br>
                    <strong>Nama: <?= $takah['Nama']; ?></strong><br>
                    <strong>Kode instansi: <?= $takah['Instansi']; ?></strong><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="<?= base_url(); ?>user/kosongkanKatalog/<?= $takah['takah_id']; ?>" class="btn btn-danger">
                        Kosongkan
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->