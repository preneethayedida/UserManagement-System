<?php
/**
 * Base Model Class
 */

abstract class Model {
    protected ?PDO $db = null;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Execute query with parameters and return PDOStatement
    protected function query(string $sql, array $params = []): PDOStatement {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Error: " . $e->getMessage() . " | SQL: $sql");
            throw new Exception("A database error occurred during query execution.");
        }
    }

    // Fetch all rows
    protected function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    // Fetch a single row
    protected function fetch(string $sql, array $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    // Fetch column value
    protected function fetchColumn(string $sql, array $params = [], int $colNum = 0) {
        return $this->query($sql, $params)->fetchColumn($colNum);
    }
}
