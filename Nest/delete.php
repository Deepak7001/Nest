<?php

header("Content-Type: application/json");

// Allows all origins
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database connection parameters
$servername = "localhost:3306";
$username = "root";
$password = "Deepak@#17sql";
$dbname = "nest";

// Create connection with MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}


// Retrieve JSON data from the request
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData);

if (isset($data->itemName)) {
    $itemName = $data->itemName;

    //  SQL query to delete the item from the database
    $sql = "DELETE FROM cartitems WHERE items_name = ?";
    $stmt = $conn->prepare($sql);
    
    try {
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        
        echo json_encode(['error' => 'Error deleting item from database: ' . $e->getMessage()]);
    }
} else {
    
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
