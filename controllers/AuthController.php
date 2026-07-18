<?php
/**
 * Authentication Controller
 */

class AuthController extends Controller {

    private User $userModel;
    private Token $tokenModel;

    public function __construct() {
        $this->userModel = new User();
        $this->tokenModel = new Token();
    }

    // Render login page
    public function showLogin(): void {
        $this->view('auth/login', [
            'title' => 'Login | ' . APP_NAME
        ]);
    }

    // Render registration page
    public function showRegister(): void {
        $this->view('auth/register', [
            'title' => 'Register | ' . APP_NAME
        ]);
    }

    // Handle Login request
    public function login(): void {
        $this->validateCsrf();
        $data = $this->getRequestData();

        $loginInput = $data['login_input'] ?? ''; // Can be username or email
        $password = $data['password'] ?? '';
        $remember = isset($data['remember']);

        $errors = [];

        if (empty($loginInput)) {
            $errors['login_input'] = 'Username or Email is required.';
        }
        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        }

        if (!empty($errors)) {
            keep_old_input($data);
            set_flash('errors', implode('<br>', $errors));
            redirect('/login');
        }

        // Search user by username or email
        $user = null;
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userModel->findByEmail($loginInput);
        } else {
            $user = $this->userModel->findByUsername($loginInput);
        }

        if ($user && password_verify($password, $user['password'])) {
            // Check status
            if ($user['status'] !== 'active') {
                keep_old_input($data);
                set_flash('error', 'Your account is ' . e($user['status']) . '. Please contact support.');
                redirect('/login');
            }

            // Set session
            $_SESSION['user'] = [
                'id'            => (int)$user['id'],
                'role_id'       => (int)$user['role_id'],
                'role_name'     => $user['role_name'],
                'full_name'     => $user['full_name'],
                'username'      => $user['username'],
                'email'         => $user['email'],
                'profile_image' => $user['profile_image']
            ];

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Handle Remember Me
            if ($remember) {
                $this->setupRememberMe((int)$user['id']);
            }

            set_flash('success', 'Welcome back, ' . e($user['full_name']) . '!');
            redirect('/dashboard');
        }

        keep_old_input($data);
        set_flash('error', 'Invalid username/email or password.');
        redirect('/login');
    }

    // Handle Registration request
    public function register(): void {
        $this->validateCsrf();
        $data = $this->getRequestData();

        $fullName = $data['full_name'] ?? '';
        $username = $data['username'] ?? '';
        $email    = $data['email'] ?? '';
        $phone    = $data['phone'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        $errors = [];

        // Validate Full Name
        if (empty($fullName)) {
            $errors['full_name'] = 'Full name is required.';
        } elseif (strlen($fullName) < 3 || strlen($fullName) > 100) {
            $errors['full_name'] = 'Full name must be between 3 and 100 characters.';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullName)) {
            $errors['full_name'] = 'Full name can only contain letters and spaces.';
        }

        // Validate Username
        if (empty($username)) {
            $errors['username'] = 'Username is required.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors['username'] = 'Username must be between 3 and 50 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors['username'] = 'Username can only contain alphanumeric characters and underscores.';
        } elseif ($this->userModel->isDuplicateUsername($username)) {
            $errors['username'] = 'Username is already taken.';
        }

        // Validate Email
        if (empty($email)) {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address format.';
        } elseif ($this->userModel->isDuplicateEmail($email)) {
            $errors['email'] = 'Email address is already registered.';
        }

        // Validate Phone (Optional)
        if (!empty($phone) && !preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            $errors['phone'] = 'Invalid phone number format (7 to 15 digits).';
        }

        // Validate Password strength
        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).';
        }

        // Confirm Password match
        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            keep_old_input($data);
            set_flash('errors', implode('<br>', $errors));
            redirect('/register');
        }

        // Hash password and save
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userData = [
            'role_id'       => 2, // Default: Regular User
            'full_name'     => $fullName,
            'username'      => $username,
            'email'         => $email,
            'phone'         => !empty($phone) ? $phone : null,
            'password'      => $hashedPassword,
            'profile_image' => null,
            'status'        => 'active'
        ];

        $userId = $this->userModel->create($userData);
        if ($userId) {
            set_flash('success', 'Registration successful! Please login.');
            redirect('/login');
        }

        set_flash('error', 'Something went wrong during registration. Please try again.');
        keep_old_input($data);
        redirect('/register');
    }

    // Handle Logout request
    public function logout(): void {
        // Clear remember me token if set
        if (!empty($_COOKIE['remember_me'])) {
            $parts = explode(':', $_COOKIE['remember_me']);
            if (count($parts) === 2) {
                $this->tokenModel->deleteTokenBySelector($parts[0]);
            }
            // Expire cookie
            setcookie('remember_me', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', true);
        }

        // Clear session
        if (is_logged_in()) {
            $this->tokenModel->deleteUserTokens((int)$_SESSION['user']['id']);
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_start();

        set_flash('success', 'Logged out successfully.');
        redirect('/login');
    }

    // Setup Remember Me Cookie & DB tokens
    private function setupRememberMe(int $userId): void {
        // Selector: random 12-char hex string (24 characters)
        $selector = bin2hex(random_bytes(12));
        // Validator: random 32-char hex string (64 characters)
        $validator = bin2hex(random_bytes(32));
        
        $expiryTime = time() + (30 * 24 * 60 * 60); // 30 days
        $expiryDate = date('Y-m-d H:i:s', $expiryTime);

        // Hash validator
        $hashedValidator = hash('sha256', $validator);

        // Store in DB
        $saved = $this->tokenModel->createToken($userId, $selector, $hashedValidator, $expiryDate);
        
        if ($saved) {
            // Set cookie: selector:validator
            $cookieValue = $selector . ':' . $validator;
            setcookie(
                'remember_me',
                $cookieValue,
                $expiryTime,
                '/',
                '',
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // secure
                true // httponly
            );
        }
    }

    // Render Forgot Password page (UI Ready)
    public function showForgotPassword(): void {
        $this->view('auth/forgot-password', [
            'title' => 'Forgot Password | ' . APP_NAME
        ]);
    }

    // Handle Forgot Password post request
    public function forgotPassword(): void {
        $this->validateCsrf();
        $data = $this->getRequestData();
        $email = $data['email'] ?? '';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', 'Please enter a valid email address.');
            redirect('/forgot-password');
        }

        // Simulating the password reset link send (UI Ready / Commercial Simulation)
        set_flash('success', 'A password reset link has been simulated &amp; sent to ' . e($email) . '!');
        redirect('/login');
    }
}
