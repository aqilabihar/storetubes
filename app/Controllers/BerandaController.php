<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RapatModel;
use App\Models\RoleRapatModel;

class BerandaController extends BaseController
{
    public function index()
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

        return view('beranda', $data); // Mengembalikan view untuk beranda
    }
}
