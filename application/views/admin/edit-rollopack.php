    <!-- Begin Page Content -->
    <div class="container-fluid">


        <div class="row">
            <!-- Form Input Data -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle; ?></h6>
                    </div>
                    <div class="card-body">
                        <?= $this->session->flashdata('message'); ?>

                        <?php echo form_open('admin/updaterollopack/' . $rollopack['rollopack_id']); ?>
                        <div class="form-group">
                            <label for="ruangan_id">Nama Ruangan:</label>
                            <select name="ruangan_id" id="ruangan_id" class="form-control">
                                <?php foreach ($ruangan as $n) : ?>
                                    <option value="<?= $n['ruangan_id']; ?>" <?= ($n['ruangan_id'] == $rollopack['ruangan_id']) ? 'selected' : ''; ?>>
                                        <?= $n['nama_ruangan']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('ruangan_id', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="nomor_rollopack">Nomor Rollopack:</label>
                            <input type="number" class="form-control" name="nomor_rollopack" value="<?= $rollopack['nomor_rollopack']; ?>">
                            <?= form_error('nomor_rollopack', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_lemari_per_rollopack">Jumlah Lemari:</label>
                            <input type="number" class="form-control" name="jumlah_lemari_per_rollopack" value="<?= $rollopack['jumlah_lemari_per_rollopack']; ?>">
                            <?= form_error('jumlah_lemari_per_rollopack', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_rak_per_lemari">Jumlah Rak:</label>
                            <input type="number" class="form-control" name="jumlah_rak_per_lemari" id="jumlah_rak_per_lemari" value="<?= $rollopack['jumlah_rak_per_lemari']; ?>">
                            <?= form_error('jumlah_rak_per_lemari', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="kapasitas_per_rak">Kapastitas Rak:</label>
                            <input type="number" class="form-control" name="kapasitas_per_rak" id="kapasitas_per_rak" value="<?= $rollopack['kapasitas_per_rak']; ?>">
                            <?= form_error('kapasitas_per_rak', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="instansi">Instansi:</label>
                            <input type="text" class="form-control" name="instansi" value="<?= $rollopack['instansi']; ?>">
                            <?= form_error('instansi', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="no_panggil_awal">No Panggil Awal:</label>
                            <input type="number" class="form-control" name="no_panggil_awal" value="<?= $rollopack['no_panggil_awal']; ?>">
                            <?= form_error('no_panggil_awal', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="no_panggil_akhir">No Panggil Akhir:</label>
                            <input type="number" class="form-control" name="no_panggil_akhir" value="<?= $rollopack['no_panggil_akhir']; ?>">
                            <?= form_error('kano_panggil_akhirasitas_per_rak', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="row justify-content-end">
                            <a href="<?php echo base_url('admin/rollopack/'); ?>" class="btn btn-secondary">Batal</a>
                            <div class="mx-3">
                                <input type="submit" value="Edit" class="btn btn-primary">
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->