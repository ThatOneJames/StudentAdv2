<?php
session_start();
require('config.php');

// Check if the form for accepting an applicant is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_applicant'])) {
    // Retrieve the applicant ID from the form submission
    $applicant_id = $_POST['applicant_id'];

    // Check if the session variable for the adviser's student number is set
    if (isset($_SESSION['studentno'])) {
        // Get the adviser's student number from the session
        $studentno = $_SESSION['studentno'];

        // Update the applicant's status to "Accepted" and set the adviser's student number as the "accepted_by" value
        $update_query = "UPDATE applicant SET status = 'Accepted', accepted_by = '$studentno' WHERE id = $applicant_id";

        // Execute the update query
        if (mysqli_query($conn, $update_query)) {
            // Check if any rows were affected
            if (mysqli_affected_rows($conn) > 0) {
                // Redirect back to the applications page
                header("Location: Applications.php");
                exit(); // Ensure no further code execution after the redirect
            } else {
                echo "No rows updated.";
            }
        } else {
            // Print an error message if the update query fails
            echo "Error updating applicant record: " . mysqli_error($conn);
        }
    } else {
        // Print an error message if the session variable is not set
        echo "Session variable 'studentno' not set.";
    }
} else {
    // Print an error message if the form is not submitted
    echo "Form not submitted.";
}
?>
