<?php
/**
 * User Model
 */

class User extends Model {
    
    // Find user by ID
    public function findById(int $id): ?array {
        $user = $this->fetch(
            "SELECT u.*, r.role_name FROM users u 
             INNER JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ?", 
            [$id]
        );
        return $user ? $user : null;
    }

    // Find user by Email
    public function findByEmail(string $email): ?array {
        $user = $this->fetch(
            "SELECT u.*, r.role_name FROM users u 
             INNER JOIN roles r ON u.role_id = r.id 
             WHERE u.email = ?", 
            [$email]
        );
        return $user ? $user : null;
    }

    // Find user by Username
    public function findByUsername(string $username): ?array {
        $user = $this->fetch(
            "SELECT u.*, r.role_name FROM users u 
             INNER JOIN roles r ON u.role_id = r.id 
             WHERE u.username = ?", 
            [$username]
        );
        return $user ? $user : null;
    }

    // Check duplicate email
    public function isDuplicateEmail(string $email, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $count = $this->fetchColumn(
                "SELECT COUNT(*) FROM users WHERE email = ? AND id != ?",
                [$email, $excludeId]
            );
        } else {
            $count = $this->fetchColumn(
                "SELECT COUNT(*) FROM users WHERE email = ?",
                [$email]
            );
        }
        return (int)$count > 0;
    }

    // Check duplicate username
    public function isDuplicateUsername(string $username, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $count = $this->fetchColumn(
                "SELECT COUNT(*) FROM users WHERE username = ? AND id != ?",
                [$username, $excludeId]
            );
        } else {
            $count = $this->fetchColumn(
                "SELECT COUNT(*) FROM users WHERE username = ?",
                [$username]
            );
        }
        return (int)$count > 0;
    }

    // Create a new user
    public function create(array $data): int {
        $sql = "INSERT INTO users (role_id, full_name, username, email, phone, password, profile_image, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->query($sql, [
            $data['role_id'],
            $data['full_name'],
            $data['username'],
            $data['email'],
            $data['phone'] ?? null,
            $data['password'],
            $data['profile_image'] ?? null,
            $data['status'] ?? 'active'
        ]);

        return (int)$this->db->lastInsertId();
    }

    // Update user profile info / details
    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "`$key` = ?";
            $params[] = $value;
        }

        $params[] = $id; // For WHERE id = ?
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() >= 0; // Return true even if same data is submitted
    }

    // Delete user
    public function delete(int $id): bool {
        $stmt = $this->query("DELETE FROM users WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }

    // Fetch users list (with searching, filtering, sorting and pagination)
    public function getList(array $options): array {
        $search = $options['search'] ?? '';
        $status = $options['status'] ?? '';
        $sortField = $options['sort'] ?? 'id';
        $sortOrder = strtoupper($options['order'] ?? 'ASC');
        $limit = (int)($options['limit'] ?? 10);
        $offset = (int)($options['offset'] ?? 0);

        // Validate sort field
        $allowedSort = ['id', 'full_name', 'username', 'email', 'status', 'created_at'];
        if (!in_array($sortField, $allowedSort)) {
            $sortField = 'id';
        }
        // Validate sort order
        if ($sortOrder !== 'ASC' && $sortOrder !== 'DESC') {
            $sortOrder = 'ASC';
        }

        $sql = "SELECT u.id, u.full_name, u.username, u.email, u.phone, u.status, u.created_at, u.profile_image, r.role_name 
                FROM users u 
                INNER JOIN roles r ON u.role_id = r.id 
                WHERE 1=1";
        
        $params = [];

        if ($search) {
            $sql .= " AND (u.full_name LIKE ? OR u.username LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $searchParam = "%$search%";
            array_push($params, $searchParam, $searchParam, $searchParam, $searchParam);
        }

        if ($status) {
            $sql .= " AND u.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY u.$sortField $sortOrder LIMIT $limit OFFSET $offset";

        return $this->fetchAll($sql, $params);
    }

    // Count users for pagination
    public function getCount(string $search = '', string $status = ''): int {
        $sql = "SELECT COUNT(*) FROM users u WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (u.full_name LIKE ? OR u.username LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $searchParam = "%$search%";
            array_push($params, $searchParam, $searchParam, $searchParam, $searchParam);
        }

        if ($status) {
            $sql .= " AND u.status = ?";
            $params[] = $status;
        }

        return (int)$this->fetchColumn($sql, $params);
    }

    // Get statistics widgets data
    public function getStatistics(): array {
        $stats = [];
        
        $stats['total_users'] = (int)$this->fetchColumn("SELECT COUNT(*) FROM users");
        $stats['admins']      = (int)$this->fetchColumn("SELECT COUNT(*) FROM users WHERE role_id = 1");
        $stats['users']       = (int)$this->fetchColumn("SELECT COUNT(*) FROM users WHERE role_id = 2");
        $stats['active']      = (int)$this->fetchColumn("SELECT COUNT(*) FROM users WHERE status = 'active'");
        $stats['inactive']    = (int)$this->fetchColumn("SELECT COUNT(*) FROM users WHERE status = 'inactive'");
        $stats['suspended']   = (int)$this->fetchColumn("SELECT COUNT(*) FROM users WHERE status = 'suspended'");
        
        // Fetch 5 most recent users
        $stats['recent_users'] = $this->fetchAll(
            "SELECT u.id, u.full_name, u.username, u.email, u.status, u.created_at, u.profile_image, r.role_name 
             FROM users u 
             INNER JOIN roles r ON u.role_id = r.id 
             ORDER BY u.id DESC LIMIT 5"
        );

        return $stats;
    }

    // Calculate user profile completion percentage
    public function getProfileCompletion(array $user): int {
        $fields = ['full_name', 'username', 'email', 'phone', 'profile_image'];
        $filledCount = 0;
        foreach ($fields as $field) {
            if (!empty($user[$field])) {
                $filledCount++;
            }
        }
        return (int)round(($filledCount / count($fields)) * 100);
    }

    // Get daily registration trend statistics (last 7 days)
    public function getRegistrationTrend(): array {
        $sql = "SELECT DATE(created_at) as reg_date, COUNT(*) as user_count 
                FROM users 
                GROUP BY DATE(created_at) 
                ORDER BY reg_date ASC 
                LIMIT 7";
        return $this->fetchAll($sql);
    }
}
