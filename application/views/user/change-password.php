    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="row lg">
            <div class="col-lg-7">
                <div class="card o-hidden border-0 shadow-sm">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col">
                                <div class="p-4">

                                    <?= $this->session->flashdata('message'); ?>

                                    <form action="<?= base_url('user/changepassword'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="password_lama">Masukkan Password Lama</label>
                                            <input type="password" class="form-control" id="password_lama" name="password_lama"
                                                placeholder="Password lama">
                                            <?= form_error('password_lama', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_baru1">Masukkan Password Baru</label>
                                            <input type="password" class="form-control" id="password_baru1" name="password_baru1"
                                                placeholder="Password baru">
                                            <?= form_error('password_baru1', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_baru2">Ulangi Password Baru</label>
                                            <input type="password" class="form-control" id="password_baru2" name="password_baru2"
                                                placeholder="Ulangi password">
                                            <?= form_error('password_baru2', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Ganti Password</button>
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