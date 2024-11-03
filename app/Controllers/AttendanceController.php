<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\RoleRapatModel;

class AttendanceController extends BaseController
{
    public function list($meetingId)
    {
        $roleRapatModel = new RoleRapatModel();
        $userId = session()->get('user_id'); // Mendapatkan ID user dari session

        // Pastikan pengguna memiliki akses sebagai 'notulen'
        $userRoleData = $roleRapatModel->where('id_rapat', $meetingId)
            ->where('id_users', $userId)
            ->where('id_roles', 2) // ID 2 untuk 'notulen'
            ->first();

        if (!$userRoleData) {
            return redirect()->to('/beranda')->with('error', 'You do not have access to view this attendance.');
        }

        // Ambil data kehadiran berdasarkan ID rapat
        $absensiModel = new AbsensiModel();
        $attendanceData = $absensiModel->where('id_rapat', $meetingId)->findAll();

        // Ambil detail pengguna untuk setiap catatan kehadiran
        $userModel = new UserModel();
        foreach ($attendanceData as &$attendance) {
            $user = $userModel->find($attendance['id_user']);
            $attendance['nama'] = $user ? $user['nama'] : 'Unknown User';
        }

        // Kirim data ke view attendance_list.php
        return view('attendance_list', ['attendanceData' => $attendanceData]);
    }
}
