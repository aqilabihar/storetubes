<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id';

    // Define the fields that can be set or updated
    protected $allowedFields = ['id_rapat', 'id_user', 'status_kehadiran', 'waktu_absen'];

    // Specify default return type
    protected $returnType = 'array';

    /**
     * Get attendance records for a specific meeting.
     *
     * @param int $idRapat
     * @return array
     */
    public function getAttendanceByMeeting($idRapat)
    {
        return $this->where('id_rapat', $idRapat)->findAll();
    }

    /**
     * Check if a user has already attended a specific meeting.
     *
     * @param int $idRapat
     * @param int $idUser
     * @return array|null
     */
    public function checkUserAttendance($idRapat, $idUser)
    {
        return $this->where(['id_rapat' => $idRapat, 'id_user' => $idUser])->first();
    }
}
