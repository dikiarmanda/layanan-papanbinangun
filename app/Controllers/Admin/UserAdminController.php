<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class UserAdminController extends BaseController
{
    public function index()
    {
        return view('admin/users/index', [
            'title' => 'Kelola Admin',
            'users' => model(AdminUserModel::class)->orderBy('id', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/users/form', [
            'title' => 'Tambah Admin',
            'user'  => null,
        ]);
    }

    public function store()
    {
        $email = (string) $this->request->getPost('email');
        if (model(AdminUserModel::class)->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah dipakai.');
        }

        model(AdminUserModel::class)->insert([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $email,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role') ?: 'admin',
            'status'   => 'aktif',
        ]);

        return redirect()->to('/admin/users')->with('success', 'Admin ditambahkan.');
    }

    public function edit(int $id)
    {
        $user = model(AdminUserModel::class)->find($id);
        if (! $user) {
            return redirect()->to('/admin/users');
        }

        return view('admin/users/form', [
            'title' => 'Edit Admin',
            'user'  => $user,
        ]);
    }

    public function update(int $id)
    {
        $user = model(AdminUserModel::class)->find($id);
        if (! $user) {
            return redirect()->to('/admin/users');
        }

        $data = [
            'nama'   => $this->request->getPost('nama'),
            'email'  => $this->request->getPost('email'),
            'role'   => $this->request->getPost('role'),
            'status' => $this->request->getPost('status'),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        model(AdminUserModel::class)->update($id, $data);

        return redirect()->to('/admin/users')->with('success', 'Admin diperbarui.');
    }
}
