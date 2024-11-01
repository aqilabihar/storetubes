<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function save()
    {
        $pesertaModel = new UserModel();

        // Ambil data dari request
        $data = [
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'), // Pastikan ini sesuai dengan nama input
            'nip_nim' => $this->request->getPost('nip_nim'), // Assuming you're getting this value
            'telepon' => $this->request->getPost('telepon'), // Assuming you're getting this value
            'email' => $this->request->getPost('email'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'), // Assuming you're getting this value
            'username' => $this->request->getPost('username'), // Assuming you're getting this value
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT), // Hash password
            'pertanyaan_keamanan' => $this->request->getPost('pertanyaan_keamanan'), // Assuming you're getting this value
            'jawaban_keamanan' => $this->request->getPost('jawaban_keamanan') // Assuming you're getting this value
        ];

        // Validasi data jika diperlukan
        // (Anda bisa menambahkan validasi di sini jika diinginkan)

        // Simpan data ke database
        $pesertaModel->insert($data);

        // Redirect setelah sukses
        return redirect()->to('/auth/login');
    }

    public function show()
    {
        $pesertaModel = new UserModel();
        $data['peserta'] = $pesertaModel->findAll();

        return view('peserta_list', $data);
    }

    public function login()
    {
        // Load login view
        return view('login');
    }

    public function authenticate()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Authenticate user
        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password_hash'])) {
            session()->set('user_id', $user['id']); // Store user ID in session
            return redirect()->to('/beranda');
        }

        return redirect()->to('/login')->with('error', 'Login failed');
    }

    public function logout()
    {
        session()->destroy(); // End session
        return redirect()->to('auth/login');
    }
}
