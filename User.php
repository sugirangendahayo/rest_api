<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create user
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, email) VALUES (:name, :email)";
        
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    // Read all users
    public function read() {
        $query = "SELECT id, name, email, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Read single user
    public function readOne() {
        $query = "SELECT id, name, email, created_at FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }
        
        return false;
    }

    // Update user
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, email = :email WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Partial update user
    public function patch($fields) {
        $set_clause = [];
        $params = [];
        
        foreach($fields as $key => $value) {
            if(property_exists($this, $key) && $key !== 'id') {
                $set_clause[] = "$key = :$key";
                $params[":$key"] = htmlspecialchars(strip_tags($value));
                $this->$key = $value;
            }
        }
        
        if(empty($set_clause)) {
            return false;
        }
        
        $query = "UPDATE " . $this->table_name . " SET " . implode(", ", $set_clause) . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        foreach($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Delete user
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Search users
    public function search($keywords) {
        $query = "SELECT id, name, email, created_at FROM " . $this->table_name . " 
                  WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        
        $stmt->execute();
        
        return $stmt;
    }
}
?>
