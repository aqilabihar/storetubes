<?php
// File: app/Models/TranskripsiRapatModel.php

namespace App\Models;

use CodeIgniter\Model;

class TranskripsiRapatModel extends Model
{
    protected $table = 'transkripsi_rapat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_rapat', 'transkripsi', 'created_at', 'updated_at'];
}
