<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\PresensiModel;
use App\Models\AbsenModel;

class Admin extends BaseController
{

    protected $AdminModel;
    protected $SiswaModel;
    protected $KelasModel;
    protected $PresensiModel;
    protected $AbsenModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->AdminModel = new AdminModel();
        $this->SiswaModel = new SiswaModel();
        $this->KelasModel = new KelasModel();
        $this->PresensiModel = new PresensiModel();
        $this->AbsenModel = new AbsenModel();
        date_default_timezone_set('Asia/Jakarta');
    }


    public function index()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['total_students'] = count($this->SiswaModel->asObject()->findAll());
        $data['total_classes'] = count($this->KelasModel->asObject()->findAll());
        $data['total_events'] = count($this->PresensiModel->asObject()->findAll());
        $data['total_admin'] = count($this->AdminModel->asObject()->findAll());

        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/dashboard', $data);
    }

    // START::PROFILE
    public function profile()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/admin/profile', $data);
    }
    public function profile_()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $fileGambar = $this->request->getFile('gambar');
        // Cek Gambar, Apakah Tetap Gambar lama
        if ($fileGambar->getError() == 4) {
            $nama_gambar = $this->request->getVar('gambar_lama');
        } else {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('assets/app-assets/user', $nama_gambar);
            // hapus File Yang Lama
            if ($this->request->getVar('gambar_lama') != 'default.jpg') {
                unlink('assets/app-assets/user/' . $this->request->getVar('gambar_lama'));
            }
        }

        $this->AdminModel->save([
            'id_admin' => $this->request->getVar('id_admin'),
            'nama_admin' => $this->request->getVar('nama_admin'),
            'gambar' => $nama_gambar
        ]);

        session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Di Update!',
                        })
                </script>
                ");
        return redirect()->to('admin/profile');
    }
    // END::PROFILE

    // START::ADMIN
    public function admlist()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['admin_list'] = $this->AdminModel->asObject()->findAll();
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/admin/index', $data);
    }
    public function addadmin()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/admin/tambah', $data);
    }
    public function addadmin_()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        // Ambil data yang dikirim dari form
        $nama_admin = $this->request->getVar('nama_admin'); // Ambil data nama_admin dan masukkan ke variabel nama_admin
        $data_admin = array();

        $index = 0; // Set index array awal dengan 0
        foreach ($nama_admin as $nama) { // Kita buat perulangan berdasarkan nama_admin sampai data terakhir
            array_push($data_admin, array(
                'nama_admin' => $nama,
                'email' => $this->request->getVar('email')[$index],
                'password' => password_hash($this->request->getVar('password')[$index], PASSWORD_DEFAULT),
                'role' => 1,
                'date_created' => time(),
                'is_active' => 1,
                'gambar' => "default.jpg"
            ));

            $index++;
        }

        // var_dump($data_admin);
        // die;

        $sql = $this->AdminModel->insertBatch($data_admin);

        // Cek apakah query insert nya sukses atau gagal
        if ($sql) { // Jika sukses
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Disimpan!',
                        })
                </script>
                ");
            return redirect()->to('admin/admlist');
        } else { // Jika gagal
            session()->setFlashdata('pesan', "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal Disimpan!',
                                })
                        </script>
                        ");
            return redirect()->to('admin/admlist');
        }
    }
    public function ajaxupdateadmin()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $email = decrypt_url($this->request->getVar('email'));
            $admin = $this->AdminModel->getByEmail($email);
            echo json_encode($admin);
        }
    }
    public function delete_admin($data)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $email = decrypt_url($data);
        $admin = $this->AdminModel->getByEmail($email);

        if ($admin == null) {
            session()->setFlashdata('pesan', "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal Dihapus!',
                                })
                        </script>
                        ");
            return redirect()->to('admin/admlist');
        } else {
            $this->AdminModel->delete($admin->id_admin);
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Di hapus',
                        })
                </script>
                ");
            return redirect()->to('admin/admlist');
        }
    }
    // END::ADMIN

    // START::KELAS
    public function classes()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['kelas_list'] = $this->KelasModel->asObject()->findAll();
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/kelas/index', $data);
    }
    public function addclass()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));
        return view('admin/kelas/tambah', $data);
    }
    public function addclass_()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        // Ambil data yang dikirim dari form
        $kode_kelas = $this->request->getVar('kode_kelas');
        $data_kelas = array();

        $index = 0; // Set index array awal dengan 0
        foreach ($kode_kelas as $kelas) {
            array_push($data_kelas, array(
                'kode_kelas' => $this->request->getVar('kode_kelas')[$index],
                'nama_kelas' => $this->request->getVar('nama_kelas')[$index]
            ));

            $index++;
        }

        $sql = $this->KelasModel->insertBatch($data_kelas);

        // Cek apakah query insert nya sukses atau gagal
        if ($sql) { // Jika sukses
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Disimpan!',
                        })
                </script>
                ");
            return redirect()->to('admin/classes');
        } else { // Jika gagal
            session()->setFlashdata('pesan', "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal Disimpan!',
                                })
                        </script>
                        ");
            return redirect()->to('admin/classes');
        }
    }
    public function ajaxupdatekelas()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $kelas = decrypt_url($this->request->getVar('kelas'));
            $data_kelas = $this->KelasModel->getByKode($kelas);
            echo json_encode($data_kelas);
        }
    }
    public function updateclass()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $this->KelasModel
            ->where('kode_kelas', $this->request->getVar('kode_kelas'))
            ->set('nama_kelas', $this->request->getVar('nama_kelas'))
            ->update();

        session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Di Update!',
                        })
                </script>
                ");
        return redirect()->to('admin/classes');
    }
    public function delete_class($kode)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $kode_kelas = decrypt_url($kode);
        $kelas = $this->KelasModel->getByKode($kode_kelas);

        if ($kelas == null) {
            session()->setFlashdata('pesan', "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal Dihapus!',
                        })
                </script>
                ");
            return redirect()->to('admin/classes');
        }

        if ($kelas->kode_kelas == $kode_kelas) {
            $this->KelasModel->delete($kelas->id_kelas);

            session()->setFlashdata('pesan', "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Berhasil Dihapus!',
                        })
                </script>
                ");
            return redirect()->to('admin/classes');
        }
    }
    // END::KELAS

    // START::STUDENTS
    public function students()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['siswa_list'] = $this->SiswaModel->asObject()->findAll();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/siswa/index', $data);
    }
    public function addstudent()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/siswa/tambah', $data);
    }
    public function addstudent_()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        // Ambil data yang dikirim dari form
        $no_induk = $this->request->getVar('nim');
        $data_siswa = array();

        $index = 0; // Set index array awal dengan 0
        foreach ($no_induk as $nim) {

            array_push($data_siswa, array(
                'nim' => $nim,
                'nama_siswa' => $this->request->getVar('nama_siswa')[$index],
                'kelas_siswa' => $this->request->getVar('kelas_kode')[$index],
                'jenis_kelamin' => $this->request->getVar('jenis_kelamin')[$index],
                // 'tanggal_lahir' => $this->request->getVar('tanggal_lahir', true)[$index],
                // 'tempat_lahir' => $this->request->getVar('tempat_lahir', true)[$index],
                // 'email' => $this->request->getVar('email', true)[$index],
                'password' => password_hash($nim, PASSWORD_DEFAULT),
                'role' => 2,
                'date_created' => time(),
                'is_active' => 1,
                'gambar' => "default.jpg"
            ));

            $index++;
        }

        $sql = $this->SiswaModel->insertBatch($data_siswa);

        // Cek apakah query insert nya sukses atau gagal
        if ($sql) { // Jika sukses
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Disimpan!',
                        })
                </script>
                ");
            return redirect()->to('admin/students');
        } else { // Jika gagal
            session()->setFlashdata('pesan', "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal Disimpan!',
                                })
                        </script>
                        ");
            return redirect()->to('admin/students');
        }
    }
    public function ajaxupdatestudents()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $nim = decrypt_url($this->request->getVar('nim'));
            $siswa = $this->SiswaModel->getByNim($nim);
            echo json_encode($siswa);
        }
    }
    public function updatestudents()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $this->SiswaModel->save([
            'id_siswa' => $this->request->getVar('id_siswa'),
            'nim' => $this->request->getVar('nim'),
            'nama_siswa' => $this->request->getVar('nama_siswa'),
            'kelas_siswa' => $this->request->getVar('kelas_siswa'),
            'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
            'is_active' => $this->request->getVar('is_active')
        ]);

        session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Di Ubah',
                        })
                </script>
                ");
        return redirect()->to('admin/students');
    }
    public function delete_student($data)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $nim = decrypt_url($data);

        $siswa = $this->SiswaModel->getByNim($nim);

        if ($siswa == null) {
            session()->setFlashdata('pesan', "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal Dihapus!',
                        })
                </script>
            ");
            return redirect()->to('admin/students');
        } else {
            $this->SiswaModel->delete($siswa->id_siswa);

            session()->setFlashdata('pesan', "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Berhasil Dihapus!',
                        })
                </script>
            ");
            return redirect()->to('admin/students');
        }
    }
    // END::STUDENTS

    // START::PRESENSI
    public function presensi()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['presensi_list'] = $this->PresensiModel->getAll();
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));

        return view('admin/presensi/index', $data);
    }
    public function addpresensi()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));
        return view('admin/presensi/tambah', $data);
    }
    public function addpresensi_()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $data_presensi = [
            'no_event' => $this->request->getVar('no_event'),
            'nama_event' => $this->request->getVar('nama_event'),
            'tgl_event' => $this->request->getVar('tgl_event'),
            'dari_jam' => $this->request->getVar('dari_jam'),
            'sampai_jam' => $this->request->getVar('sampai_jam'),
            'qr_event' => encrypt_url($this->request->getVar('no_event')),
        ];

        $this->PresensiModel->save($data_presensi);
        session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Disimpan!',
                        })
                </script>
                ");
        return redirect()->to('admin/presensi');
    }

    public function showpresensi($kode)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($kode);

        $event = $this->PresensiModel->getByNoEvent($no_event);
        if ($event == null) {
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Nomor event salah',
                        })
                </script>
                ");
            return redirect()->to('admin/presensi');
        }

        $data['event'] = $event;
        $data['absen'] = $this->AbsenModel->getByNoEvent($no_event);

        $waktu_sekarang = date('Y-m-d H:i', time());
        $tgl_event = $data['event']->tgl_event;
        $jam_akhir = $data['event']->sampai_jam;
        $akhir_event = "$tgl_event $jam_akhir";

        if (strtotime($waktu_sekarang) < strtotime($akhir_event)) {
            $data['berakhir'] = "masih"; //Masih
        } else {
            $data['berakhir'] = "berakhir"; //Beakhir
        }

        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));
        $data['event'] = $event;
        return view('admin/presensi/show-masuk', $data);
    }
    public function sudah_absen()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $no_event = $this->request->getVar('content');

            $presensi =  $this->AbsenModel->getByNoEvent($no_event);

            $html = '';

            if ($presensi != null) {
                foreach ($presensi as $row) {

                    if ($row->absen_masuk == 0 && $row->izinkan == 0) {
                        $telat = '<span class="badge badge-warning mb-2">Pending Izin</span>';
                    }
                    if ($row->absen_masuk == 0 && $row->izinkan == 1) {
                        $telat = '<span class="badge badge-primary mb-2">Izin</span>';
                    }
                    if ($row->is_telat != null && $row->is_telat == 0) {
                        $telat = '<span class="badge badge-success mb-2">Sukses</span>';
                    }
                    if ($row->is_telat != null && $row->is_telat == 1) {
                        $telat = '<span class="badge badge-danger mb-2">Terlambat</span>';
                    }
                    $html .= '
                    <div class="col-sm-3 mt-3 shadow-sm bg-white rounded">
                        <a href="#" class="friends-suggestions-list">
                            <div class="position-relative">
                                <div class="float-left mb-0 mr-3">
                                    <img src="' . base_url('assets/app-assets/user/') . '/' . $row->gambar . '" alt="" class="rounded-circle thumb-md mt-2">
                                </div>
                                <div class="desc">
                                    <h5 class="font-14 mb-1 pt-2 text-dark">' . $row->nama_siswa . '</h5>';
                    $html .= '<small>' . $row->kelas . '</small><br>';
                    $html .= '' . $telat . '
                                </div>
                            </div>
                        </a>
                    </div>
                ';
                }
            } else {
                $html .= '
                    <div class="alert alert-danger" role="alert">
                        Belum Ada Data.
                    </div>
                ';
            }

            echo $html;
        }
    }
    public function belum_absen_masuk()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $no_event = $this->request->getVar('content');

        $db = \Config\Database::connect();
        $query1 = $db->query("SELECT * FROM siswa WHERE nim NOT IN ( SELECT nim_siswa FROM absen WHERE no_event = '$no_event')");
        $query1_result = $query1->getResultObject();
        $belum_absen = $query1_result;

        $html = '';

        if ($belum_absen) {
            foreach ($belum_absen as $row) {
                $kelas = $this->KelasModel->getByKode($row->kelas_siswa);
                $html .= '
                    <div class="col-sm-3 mt-3 shadow-sm bg-white rounded">
                        <a href="#" class="friends-suggestions-list">
                            <div class="position-relative">
                                <div class="float-left mb-0 mr-3">
                                    <img src="' . base_url('assets/app-assets/user/') . '/' . $row->gambar . '" alt="" class="rounded-circle thumb-md mt-2">
                                </div>
                                <div class="desc">
                                    <h5 class="font-14 mb-1 pt-2 text-dark">' . $row->nama_siswa . '</h5>';
                $html .= '<small>' . $kelas->nama_kelas . '</small><br>';
                $html .= '<small>' . $kelas->kode_kelas . '</small><br>';
                $html .= '<span class="badge badge-danger mb-2">Belum Absen</span>';
                $html .= '</div>
                            </div>
                        </a>
                    </div>
                ';
            }
        } else {
            $html .= '
                    <div class="alert alert-success" role="alert">
                        Sudah Absen semua
                    </div>
                ';
        }

        echo $html;
    }
    public function showpresensikeluar($kode)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($kode);
        $event = $this->PresensiModel->getByNoEvent($no_event);
        if ($event == null) {
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Nomor event salah',
                        })
                </script>
                ");
            return redirect()->to('admin/presensi');
        }

        $data['event'] = $event;
        $data['absen'] = $this->AbsenModel->getByNoEvent($no_event);

        $waktu_sekarang = date('Y-m-d H:i', time());
        $tgl_event = $data['event']->tgl_event;
        $jam_akhir = $data['event']->sampai_jam;
        $akhir_event = "$tgl_event $jam_akhir";

        if (strtotime($waktu_sekarang) < strtotime($akhir_event)) {
            $data['berakhir'] = "masih"; //Masih
        } else {
            $data['berakhir'] = "berakhir"; //Beakhir
        }

        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));
        $data['event'] = $event;
        return view('admin/presensi/show-keluar', $data);
    }
    public function sudah_absen_keluar()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $no_event = $this->request->getVar('content');

            $presensi =  $this->AbsenModel->getByNoEvent($no_event);

            $html = '';

            if ($presensi != null) {
                foreach ($presensi as $row) {
                    if ($row->absen_keluar != 0 || $row->izinkan == '0' || $row->izinkan == '1') {

                        $kelas = $this->KelasModel->getByKode($row->kelas_siswa);

                        if ($row->izinkan == '1') {
                            $telat = '<span class="badge badge-primary mb-2">Izin</span>';
                        }
                        if ($row->izinkan == '0') {
                            $telat = '<span class="badge badge-warning mb-2">Pending</span>';
                        }
                        if ($row->keterangan == "Selesai Sebelum Waktu" && $row->absen_masuk != 0 && $row->absen_keluar !== 0) {
                            $telat = '<span class="badge badge-danger mb-2">Selesai Sebelum Waktu</span>';
                        }
                        if ($row->keterangan == "Tepat Waktu" && $row->absen_masuk != 0 && $row->absen_keluar !== 0) {
                            $telat = '<span class="badge badge-success mb-2">Sukses</span>';
                        }

                        $html .= '
                            <div class="col-sm-3 mt-3 shadow-sm bg-white rounded">
                                <a href="#" class="friends-suggestions-list">
                                    <div class="position-relative">
                                        <div class="float-left mb-0 mr-3">
                                            <img src="' . base_url('assets/app-assets/user/') . '/' . $row->gambar . '" alt="" class="rounded-circle thumb-md mt-2">
                                        </div>
                                        <div class="desc">
                                            <h5 class="font-14 mb-1 pt-2 text-dark">' . $row->nama_siswa . '</h5>';
                        $html .= '<small class="text-muted">' . $kelas->nama_kelas . '</small><br>';
                        $html .= $telat;
                        $html .= '
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ';
                    }
                }
            } else {
                $html .= '
                    <div class="alert alert-danger" role="alert">
                        Belum Ada Data.
                    </div>
                ';
            }

            echo $html;
        }
    }
    public function belum_absen_keluar()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $no_event = $this->request->getVar('content');

            $presensi =  $this->AbsenModel->getByNoEvent($no_event);

            $html = '';

            if ($presensi != null) {
                foreach ($presensi as $row) {
                    if ($row->absen_masuk != 0 && $row->absen_keluar == '0') {

                        $kelas = $this->KelasModel->getByKode($row->kelas_siswa);

                        $html .= '
                            <div class="col-sm-3 mt-3 shadow-sm bg-white rounded">
                                <a href="#" class="friends-suggestions-list">
                                    <div class="position-relative">
                                        <div class="float-left mb-0 mr-3">
                                            <img src="' . base_url('assets/app-assets/user/') . '/' . $row->gambar . '" alt="" class="rounded-circle thumb-md">
                                        </div>
                                        <div class="desc">
                                            <h5 class="font-14 mb-1 pt-2 text-dark">' . $row->nama_siswa . '</h5>';
                        $html .= '<p class="text-muted">' . $kelas->nama_kelas . '</p>';
                        $html .= '                                            
                                        </div>
                                    </div>
                                </a>
                            </div>
                        ';
                    }
                }
            } else {
                $html .= '
                    <div class="alert alert-success" role="alert">
                        Sudah Absen Semua
                    </div>
                ';
            }

            echo $html;
        }
    }
    public function listpermohonan($kode)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($kode);
        $event = $this->PresensiModel->getByNoEvent($no_event);
        if ($event == null) {
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Nomor event salah',
                        })
                </script>
                ");
            return redirect()->to('admin/presensi');
        }
        $data['list_izin'] = $this->AbsenModel
            ->where('no_event', $no_event)
            ->where('absen_masuk', '0')
            ->get()->getResultObject();

        $data['admin'] = $this->AdminModel->getByEmail(session()->get('email'));
        $data['event'] = $event;
        return view('admin/presensi/list-izin', $data);
    }
    public function izinkan()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $nim = decrypt_url($this->request->getVar('siswa'));
        $no_event = decrypt_url($this->request->getVar('event'));

        $this->AbsenModel
            ->where('no_event', $no_event)
            ->where('nim_siswa', $nim)
            ->set('izinkan', 1)
            ->update();

        session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Permohonan Di izinkan',
                        })
                </script>
                ");

        return redirect()->to('admin/listpermohonan/' . $this->request->getVar('event'));
    }
    public function hapus_absen($kode)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($kode);
        $event = $this->PresensiModel->getByNoEvent($no_event);
        if ($event == null) {
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Nomor event salah',
                        })
                </script>
                ");
            return redirect()->to('admin/presensi');
        }

        $this->AbsenModel
            ->where('no_event', $no_event)
            ->delete();

        $this->PresensiModel
            ->where('no_event', $no_event)
            ->delete();

        session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data Berhasil Dihapus',
                        })
                </script>
                ");
        return redirect()->to('admin/presensi');
    }
    // END::PRESENSI
}
