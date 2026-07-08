<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            redirect('/dashboard');
        }

        return $this->view('auth.login', [
            'title' => 'Login - SDN Demakijo 1'
        ]);
    }

    public function loginAction()
    {
        $email    = $this->input('email');
        // PENTING: Ambil password langsung dari $_POST, JANGAN pakai $this->input()
        // karena htmlspecialchars() akan mengubah karakter seperti ! & " < > 
        // dan menyebabkan password_verify() selalu gagal.
        $password = $_POST['password'] ?? '';

        // Validasi input
        $validator = new Validator();
        if (!$validator->make(['email' => $email, 'password' => $password], [
            'email'    => 'required|email',
            'password' => 'required'
        ])) {
            Session::setFlash('errors', $validator->errors());
            Session::setFlash('old_email', $email);
            redirect('/login');
        }

        // Coba login
        if (Auth::attempt($email, $password)) {
            $user = Auth::user();
            log_activity("Pengguna '{$user->name}' berhasil masuk ke sistem", "auth");
            Session::setFlash('success', 'Selamat datang, ' . $user->name . '!');
            redirect('/dashboard');
        }

        // Login gagal
        Session::setFlash('error', 'Email atau password salah.');
        Session::setFlash('old_email', $email);
        redirect('/login');
    }

    public function logoutAction()
    {
        $user = Auth::user();
        if ($user) {
            log_activity("Pengguna '{$user->name}' keluar dari sistem", "auth");
        }
        Auth::logout();
        redirect('/login');
    }
}
