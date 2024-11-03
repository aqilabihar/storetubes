<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RapatModel;
use App\Models\RoleRapatModel;
use App\Models\AbsensiModel;


class BerandaController extends BaseController
{
    public function index()
    {



        // Pass $userRole to the view
        $absensiModel = new AbsensiModel();

        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login'); // Mengarahkan ke halaman login jika belum login
        }
        $userModel = new UserModel();
        $rapatModel = new RapatModel();
        $roleRapatModel = new RoleRapatModel();
        // Get the logged-in user's ID
        if (!session()->has('user_id')) {
            echo "User ID not found in session.";
            exit; // Stop further execution for debugging
        }

        $userId = session()->get('user_id');
        echo "User ID: " . $userId; // Debug output
        $data['userId'] = $userId;

        // Initialize user role variable
        // Initialize user role variable
        $userRole = ''; // Default in case no role is found

        // Check if the user has a specific role in any meeting
        $userRoleData = $roleRapatModel->where('id_users', $userId)->first();
        if ($userRoleData) {
            // Assuming 'id_roles' is the field in your 'rolerapat' table that identifies roles
            switch ($userRoleData['id_roles']) {
                case 1:
                    $userRole = 'ketua';
                    break;
                case 2:
                    $userRole = 'notulen';
                    break;
                case 3:
                    $userRole = 'anggota';
                    break;
                default:
                    $userRole = 'unknown';
            }
        }
        $data['userRole'] = $userRole;



        // Ambil data pengguna yang sedang login
        $user_id = session()->get('user_id');
        $data['user'] = $userModel->find($user_id);

        // Mengambil semua data rapat
        $data['rapats'] = $rapatModel->findAll();

        // Mengambil data peran untuk setiap rapat
        $data['roles'] = [];
        foreach ($data['rapats'] as $rapat) {
            $data['roles'][$rapat['id']] = $roleRapatModel->where('id_rapat', $rapat['id'])->findAll();
        }

        // Menyiapkan array untuk menyimpan nama pengguna berdasarkan ID
        $userNames = [];
        $users = $userModel->findAll();
        foreach ($users as $user) {
            $userNames[$user['id']] = $user['nama'];
        }

        // Menyimpan nama pengguna ke dalam data
        $data['userNames'] = $userNames;

        // Menghitung jumlah rapat yang diikuti oleh pengguna
        $jumlah_rapat = [
            'ketua' => 0,
            'notulensi' => 0,
            'anggota' => 0,
        ];

        foreach ($data['roles'] as $role) {
            foreach ($role as $item) {
                if ($item['id_users'] == $user_id) {
                    if ($item['id_roles'] == 1) { // ID untuk ketua
                        $jumlah_rapat['ketua']++;
                    } elseif ($item['id_roles'] == 2) { // ID untuk notulen
                        $jumlah_rapat['notulensi']++;
                    } elseif ($item['id_roles'] == 3) { // ID untuk anggota
                        $jumlah_rapat['anggota']++;
                    }
                }
            }
        }

        // Mendapatkan daftar rapat
        $data['rapats'] = $rapatModel->findAll();

        // Menyiapkan array untuk menyimpan status kehadiran pengguna di setiap rapat
        $data['has_attended'] = [];

        // Loop setiap rapat dan cek apakah user sudah absen
        foreach ($data['rapats'] as $rapat) {
            // Cek apakah pengguna sudah hadir di rapat ini
            $attendanceRecord = $absensiModel->where([
                'id_rapat' => $rapat['id'],
                'id_user' => $userId,
                'status_kehadiran' => 'hadir'
            ])->first();

            // Jika ada record, berarti sudah absen; jika tidak ada, berarti belum
            $data['has_attended'][$rapat['id']] = $attendanceRecord ? true : false;
        }

        // Menyimpan jumlah rapat ke dalam data
        $data['jumlah_rapat'] = $jumlah_rapat;

        return view('beranda', $data); // Mengembalikan view untuk beranda
    }
    // BerandaController
    public function absenHadir($idRapat)
    {
        $userId = session()->get('user_id');
        $absensiModel = new \App\Models\AbsensiModel();

        // Check if the user is already marked present
        if (!$absensiModel->where(['id_rapat' => $idRapat, 'id_user' => $userId])->first()) {
            $absensiModel->save([
                'id_rapat' => $idRapat,
                'id_user' => $userId,
                'status_kehadiran' => 'hadir'
            ]);
        }
        return redirect()->to('/beranda')->with('message', 'Attendance recorded');
    }
    protected function hasAccessToMeeting($meetingId, $roleId)
    {
        // Check if user is authenticated
        if (!session()->get('isLoggedIn')) {
            return false; // User is not logged in
        }

        $userId = session()->get('userId'); // Adjust according to your session variable

        // Check if the user has the specified role in the meeting
        $roles = $this->getRolesForMeeting($meetingId); // Assume you have a method to get roles for the meeting
        return isset($roles[$roleId]) && in_array($userId, array_column($roles[$roleId], 'id_users'));
    }

    protected function getRolesForMeeting($meetingId)
    {
        $roleModel = new RoleRapatModel();
        return $roleModel->where('id_rapat', $meetingId)->findAll();
    }

    public function attendanceList($meetingId)
    {
        // Ensure the user has the notulen role and belongs to the meeting
        if ($this->hasAccessToMeeting($meetingId, 2)) {
            // Retrieve attendance data for the specified meeting
            $absenModel = new AbsensiModel();
            $attendanceData = $absenModel->where('id_rapat', $meetingId)->findAll();

            // Check if attendance data exists
            if (empty($attendanceData)) {
                return view('attendance_list', ['attendanceData' => [], 'message' => 'No attendance data found.']);
            }

            // Get user details for each attendance record
            $userModel = new UserModel();
            foreach ($attendanceData as &$attendance) {
                if (isset($attendance['id_users'])) {
                    $user = $userModel->find($attendance['id_users']);
                    $attendance['nama'] = $user ? $user['nama'] : 'Unknown User';
                } else {
                    $attendance['nama'] = 'No User ID';
                }
            }

            // Pass the attendance data to the view
            return view('attendance_list', ['attendanceData' => $attendanceData]);
        } else {
            // Redirect or show error if the user does not have access
            return redirect()->to('/beranda')->with('error', 'You do not have access to view this attendance.');
        }
    }
    public function getAttendance($meetingId)
    {
        $absenModel = new AbsensiModel();
        // Fetch attendance data for the specified meeting
        $attendanceData = $absenModel->where('id_rapat', $meetingId)->findAll();

        if (empty($attendanceData)) {
            return $this->response->setJSON(['error' => 'No attendance records found.']);
        }

        $userModel = new UserModel();
        foreach ($attendanceData as &$attendance) {
            // Ensure 'id_user' matches your database column
            $user = $userModel->find($attendance['id_user']);
            $attendance['nama'] = $user ? $user['nama'] : 'Unknown User';
        }

        return $this->response->setJSON($attendanceData); // Return data as JSON
    }
}
