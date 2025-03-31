<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "portfolio_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed!"]));
}else{
    echo "Database connected!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Input sanitization
    $name = filter_var($_POST['name']);
    $phone = filter_var($_POST['phone']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    if (!$email) {
        echo json_encode(["status" => "error", "message" => "Invalid email address!"]);
        exit();
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO messages (name, phone, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $message);

    // Execute and return JSON response
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Message received successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something went wrong, please try again."]);
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
