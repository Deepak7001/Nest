<?php
header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *"); // Allows all origins
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



// Database connection parameters
$servername = "localhost:3306";
$username = "root";
$password = "Deepak@#17sql";
$dbname = "nest";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Return a JSON response for connection error
    header('Content-Type: application/json');
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the JSON POST data
$data = json_decode(file_get_contents('php://input'), true);


if (!empty($data) && isset($data['cart'])) {
    foreach ($data['cart'] as $item) {
        $itemName = $item['name'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        // Check if item already exists
        $checkQuery = "SELECT id, quantity FROM cartItems WHERE items_name = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $itemName);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Update quantity if item exists
            $row = $result->fetch_assoc();
            $newQuantity =  $quantity;
            $updateQuery = "UPDATE cartItems SET quantity = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $newQuantity, $row['id']);
            $updateStmt->execute();
        } else {
            // Insert new item if not exists
            $insertStmt = $conn->prepare("INSERT INTO cartItems(items_name, quantity, price) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sii", $itemName, $quantity, $price);
            $insertStmt->execute();
        }
    }

    // Return a JSON response for successful operation
    echo json_encode(['success' => 'Cart data saved successfully']);
} else {
    // Return a JSON response if no data is received
    
    echo json_encode(['error' => 'No data received']);
}

// Close the database connection
$conn->close();
?>
