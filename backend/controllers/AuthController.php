<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Services\AuthService;

class AuthController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showLogin(Request $request): Response
    {
        return view('auth.login', [
            'pageTitle' => 'Masuk Sistem - GMKI Cabang Padang',
        ], null);
    }

    public function login(Request $request): Response
    {
        $username = trim((string)$request->post('username'));
        $password = (string)$request->post('password');

        $validator = Validator::make([
            'username' => $username,
            'password' => $password,
        ], [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            Session::setOld(['username' => $username]);
            redirect('/login');
        }

        $result = $this->authService->authenticate(
            $username,
            $password,
            $request->ip(),
            $request->userAgent()
        );

        if (!$result['success']) {
            Session::flash('error', $result['message']);
            Session::setOld(['username' => $username]);
            redirect('/login');
        }

        Session::flash('success', 'Selamat datang kembali di Sistem GMKI Cabang Padang.');

        if ($result['user']['role'] === 'ketcab') {
            redirect('/ketcab/dashboard');
        } elseif (in_array($result['user']['role'], ['sekcab', 'bencab', 'sekfung_medko', 'operator'], true)) {
            redirect('/ruang-kerja');
        } else {
            redirect('/admin/dashboard');
        }
    }

    public function logout(Request $request): Response
    {
        $this->authService->logout();
        Session::flash('success', 'Anda telah berhasil keluar dari sistem.');
        redirect('/login');
    }

    public function logoutDirect(Request $request): Response
    {
        $this->authService->logout();
        Session::flash('info', 'Sesi telah ditutup. Anda telah keluar dari sistem.');
        redirect('/login');
    }

    public function autoLogout(Request $request): Response
    {
        $this->authService->logout();
        return (new Response())->json([
            'success' => true,
            'message' => 'Sesi kedaluwarsa atau browser ditutup. Sistem telah direset otomatis demi keamanan.',
        ]);
    }

    public function showChangePassword(Request $request): Response
    {
        $layout = 'dashboard';

        return view('auth.ubah-password', [
            'pageTitle' => 'Ubah Kata Sandi - GMKI Cabang Padang',
        ], $layout);
    }

    public function updatePassword(Request $request): Response
    {
        $oldPassword = (string)$request->post('old_password');
        $newPassword = (string)$request->post('new_password');
        $newPasswordConfirmation = (string)$request->post('new_password_confirmation');

        $validator = Validator::make([
            'old_password' => $oldPassword,
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPasswordConfirmation,
        ], [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first());
            redirect('/ubah-password');
        }

        $userId = (int)Auth::id();
        $result = $this->authService->changePassword($userId, $oldPassword, $newPassword);

        if (!$result['success']) {
            Session::flash('error', $result['message']);
            redirect('/ubah-password');
        }

        Session::flash('success', $result['message']);
        if (Auth::role() === 'ketcab') {
            redirect('/ketcab/dashboard');
        }
        redirect('/ruang-kerja');
    }
}
