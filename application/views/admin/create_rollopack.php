    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->

        <div class="row">
            <!-- Form Input Data -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle; ?></h6>
                    </div>
                    <div class="card-body">
                        <?= $this->session->flashdata('message'); ?>

                        <!-- memanggil jquery hasil menggitung kapasitas rollopack -->
                        <p id="kapasitas-rollopack">Kapasitas Rollopack: </p>

                        <?php echo form_open('admin/storerollopack'); ?>
                        <div class="form-group">
                            <label for="ruangan_id">Nama Ruangan:</label>
                            <select name="ruangan_id" id="ruangan_id" class="form-control">
                                <option value="">Pilih Ruangan</option>
                                <?php foreach ($ruangan as $n) : ?>
                                    <option value="<?= $n['ruangan_id']; ?>"><?= $n['nama_ruangan']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('ruangan_id', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="nomor_rollopack">Nomor Rollopack:</label>
                            <input type="number" class="form-control" name="nomor_rollopack" id="nomor_rollopack" value="<?= set_value('nomor_rollopack'); ?>" required>
                            <?= form_error('nomor_rollopack', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_lemari_per_rollopack">Jumlah Lemari:</label>
                            <input type="number" class="form-control" name="jumlah_lemari_per_rollopack" id="jumlah_lemari_per_rollopack" value="<?= set_value('jumlah_lemari_per_rollopack'); ?>" required>
                            <?= form_error('jumlah_lemari_per_rollopack', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class=" form-group">
                            <label for="jumlah_rak_per_lemari">Jumlah Rak:</label>
                            <input type="number" class="form-control" name="jumlah_rak_per_lemari" id="jumlah_rak_per_lemari" value="<?= set_value('jumlah_rak_per_lemari'); ?>" required>
                            <?= form_error('jumlah_rak_per_lemari', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="kapasitas_per_rak">Kapastitas Rak:</label>
                            <input type="number" class="form-control" name="kapasitas_per_rak" id="kapasitas_per_rak" value="<?= set_value('kapasitas_per_rak'); ?>" required>
                            <?= form_error('kapasitas_per_rak', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="instansi">Instansi:</label>
                            <select name="instansi" id="instansi" class="form-control">
                                <option value="">Pilih Instansi</option>
                                <?php foreach ($instansi as $in) : ?>
                                    <option value="<?= $in['instansi']; ?>"><?= $in['instansi']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('instansi', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="no_panggil_awal">No Panggil Awal:</label>
                            <input type="number" class="form-control" name="no_panggil_awal" id="no_panggil_awal" value="<?= set_value('no_panggil_awal'); ?>" required>
                            <?= form_error('no_panggil_awal', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="no_panggil_akhir">No Panggil Akhir:</label>
                            <input type="number" class="form-control" name="no_panggil_akhir" id="no_panggil_akhir" value="<?= set_value('no_panggil_akhir'); ?>" required>
                            <?= form_error('no_panggil_akhir', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="row justify-content-end">
                            <a href="<?php echo site_url('admin/rollopack/'); ?>" class="btn btn-secondary">Batal</a>
                            <div class="mx-3">
                                <input type="submit" value="Simpan" class="btn btn-primary">
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