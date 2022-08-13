<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'id_event';
    protected $allowedFields = ['no_event', 'nama_event', 'dari_jam', 'sampai_jam', 'qr_event'];

    public function getAll()
    {
        return $this
            ->orderBy('id_event', 'DESC')
            ->get()->getResultObject();
    }

    public function getByNoEvent($no_event)
    {
        return $this
            ->where('no_event', $no_event)
            ->get()->getRowObject();
    }
}
