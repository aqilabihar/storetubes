<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleRapatModel extends Model
{
    protected $table = 'rolerapat'; // Nama tabel di database
    protected $primaryKey = 'id'; // Kolom utama (primary key)
    protected $allowedFields = ['id_users', 'id_roles', 'id_rapat']; // Kolom yang dapat diisi
}
