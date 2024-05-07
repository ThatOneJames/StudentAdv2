<?php
session_start();
require('config.php'); // Include the database connection configuration

if (isset($_GET['studentno'])) {
    $studentno = $_GET['studentno'];

    // Perform validation and delete the prospect record from the database
    // You need to implement this part to validate and delete the prospect record.

    // Example: Assuming 'adviser' is the correct table name
    $query = "DELETE FROM adviser WHERE studentno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $studentno); // Assuming 'studentno' is an integer

    if ($stmt->execute()) {
        // Check if any rows were affected by the deletion
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $conn->close();
            header("Location: Apply.php"); // Redirect to the Apply.php page after deleting
            exit();
        } else {
            echo "No records found for the provided student number.";
            exit();
        }
    } else {
        echo "Error executing the deletion query: " . $conn->error;
        exit();
    }
} else {
    echo "Student number not provided. ";
    echo "Debug: GET parameters: ";
    print_r($_GET);
    exit();
}
?>
