<?php
/**
 * Admin User CRUD Controller
 */

class AdminController extends Controller {

    private User $userModel;
    private Role $roleModel;
    private Token $tokenModel;

    public function __construct() {
        $this->userModel = new User();
        $this->roleModel = new Role();
        $this->tokenModel = new Token();
    }

    // Render admin CRUD panel view
    public function index(): void {
        $roles = $this->roleModel->all();
        $this->view('admin/index', [
            'title' => 'User Management | ' . APP_NAME,
            'roles' => $roles
        ]);
    }

    // AJAX: Fetch paginated, sorted, and filtered user list
    public function listUsers(): void {
        $request = $this->getRequestData();

        $search = $request['search'] ?? '';
        $status = $request['status'] ?? '';
        $sort   = $request['sort'] ?? 'id';
        $order  = $request['order'] ?? 'asc';
        $limit  = (int)($request['limit'] ?? 10);
        $page   = (int)($request['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        $options = [
            'search' => $search,
            'status' => $status,
            'sort'   => $sort,
            'order'  => $order,
            'limit'  => $limit,
            'offset' => $offset
        ];

        // Fetch records
        $users = $this->userModel->getList($options);
        $totalRecords = $this->userModel->getCount($search, $status);
        $totalPages = ceil($totalRecords / $limit);

        // Sanitize response array elements
        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'id'            => (int)$user['id'],
                'full_name'     => e($user['full_name']),
                'username'      => e($user['username']),
                'email'         => e($user['email']),
                'phone'         => e($user['phone']),
                'status'        => e($user['status']),
                'role_name'     => e($user['role_name']),
                'profile_image' => get_avatar_url($user['profile_image']),
                'created_at'    => date('Y-m-d H:i', strtotime($user['created_at']))
            ];
        }

