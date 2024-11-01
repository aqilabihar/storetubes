<?php

namespace App\Controllers;

use App\Models\PesertaModel;
use CodeIgniter\Controller;

class Peserta extends Controller
{
    public function index()
    {
        return view('peserta_form');
    }

    public function save()
    {
        $pesertaModel = new PesertaModel();

        $data = [
            'nama'   => $this->request->getPost('nama'),
            'email'  => $this->request->getPost('email'),
            'alamat' => $this->request->getPost('alamat'),
        ];

        $pesertaModel->insert($data);

        return redirect()->to('/peserta/show');
    }

    public function show()
    {
        $pesertaModel = new PesertaModel();
        $data['peserta'] = $pesertaModel->findAll();

        return view('peserta_list', $data);
    }
}
