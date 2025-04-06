<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$dbname = 'tros'; // Replace with your database name
$username = 'root'; // Replace with your username
$password = ''; // Replace with your password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT id, name, price, type, colour, rating, short_description, long_description, picture, tags FROM technical_hardware");
    $stmt->execute();
    
    // Fetch all results
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *'); // For CORS if needed
    echo json_encode([
        'status' => 'success',
        'data' => $products,
        'message' => 'Products retrieved successfully'
    ]);

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
    // Return detailed error response for other issues
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