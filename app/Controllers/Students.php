<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\PresensiModel;
use App\Models\AbsenModel;

class Students extends BaseController
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
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $data['siswa'] = $this->SiswaModel->getByNim(session()->get('nim'));
        return view('siswa/dashboard', $data);
    }

    // START::PROFILE
    public function profile()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $data['siswa'] = $this->SiswaModel->getByNim(session()->get('nim'));
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        return view('siswa/profile', $data);
    }
    public function profile_()
    {
        if (session()->get('role') != 2) {
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

        $this->SiswaModel->save([
            'id_siswa' => $this->request->getVar('id_siswa'),
            'nama_siswa' => $this->request->getVar('nama_siswa'),
            'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
            'tempat_lahir' => $this->request->getVar('tempat_lahir'),
            'email' => $this->request->getVar('email'),
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
        return redirect()->to('students/profile');
    }
    // END::PROFILE

    // START::PRESENSI
    public function presensi()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $data['siswa'] = $this->SiswaModel->getByNim(session()->get('nim'));
        $data['presensi_list'] = $this->PresensiModel->getAll();
        return view('siswa/presensi/index', $data);
    }
    public function absenmasuk($argument)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($argument);
        $current_date = date('Y-m-d');
        $event = $this->PresensiModel->getByNoEvent($no_event);
        $data['event'] = $event;

        $waktu_sekarang = date('Y-m-d H:i', time());
        $tgl_event = $data['event']->tgl_event;
        $jam_akhir = $data['event']->sampai_jam;
        $akhir_event = "$tgl_event $jam_akhir";

        if (strtotime($waktu_sekarang) < strtotime($akhir_event)) {
            $data['berakhir'] = "masih"; //Masih
        } else {
            $data['berakhir'] = "berakhir"; //Beakhir
        }

        $data['siswa'] = $this->SiswaModel->getByNim(session()->get('nim'));
        //$data['absen_siswa'] = $this->AbsenModel->getByNoEventAndNim($no_event, session()->get('nim'));
        $data['absen_siswa'] = $this->AbsenModel->getByNoEventAndNim($no_event, session()->get('nim'), $current_date);
        // dd($data['absen_siswa']);
        return view('siswa/presensi/show-masuk', $data);
    }
    public function absen_masuk()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $no_event = decrypt_url($this->request->getVar('content'));
            $event = $this->PresensiModel->getByNoEvent($no_event);

            if ($no_event == null) {
                return 'error';
            } else {
                if ($event == null) {
                    return 'error';
                } else {
                    $siswa = $this->SiswaModel->getByNim(session()->get('nim'));

                    $waktu_scan =  date('H:i', time());
                    $waktu_scan2 = time();
                    $batas1 = $event->dari_jam;

                    if ((strtotime($waktu_scan) > strtotime($batas1))) {
                        // echo "<b>Batas waktu sudah berakhir</b><br>";
                        $telat = 1;
                    } else {
                        // echo "<b>Masih dalam jangka waktu</b><br>";
                        $telat = 0;
                    }
                    $kelas = $this->KelasModel->getByKode($siswa->kelas_siswa);
                    $data = [
                        'no_event' => $no_event,
                        'nama_siswa' => $siswa->nama_siswa,
                        'nim_siswa' => $siswa->nim,
                        'kelas' => $kelas->nama_kelas,
                        'absen_masuk' => $waktu_scan2,
                        'is_telat' => $telat,
                    ];
                    $this->AbsenModel->save($data);
                    return 'success';
                }
            }
        }
    }
    public function absenkeluar($argument)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($argument);
        $current_date = date('Y-m-d');
        $event = $this->PresensiModel->getByNoEvent($no_event);
        $data['event'] = $event;

        $waktu_sekarang = date('Y-m-d H:i', time());
        $tgl_event = $data['event']->tgl_event;
        $jam_akhir = $data['event']->sampai_jam;
        $akhir_event = "$tgl_event $jam_akhir";

        if (strtotime($waktu_sekarang) < strtotime($akhir_event)) {
            $data['berakhir'] = "masih"; //Masih
        } else {
            $data['berakhir'] = "berakhir"; //Beakhir
        }

        $data['siswa'] = $this->SiswaModel->getByNim(session()->get('nim'));
        //$data['absen_siswa'] = $this->AbsenModel->getByNoEventAndNim($no_event, session()->get('nim'));
        $data['absen_siswa'] = $this->AbsenModel->getByNoEventAndNim($no_event, session()->get('nim'), $current_date);
        return view('siswa/presensi/show-keluar', $data);
    }
    public function absen_keluar()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($this->request->getVar('content'));
        $event = $this->PresensiModel->getByNoEvent($no_event);

        if ($no_event == null) {
            return 'error';
        } else {
            if ($event == null) {
                return 'error';
            } else {
                $siswa = $this->SiswaModel->getByNim(session()->get('nim'));

                $waktu_scan =  date('H:i', time());
                $waktu_scan2 = time();
                $batas1 = $event->sampai_jam;
                // $batas2 = intval($batas1);
                // $batas = date('H:i', $batas1);
                if ((strtotime($waktu_scan) < strtotime($batas1))) {
                    $keterangan = "Selesai Sebelum Waktu";
                } else {
                    $keterangan = "Tepat Waktu";
                }

                $this->AbsenModel
                    ->set('absen_keluar', $waktu_scan2)
                    ->set('keterangan', $keterangan)
                    ->where('no_event', $no_event)
                    ->where('nim_siswa', $siswa->nim)
                    ->update();

                return 'success';
            }
        }
    }
    public function izin($argument)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $no_event = decrypt_url($argument);

        $event = $this->PresensiModel->getByNoEvent($no_event);
        $data['event'] = $event;

        $waktu_sekarang = date('Y-m-d H:i', time());
        $tgl_event = $data['event']->tgl_event;
        $jam_akhir = $data['event']->sampai_jam;
        $akhir_event = "$tgl_event $jam_akhir";

        if (strtotime($waktu_sekarang) < strtotime($akhir_event)) {
            $data['berakhir'] = "masih"; //Masih
        } else {
            $data['berakhir'] = "berakhir"; //Beakhir
        }

        $data['list_izin'] = $this->AbsenModel
            ->where('no_event', $no_event)
            ->where('nim_siswa', session()->get('nim'))
            ->where('absen_masuk', '0')
            ->where('absen_keluar', '0')
            ->get()->getRowObject();

        $data['list_presensi'] = $this->AbsenModel
            ->where('no_event', $no_event)
            ->where('nim_siswa', session()->get('nim'))
            ->get()->getRowObject();

        $data['siswa'] = $this->SiswaModel->getByNim(session()->get('nim'));
        return view('siswa/presensi/izin', $data);
    }
    public function kirim_izin()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $no_event = $this->request->getVar('no_event');
        $siswa = $this->SiswaModel->getByNim(session()->get('nim'));
        $kelas = $this->KelasModel->getByKode($siswa->kelas_siswa);

        $fileSuket = $this->request->getFile('suket');
        // Cek Apakah Ada File Yg dikirm
        if ($fileSuket->getError() == 4) {
            session()->setFlashdata('pesan', "
                <script>
                   Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Harus Memasukan Bukti!',
                        })
                </script>
                ");
            return redirect()->to('students/izin' . '/' . encrypt_url($no_event));
        }

        // Generate nama file Random
        $suket = $fileSuket->getRandomName();
        // Upload File
        $fileSuket->move('assets/app-assets/izin', $suket);
        $data = [
            'no_event' => $no_event,
            'nama_siswa' => $siswa->nama_siswa,
            'nim_siswa' => $siswa->nim,
            'kelas' => $kelas->nama_kelas,
            'izinkan' => 0,
            'suket' => $suket,
            'keterangan' => $this->request->getVar('keterangan')

        ];

        $sql = $this->AbsenModel->save($data);

        if ($sql) {
            session()->setFlashdata('pesan', '
                <script>
                        Swal.fire(
                        "Berhasil!",
                        "Data Berhasil Dikirim",
                        "success"
                    );
                </script>
            ');
            return redirect()->to('students/izin/' . encrypt_url($no_event));
        } else {
            session()->setFlashdata('pesan', '
                <script>
                        Swal.fire(
                        "Oopss!",
                        "Gagal Dikirim",
                        "error"
                    );
                </script>
            ');
            return redirect()->to('students/izin/' . encrypt_url($no_event));
        }
    }
    // END::PRESENSI
}
