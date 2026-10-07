<?php

namespace App\Controllers;

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

    public function index(Request $request): Response
    {
        Authorization::authorize('users.view');

        $page = (int)$request->query('page', 1);
        $usersData = $this->userService->getUsersList($page, 20);

        return view('users.index', [
            'pageTitle' => 'Manajemen Pengguna Sistem - GMKI Cabang Padang',
            'users' => $usersData['data'],
            'total' => $usersData['total'],
            'currentPage' => $usersData['current_page'],
            'totalPages' => $usersData['total_pages'],
        ], 'dashboard');
    }

    public function create(Request $request): Response
    {
        Authorization::authorize('users.create');

        return view('users.form', [
            'pageTitle' => 'Tambah Pengguna Baru - GMKI Cabang Padang',
            'user' => null,
            'isEdit' => false,
        ], 'dashboard');
    }

    public function store(Request $request): Response
    {
        Authorization::authorize('users.create');

        $validator = Validator::make($request->post(), [
            'username' => 'required|min:4|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nama_lengkap' => 'required|min:3',
            'role' => 'required|in:admin,ketcab,sekcab,bencab,sekfung_medko,pengawas,operator',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld($request->post());
            redirect('/admin/users/create');
        }

        $this->userService->createUser($request->post());

        Session::flash('success', 'Pengguna baru berhasil dibuat.');
        redirect('/admin/users');
    }

    public function edit(Request $request, string $id): Response
    {
        Authorization::authorize('users.update');

        $user = $this->userService->getUser((int)$id);
        if (!$user) {
            Session::flash('error', 'Pengguna tidak ditemukan.');
            redirect('/admin/users');
        }

        return view('users.form', [
            'pageTitle' => 'Edit Pengguna - ' . $user['username'],
            'user' => $user,
            'isEdit' => true,
        ], 'dashboard');
    }

    public function update(Request $request, string $id): Response
    {
        Authorization::authorize('users.update');

        $rules = [
            'nama_lengkap' => 'required|min:3',
            'email' => "required|email|unique:users,email,{$id}",
            'role' => 'required|in:admin,ketcab,sekcab,bencab,sekfung_medko,pengawas,operator',
            'status' => 'required|in:aktif,nonaktif',
        ];

        // Jika password diisi, cek minimal panjang
        if (!empty($request->post('password'))) {
            $rules['password'] = 'min:8';
        }

        $validator = Validator::make($request->post(), $rules);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            redirect("/admin/users/{$id}/edit");
        }

        $this->userService->updateUser((int)$id, $request->post());

        Session::flash('success', 'Perubahan pengguna berhasil disimpan.');
        redirect('/admin/users');
    }

    public function delete(Request $request, string $id): Response
    {
        Authorization::authorize('users.delete');

        $result = $this->userService->deleteUser((int)$id);

        if (!$result['success']) {
            Session::flash('error', $result['message']);
        } else {
            Session::flash('success', 'Pengguna berhasil dihapus.');
        }

        redirect('/admin/users');
    }
}
