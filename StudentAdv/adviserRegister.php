<?php
	session_start();
	require('config.php');
	?>
<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
		<?php include "CSS.css" ?>
	</style>
        <title> Home </title>
    
<body>
<div id="header">
    <?php
    if (isset($_SESSION['studentno']) || isset($_SESSION['employeeid'])) {
        echo "<div style='float:right;'>Welcome, ";
        if (isset($_SESSION['studentno'])) {
            // Display student's name
            $name_query = mysqli_query($conn, "SELECT firstname FROM adviser WHERE studentno = '" . $_SESSION['studentno'] . "'");
            $position = "Adviser";
        } elseif (isset($_SESSION['employeeid'])) {
            // Display employee's name
            $name_query = mysqli_query($conn, "SELECT firstname FROM admin WHERE employeeid = '" . $_SESSION['employeeid'] . "'");
            $position = "Admin";
        }
        $name_result = mysqli_fetch_assoc($name_query);
        echo "<strong>" . $position . " " . $name_result['firstname'] . "</strong>";
        echo "</div>";
    }
    ?>
    <center>
        <h1>MAPUA UNIVERSITY</h1>
        <br/>
        <h1>COUNCIL OF STUDENT ADVISING</h1>
        <hr class="medium">
        <a href="Home.php" class="header">Home</a> |
        <a href="Announcements.php" class="header">Events/Announcements</a> |
        <div class="dropdown">
            <?php
			  if (isset($_SESSION['employeeid']))
			  {
				echo "
				<button class=\"dropbtn\">Register</button> |
            <div class=\"dropdown-content\">
                <a href=\"adviserRegister.php\">Adviser</a>
                <a href=\"adminRegister.php\">Admin</a>
				</div>";
				}
				?>

        </div>
        <div class="dropdown">
            <button class="dropbtn">Login</button> |
            <div class="dropdown-content">
                <?php
                if (isset($_SESSION['studentno']) || isset($_SESSION['employeeid'])) {
                    echo "<a href=\"logout.php\">Logout</a>";
                } else {
                    echo "<a href=\"adviserLogin.php\">Adviser</a>";
                    echo "<a href=\"adminLogin.php\">Admin</a>";
                }
                ?>
            </div>
        </div>
        <a href="Apply.php" class="header">Apply</a>
        <?php
        // Check if either session variable is set
        if (isset($_SESSION['studentno']) || isset($_SESSION['employeeid'])) {
            echo " | <a href=\"Applications.php\" class=\"header\">Applications</a>";
        }
        ?>
        <hr class="medium">
		</head>
    </center>
</div><br/>

	
	<div class=body><br/>
		<h2> Adviser Registration Page </h2>
		<form action="adviserRegister.php" method="POST">
			<table align=center>
				<tr>
					<td> Enter First Name:</td>
					<td><input type="text" name="firstname" required="required" /></td>
				</tr>
				<tr>
					<td> Enter Middle Name:</td>
					<td><input type="text" name="middlename" required="required" /></td>
				</tr>
				<tr>
					<td> Enter Last Name:</td>
					<td><input type="text" name="lastname" required="required" /></td>
				</tr>
				<tr>
					<td> Enter Student ID Number:</td>
					<td><input type="text" name="studentno" required="required" /></td>
				</tr>
				<tr>
					<td> Enter Program:</td>
					<td><input type="text" name="program" required="required" /></td>
				</tr>
				<tr>
					<td> Enter Password:</td>
					<td><input type="password" name="password" required="required" /></td>
				</tr>
				<tr>
					<td colspan=2 align=center><input type="submit" name="register" value="Register"/></td>
				</tr>
			</table>
			<center><a href="adviserLogin.php">Already have an account? Login Here!</a></center>
	</div>
</body>
</html>

<?php
require('config.php');
// Check if the form is submitted
if (isset($_POST['register'])) {
    // Retrieve user inputs
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $studentno = $_POST['studentno'];
    $program = $_POST['program'];
    $password = $_POST['password'];

    // SQL query to insert user data into the database
    $sql = "INSERT INTO adviser (firstname, middlename, lastname, studentno, program, password) VALUES (?, ?, ?, ?, ?, ?)";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters and execute the statement
    $stmt->bind_param("ssssss", $firstname, $middlename, $lastname, $studentno, $program, $password);
    
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
