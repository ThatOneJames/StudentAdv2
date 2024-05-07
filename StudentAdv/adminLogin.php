<?php
session_start();
require('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $employeeid = $_POST['employeeid'];
    $password = $_POST['password'];

    // Perform user authentication (compare credentials with the database)
    // You need to implement this part to check the username and password against your user database.

    // Example:
    $query = "SELECT * FROM admin WHERE employeeid = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $employeeid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Authentication successful
        // Redirect the user to their dashboard or another page
        $_SESSION['employeeid'] = $employeeid;
        header("Location: Home.php");
        exit();
    } else {
        // Authentication failed
        $loginError = "Invalid username or password. Please try again.";
    }

    $stmt->close();
}
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

	
	 <div class="body">
        <br/><h2>Admin Login Page</h2><hr class="xsmall">
        <form action="adminLogin.php" method="POST">
            <table align="center">
                <tr>
                    <td> Enter Employee ID:</td>
                    <td><input type="text" name="employeeid" required="required" /></td>
                </tr>
                <tr>
                    <td> Enter Password:</td>
                    <td><input type="password" name="password" required="required" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="login" value="Login" /></td>
                </tr>
            </table>
            <center><a href="adminRegister.php">Don't have an account? Register here!</a></center>
        </form>
        <?php
        // Display the login error message if it exists
        if (isset($loginError)) {
            echo "<p>{$loginError}</p>";
        }
        ?>
    </div>
</body>
</html>