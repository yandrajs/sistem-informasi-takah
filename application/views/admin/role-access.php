    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $subtitle; ?></h1>

        <div class="row">
            <div class="col-lg-8">

                <?= $this->session->flashdata('message'); ?>

                <h5>Role: <?= $role['role']; ?></h5>

                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Role Akses</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr align="center">
                                        <th scope="col">#</th>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Akses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($menu as $m) : ?>
                                        <tr align="center">
                                            <th scope="row"><?= $i; ?></th>
                                            <td><?= $m['menu']; ?></td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input role" type="checkbox" <?= check_access($role['role_id'], $m['menu_id']); ?> data-role="<?= $role['role_id']; ?>" data-menu="<?= $m['menu_id']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?= $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->