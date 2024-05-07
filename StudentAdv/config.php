<?php
    $db_user = "root";
    $db_pass = "";
    $db_name = "advising";

    $conn = mysqli_connect("localhost","root","","advising");
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>