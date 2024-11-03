<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RapatModel;
use App\Models\RoleRapatModel;
use App\Models\AbsensiModel;

class MeetController extends BaseController
{
    public function tambah()
    {

        $userModel = new UserModel();
        $rapatModel = new RapatModel();
        $roleRapatModel = new RoleRapatModel();

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

        // Menyimpan jumlah rapat ke dalam data
        $data['jumlah_rapat'] = $jumlah_rapat;

        return view('kita_rapat', $data); // Mengembalikan view 
    }
    public function buattrapat()
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

        return view('beranda_copy', $data); // Mengembalikan view untuk beranda

        // BerandaController
    }
    public function absenHadir($rapatId)
    {
        $absensiModel = new AbsensiModel();
        $userId = session()->get('user_id');

        $absensiModel->save([
            'id_user' => $userId,
            'id_rapat' => $rapatId,
            'status' => 'hadir'
        ]);

        return redirect()->back()->with('success', 'Berhasil absen hadir.');
    }

    public function absenTidakHadir($rapatId)
    {
        $absensiModel = new AbsensiModel();
        $userId = session()->get('user_id');

        $absensiModel->save([
            'id_user' => $userId,
            'id_rapat' => $rapatId,
            'status' => 'tidak hadir'
        ]);

        return redirect()->back()->with('success', 'Berhasil absen tidak hadir.');
    }

    public function tambahrapat()
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

        return view('tambah_rapat', $data); // Mengembalikan view untuk beranda

    }
}
