<?php

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: backend/controllers/Admin/UserController.php
 * Deskripsi: Controller Pengelolaan Akun Pengguna Sistem (/admin/akun)
 * Lapisan: Controller (Admin)
 * =====================================================================
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Authorization;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Services\UserService;

class UserController
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Tampilkan daftar akun pengguna sistem
     */
    public function index(Request $request): Response
    {
        Authorization::authorize('users.view');

        $page = (int)$request->query('page', 1);
        $usersData = $this->userService->getUsersList($page, 20);

        return view('admin.akun.index', [
            'pageTitle' => 'Manajemen Akun Pengguna Sistem - GMKI Cabang Padang',
            'users' => $usersData['data'],
            'total' => $usersData['total'],
            'currentPage' => $usersData['current_page'],
            'totalPages' => $usersData['total_pages'],
        ], 'admin');
    }

    /**
     * Tampilkan formulir tambah akun pengguna baru
     */
    public function create(Request $request): Response
    {
        Authorization::authorize('users.create');

        return view('admin.akun.form', [
            'pageTitle' => 'Tambah Akun Pengguna Baru - GMKI Cabang Padang',
            'user' => null,
            'isEdit' => false,
        ], 'admin');
    }

    /**
     * Simpan akun pengguna baru
     */
    public function store(Request $request): Response
    {
        Authorization::authorize('users.create');

        $validator = Validator::make($request->post(), [
            'username' => 'required|min:4|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nama_lengkap' => 'required|min:3',
            'role' => 'required|in:admin,pengawas,operator',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld($request->post());
            redirect('/admin/akun/create');
        }

        $this->userService->createUser($request->post());

        Session::flash('success', 'Akun pengguna baru berhasil dibuat.');
        redirect('/admin/akun');
    }

    /**
     * Tampilkan formulir edit akun pengguna
     */
    public function edit(Request $request, string $id): Response
    {
        Authorization::authorize('users.update');

        $user = $this->userService->getUser((int)$id);
        if (!$user) {
            Session::flash('error', 'Pengguna tidak ditemukan.');
            redirect('/admin/akun');
        }

        return view('admin.akun.form', [
            'pageTitle' => 'Edit Akun Pengguna - ' . $user['username'],
            'user' => $user,
            'isEdit' => true,
        ], 'admin');
    }

    /**
     * Simpan perubahan akun pengguna
     */
    public function update(Request $request, string $id): Response
    {
        Authorization::authorize('users.update');

        $rules = [
            'nama_lengkap' => 'required|min:3',
            'email' => "required|email|unique:users,email,{$id}",
            'role' => 'required|in:admin,pengawas,operator',
            'status' => 'required|in:aktif,nonaktif',
        ];

        if (!empty($request->post('password'))) {
            $rules['password'] = 'min:8';
        }

        $validator = Validator::make($request->post(), $rules);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            redirect("/admin/akun/{$id}/edit");
        }

        $this->userService->updateUser((int)$id, $request->post());

        Session::flash('success', 'Perubahan akun pengguna berhasil disimpan.');
        redirect('/admin/akun');
    }

    /**
     * Hapus akun pengguna
     */
    public function delete(Request $request, string $id): Response
    {
        Authorization::authorize('users.delete');

        $result = $this->userService->deleteUser((int)$id);

        if (!$result['success']) {
            Session::flash('error', $result['message']);
        } else {
            Session::flash('success', 'Akun pengguna berhasil dihapus.');
        }

        redirect('/admin/akun');
    }
}
