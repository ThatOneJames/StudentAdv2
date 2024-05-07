<?php
	session_start();
	require('config.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		$title = $_POST["title"];
		$content = $_POST["content"];
		
		$imagePath = basename($_FILES["image"]["name"]);
		if (!empty($imagePath)) 
			{
				move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
			}
		$conn = new mysqli("localhost", "root", "", "advising");
		
		if ($conn->connect_error) 
			{
				die("Connection failed: " . $conn->connect_error);
			}
		
		if (!empty($_POST["editNoticeId"])) 
			{
				$editNoticeId = $_POST["editNoticeId"];
				$stmt = $conn->prepare("UPDATE announcements SET title = ?, content = ?, image_path = ? WHERE id = ?");
				$stmt->bind_param("sssi", $title, $content, $imagePath, $editNoticeId);
			} 

		else if (!empty($_POST["deleteNoticeId"])) 
			{
				$deleteNoticeId = $_POST["deleteNoticeId"];
				$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
				$stmt->bind_param("i", $deleteNoticeId);
			
				if ($stmt->execute()) 
				{
					echo "Announcement deleted successfully.";
					header("location:Announcements.php");
				} 
				else 
				{
					echo "Error deleting announcement: " . $stmt->error;
				}

				$stmt->close();
			}
		else 
			{
				// Perform an INSERT operation
				$stmt = $conn->prepare("INSERT INTO announcements (title, content, image_path, date_posted) VALUES (?, ?, ?, NOW())");
				$stmt->bind_param("sss", $title, $content, $imagePath);
			}
		
		if ($stmt->execute()) 
			{
				echo "Announcement updated successfully.";
				header("location:Announcements.php");
			} 
		else 
			{
				echo "Error updating announcement: " . $stmt->error;
			}

		$stmt->close();
		$conn->close();
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

		<style>
        /* Style for the modal */
        .modal 
		{
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
        }

        .modal-content 
		{
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
			border-radius: 10px;
			max-width: fit-content;
        }

        .close 
		{
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
		}
		label
		{
			font-family: Arial, Helvetica, sans-serif;
			font-weight: bold;
			font-size: large;
			margin-left: .25rem;
		}

		input, textarea
		{
			margin-top: 10px;
		}

		textarea
		{
			font-family: Arial, Helvetica, sans-serif;
			box-sizing: border-box;
			width: 500px;
			max-width: 100%;
			height: 150px;
			min-height: 50px;
			border-radius: .5rem;
			margin-bottom: 5px;
			vertical-align: baseline;
			line-height: 1.29;
			letter-spacing: .16px;
			border-radius: 0;
			outline: 2px solid transparent;
			outline-offset: -2px;
			border: none;
			border-bottom: 1px solid #8d8d8d;
			border-radius: 1rem;
			background-color: #f4f4f4;
			padding: 0 16px;
			padding-top: 16px;
			color: #161616;
			transition: background-color 70ms cubic-bezier(.2,0,.38,.9),outline 70ms cubic-bezier(.2,0,.38,.9); 
		}

		textarea:focus
		{
			outline: 1.5px solid #8d8d8d;
            outline-offset: -2px;
			border-radius: 1rem;
			box-shadow: 2px 2px 5px #8d8d8d;
		}

		input[type="text"]
		{
			font-family: Arial, Helvetica, sans-serif;
			border-radius: .5rem;
			min-height: 20px;	
			padding: 7px 8px;
			vertical-align: baseline;
			box-sizing: border-box;
			line-height: 1.29;
			letter-spacing: .16px;
			border-radius: 0;
			outline: 2px solid transparent;
			outline-offset: -2px;
			width: 100%;
			height: 40px;
			border: none;
			border-bottom: 1px solid #8d8d8d;
			border-radius: 1rem;
			background-color: #f4f4f4;
			padding: 0 16px;
			color: #161616;
			transition: background-color 70ms cubic-bezier(.2,0,.38,.9),outline 70ms cubic-bezier(.2,0,.38,.9);  
		}

		input[type="text"]:focus
		{
			outline: 1.5px solid #8d8d8d;
            outline-offset: -2px;
			border-radius: 1rem;
			box-shadow: 2px 2px 5px #8d8d8d;
		}

		hr
		{
			width:100%;
			color: rgba(0,0,0,0.2);
			border-top: 1px solid;
			border-radius: 5px;
		}
		#addBtn, .editBtn, .deleteBtn, .submit
		{
		align-items: center;
		background-image: linear-gradient(rgba(213, 91, 85, 1), rgba(175, 72, 66, 1));
		border: 1px solid transparent;
		border-radius: .5rem;
		box-shadow: rgba(0, 0, 0, 0.02) 0 1px 3px 0;
		color: #fff;
		cursor: pointer;
		font-size: 16px;
		font-weight: 600;
		line-height: 1.25;
		min-height: 3rem;
		padding: calc(.875rem - 1px) calc(1.5rem - 1px);
		}

		.submit
		{
		align-items: center;
		background-image: linear-gradient(rgba(213, 91, 85, 1), rgba(175, 72, 66, 1));
		border: 1px solid transparent;
		border-radius: .5rem;
		box-shadow: rgba(0, 0, 0, 0.02) 0 1px 3px 0;
		color: #fff;
		cursor: pointer;
		font-size: 16px;
		font-weight: 600;
		line-height: 1.25;
		min-height: 3rem;
		padding: calc(.875rem - 1px) calc(1.5rem - 1px);
		width: 100%;
		}

		#addBtn:hover, .editBtn:hover, .deleteBtn:hover, .submit:hover,
		#addBtn:focus, .editBtn:focus, .deleteBtn:focus, .submit:focus
		{
		box-shadow: rgba(0, 0, 0, 0.1) 0 4px 12px;
		}

		#addBtn:hover, .editBtn:hover, .deleteBtn:hover, .submit:hover
		{
		transform: translateY(-1px);
		}

		#addBtn:active, .editBtn:active, .deleteBtn:active, .submit:active
		{
		box-shadow: rgba(0, 0, 0, .06) 0 2px 4px;
		transform: translateY(0);
		}

		#addBtn
		{
			margin-left: 5%;
		}
		.editBtn
		{
			background-color: darkgreen; 
			background-image: none;
		}
		.deleteBtn
		{
			background-color: darkred; 
			background-image: none;
		}

		.title
		{
			font-size: 20pt;
			line-height: 5px;
			font-family: Courier New, Garamond, Arial;
			color: #252C21;
		}
		.notice
		{
			padding: 20px;
			display: flex;
			gap: 20px;
		}
		
		.notice > :last-child
		{
			margin-left: auto;
		}
		.text
		{
			max-width: 100%;
			padding: 30px;
		}
		.date
		{
			font-style: italic;
		}

		.options
		{
			display: flex;
			flex-direction: column;
			justify-content: center;
			max-height: fit-content;
			align-items: center;
			min-width: 150px;
		}
    </style>
    
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
	<?php
// Check if either session variable is set
if (isset($_SESSION['employeeid'])) {
    echo '<button id="addBtn">Add Notice</button>';

    echo '<div id="addModal" class="modal">';
    echo '    <div class="modal-content">';
    echo '        <span class="close" id="closeModalBtn">&times;</span>';
    echo '        <form action="Announcements.php" method="post" enctype="multipart/form-data">';
    echo '            <h1>Add Notice</h1>';
    echo '            <hr>';
    echo '            <br>';
    echo '            <label>Title</label>';
    echo '            <input type="text" name="title" placeholder="Notice Title" required><br>';
    echo '            <br>';
    echo '            <label>Content</label><br>';
    echo '            <textarea id="content" name="content" placeholder="Notice Content" required></textarea><br>';
    echo '            <br>';
    echo '            <label>Upload Image</label><br>';
    echo '            <input type="file" name="image" accept="image/*"><br><br>';
    echo '            <br>';
    echo '            <input class="submit" type="submit" value="Add Notice">';
    echo '        </form>';
    echo '    </div>';
    echo '</div>';

    echo '<div id="editModal" class="modal">';
    echo '    <div class="modal-content">';
    echo '        <span class="close" id="closeEditModalBtn">&times;</span>';
    echo '        <form action="Announcements.php" method="post" enctype="multipart/form-data">';
    echo '            <h1>Edit Notice</h1>';
    echo '            <hr>';
    echo '            <br>';
    echo '            <input type="hidden" id="editNoticeId" name="editNoticeId" value="<?php echo $editNoticeId; ?>">';
    echo '            <label>Title</label><br>';
    echo '            <input type="text" id="editTitle" name="title" placeholder="Notice Title"  required ><br>';
    echo '            <br>';
    echo '            <label>Content</label><br>';
    echo '            <textarea name="content" id="editContent" placeholder="Notice Content" required></textarea><br>';
    echo '            <br>';
    echo '            <label>Upload Image</label><br>';
    echo '            <input type="file" name="image" accept="image/*"><br><br>';
    echo '            <br>';
    echo '            <input class="submit" type="submit" value="Update Notice">';
    echo '        </form>';
    echo '    </div>';
    echo '</div>';
}
?>


	<?php
		$conn = new mysqli("localhost", "root", "", "advising");

		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$result = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC");

		while ($row = $result->fetch_assoc()) 
		{
			$posted = strtotime($row["date_posted"]);
    		$datePosted = date("F d, Y h:i A", $posted);

			echo "<div class = 'notice'>";
			if (!empty($row["image_path"]))
			{
				echo "<img src='" . $row["image_path"] . "' alt='Announcement Image' width='300'>";
			}
			
			echo "<div class = 'text'>";
			echo "<p class='date'>" . $datePosted . "</p>";
			echo "<h1 class = 'title'>" . htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8') . "</h2>";
			echo "<p>" . nl2br(htmlspecialchars($row["content"], ENT_QUOTES, 'UTF-8')) . "</p>";
			echo "</div>";
			echo "<div class = 'options'>";
			if (isset($_SESSION['employeeid'])) 
			{
				echo "<button class='editBtn'data-notice-id='" . $row['id'] . "' data-title='" . htmlspecialchars($row['title']) . "' data-content='" . htmlspecialchars($row['content']) . "'>Edit</button><br><br>";
				echo "<button class='deleteBtn'". "data-notice-id='" . $row['id'] . "'>Delete</button>";
			}
			echo "</div>";
			echo "</div>";
			echo "<hr>";

		}

		$result->free();
		$conn->close();
	?>

<script>
        // JS to show and hide the modal
        var addBtn = document.getElementById("addBtn");
		var editBtn = document.querySelectorAll(".editBtn");
		var deleteBtn = document.querySelectorAll(".deleteBtn");

        var addModal = document.getElementById("addModal");
		var editModal = document.getElementById("editModal");

		var editTitle = document.getElementById("editTitle");
		var editContent = document.getElementById("editContent");
		var editNoticeId = document.getElementById("editNoticeId");

		var closeModalBtn = document.getElementById("closeModalBtn");

        addBtn.onclick = function() 
			{
				addModal.style.display = "block";
			}

		editBtn.forEach(function (button) {
        button.addEventListener("click", function () {
            var noticeId = button.getAttribute("data-notice-id");
			var existingTitle = button.getAttribute("data-title");
        	var existingContent = button.getAttribute("data-content");
            editNoticeId.value = noticeId;
            editTitle.value = existingTitle;
            editContent.value = existingContent;
            editModal.style.display = "block";
        });
    });

		function deleteAnnouncement(noticeId) {
			if (confirm("Are you sure you want to delete this announcement?")) {
				var form = document.createElement("form");
				form.method = "POST";
				form.action = "Announcements.php";

				var deleteInput = document.createElement("input");
				deleteInput.type = "hidden";
				deleteInput.name = "deleteNoticeId";
				deleteInput.value = noticeId;

				form.appendChild(deleteInput);
				document.body.appendChild(form);

				form.submit();
			}
		}

		deleteBtn.forEach(function (button) {
			button.addEventListener("click", function () {
				var noticeId = button.getAttribute("data-notice-id");
				deleteAnnouncement(noticeId);
			});
		});

			closeModalBtn.onclick = function() {
				addModal.style.display = "none";
			}
			closeEditModalBtn.onclick = function() {
				editModal.style.display = "none";
			}

			window.onclick = function(event) {
				if (event.target == addModal || event.target == editModal) {
					addModal.style.display = "none";
					editModal.style.display = "none";
				}
			}
</script>
</head>
</body>
