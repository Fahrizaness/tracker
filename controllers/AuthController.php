<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        // If user is already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%');
            exit();
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            // Show login form
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    private function handleLogin() {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Basic validation
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email dan password harus diisi';
            header('Location: /tumbuh1%/login');
            exit();
        }

        // Get user by email
        $user = $this->userModel->getUserByEmail($email);

        // Verify user exists and password is correct
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Email atau password salah';
            header('Location: /tumbuh1%/login');
            exit();
        }

                // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['user_avatar'] = $user['avatar'] ?? '/tumbuh1%/assets/images/default-avatar.png';

        

        // Set login time
        $_SESSION['login_time'] = time();

        // Redirect to home with success message
        $_SESSION['success'] = 'Login berhasil! Selamat datang kembali, ' . htmlspecialchars($user['name']);
        header('Location: /tumbuh1%');
        exit();
    }

    public function register() {
        // If user is already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%');
            exit();
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegistration();
        } else {
            // Show registration form
            require_once __DIR__ . '/../views/auth/register.php';
        }
    }

    private function handleRegistration() {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate inputs
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Nama harus diisi';
        }

        if (empty($email)) {
            $errors[] = 'Email harus diisi';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid';
        } elseif ($this->userModel->getUserByEmail($email)) {
            $errors[] = 'Email sudah terdaftar';
        }

        if (empty($password)) {
            $errors[] = 'Password harus diisi';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Konfirmasi password tidak sesuai';
        }

        // If there are errors, show them
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'name' => $name,
                'email' => $email
            ];
            header('Location: /tumbuh1%/register');
            exit();
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create new user
        $userId = $this->userModel->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'is_admin' => 0 // Default to non-admin
        ]);

        if ($userId) {
            // Auto-login after registration
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['is_admin'] = 0;
            $_SESSION['login_time'] = time();

            $_SESSION['success'] = 'Registrasi berhasil! Selamat datang, ' . htmlspecialchars($name);
            header('Location: /tumbuh1%');
            exit();
        } else {
            $_SESSION['error'] = 'Gagal membuat akun. Silakan coba lagi.';
            header('Location: /tumbuh1%/register');
            exit();
        }
    }

    public function logout() {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header('Location: /tumbuh1%/login');
        exit();
    }

    public function forgotPassword() {
        // Implementation for password reset would go here
        require_once __DIR__ . '/../views/auth/forgot_password.php';
    }
}