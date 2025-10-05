<div class="container-fluid">

    <h1 class="h4 text-gray-900 mb-4"><?= $subtitle; ?></h1>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle; ?></h6>
                </div>
                <div class="card-body">
                    <?= $this->session->flashdata('message'); ?>
                    <form class="user" method="post" action="<?= base_url('admin/createaccount'); ?>">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control stylish-input" id="nama" name="nama" placeholder="Masukkan Nama" value="<?= set_value('nama'); ?>">
                            <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control stylish-input" id="username" name="username" placeholder="Masukkan Username" value="<?= set_value('username'); ?>">
                            <?= form_error('username', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label for="password1" class="form-label">Password</label>
                                <input type="password" class="form-control stylish-input" id="password1" name="password1" placeholder="Masukkan Password">
                                <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="col-sm-6">
                                <label for="password2" class="form-label">Ulangi Password</label>
                                <input type="password" class="form-control stylish-input" id="password2" name="password2" placeholder="Ulangi Password">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select name="role_id" id="role_id" class="form-control stylish-select">
                                <option value="">Pilih Role</option>
                                <?php foreach ($role as $r) : ?>
                                    <option value="<?= $r['role_id']; ?>"><?= $r['role']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row justify-content-end">
                            <a href="<?php echo site_url('admin/manageaccount/'); ?>" class="btn btn-secondary">Kembali</a>
                            <div class="mx-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Buat Akun
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>