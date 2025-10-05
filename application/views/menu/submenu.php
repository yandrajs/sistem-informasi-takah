    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Submenu</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('menu/searchSubmenu'); ?>" method="post">
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Cari Submenu" value="<?= $keywordSubmenu; ?>" name="keywordSubmenu" autocomplete="off" autofocus>
                                <div class="input-group-append">
                                    <button class="btn btn-primary shadow-sm" type="submit" name="submit">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 text-gray-800 font-weight-bold">Data Submenu</h2>
            <a href="" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahSubmenu">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Submenu Baru</a>
        </div>

        <!-- Menampilkan pesan error jika field ada yang tidak diisi -->
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger" role="alert">
                <?= validation_errors(); ?>
            </div>
        <?php endif; ?>
        <!-- Menampilkan pesan error jika submenu gagal ditambahkan -->
        <?= $this->session->flashdata('message'); ?>


        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Submenu</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th scope="col">#</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Menu</th>
                                <th scope="col">URL</th>
                                <th scope="col">Icon</th>
                                <th scope="col">Aktif</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($subMenu)) : ?>
                                <tr>
                                    <td colspan="15">
                                        <div class="alert alert-info" role="alert">
                                            Data tidak ditemukan
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($subMenu as $sm) : ?>
                                <tr align="center">
                                    <th scope="row"><?= ++$start; ?></th>
                                    <td><?= $sm['title']; ?></td>
                                    <td><?= $sm['menu']; ?></td>
                                    <td><?= $sm['url']; ?></td>
                                    <td><?= $sm['icon']; ?></td>
                                    <td><?= $sm['is_active']; ?></td>
                                    <td>
                                        <a href="<?= base_url(); ?>menu/editsubmenu/<?= $sm['sub_menu_id']; ?>" class="btn btn-sm btn-outline-success mr-2" data-toggle="modal" data-target="#modalEditSubmenu<?= $sm['sub_menu_id']; ?>">
                                            <i class="fas fa-edit"></i>Edit</a>
                                        <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalDeleteSubmenu<?= $sm['sub_menu_id']; ?>">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->

    </div>
    <!-- End of Main Content -->


    <!-- Modal Tanbah Submenu-->
    <div class="modal fade" id="modalTambahSubmenu" tabindex="-1" aria-labelledby="modalTambahSubmenu" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahSubmenuLabel">Tambah Submenu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('menu/addSubmenu'); ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Tambah Judul</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Submenu">
                        </div>
                        <div class="form-group">
                            <label for="title">Tambah Judul</label>
                            <select name="menu_id" id="menu_id" class="form-control">
                                <option value="">Pilih Menu</option>
                                <?php foreach ($menu as $m) : ?>
                                    <option value="<?= $m['menu_id']; ?>"><?= $m['menu']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="Submenu URL">
                        </div>
                        <div class="form-group">
                            <label for="icon">Icon</label>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu Icon">
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Aktif?
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Submenu-->
    <?php foreach ($subMenu as $sm) : ?>
        <div class="modal fade" id="modalEditSubmenu<?= $sm['sub_menu_id']; ?>" tabindex="-1" aria-labelledby="modalEditSubmenu" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSubmenuLabel<?= $sm['sub_menu_id']; ?>">Edit Submenu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('menu/editSubmenu'); ?>" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="sub_menu_id" value="<?= $sm['sub_menu_id']; ?>">
                                <label for="title">Edit Judul</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Submenu" value="<?= $sm['title']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="title">Edit Menu</label>
                                <select name="menu_id" id="menu_id" class="form-control">
                                    <?php foreach ($menu as $m) : ?>
                                        <option value="<?= $m['menu_id']; ?>" <?= ($m['menu_id'] == $sm['menu_id']) ? 'selected' : ''; ?>>
                                            <?= $m['menu']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="url">Edit URL</label>
                                <input type="text" class="form-control" id="url" name="url" placeholder="Submenu URL" value="<?= $sm['url']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="icon">Edit Icon</label>
                                <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu Icon" value="<?= $sm['icon']; ?>">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" <?= $sm['is_active'] == 1 ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">
                                        Aktif?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal Hapus Submenu -->
    <?php foreach ($subMenu as $sm) : ?>
        <div class="modal fade" id="modalDeleteSubmenu<?= $sm['sub_menu_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDeleteSubmenuLabel<?= $sm['sub_menu_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeleteSubmenuLabel<?= $sm['sub_menu_id']; ?>">Hapus Submenu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus <?= $sm['title']; ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="<?php echo site_url('menu/deletesubmenu/' . $sm['sub_menu_id']); ?>" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>