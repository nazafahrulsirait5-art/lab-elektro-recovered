<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        // Generate simple Math Captcha
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session()->set('captcha_answer', $num1 + $num2);
        
        $data = [
            'captcha_question' => "$num1 + $num2 = ?"
        ];

        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $captcha  = $this->request->getPost('captcha');

        // Verify Captcha (Sementara dimatikan agar mempermudah login)
        // if ($captcha != session()->get('captcha_answer')) {
        //     return redirect()->back()->with('error', 'Jawaban keamanan (Captcha) salah. Silakan coba lagi.');
        // }

        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $sessionData = [
                'id'           => $user['id'],
                'username'     => $user['username'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role'         => $user['role'],
                'foto_profil'  => $user['foto_profil'],
                'logged_in'    => true,
            ];
            session()->set($sessionData);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // -----------------------------------------------------
    // REGISTRASI MAHASISWA
    // -----------------------------------------------------
    public function register()
    {
        $userModel = new UserModel();
        
        $username = $this->request->getPost('username');
        $nama_lengkap = $this->request->getPost('nama_lengkap');
        $password = $this->request->getPost('password');

        // Check if username (NPM) already exists
        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'NPM / NIM tersebut sudah terdaftar di sistem. Silakan login.');
        }

        $data = [
            'username'     => $username,
            'nama_lengkap' => $nama_lengkap,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role'         => 'mahasiswa',
            'status_akun'  => 'aktif'
        ];

        $userModel->insert($data);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Akun Anda sudah bisa digunakan untuk Login.');
    }

    // -----------------------------------------------------
    // LUPA PASSWORD & RESET
    // -----------------------------------------------------
    
    public function lupaPassword()
    {
        return view('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        
        $user = $userModel->where('email', $email)->first();
        
        if (!$user) {
            // Untuk keamanan, beri pesan seolah-olah sukses untuk mencegah enumeration
            return redirect()->back()->with('success', 'Jika email terdaftar, instruksi reset telah dikirim.');
        }

        // Generate Token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $userModel->update($user['id'], [
            'reset_token'   => $token,
            'reset_expires' => $expires
        ]);

        // Karena di Localhost, kita tampilkan langsung linknya di flash data untuk demo
        $resetUrl = base_url('reset-password/' . $token);
        $pesanEdukasi = "Simulasi Email Terkirim ke $email.<br><br>Klik tautan ini untuk reset: <br><a href='$resetUrl' style='word-break: break-all; color:#1e40af; font-weight:bold; text-decoration:underline;'>$resetUrl</a>";
        
        return redirect()->back()->with('simulate_email', $pesanEdukasi);
    }

    public function resetPassword($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)
                          ->where('reset_expires >=', date('Y-m-d H:i:s'))
                          ->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token reset password tidak valid atau sudah kadaluwarsa.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function updatePassword($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)
                          ->where('reset_expires >=', date('Y-m-d H:i:s'))
                          ->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Sesi reset password tidak valid atau sudah kadaluwarsa.');
        }

        $password = $this->request->getPost('password');
        $confirm = $this->request->getPost('confirm_password');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }

        // Update Password dan bersihkan token
        $userModel->update($user['id'], [
            'password'      => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null
        ]);

        return redirect()->to('/login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
