<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


    <div class="row">
        <!-- Form Input Data -->
        <div class="col-lg-6">
            <?= $this->session->flashdata('message'); ?>
            <div class="card shadow-sm border-left-primary">

                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/aturUsiaPensiun'); ?>">
                        <div class="form-group">
                            <label for="usia_bup">Usia BUP Pegawai</label>
                            <input type="number" class="form-control" id="usia_bup" name="usia_bup" value="<?= $usia_bup; ?>">
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