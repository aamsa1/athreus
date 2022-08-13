<?php
namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $allowedFields = ['nim', 'nama_siswa', 'kelas_siswa', 'jenis_kelamin', 'tanggal_lahir', 'tempat_lahir', 'email', 'password', 'role', 'date_created', 'is_active', 'gambar'];

    public function getByEmail($email)
    {
    	return $this
    	->where('email', $email)
    	->get()->getRowObject();
    }

    public function getByNim($nim)
    {
    	return $this
    	->where('nim', $nim)
    	->get()->getRowObject();
    }
}
