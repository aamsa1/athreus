<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('layout/navbar/admin'); ?>
<?= session()->getFlashdata('pesan'); ?>

<div class="wrapper">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">Presensi Keluar <?= $event->nama_event; ?></h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">E-Presensi</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Presensi</a></li>
                        <li class="breadcrumb-item active"><?= $event->nama_event; ?></li>
                    </ol>
                </div>
            </div>
            <!-- end row -->
        </div>
        <div class="row">
            <?php if ($berakhir == "masih") : ?>
                <div class="col-lg-8 offset-lg-2 text-center">
                    <h1>SCAN HERE!</h1>
                    <div class="mt-4">
                        <center>
                            <div id="qrEvent"></div>
                        </center>
                    </div>
                    <a href="<?= base_url('file/exportqr/') . '/' . encrypt_url($event->no_event); ?>" target="_blank" class="btn btn-primary mt-2 ml-auto">Export QR</a>
                </div>
            <?php else : ?>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <div class="alert alert-success mb-0" role="alert">
                                    <h4 class="alert-heading mt-0 font-18">Well done!</h4>
                                    <p>Presensi Telah Berakhir</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Presensi Pulang</h4>
                        <div class="friends-suggestions">
                            <div class="row" id="sudah-absen_keluar">
                                <button class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Belum Presensi Pulang</h4>
                        <div class="friends-suggestions">
                            <div class="row" id="belum-absen_keluar">
                                <button class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container-fluid -->
</div>
<!-- end wrapper -->

<script>
    <?php if ($berakhir == "masih") : ?>
        new QRCode(document.getElementById("qrEvent"), "<?= $event->qr_event ?>");
    <?php endif; ?>
    setInterval(() => {
        const content = "<?= decrypt_url($event->qr_event); ?>"
        $.ajax({
            type: 'POST',
            data: {
                content: content
            },
            url: "<?= base_url('admin/sudah_absen_keluar') ?>",
            async: true,
            success: function(data) {
                $('#sudah-absen_keluar').html(data);
            }
        })
    }, 5000);


    setInterval(() => {
        const content = "<?= decrypt_url($event->qr_event); ?>"
        $.ajax({
            type: 'POST',
            data: {
                content: content
            },
            url: "<?= base_url('admin/belum_absen_keluar') ?>",
            async: true,
            success: function(data) {
                $('#belum-absen_keluar').html(data);
            }
        })
    }, 5000);
</script>

<?= $this->endSection(); ?>