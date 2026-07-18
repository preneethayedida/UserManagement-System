<?php
/**
 * Profile Controller
 */

class ProfileController extends Controller {

    private User $userModel;
    private Token $tokenModel;

    public function __construct() {
        $this->userModel = new User();
        $this->tokenModel = new Token();
    }

    // Show profile index page
    public function index(): void {
        $userId = (int)$_SESSION['user']['id'];
        $profile = $this->userModel->findById($userId);

        if (!$profile) {
            set_flash('error', 'User not found.');
            redirect('/dashboard');
        }

        $this->view('profile/index', [
            'title'   => 'Profile Settings | ' . APP_NAME,
            'profile' => $profile
        ]);
    }

    // Update Profile Information
    public function update(): void {
        $this->validateCsrf();
        $data = $this->getRequestData();

        $userId = (int)$_SESSION['user']['id'];
        $fullName = $data['full_name'] ?? '';
        $username = $data['username'] ?? '';
        $email    = $data['email'] ?? '';
        $phone    = $data['phone'] ?? '';

        $errors = [];

        // Validate Full Name
        if (empty($fullName)) {
            $errors[] = 'Full name is required.';
        } elseif (strlen($fullName) < 3 || strlen($fullName) > 100) {
            $errors[] = 'Full name must be between 3 and 100 characters.';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullName)) {
            $errors[] = 'Full name can only contain letters and spaces.';
        }

        // Validate Username
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = 'Username must be between 3 and 50 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Username can only contain alphanumeric characters and underscores.';
        } elseif ($this->userModel->isDuplicateUsername($username, $userId)) {
            $errors[] = 'Username is already taken.';
        }

        // Validate Email
        if (empty($email)) {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address format.';
        } elseif ($this->userModel->isDuplicateEmail($email, $userId)) {
            $errors[] = 'Email address is already in use.';
        }

        // Validate Phone (Optional)
        if (!empty($phone) && !preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            $errors[] = 'Invalid phone number format (7 to 15 digits).';
        }

        if (!empty($errors)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => implode('<br>', $errors)], 422);
            } else {
                set_flash('errors', implode('<br>', $errors));
                redirect('/profile');
            }
        }

        $updateData = [
            'full_name' => $fullName,
            'username'  => $username,
            'email'     => $email,
            'phone'     => !empty($phone) ? $phone : null
        ];

        $updated = $this->userModel->update($userId, $updateData);

        if ($updated) {
            // Update session values
            $_SESSION['user']['full_name'] = $fullName;
            $_SESSION['user']['username']  = $username;
            $_SESSION['user']['email']     = $email;

            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Profile details updated successfully!']);
            } else {
                set_flash('success', 'Profile details updated successfully!');
                redirect('/profile');
            }
        }

        if ($this->isAjax()) {
            $this->json(['success' => false, 'message' => 'No changes were made or update failed.'], 400);
        } else {
            set_flash('error', 'No changes were made or update failed.');
            redirect('/profile');
        }
    }

    // Change User Password
    public function changePassword(): void {
        $this->validateCsrf();
        $data = $this->getRequestData();

        $userId = (int)$_SESSION['user']['id'];
        $currentPassword = $data['current_password'] ?? '';
        $newPassword     = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        $errors = [];

        // Verify inputs are not empty
        if (empty($currentPassword)) {
            $errors[] = 'Current password is required.';
        }
        if (empty($newPassword)) {
            $errors[] = 'New password is required.';
        }
        if (empty($confirmPassword)) {
            $errors[] = 'Confirm password is required.';
        }

        if (!empty($errors)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => implode('<br>', $errors)], 422);
            } else {
                set_flash('errors', implode('<br>', $errors));
                redirect('/profile');
            }
        }

        // Fetch user record
        $user = $this->userModel->findById($userId);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $msg = 'Invalid current password.';
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => $msg], 400);
            } else {
                set_flash('error', $msg);
                redirect('/profile');
            }
        }

        // Validate Password strength
        if (strlen($newPassword) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPassword)) {
            $errors[] = 'New password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New password and confirmation do not match.';
        }

        if (!empty($errors)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => implode('<br>', $errors)], 422);
            } else {
                set_flash('errors', implode('<br>', $errors));
                redirect('/profile');
            }
        }

        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updated = $this->userModel->update($userId, ['password' => $hashedPassword]);

        if ($updated) {
            // Revoke remember me sessions on password change
            $this->tokenModel->deleteUserTokens($userId);

            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Password changed successfully!']);
            } else {
                set_flash('success', 'Password changed successfully!');
                redirect('/profile');
            }
        }

        $msg = 'Failed to update password. Please try again.';
        if ($this->isAjax()) {
            $this->json(['success' => false, 'message' => $msg], 500);
        } else {
            set_flash('error', $msg);
            redirect('/profile');
        }
    }

    // Upload Profile Avatar (AJAX preferred)
    public function uploadAvatar(): void {
        $this->validateCsrf();
        $userId = (int)$_SESSION['user']['id'];
        
        $appConfig = require ROOT_PATH . 'config' . DS . 'app.php';
        $uploadConfig = $appConfig['uploads'];

        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'message' => 'No file uploaded or error occurred during upload.'], 400);
        }

        $file = $_FILES['profile_image'];

        // Size Check
        if ($file['size'] > $uploadConfig['max_size']) {
            $this->json(['success' => false, 'message' => 'File size exceeds maximum limit of 2MB.'], 422);
        }

        // Mimetype Check using Fileinfo extension
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, $uploadConfig['allowed_types'])) {
            $this->json(['success' => false, 'message' => 'Invalid file format. Only JPG, JPEG, PNG, and WEBP files are allowed.'], 422);
        }

        $extension = $uploadConfig['allowed_types'][$mimeType];

        // Sanitize and generate unique filename to prevent path traversal & namespace collisions
        $newFilename = md5(uniqid((string)microtime(true), true)) . '.' . $extension;

        // Ensure upload path exists and is writeable
        $uploadDir = $uploadConfig['profile_path'];
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $targetFile = $uploadDir . $newFilename;

        // Fetch current profile image
        $user = $this->userModel->findById($userId);

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Delete old avatar from folder if it exists
            if ($user && $user['profile_image']) {
                $oldFile = $uploadDir . $user['profile_image'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Save new filename in DB
            $this->userModel->update($userId, ['profile_image' => $newFilename]);

            // Update session
            $_SESSION['user']['profile_image'] = $newFilename;

            $this->json([
                'success' => true,
                'message' => 'Profile image uploaded successfully!',
                'avatar_url' => get_avatar_url($newFilename)
            ]);
        }

        $this->json(['success' => false, 'message' => 'Failed to save uploaded file. Please try again.'], 500);
    }

    // Render general settings page
    public function settings(): void {
        $currentUser = $_SESSION['user'];
        $profile = $this->userModel->findById((int)$currentUser['id']);
        
        $this->view('profile/settings', [
            'title'   => 'System Settings | ' . APP_NAME,
            'profile' => $profile
        ]);
    }

    // Save general settings (simulated)
    public function saveSettings(): void {
        $this->validateCsrf();
        set_flash('success', 'General system settings saved successfully!');
        redirect('/settings');
    }
}
