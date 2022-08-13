<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $allowedFields = ['kode_kelas', 'nama_kelas'];

    public function getByKode($kode)
    {
        return $this
            ->where('kode_kelas', $kode)
            ->get()->getRowObject();
    }
}
