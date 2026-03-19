<?php
require_once 'config.php';
require_once 'User.php';

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Parse URL to get resource and ID
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = dirname($_SERVER['PHP_SELF']);
$path = str_replace($base_path, '', $request_uri);
$path_parts = explode('/', trim($path, '/'));

$resource = $path_parts[0] ?? '';
$id = isset($path_parts[1]) ? (int)$path_parts[1] : null;

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize user object
$user = new User($db);

// Get request data
$input_data = json_decode(file_get_contents("php://input"), true) ?? [];

try {
    switch($method) {
        case "GET":
            if ($id) {
                // Get single user
                $user->id = $id;
                if($user->readOne()) {
                    http_response_code(200);
                    echo json_encode([
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'User not found']);
                }
            } else {
                // Get all users or search
                if(isset($_GET['search'])) {
                    $keywords = htmlspecialchars(strip_tags($_GET['search']));
                    $stmt = $user->search($keywords);
                    $users_arr = [];
                    
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $users_arr[] = $row;
                    }
                    
                    http_response_code(200);
                    echo json_encode($users_arr);
                } else {
                    $stmt = $user->read();
                    $users_arr = [];
                    
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $users_arr[] = $row;
                    }
                    
                    http_response_code(200);
                    echo json_encode($users_arr);
                }
            }
            break;

        case "POST":
            // Create new user
            if(!empty($input_data['name']) && !empty($input_data['email'])) {
                $user->name = $input_data['name'];
                $user->email = $input_data['email'];
                
                if($user->create()) {
                    http_response_code(201);
                    echo json_encode([
                        'message' => 'User created successfully',
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ]
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'User creation failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Name and email are required']);
            }
            break;

        case "PUT":
            // Update entire user
            if($id && !empty($input_data['name']) && !empty($input_data['email'])) {
                $user->id = $id;
                $user->name = $input_data['name'];
                $user->email = $input_data['email'];
                
                if($user->update()) {
                    http_response_code(200);
                    echo json_encode([
                        'message' => 'User updated successfully',
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ]
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'User update failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'User ID, name, and email are required']);
            }
            break;

        case "PATCH":
            // Partial update user
            if($id && !empty($input_data)) {
                $user->id = $id;
                
                if($user->patch($input_data)) {
                    $user->readOne(); // Get updated data
                    http_response_code(200);
                    echo json_encode([
                        'message' => 'User partially updated successfully',
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ]
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'User update failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'User ID and at least one field are required']);
            }
            break;

        case "DELETE":
            // Delete user
            if($id) {
                $user->id = $id;
                
                if($user->delete()) {
                    http_response_code(200);
                    echo json_encode(['message' => 'User deleted successfully']);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'User deletion failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'User 1 deleted sucessfully']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
            break;
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $e->getMessage()
    ]);
}
?>
