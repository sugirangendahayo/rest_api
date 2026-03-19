# PHP REST API

A professional REST API built with PHP that demonstrates all HTTP methods (GET, POST, PUT, PATCH, DELETE) with proper error handling, database integration, and security features.

## Features

- **CRUD Operations**: Complete Create, Read, Update, Delete functionality
- **Database Integration**: MySQL database with PDO for security
- **Error Handling**: Comprehensive error handling with proper HTTP status codes
- **CORS Support**: Cross-Origin Resource Sharing enabled
- **Input Validation**: Sanitization and validation of user input
- **Search Functionality**: Search users by name or email
- **Professional Structure**: Object-oriented programming with separate classes

## Setup

### 1. Database Setup

Import the `database.sql` file into your MySQL database:

```bash
mysql -u root -p < database.sql
```

### 2. Configuration

Update the database credentials in `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'rest_api_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Web Server

Place the files in your web server directory (Apache/Nginx) or use PHP's built-in server:

```bash
php -S localhost:8000
```

## API Endpoints

### Base URL
```
http://localhost/api-project/api.php
```

### HTTP Methods

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api.php` | Get all users |
| GET | `/api.php/{id}` | Get single user |
| GET | `/api.php?search={query}` | Search users |
| POST | `/api.php` | Create new user |
| PUT | `/api.php/{id}` | Update entire user |
| PATCH | `/api.php/{id}` | Partial update user |
| DELETE | `/api.php/{id}` | Delete user |

## API Usage Examples

### Get All Users
```bash
curl -X GET http://localhost/api-project/api.php
```

### Get Single User
```bash
curl -X GET http://localhost/api-project/api.php/1
```

### Create User
```bash
curl -X POST http://localhost/api-project/api.php \
  -H "Content-Type: application/json" \
  -d '{"name": "John Doe", "email": "john@example.com"}'
```

### Update User (PUT)
```bash
curl -X PUT http://localhost/api-project/api.php/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "John Updated", "email": "john.updated@example.com"}'
```

### Partial Update (PATCH)
```bash
curl -X PATCH http://localhost/api-project/api.php/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "John Smith"}'
```

### Delete User
```bash
curl -X DELETE http://localhost/api-project/api.php/1
```

### Search Users
```bash
curl -X GET "http://localhost/api-project/api.php?search=john"
```

## Response Format

### Success Response
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2023-01-01 12:00:00"
}
```

### Error Response
```json
{
    "error": "Not Found",
    "message": "User not found"
}
```

## HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid input |
| 404 | Not Found - Resource not found |
| 405 | Method Not Allowed - Unsupported HTTP method |
| 500 | Internal Server Error - Server error |

## File Structure

```
rest_api/
├── config.php      # Database configuration and settings
├── User.php         # User model class
├── api.php          # Main API endpoint
├── database.sql     # Database schema and sample data
├── test_api.php     # HTML test interface
└── README.md        # Documentation
```

## Testing

Use the provided `test_api.php` file to test all API endpoints through a web interface:

1. Open `http://localhost/api-project/test_api.php` in your browser
2. Use the interface to test different API operations
3. View responses and status codes in real-time

## Security Features

- **SQL Injection Prevention**: Uses PDO prepared statements
- **XSS Protection**: Input sanitization with `htmlspecialchars()`
- **CORS Configuration**: Proper cross-origin resource sharing setup
- **Error Handling**: Secure error messages without exposing sensitive information

## Database Schema

### Users Table
- `id` (INT, Primary Key, Auto-increment)
- `name` (VARCHAR(100), Not Null)
- `email` (VARCHAR(100), Not Null, Unique)
- `created_at` (TIMESTAMP, Default: Current Timestamp)
- `updated_at` (TIMESTAMP, Auto-update)

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Web server (Apache, Nginx, or PHP built-in server)
- PDO extension enabled

## License

This project is open source and available under the MIT License.
