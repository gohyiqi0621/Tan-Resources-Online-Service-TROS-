<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$dbname = 'tros'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare and execute query - Added price column
    $stmt = $pdo->prepare("SELECT id, name, type, colour, rating, tags, price FROM technical_hardware ORDER BY id");
    $stmt->execute();
    
    // Fetch all results
    $hardware = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if results are empty
    if (empty($hardware)) {
        // Return empty array with success status
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); // For CORS if needed
        echo json_encode([
            'status' => 'success',
            'data' => [],
            'message' => 'No hardware found'
        ]);
    } else {
        // Return results as JSON
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); // For CORS if needed
        echo json_encode([
            'status' => 'success',
            'data' => $hardware,
            'message' => 'Hardware data retrieved successfully'
        ]);
    }

} catch (PDOException $e) {
    // Return detailed error response for database issues
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *'); // For CORS if needed
    echo json_encode([
        'status' => 'error',
        'error' => 'Database error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Exception $e) {
    // Catch any other errors
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *'); // For CORS if needed
    echo json_encode([
        'status' => 'error',
        'error' => 'General error: ' . $e->getMessage()
    ]);
} finally {
    // Close the PDO connection
    $pdo = null;
}
?>