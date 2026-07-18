<?php
/**
 * Remember Me Token Model
 */

class Token extends Model {

    // Store a new persistent token
    public function createToken(int $userId, string $selector, string $hashedValidator, string $expiry): bool {
        // Delete any existing tokens for this user first (single session remember limit)
        $this->deleteUserTokens($userId);

        $sql = "INSERT INTO user_tokens (user_id, selector, hashed_validator, expiry) VALUES (?, ?, ?, ?)";
        $stmt = $this->query($sql, [$userId, $selector, $hashedValidator, $expiry]);
        return $stmt->rowCount() > 0;
    }

    // Find token by selector
    public function findTokenBySelector(string $selector): ?array {
        $sql = "SELECT ut.*, u.username, u.email, u.role_id, u.full_name, u.status 
                FROM user_tokens ut 
                INNER JOIN users u ON ut.user_id = u.id 
                WHERE ut.selector = ? AND ut.expiry >= NOW()";
        
        $token = $this->fetch($sql, [$selector]);
        return $token ? $token : null;
    }

    // Delete token by selector
    public function deleteTokenBySelector(string $selector): bool {
        $stmt = $this->query("DELETE FROM user_tokens WHERE selector = ?", [$selector]);
        return $stmt->rowCount() > 0;
    }

    // Delete all tokens for a user (on logout or password change)
    public function deleteUserTokens(int $userId): bool {
        $stmt = $this->query("DELETE FROM user_tokens WHERE user_id = ?", [$userId]);
        return $stmt->rowCount() > 0;
    }

    // Cleanup expired tokens
    public function deleteExpiredTokens(): int {
        $stmt = $this->query("DELETE FROM user_tokens WHERE expiry < NOW()");
        return $stmt->rowCount();
    }
}
