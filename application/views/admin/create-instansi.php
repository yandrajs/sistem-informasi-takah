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
                        <?php echo validation_errors(); ?>
                        <?php echo form_open('admin/storeinstansi'); ?>
                        <div class="form-group">
                            <label for="instansi">Nama Instansi:</label>
                            <input type="text" class="form-control" name="instansi" required><br>
                        </div>
                        <div class="row justify-content-end">
                            <a href="<?php echo site_url('admin/manageinstansi/'); ?>" class="btn btn-secondary">Batal</a>
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