<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?= $this->session->flashdata('success'); ?>
    <?= $this->session->flashdata('message'); ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Import Data</h6>
                </div>
                <div class="card-body">
                    <?php echo form_open_multipart('user/do_import'); ?>
                    <div class="form-group">
                        <label for="ruangan_id">Pilih Ruangan:</label>
                        <select name="ruangan_id" id="ruangan_id" class="form-control selectpicker" data-live-search="true">
                            <option value="">Pilih Ruangan</option>
                            <?php foreach ($ruangan as $n) : ?>
                                <option value="<?= $n['ruangan_id']; ?>"><?= $n['nama_ruangan']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="import">Import File</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excel_file" name="excel_file" required>
                            <label class="custom-file-label" for="excel_file">
                                <i class="fas fa-upload mr-2"></i> Pilih file
                            </label>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-icon-split" id="import" name="import">
                            <span class="icon text-white-50">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="text">Import</span>
                        </button>
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