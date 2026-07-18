<?php
/**
 * Role Model
 */

class Role extends Model {
    
    // Fetch all roles
    public function all(): array {
        return $this->fetchAll("SELECT * FROM roles ORDER BY id ASC");
    }

    // Find role by ID
    public function findById(int $id): ?array {
        $role = $this->fetch("SELECT * FROM roles WHERE id = ?", [$id]);
        return $role ? $role : null;
    }
}
