    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Cari Menu</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('menu/searchMenu'); ?>" method="post">
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Cari menu" value="<?= $keywordMenu; ?>" name="keywordMenu" autocomplete="off" autofocus>
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
            <h2 class="h4 text-gray-800 font-weight-bold">Data Menu</h2>
            <a href="" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahMenu">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Menu Baru
            </a>
        </div>

        <?= $this->session->flashdata('message'); ?>

        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Data Menu</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th scope="col">#</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($menu)) : ?>
                                <tr>
                                    <td colspan="15">
                                        <div class="alert alert-info" role="alert">
                                            Data tidak ditemukan
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($menu as $m) : ?>
                                <tr align="center">
                                    <th scope="row"><?= ++$start; ?></th>
                                    <td><?= $m['menu']; ?></td>
                                    <td>
                                        <a href="<?= base_url(); ?>menu/editmenu/<?= $m['menu_id']; ?>" class="btn btn-sm btn-outline-success mr-2" data-toggle="modal" data-target="#modalEditMenu<?= $m['menu_id']; ?>">
                                            <i class="fas fa-edit"></i>Edit</a>
                                        <button class="btn btn-sm btn-outline-danger mr-2" data-toggle="modal" data-target="#modalDeleteMenu<?= $m['menu_id']; ?>">
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
    </div>

    <!-- Modal Tambah Menu-->
    <div class="modal fade" id="modalTambahMenu" tabindex="-1" aria-labelledby="modalTambahMenu" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMenuLabel">Tambah Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('menu/addMenu'); ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="menu">Tambah Menu</label>
                            <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu">
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
    <!-- Modal Akhir Tambah Menu-->


    <!-- Modal Edit Menu-->
    <?php foreach ($menu as $m) : ?>
        <div class="modal fade" id="modalEditMenu<?= $m['menu_id']; ?>" tabindex="-1" aria-labelledby="modalEditMenu" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditMenuLabel<?= $m['menu_id']; ?>">Edit Menu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('menu/editmenu'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $m['menu_id']; ?>">
                            <div class="form-group">
                                <label for="menu">Edit Menu</label>
                                <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu" value="<?= $m['menu']; ?>">
                                <?= form_error('menu', '<small class="text-danger pl-3">', '</small>'); ?>
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

    <?php foreach ($menu as $m) : ?>
        <!-- Modal Hapus Menu -->
        <div class="modal fade" id="modalDeleteMenu<?= $m['menu_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDeleteMenuLabel<?= $m['menu_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeleteMenuLabel<?= $m['menu_id']; ?>">Hapus Menu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus <?= $m['menu']; ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="<?php echo site_url('menu/deletemenu/' . $m['menu_id']); ?>" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>