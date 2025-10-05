    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="row row-cols-1 row-cols-md-2">
            <?php foreach ($denah as $d) : ?>
                <div class="col mb-4">
                    <div class="card">
                        <a href="<?= base_url('assets/img/denah/' . $d['denah_id']); ?>" class="pop" data-toggle="modal"
                            data-target="#modalShowDenah<?= $d['denah_id']; ?>">
                            <img src="<?= base_url('assets/img/denah/' . $d['gambar']); ?>" style="width: 100%; height: 264px;" class="img-responsive">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?= $d['nama']; ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($denah)) : ?>
                <tr>
                    <td colspan="15">
                        <div class="alert alert-info" role="alert">
                            Denah kosong!
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </div>

        <?php foreach ($denah as $d) : ?>
            <div class="modal fade" id="modalShowDenah<?= $d['denah_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalShowDenahLabel<?= $d['denah_id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalShowDenahLabel<?= $d['denah_id']; ?>"><?= $d['nama']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="<?= base_url('assets/img/denah/' . $d['gambar']); ?>" class="img-fluid rounded" style="width: 100%; height: auto;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->