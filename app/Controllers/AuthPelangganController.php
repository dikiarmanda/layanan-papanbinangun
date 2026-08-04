<?php

namespace App\Controllers;

use App\Models\PelangganModel;

class AuthPelangganController extends BaseController
{
    public function login()
    {
        if (session()->get('pelanggan_id')) {
            return redirect()->to('/akun');
        }

        return view('auth/login', [
            'title' => 'Masuk',
        ]);
    }

    public function attempt()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email dan password wajib diisi.');
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        $akun = model(PelangganModel::class)->findAkunByEmail($email);

        if (!$akun || !password_verify($password, (string) $akun['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        session()->set([
            'pelanggan_id' => (int) $akun['id'],
            'pelanggan_nama' => $akun['nama'],
            'pelanggan_email' => $akun['email'],
        ]);

        $redirect = (string) (session()->getTempdata('redirect_after_login') ?: '/akun');
        session()->removeTempdata('redirect_after_login');

        return redirect()->to($redirect)->with('success', 'Selamat datang, ' . $akun['nama'] . '!');
    }

    public function register()
    {
        if (session()->get('pelanggan_id')) {
            return redirect()->to('/akun');
        }

        return view('auth/register', [
            'title' => 'Daftar Akun',
        ]);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]',
            'no_hp' => 'required|min_length[10]|max_length[20]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'nama' => trim((string) $this->request->getPost('nama')),
            'email' => strtolower(trim((string) $this->request->getPost('email'))),
            'no_hp' => trim((string) $this->request->getPost('no_hp')),
            'password' => (string) $this->request->getPost('password'),
        ];

        try {
            $id = model(PelangganModel::class)->registerAkun($data);
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', 'Gagal daftar pelanggan: {msg}', ['msg' => $e->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'Pendaftaran gagal. Coba lagi.');
        }

        session()->set([
            'pelanggan_id' => $id,
            'pelanggan_nama' => $data['nama'],
            'pelanggan_email' => $data['email'],
        ]);

        return redirect()->to('/akun')->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function logout()
    {
        session()->remove(['pelanggan_id', 'pelanggan_nama', 'pelanggan_email']);

        return redirect()->to('/')->with('success', 'Anda telah keluar.');
    }
}
