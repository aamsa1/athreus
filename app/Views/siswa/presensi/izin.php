<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('layout/navbar/student'); ?>

<div class="wrapper">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">Permohonan Izin</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">E-Presensi</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('students/presensi'); ?>">Presensi</a></li>
                        <li class="breadcrumb-item active">Permohonan Izin</li>
                    </ol>
                </div>
            </div>
            <!-- end row -->
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class=" card m-b-30">
                    <div class="card-body">
                        <h4 class="card-title font-16 mt-0 mb-3">Permohonan Izin <?= $event->nama_event; ?></h4>
                        <?php if ($list_izin != null) : ?>
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="th">KETERANGAN</th>
                                            <th class="th">FILE</th>
                                            <th class="th">STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td align="center"><?= $list_izin->keterangan; ?></td>
                                            <td align="center">
                                                <a href="<?= base_url('file/suket/') . '/' . $list_izin->suket; ?>" class="btn btn-success">Unduh</a>
                                            </td>
                                            <td align="center">
                                                <?php if ($list_izin->izinkan == 0) : ?>
                                                    <a href="javascript:void(0);" class="badge badge-warning">Pending</a>
                                                <?php else : ?>
                                                    <a href="javascript:void(0);" class="badge badge-success">Di izinkan</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <?php if ($list_presensi) : ?>
                                <?php if ($list_presensi->absen_masuk == 0 && $list_presensi->absen_keluar == 0 && $list_presensi->izinkan === null) : ?>
                                    <?php if ($berakhir == 'masih') : ?>
                                        <form action="<?= base_url('students/kirim_izin'); ?>" method="POST" class="form" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="">Keterangan</label>
                                                <input type="text" name="keterangan" class="form-control" placeholder="eg: sakit demam" required>
                                                <input type="hidden" name="no_event" class="form-control" value="<?= $event->no_event; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Bukti</label><br>
                                                <input type="file" name="suket" required accept="image/*,.pdf,.doc,.docx">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                                        </form>
                                    <?php else : ?>
                                        <p>Anda tidak dapat mengirimkan permohonan izin Dikarenakan Waktu Absen Sudah Selesai</p>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <p>Anda tidak dapat mengirimkan permohonan izin, Anda sudah melakukan presensi</p>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php if ($berakhir == 'masih') : ?>
                                    <form action="<?= base_url('students/kirim_izin'); ?>" method="POST" class="form" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="">Keterangan</label>
                                            <input type="text" name="keterangan" class="form-control" placeholder="eg: sakit demam" required>
                                            <input type="hidden" name="no_event" class="form-control" value="<?= $event->no_event; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bukti</label><br>
                                            <input type="file" name="suket" required accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                                    </form>
                                <?php else : ?>
                                    <p>Anda tidak dapat mengirimkan permohonan izin Dikarenakan Waktu Absen Sudah Selesai</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class=" card m-b-30">
                    <div class="card-body">
                        <h4 class="card-title font-16 mt-0 mb-3">Warning!</h4>
                        <p>Contoh Bukti<br>surat keterangan sakit dari dokter berupa foto ataupun pdf</p>

                        <p>Diharuskan memasukan bukti dengan format gambar ataupun dokumen</p>
                        <span class="blockquote-footer">I do your job</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container-fluid -->
</div>
<!-- end wrapper -->

<?= $this->endSection(); ?>