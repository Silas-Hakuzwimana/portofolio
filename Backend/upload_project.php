<?php
include 'test.php'; // Assuming 'test.php' contains your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get project details from POST request
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = 'projects-image/' . $imageName; // Destination path for the image

        // Move the uploaded image to the 'projects-image' folder
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Prepare SQL query to insert project data into the database
            $sql = "INSERT INTO projects (title, description, image, link) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $title, $description, $imagePath, $link);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Project added successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to add project."]);
            }

            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to upload image."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No image uploaded or error in upload."]);
    }
}

$conn->close();
?>
