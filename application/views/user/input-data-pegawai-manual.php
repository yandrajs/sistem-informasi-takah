<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800"><?= $subtitle; ?></h1>
        <div class="justify-content-md-end">
            <a href="<?= base_url(); ?>user/addDataPegawai" class="btn btn-md btn-primary mr-2 ">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Form Input Data -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Input Data Pegawai</h6>
                </div>
                <div class="card-body">
                    <?= $this->session->flashdata('message'); ?>
                    <form action="<?= base_url('user/inputManual'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="ruangan_id">Pilih Ruangan:</label>
                            <select name="ruangan_id" id="ruangan_id" class="form-control">
                                <option value="">Pilih Ruangan</option>
                                <?php if (isset($ruangan) && is_array($ruangan)): ?>
                                    <?php foreach ($ruangan as $n) : ?>
                                        <option value="<?= $n['ruangan_id']; ?>"><?= $n['nama_ruangan']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="no_panggil" class="form-label">No Panggil</label>
                                <input type="number" class="form-control stylish-input" id="no_panggil" name="no_panggil" placeholder="no panggil">
                                <?= form_error('no_panggil', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="col">
                                <label for="rollopack" class="form-label">Rollopack</label>
                                <input type="number" class="form-control stylish-input" id="rollopack" name="rollopack" placeholder="rollopack">
                                <?= form_error('rollopack', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="col">
                                <label for="lemari" class="form-label">Lemari</label>
                                <input type="number" class="form-control stylish-input" id="lemari" name="lemari" placeholder="lemari">
                                <?= form_error('lemari', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="col">
                                <label for="rak" class="form-label">Rak</label>
                                <input type="number" class="form-control stylish-input" id="rak" name="rak" placeholder="rak">
                                <?= form_error('rak', '<small class="text-danger pl-3">', '</small>'); ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="number" class="form-control" id="nip" name="nip" placeholder="NIP" value="<?= set_value('nip'); ?>">
                            <?= form_error('nip', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="<?= set_value('nama'); ?>">
                            <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="instansi">Pilih Instansi:</label>
                            <select name="instansi" id="instansi" class="form-control">
                                <option value="">Pilih Instansi</option>
                                <?php if (isset($rollopack) && is_array($rollopack)): ?>
                                    <?php foreach ($rollopack as $rp) : ?>
                                        <option value="<?= $rp['instansi']; ?>"><?= $rp['instansi']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Dokumen Checklist:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="d2nip" name="d2nip" value="1" <?= set_checkbox('d2nip', '1'); ?>>
                                        <label class="form-check-label" for="d2nip">D2NIP</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ijazah" name="ijazah" value="1" <?= set_checkbox('ijazah', '1'); ?>>
                                        <label class="form-check-label" for="ijazah">Ijazah</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="drh" name="drh" value="1" <?= set_checkbox('drh', '1'); ?>>
                                        <label class="form-check-label" for="drh">DRH</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="skcpns" name="skcpns" value="1" <?= set_checkbox('skcpns', '1'); ?>>
                                        <label class="form-check-label" for="skcpns">SKCPNS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="skpns" name="skpns" value="1" <?= set_checkbox('skpns', '1'); ?>>
                                        <label class="form-check-label" for="skpns">SKPNS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sk_perubahan_data" name="sk_perubahan_data" value="1" <?= set_checkbox('sk_perubahan_data', '1'); ?>>
                                        <label class="form-check-label" for="sk_perubahan_data">SK Perubahan Data</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sk_jabatan" name="sk_jabatan" value="1" <?= set_checkbox('sk_jabatan', '1'); ?>>
                                        <label class="form-check-label" for="sk_jabatan">SK Jabatan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sk_pemberhentian" name="sk_pemberhentian" value="1" <?= set_checkbox('sk_pemberhentian', '1'); ?>>
                                        <label class="form-check-label" for="sk_pemberhentian">SK Pemberhentian</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sk_pensiun" name="sk_pensiun" value="1" <?= set_checkbox('sk_pensiun', '1'); ?>>
                                        <label class="form-check-label" for="sk_pensiun">SK Pensiun</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="mx-1">
                                <a href="<?php echo site_url('user/inputmanual'); ?>" class="btn btn-success">Bersihkan</a>
                            </div>
                            <div class="mx-1">
                                <a href="<?php echo site_url('user/adddatapegawai'); ?>" class="btn btn-secondary">Batal</a>
                            </div>
                            <div class="mx-1">
                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-left-success">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Data Baru Diinput</h6>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('new_data')) : ?>
                        <?php $new_data = $this->session->flashdata('new_data'); ?>
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th>NIP</th>
                                <td><?= $new_data['NIP']; ?></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td><?= $new_data['Nama']; ?></td>
                            </tr>
                            <tr>
                                <th>Kode Instansi</th>
                                <td><?= $new_data['Instansi']; ?></td>
                            </tr>
                            <tr>
                                <th>No Panggil</th>
                                <td><?= $new_data['No_Panggil']; ?></td>
                            </tr>
                            <tr>
                                <th>Dokumen</th>
                                <td>
                                    <?= $new_data['D2NIP'] ? 'D2NIP, ' : ''; ?>
                                    <?= $new_data['Ijazah'] ? 'Ijazah, ' : ''; ?>
                                    <?= $new_data['DRH'] ? 'DRH, ' : ''; ?>
                                    <?= $new_data['SKCPNS'] ? 'SKCPNS, ' : ''; ?>
                                    <?= $new_data['SKPNS'] ? 'SKPNS, ' : ''; ?>
                                    <?= $new_data['SK_Perubahan_Data'] ? 'SK Perubahan Data, ' : ''; ?>
                                    <?= $new_data['SK_Jabatan'] ? 'SK Jabatan, ' : ''; ?>
                                    <?= $new_data['SK_Pemberhentian'] ? 'SK Pemberhentian, ' : ''; ?>
                                    <?= $new_data['SK_Pensiun'] ? 'SK Pensiun' : ''; ?>
                                </td>
                            </tr>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-info" role="alert">
                            Belum ada data baru yang diinput.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->