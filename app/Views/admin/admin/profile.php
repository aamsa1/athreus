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
                    <h4 class="page-title">Profile</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">E-Presensi</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
            <!-- end row -->
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title font-16 mt-0"></h4>
                        <img src="<?= base_url('assets/app-assets/user/') . '/' . $admin->gambar; ?>" alt="E-Presensi Abduloh" class="img-thumbnail">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title font-16 mt-0">Update My Profile</h4>
                        <form action="<?= base_url('admin/profile_'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="nama_admin" value="<?= $admin->nama_admin; ?>" required>
                                <input type="hidden" class="form-control" name="id_admin" value="<?= $admin->id_admin; ?>">
                            </div>
                            <div class="form-group">
                                <label for="">Foto</label><br>
                                <input type="file" name="gambar" id="gambar-profile">
                                <input type="hidden" name="gambar_lama" value="<?= $admin->gambar; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container-fluid -->
</div>
<!-- end wrapper -->

<?= $this->endSection(); ?>