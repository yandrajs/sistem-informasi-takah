<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800"><?= $subtitle; ?></h1>
        <div class="justify-content-md-end">
            <a href="<?= base_url(); ?>user/editDataPegawaiBUP/<?= $takah_bup['takah_bup_id']; ?>" class="btn btn-md btn-success mr-2 ">
                <i class="fas fa-edit"></i>Edit
            </a>
            <a href="<?= base_url(); ?>user/katalogBUP" class="btn btn-md btn-primary mr-2 ">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

    </div>
    <div class="row">
        <!-- Form Input Data -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Data Pegawai BUP <?= $takah_bup['Nama']; ?></h6>
                </div>
                <div class="card-body">
                    <div class="row m-1">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" class="form-control" name="nip" value="<?= $takah_bup['NIP']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" value="<?= $takah_bup['Nama']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Instansi</label>
                                <input type="text" class="form-control" name="instansi" value="<?= $takah_bup['Kode_instansi']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Usia</label>
                                <input type="text" class="form-control" name="usia" value="<?= $takah_bup['usia']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" class="form-control" name="status" value="<?= $takah_bup['status_pensiun']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Tanggal BUP</label>
                                <input type="text" class="form-control" name="tanggal_bup" value="<?= $takah_bup['tanggal_bup']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>No Panggil</label>
                                <input type="number" class="form-control" name="no_panggil" value="<?= $takah_bup['No_Panggil']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nama Ruangan</label>
                                <input type="text" class="form-control" name="nama_ruangan" value="<?= $takah_bup['nama_ruangan']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>No Lemari</label>
                                <input type="number" class="form-control" name="no_lemari" value="<?= $takah_bup['no_lemari']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>No Rollopack</label>
                                <input type="number" class="form-control" name="no_rollopack" value="<?= $takah_bup['no_rollopack']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>No Rak</label>
                                <input type="number" class="form-control" name="no_rak" value="<?= $takah_bup['no_rak']; ?>" readonly>
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
                                <div class="custom-checkbox <?= $takah_bup['D2NIP'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">D2NIP</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['Ijazah'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">Ijazah</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['DRH'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">DRH</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['SKCPNS'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SKCPNS</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['SKPNS'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SKPNS</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['SK_Perubahan_Data'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Perubahan Data</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['SK_Jabatan'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Jabatan</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['SK_Pemberhentian'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Pemberhentian</label>
                            </div>
                            <div class="form-group">
                                <div class="custom-checkbox <?= $takah_bup['SK_Pensiun'] ? 'checked' : ''; ?>"></div>
                                <label class="form-check-label">SK Pensiun</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Kosongkan Katalog -->
    <div class="modal fade" id="modalKosongkanKatalog<?= $takah_bup['takah_bup_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalKosongkanKatalogLabel<?= $takah_bup['takah_bup_id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKosongkanKatalogLabel<?= $takah_bup['takah_bup_id']; ?>">Kosongkan Katalog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> Apakah anda yakin ingin mengosongkan dengan katalog:</p>
                    <h5 style="font-weight:bold;">Status: <?= $takah_bup['Status']; ?></h5>
                    <strong>NIP: <?= $takah_bup['NIP']; ?></strong><br>
                    <strong>Nama: <?= $takah_bup['Nama']; ?></strong><br>
                    <strong>Kode instansi: <?= $takah_bup['Kode_instansi']; ?></strong><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="<?= base_url(); ?>user/kosongkanKatalog/<?= $takah_bup['takah_bup_id']; ?>" class="btn btn-danger">
                        Kosongkan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- /.container-fluid -->
</div>

</div>
<!-- End of Main Content -->