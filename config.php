<?php
// Database configuration
define('DB_HOST', 'dpg-d6tr0d75gffc739ba260-a.oregon-postgres.render.com');
define('DB_NAME', 'rest_api_db_r5rl');
define('DB_USER', 'rest_api_db_r5rl_user');
define('DB_PASS', 'Tx8XD8UfWUUZMiDXKYVxoMmpCoUoRUHE');
define('DB_PORT', '5432');

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Set content type to JSON
header("Content-Type: application/json");

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Custom error handler
set_error_handler(function($severity, $message, $file, $line) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $message,
        'file' => $file,
        'line' => $line
    ]);
    exit;
});

// Database connection class
class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";port=" . DB_PORT . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo json_encode([
                'error' => 'Database connection failed',
                'message' => $exception->getMessage()
            ]);
            exit;
        }
        
        return $this->conn;
    }
}
?>
