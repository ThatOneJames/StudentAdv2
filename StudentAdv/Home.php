<?php
	session_start();
	require('config.php');
	
	if (isset($_POST['logout'])) {
        session_destroy(); // Destroy the session
        header("Location: Home.php"); // Redirect to the homepage
        exit();
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

	
	<div class=body><br/>
<!-- info taken directly from their official website-->
	<p class=small>Student advising is an essential aspect of the students' experience at Mapúa University. Consequently, the Center for Student Advising (CSA) offers services to support the students in addressing their academic, personal, social, and career adjustment throughout their stay in the university. It aims to help students develop focus in their studies to achieve their goals.</p>

	<p class=small>CSA caters to all students regardless of gender, religion, ethnicity, social and economic status, etc. It aims to ensure that all students have a chance to complete their program of study and succeed in their chosen field. It also provides advising/mentoring to female students, students from low-income families, and PWDs</p>

	<p class=small>Student advising involves Academic Advising, Peer Advising, Developmental Advising, and Life Coaching.</p>

	<p class=small>Students may visit the Center for Student Advising at its satellite offices:</p>
	
	<table align=center>
		<tr><td>OFFICE</td><td>ROOM</td></tr>
		<tr><td>CSA Intramuros Branch</td><td>North 103B<br/> South101B <br/> Southwest 306B</td></tr>
		<tr><td>CSA Makati Branch</td><td>Room 215</td></tr>
	</table>
	</div>
</body>
</html>