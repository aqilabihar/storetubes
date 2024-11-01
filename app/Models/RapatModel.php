<?php

namespace App\Models;

use CodeIgniter\Model;

class RapatModel extends Model
{
    protected $table = 'rapat'; // Nama tabel di database
    protected $primaryKey = 'id'; // Kolom utama (primary key)
    protected $allowedFields = ['nama_rapat', 'ruangan', 'waktu_mulai', 'waktu_selesai']; // Kolom yang dapat diisi
}
