<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $subtitle; ?></h1>

    <div class="row lg">
        <div class="col-lg-7">
            <div class="card o-hidden border-0 shadow-sm">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col">
                            <div class="p-4">
                                <?= $this->session->flashdata('message'); ?>
                                <form action="<?= base_url('user/editDataPegawaiBUP/' . $takah_bup['takah_bup_id']); ?>" method="post">
                                    <input type="hidden" name="takah_bup_id" value="<?= $takah_bup['takah_bup_id']; ?>">
                                    <div class="form-group">
                                        <label for="ruangan_id">Pilih Ruangan:</label>
                                        <select name="ruangan_id" id="ruangan_id" class="form-control">
                                            <?php foreach ($ruangan as $r) : ?>
                                                <option value="<?= $r['ruangan_id']; ?>" <?= ($r['ruangan_id'] == $takah_bup['ruangan_id']) ? 'selected' : ''; ?>>
                                                    <?= $r['nama_ruangan']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">
                                            <label for="no_panggil" class="form-label">No Panggil</label>
                                            <input type="number" class="form-control stylish-input" id="no_panggil" name="no_panggil" value="<?= $takah_bup['No_Panggil']; ?>">
                                            <?= form_error('no_panggil', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                        <div class="col">
                                            <label for="rollopack" class="form-label">Rollopack</label>
                                            <input type="number" class="form-control stylish-input" id="rollopack" name="rollopack" value="<?= $takah_bup['no_rollopack']; ?>">
                                            <?= form_error('rollopack', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                        <div class="col">
                                            <label for="lemari" class="form-label">Lemari</label>
                                            <input type="number" class="form-control stylish-input" id="lemari" name="lemari" value="<?= $takah_bup['no_lemari']; ?>">
                                            <?= form_error('lemari', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                        <div class="col">
                                            <label for="rak" class="form-label">Rak</label>
                                            <input type="number" class="form-control stylish-input" id="rak" name="rak" value="<?= $takah_bup['no_rak']; ?>">
                                            <?= form_error('rak', '<small class="text-danger pl-3">', '</small>'); ?>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nip">NIP</label>
                                        <input type="number" class="form-control" id="nip" name="nip" placeholder="NIP" value="<?= $takah_bup['NIP']; ?>">
                                        <?= form_error('nip', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class=" form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="<?= $takah_bup['Nama']; ?>">
                                        <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="instansi">Pilih Instansi:</label>
                                        <select name="instansi" id="instansi" class="form-control">
                                            <?php foreach ($rollopack as $rp) : ?>
                                                <option value="<?= $rp['instansi']; ?>" <?= ($rp['instansi'] == $takah_bup['Kode_instansi']) ? 'selected' : ''; ?>>
                                                    <?= $rp['instansi']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Dokumen Checklist:</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="d2nip" name="d2nip" value="1" <?= $takah_bup['D2NIP'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="d2nip">D2NIP</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="ijazah" name="ijazah" value="1" <?= $takah_bup['Ijazah'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="ijazah">Ijazah</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="drh" name="drh" value="1" <?= $takah_bup['DRH'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="drh">DRH</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="skcpns" name="skcpns" value="1" <?= $takah_bup['SKCPNS'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="skcpns">SKCPNS</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="skpns" name="skpns" value="1" <?= $takah_bup['SKPNS'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="skpns">SKPNS</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="sk_perubahan_data" name="sk_perubahan_data" value="1" <?= $takah_bup['SK_Perubahan_Data'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="sk_perubahan_data">SK Perubahan Data</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="sk_jabatan" name="sk_jabatan" value="1" <?= $takah_bup['SK_Jabatan'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="sk_jabatan">SK Jabatan</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="sk_pemberhentian" name="sk_pemberhentian" value="1" <?= $takah_bup['SK_Pemberhentian'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="sk_pemberhentian">SK Pemberhentian</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="sk_pensiun" name="sk_pensiun" value="1" <?= $takah_bup['SK_Pensiun'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="sk_pensiun">SK Pensiun</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="mx-3">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->