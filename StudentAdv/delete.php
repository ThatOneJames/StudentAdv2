<?php
session_start();
require('config.php'); // Include the database connection configuration

if (isset($_POST['applicant_id'])) {
    $id = $_POST['applicant_id'];

    // Perform validation and delete the prospect record from the database
    // You need to implement this part to validate and delete the prospect record.

    // Example:
    $query = "DELETE FROM applicant WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: Applications.php"); // Redirect to the applications page after deleting
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID not provided.";
    exit();
}
?>
