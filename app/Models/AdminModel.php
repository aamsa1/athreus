<?php
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['nama_admin', 'email', 'password', 'role', 'date_created', 'is_active', 'gambar'];

    public function getByEmail($email)
    {
    	return $this
    	->where('email', $email)
    	->get()->getRowObject();
    }
}
