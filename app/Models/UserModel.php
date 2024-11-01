<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nama',
        'jenis_kelamin',
        'nip_nim',
        'telepon',
        'email',
        'tanggal_lahir',
        'username',
        'password_hash',
        'pertanyaan_keamanan',
        'jawaban_keamanan'
    ];
}
