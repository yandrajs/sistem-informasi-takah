    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $subtitle; ?></h1>
        <div class="row">
            <!-- Form Input Data -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-body">
                        <form method="post" action="<?= base_url('admin/addusiabup'); ?>">
                            <div class="form-group">
                                <label for="usia_bup">Usia BUP Pegawai</label>
                                <input type="number" class="form-control" id="usia_bup" name="usia_bup">
                                <?= form_error('usia_bup', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="row justify-content-end">
                                <div class="mx-3">
                                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->