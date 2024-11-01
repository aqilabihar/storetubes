<?php

namespace App\Controllers;

use App\Models\RapatModel;
use App\Models\RoleRapatModel;
use App\Models\UserModel;

class Rapat extends BaseController
{
    public function index()
    {
        $rapatModel = new RapatModel();
        $roleRapatModel = new RoleRapatModel();
        $userModel = new UserModel();

        // Mengambil semua data rapat
        $data['rapats'] = $rapatModel->findAll();

        // Mengambil data peran untuk setiap rapat
        $data['rapatRoles'] = [];
        foreach ($data['rapats'] as $rapat) {
            $roles = $roleRapatModel->where('id_rapat', $rapat['id'])->findAll();
            $data['rapatRoles'][$rapat['id']] = $roles;

            // Fetch user details for each role
            foreach ($roles as &$role) {
                $role['user'] = $userModel->find($role['id_users']);
            }
        }

        return view('beranda', $data); // Mengembalikan view beranda
    }
    public function create()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll(); // Ambil semua pengguna untuk dipilih sebagai ketua

        return view('create_rapat', $data); // Mengembalikan view untuk membuat rapat
    }

    public function save()
    {
        $rapatModel = new RapatModel();
        $roleRapatModel = new RoleRapatModel();

        // Ambil data dari form
        $dataRapat = [
            'nama_rapat' => $this->request->getPost('nama_rapat'),
            'ruangan' => $this->request->getPost('ruangan'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
        ];

        // Simpan data rapat
        $rapatModel->insert($dataRapat);
        $idRapat = $rapatModel->insertID(); // Ambil ID rapat yang baru disimpan

        // Simpan ketua
        $dataRoleKetua = [
            'id_users' => $this->request->getPost('ketua'), // ID ketua dari form
            'id_roles' => 1, // ID untuk ketua
            'id_rapat' => $idRapat
        ];
        $roleRapatModel->insert($dataRoleKetua);

        // Simpan anggota
        $anggota = $this->request->getPost('anggota'); // Ambil anggota yang dipilih
        foreach ($anggota as $idUser) {
            $dataRoleAnggota = [
                'id_users' => $idUser,
                'id_roles' => 3, // ID untuk anggota
                'id_rapat' => $idRapat
            ];
            $roleRapatModel->insert($dataRoleAnggota);
        }

        // Simpan notulen (yang membuat rapat)
        $dataRoleNotulen = [
            'id_users' => session()->get('user_id'), // ID pengguna yang membuat rapat
            'id_roles' => 2, // ID untuk notulen
            'id_rapat' => $idRapat
        ];
        $roleRapatModel->insert($dataRoleNotulen);

        return redirect()->to('/rapat/show'); // Redirect ke halaman daftar rapat
    }

    // public function show()
    // {
    //     $rapatModel = new RapatModel();
    //     $roleRapatModel = new RoleRapatModel();

    //     // Mengambil semua data rapat
    //     $data['rapats'] = $rapatModel->findAll();

    //     // Mengambil data peran untuk setiap rapat
    //     $data['roles'] = [];
    //     foreach ($data['rapats'] as $rapat) {
    //         $data['roles'][$rapat['id']] = $roleRapatModel->where('id_rapat', $rapat['id'])->findAll();
    //     }

    //     return view('show_rapat', $data); // Mengembalikan view untuk menampilkan daftar rapat
    // }
    public function show()
    {
        $rapatModel = new RapatModel();
        $roleRapatModel = new RoleRapatModel();
        $userModel = new UserModel(); // Ensure you have this model to get user details

        // Mengambil semua data rapat
        $data['rapats'] = $rapatModel->findAll();

        // Mengambil data peran untuk setiap rapat
        $data['roles'] = [];
        foreach ($data['rapats'] as $rapat) {
            $data['roles'][$rapat['id']] = $roleRapatModel->where('id_rapat', $rapat['id'])->findAll();
        }

        // Optional: Fetch user details to display names
        $data['users'] = [];
        foreach ($data['roles'] as $roleList) {
            foreach ($roleList as $role) {
                $data['users'][$role['id_users']] = $userModel->find($role['id_users']); // Fetch user details by ID
            }
        }

        return view('beranda', $data); // Ensure you return the correct view name here
    }
}
