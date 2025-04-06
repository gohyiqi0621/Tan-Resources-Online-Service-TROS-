<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$sName = "localhost";
$uName = "root";
$pass = "";
$db_name = "tros";

try {
    // Attempt to create a PDO connection and assign to $pdo variable
    $pdo = new PDO("mysql:host=$sName;dbname=$db_name;charset=utf8", $uName, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Set error mode to exception
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
    ]);
} catch (PDOException $e) {
    // Handle connection failure
    die("Connection failed: " . $e->getMessage());
}
?>
