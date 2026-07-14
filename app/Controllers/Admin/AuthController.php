<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('admin_id')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/auth/login', ['title' => 'Login Admin']);
    }

    public function attempt()
    {
        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $admin = model(AdminUserModel::class)->where('email', $email)->first();

        if (! $admin || $admin['status'] !== 'aktif' || ! password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        model(AdminUserModel::class)->update($admin['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        session()->set([
            'admin_id'    => $admin['id'],
            'admin_nama'  => $admin['nama'],
            'admin_email' => $admin['email'],
            'admin_role'  => $admin['role'],
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/admin/login');
    }
}