        $this->json([
            'success' => true,
            'data' => $formattedUsers,
            'pagination' => [
                'total_records' => $totalRecords,
                'total_pages'   => $totalPages,
                'current_page'  => $page,
                'limit'         => $limit
            ]
        ]);
    }

    // AJAX: Fetch single user details for viewing/editing
    public function show(array $params): void {
        $id = (int)($params['id'] ?? 0);
        $user = $this->userModel->findById($id);

        if ($user) {
            // Unset password for safety
            unset($user['password']);
            $user['profile_image_url'] = get_avatar_url($user['profile_image']);
            
            $this->json([
                'success' => true,
                'data' => $user
            ]);
        }

        $this->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    // AJAX: Create a new user record
    public function store(): void {
        $this->validateCsrf();
        $data = $this->getRequestData();

        $fullName = $data['full_name'] ?? '';
        $username = $data['username'] ?? '';
        $email    = $data['email'] ?? '';
        $phone    = $data['phone'] ?? '';
        $roleId   = (int)($data['role_id'] ?? 2);
        $status   = $data['status'] ?? 'active';
        $password = $data['password'] ?? '';

        $errors = [];

        // Full Name Validation
        if (empty($fullName)) {
            $errors[] = 'Full name is required.';
        } elseif (strlen($fullName) < 3 || strlen($fullName) > 100) {
            $errors[] = 'Full name must be between 3 and 100 characters.';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullName)) {
            $errors[] = 'Full name can only contain letters and spaces.';
        }

        // Username Validation
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = 'Username must be between 3 and 50 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Username can only contain alphanumeric characters and underscores.';
        } elseif ($this->userModel->isDuplicateUsername($username)) {
            $errors[] = 'Username is already taken.';
        }

        // Email Validation
        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address format.';
        } elseif ($this->userModel->isDuplicateEmail($email)) {
            $errors[] = 'Email address is already registered.';
        }

        // Phone Validation (Optional)
        if (!empty($phone) && !preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            $errors[] = 'Invalid phone number format.';
        }

        // Role ID validation
        if ($roleId !== 1 && $roleId !== 2) {
            $errors[] = 'Invalid role selection.';
        }

        // Status validation
        if (!in_array($status, ['active', 'inactive', 'suspended'])) {
            $errors[] = 'Invalid status selected.';
        }

        // Password strength validation
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).';
        }

        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => implode('<br>', $errors)], 422);
        }

        // Save new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userModel->create([
            'role_id'       => $roleId,
            'full_name'     => $fullName,
            'username'      => $username,
            'email'         => $email,
            'phone'         => !empty($phone) ? $phone : null,
            'password'      => $hashedPassword,
            'status'        => $status,
            'profile_image' => null
        ]);

        if ($userId) {
            $this->json(['success' => true, 'message' => 'User created successfully!']);
        }

        $this->json(['success' => false, 'message' => 'Failed to create user. Please try again.'], 500);
    }

    // AJAX: Update an existing user record
    public function update(array $params): void {
        $this->validateCsrf();
        $id = (int)($params['id'] ?? 0);

        $user = $this->userModel->findById($id);
        if (!$user) {
            $this->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $data = $this->getRequestData();

        $fullName = $data['full_name'] ?? '';
        $username = $data['username'] ?? '';
        $email    = $data['email'] ?? '';
        $phone    = $data['phone'] ?? '';
        $roleId   = (int)($data['role_id'] ?? 2);
        $status   = $data['status'] ?? 'active';
        $password = $data['password'] ?? ''; // Optional on update

        $errors = [];

        // Verify Full Name
        if (empty($fullName)) {
            $errors[] = 'Full name is required.';
        } elseif (strlen($fullName) < 3 || strlen($fullName) > 100) {
            $errors[] = 'Full name must be between 3 and 100 characters.';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullName)) {
            $errors[] = 'Full name can only contain letters and spaces.';
        }

        // Verify Username
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = 'Username must be between 3 and 50 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Username can only contain alphanumeric characters and underscores.';
        } elseif ($this->userModel->isDuplicateUsername($username, $id)) {
            $errors[] = 'Username is already taken.';
        }

        // Verify Email
        if (empty($email)) {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address format.';
        } elseif ($this->userModel->isDuplicateEmail($email, $id)) {
            $errors[] = 'Email address is already in use.';
        }

        // Verify Phone (Optional)
        if (!empty($phone) && !preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            $errors[] = 'Invalid phone number format.';
        }

        // Role ID validation
        if ($roleId !== 1 && $roleId !== 2) {
            $errors[] = 'Invalid role selection.';
        }

        // Status validation
        if (!in_array($status, ['active', 'inactive', 'suspended'])) {
            $errors[] = 'Invalid status selected.';
        }

        // Verify password strength if user changed it
        if (!empty($password)) {
            if (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters.';
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).';
            }
        }

        // Check if admin is trying to modify their own status or role to user
        $currentAdminId = (int)$_SESSION['user']['id'];
        if ($id === $currentAdminId) {
            if ($roleId !== 1) {
                $errors[] = 'You cannot demote yourself from Admin role.';
            }
            if ($status !== 'active') {
                $errors[] = 'You cannot deactivate or suspend yourself.';
            }
        }

        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => implode('<br>', $errors)], 422);
        }

        $updateData = [
            'role_id'   => $roleId,
            'full_name' => $fullName,
            'username'  => $username,
            'email'     => $email,
            'phone'     => !empty($phone) ? $phone : null,
            'status'    => $status
        ];

        // Hash and append password if modified
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $updated = $this->userModel->update($id, $updateData);

        if ($updated) {
            // If updating current user's profile, update session too
            if ($id === $currentAdminId) {
                $_SESSION['user']['full_name'] = $fullName;
                $_SESSION['user']['username']  = $username;
                $_SESSION['user']['email']     = $email;
            }

            // Revoke active sessions for updated user if password was modified
            if (!empty($password)) {
                $this->tokenModel->deleteUserTokens($id);
            }

            $this->json(['success' => true, 'message' => 'User updated successfully!']);
        }

        $this->json(['success' => false, 'message' => 'Failed to update user details.'], 500);
    }

    // AJAX: Delete a user record
    public function delete(array $params): void {
        $this->validateCsrf();
        $id = (int)($params['id'] ?? 0);

        // Guard against self-deletion
        $currentAdminId = (int)$_SESSION['user']['id'];
        if ($id === $currentAdminId) {
            $this->json(['success' => false, 'message' => 'Access Denied. You cannot delete your own account.'], 403);
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $this->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Delete user record (Cascades user_tokens deletion)
        $deleted = $this->userModel->delete($id);

        if ($deleted) {
            // Delete actual uploaded profile image from storage if existing
            if ($user['profile_image']) {
                $appConfig = require ROOT_PATH . 'config' . DS . 'app.php';
                $oldFile = $appConfig['uploads']['profile_path'] . $user['profile_image'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $this->json(['success' => true, 'message' => 'User account deleted successfully!']);
        }

        $this->json(['success' => false, 'message' => 'Failed to delete user account.'], 500);
    }

    // Render roles list view (UI Ready / RBAC Overview)
    public function roles(): void {
        $roleModel = new Role();
        // Since there is a Role model, let's load role data if available or prepare it
        $rolesList = $roleModel->all();
        
        $this->view('admin/roles', [
            'title'     => 'Roles &amp; Permissions | ' . APP_NAME,
            'rolesList' => $rolesList
        ]);
    }
}
