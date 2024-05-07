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
    <title>Home</title>
    <script>
	
        function validateForm() {
            var phone = document.forms["applyForm"]["phone"].value;
            var email = document.forms["applyForm"]["email"].value;
            
            if (phone.length !== 11) {
                alert("Phone number must be 11 digits");
                return false; // Prevent form submission
            }
            
            if (!validateEmail(email)) {
                alert("Invalid email format");
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }

        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }
    </script>

    
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
            <hr class="medium">
        </center>
    </div><br/>
	
	<div class="body">
		<table align="center">
			<caption>Student Advisers</caption>
			<tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Program</th>
            
            <?php
			require('config.php'); // Connect to server

			if(isset($_SESSION['employeeid'])) {
				echo "<th>Delete Adviser</th>";
			}

			$query = mysqli_query($conn, "SELECT * FROM adviser"); // SQL Query

			while ($row = mysqli_fetch_array($query)) {
				echo "<tr>";
				echo '<td align="center">' . $row['firstname'] . "</td>";
				echo '<td align="center">' . $row['lastname'] . "</td>";
				echo '<td align="center">' . $row['program'] . "</td>";
			if (isset($_SESSION['employeeid'])) {
				echo '<td align="center"><a href="deleteAdviser.php?studentno='. $row['studentno'] .'">delete</a> </td>';
			}
			echo "</tr>";
			}
			?>

        </table>
	</div><br/><br/>

	<div class="body">
		<form action="Apply.php" method="POST" name="applyForm" onsubmit="return validateForm()">
			<table align="center">
				<tr>
					<td>Name:</td>
					<td><input type="text" name="name" required="required"></td>
					<td>Email:</td>
					<td><input type="text" name="email" required="required"></td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td><input type="tel" name="phone" required="required"></td>
					<td>Specialization</td>
					<td><input type="text" name="spec"></td>
				</tr>
				<tr>
					<td>Preferred Adviser:</td>
					<td><input type="text" placeholder="if applicable" name="pref_mentor"></td>
					<td>
						<select name="pref_day">
							<option value="disabled" selected hidden>Choose Preferred Day</option>
							<option value="mon">Monday</option>
							<option value="tue">Tuesday</option>
							<option value="wed">Wednesday</option>
							<option value="thu">Thursday</option>
							<option value="fri">Friday</option>
							<option value="sat">Saturday</option>
						</select>
					</td>
					<td>
						<label for="pref_time">Enter Preferred Time:</label>
						<input type="time" id="time" name="pref_time">
					</td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<input type="submit" name="apply" value="apply">
					</td>
				</tr>
			</table>
		</form>
	</div>
	</head>
</body>
</html>

<!-- PHP code to handle the form submission -->
<?php
require('config.php');

if (isset($_POST['apply'])) {
    // Retrieve user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $spec = $_POST['spec'];
    $pref_mentor = $_POST['pref_mentor'];
    $pref_day = $_POST['pref_day'];
    $pref_time = $_POST['pref_time'];

    // Validate phone number length
    if (strlen($phone) !== 11) {
        echo "Phone number must be 11 digits";
    } else {
        // SQL query to insert user data into the database
        $sql = "INSERT INTO applicant (name, email, phone, spec, pref_mentor, pref_day, pref_time) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind parameters and execute the statement
        $stmt->bind_param("sssssss", $name, $email, $phone, $spec, $pref_mentor, $pref_day, $pref_time);

        if ($stmt->execute()) {
            echo "Data inserted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and database connection
        $stmt->close();
    }

    $conn->close();
}
?>

