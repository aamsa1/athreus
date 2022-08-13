<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenModel extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id_absen';
    protected $allowedFields = ['no_event', 'nama_siswa', 'nim_siswa', 'kelas', 'absen_masuk', 'is_telat', 'absen_keluar', 'izinkan', 'suket', 'keterangan'];

    public function getByNoEvent($no_event)
    {
        return $this
            ->join('siswa', 'siswa.nim=absen.nim_siswa')
            ->where('absen.no_event', $no_event)
            ->get()->getResultObject();
    }

    public function getByNoEventAndNim($no_event, $nim, $current_date)
    {
        return $this
            ->join('siswa', 'siswa.nim=absen.nim_siswa')
            ->where('absen.no_event', $no_event)
            ->where('absen.nim_siswa', $nim)
            ->where('absen.absen_masuk_date', $current_date)
            ->get()->getRowObject();
    }
}
