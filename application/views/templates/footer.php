            <!-- Footer
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer> -->
            <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

            </div>
            <!-- End of Page Wrapper -->

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Apakah kamu yakin ingin logout?</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                            <a class="btn btn-primary" href="<?= base_url('auth/logout'); ?>">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap core JavaScript-->
            <script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
            <script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>

            <!-- Untuk mengelola role di fifle role-acces-->
            <script>
                $('.role').on('click', function() {
                    const menu_id = $(this).data('menu');
                    const role_id = $(this).data('role');

                    $.ajax({
                        url: "<?= base_url('admin/changeaccess'); ?>",
                        type: 'post',
                        data: {
                            menu_id: menu_id,
                            role_id: role_id
                        },

                        success: function() {
                            document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + role_id;
                        }
                    });
                });


                // Untuk zoom gambar
                $(function() {
                    $('.pop').on('click', function() {
                        $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                        $('#imagemodal').modal('show');
                    });
                });
                $('.custom-file-input').on('change', function() {
                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                });


                // Untuk menghitung kapasitas rollopack
                $(document).ready(function() {
                    $('#kapasitas_per_rak, #jumlah_rak_per_lemari, #jumlah_lemari_per_rollopack').on('input', function() {
                        var kapasitasRak = $('#kapasitas_per_rak').val();
                        var jumlahRak = $('#jumlah_rak_per_lemari').val();
                        var jumlahLemari = $('#jumlah_lemari_per_rollopack').val();
                        var kapasitasRollopack = kapasitasRak * jumlahRak * jumlahLemari; // rumus menghitung kapasitas rollopack
                        $('#kapasitas-rollopack').text('Kapasitas Rollopack: ' + kapasitasRollopack);
                    });
                });
            </script>


            </body>

            </html>