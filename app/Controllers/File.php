<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\PresensiModel;
use App\Models\AbsenModel;

class File extends BaseController
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

    public function suket($nama)
    {
        return $this->response->download('./assets/app-assets/izin/' . $nama, null);
    }
    public function exportqr($kode)
    {
        $no_event = decrypt_url($kode);
        $event = $this->PresensiModel->getByNoEvent($no_event);

        $html = '
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Report</title>
                <script src="' . base_url('assets/app-assets/template/presensi-abdul/plugins/qrcode/qrcode.js') . '"></script>
                <style>
                    body{
                        font-family: sans-serif;
                    }
                    table{
                        border: 0.1px solid #708090;
                    }
                    tr td{
                        text-align: center;
                        border: 0.1px solid #708090;
                        font-weight: 20;
                    }
                    tr th{
                        border: 0.1px solid #708090;
                    }
                    input[type=text] {
                        border: none;
                        background: transparent;
                    }
                </style>
            </head>

            <body>
                <h2 style="text-align: center;">E-PRESENSI BY I do your job<br><small>Built With Love for you</small></h2>
                <p style="text-align: center;"></p>
                <hr>
                <p style="text-align: center; font-weight: bold;">QR Code Presensi ' . $event->nama_event . '</p>
                <center>
                    <div id="qr-event"></div>
                </center>
            </body>
            <script>
                new QRCode(document.getElementById("qr-event"), "' . $event->qr_event . '");
                setTimeout(() => {
                    window.print();
                }, 1000);
            </script>
            </html>';
        echo $html;
    }

    public function absensi($kode)
    {
        $no_event = decrypt_url($kode);
        $event = $this->PresensiModel->getByNoEvent($no_event);
        $presensi = $this->AbsenModel->getByNoEvent($no_event);
        $html = '
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Report</title>
                <script src="' . base_url('assets/app-assets/template/presensi-abdul/plugins/qrcode/qrcode.js') . '"></script>
                <style>
                    body{
                        font-family: sans-serif;
                    }
                    table{
                        border: 0.1px solid #708090;
                    }
                    tr td{
                        text-align: center;
                        border: 0.1px solid #708090;
                        font-weight: 20;
                    }
                    tr th{
                        border: 0.1px solid #708090;
                    }
                    input[type=text] {
                        border: none;
                        background: transparent;
                    }
                </style>
            </head>

            <body>
            <h2 style="text-align: center;">E-PRESENSI BY I do your job<br><small>Built With Love for you</small></h2>
                <p style="text-align: center;"></p>
                <hr>
                <center>
                    <table width="100%" align="center" style="border: none;">
                        <tr>
                            <th style="border: none;">Nomor Absen</th>
                            <th style="border: none;">Nama Absen</th>
                            <th style="border: none;">Jam</th>
                        </tr>
                        <tr>
                            <td style="border: none; background: trasparent">' . $event->no_event . '</td>
                            <td style="border: none; background: trasparent">' . $event->nama_event . '</td>
                            <td style="border: none; background: trasparent">' . $event->dari_jam . '-' . $event->sampai_jam . '</td>
                        </tr>
                    </table>
                </center>
                <hr>
                    <h4 style="text-align: center;">List Sudah Presensi</h4>
                <table border="0.1" cellpadding="10" cellspacing="0" width="100%">
                    <tr>
                        <th>NAMA KARYAWAN</th>
                        <th>NIK</th>
                        <th>PERUSAHAAN</th>
                        <th>ABSEN MASUK</th>
                        <th>KETERANGAN</th>
                        <th>ABSEN PULANG</th>
                        <th>KETERANGAN AKHIR</th>
                    </tr>';
        $kelas = $this->KelasModel->asObject()->findAll();
        foreach ($presensi as $p) {
            $html .= '<tr>';
            $html .= '<td>' . $p->nama_siswa . '</td>';
            $html .= '<td>' . $p->nim_siswa . '</td>';
            $html .= '<td>' . $p->kelas . '</td>';
            if ($p->absen_masuk == 0 && $p->izinkan == 0) {
                $html .= '<td>' . "Pending" . '</td>';
            }
            if ($p->absen_masuk == 0 && $p->izinkan == 1) {
                $html .= '<td>' . "izin" . '</td>';
            }
            if ($p->absen_masuk != 0) {
                $html .= '<td>' . date('Y-m-d H:i', $p->absen_masuk) . '</td>';
            }


            if ($p->absen_masuk == 0 && $p->izinkan == 0) {
                $html .= '<td>' . "Pending" . '</td>';
            }
            if ($p->absen_masuk == 0 && $p->izinkan == 1) {
                $html .= '<td>' . "Izin" . '</td>';
            }
            if ($p->is_telat != null && $p->is_telat == 0) {
                $html .= '<td>' . "Sukses" . '</td>';
            }
            if ($p->is_telat != null && $p->is_telat == 1) {
                $html .= '<td>' . "Terlambat" . '</td>';
            }

            if ($p->absen_masuk == 0 && $p->izinkan == 0) {
                $html .= '<td>' . "Pending" . '</td>';
            }
            if ($p->absen_masuk == 0 && $p->izinkan == 1) {
                $html .= '<td>' . "izin" . '</td>';
            }
            if ($p->absen_masuk != 0 && $p->absen_keluar == 0) {
                $html .= '<td>-</td>';
            }
            if ($p->absen_masuk != 0 && $p->absen_keluar != 0) {
                $html .= '<td>' . date('Y-m-d H:i', $p->absen_keluar) . '</td>';
            }

            if ($p->absen_masuk != 0 && $p->absen_keluar == 0) {
                $html .= '<td>Bolos</td>';
            } else {
                $html .= '<td>' . $p->keterangan . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .=
            '
            <h4 style="text-align: center;">List Belum Presensi</h4>
                <table border="0.1" cellpadding="10" cellspacing="0" width="100%">
                    <tr>
                        <th>NAMA KARYAWAN</th>
                        <th>NIK</th>
                        <th>POS</th>
                        <th>PERUSAHAAN</th>
                    </tr>
        ';
        $db = \Config\Database::connect();
        $query1 = $db->query("SELECT * FROM siswa WHERE nim NOT IN ( SELECT nim_siswa FROM absen WHERE no_event = '$no_event')");
        $query1_result = $query1->getResultObject();
        $belum_absen = $query1_result;

        foreach ($belum_absen as $belum) {
            $html .= '<tr>';
            $html .= '<td>' . $belum->nama_siswa . '</td>';
            $html .= '<td>' . $belum->nim . '</td>';
            $html .= '<td>' . $belum->kelas_siswa . '</td>';
            foreach ($kelas as $kel) {
                if ($kel->kode_kelas == $belum->kelas_siswa) {
                    $html .= '<td>' . $kel->nama_kelas . '</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '
                </table>
                </body>
                <script>
                    setTimeout(() => {
                        window.print();
                    }, 1000);
                </script>
                </html>
                ';
        echo $html;
    }
    // END::PRESENSI
}
