<?php
// Include config file
require_once 'config.php';
?>

<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Signout</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
  </head>

<body>

<div id="status">
	<div id="return">
		
	</div>
</div>
<div class="grid-container">
	<div id="selection" class="grid-x">
		<div class="cell small-4">
			<?php 
			// Creates a drop down list from all students in student database
				// Prepare a select statement
					$sql = "SELECT student, first_name, last_name FROM student";

					if($stmt = mysqli_prepare($link, $sql)){
						
						// Attempt to execute the prepared statement
						if(mysqli_stmt_execute($stmt)){
							// Bind variables to prepared statement
							mysqli_stmt_bind_result($stmt, $col1, $col2, $col3);
							
							// Begins HTML string for return
							$dropDownHTML = "<select id='studentDropdown'><option value='undefined'>Who are you?</option>";

							// Adds options to the HTML String by assigning results from sql results
							while ($result = mysqli_stmt_fetch($stmt)) {
								$dropDownHTML .= "<option value='";
								$dropDownHTML .= $col2;
								$dropDownHTML .= " ";
								$dropDownHTML .= $col3;
								$dropDownHTML .= "'>";
								$dropDownHTML .= $col2;
								$dropDownHTML .= " ";
								$dropDownHTML .= $col3;
								$dropDownHTML .= "</option>";
							};

							// Closes HTML string
							$dropDownHTML .= "</select>";

							// Returns HTML string
							echo $dropDownHTML;
						} else {
							echo "SQL statement not executed";
						}
					} else {
						echo "SQL statement not prepared";
					}
					// Close statement
					mysqli_stmt_close($stmt);
			?>
		</div>
		<div class="cell small-4">
			<select id="locationDropdown">
				<option value="bathroom" selected="selected">Bathroom</option>
				<option value="errand">Teacher Errand</option>
				<option value="break">Walking Break</option>
				<option value="other">Other</option>
			</select>
		</div>
		<div id="leaveClass" class="cell">
			<input type="button" name="leaveClass" onclick="leaveClass(document.getElementById('studentDropdown').value , document.getElementById('locationDropdown').value )" value="Leave the Classroom"/>
		</div>
	</div>
</div>
	<script type="text/javascript">
		// Adds new classroom signout row and places callout with name and location
		// Sends POST info to leaveClassroom.php
		// Requires student number and location string
		// Called by "leaveClass" button
		function leaveClass(student, location){
			condensedstudent = student.replace(/\s+/g, '');
			var a = document.getElementById(condensedstudent)
			if (a) {
				window.alert("Student is already out");
			} else {
				$.ajax({
					type: "POST",
					url: "leaveClassroom.php",
					data: {
						student: student,
						location: location
					},
					success: function(result){
						//var z = document.createElement('div');
						//z.innerHTML = result;
						//document.getElementById('status').appendChild(z);
						$('#status').append(result);
						timer = setTimeout(yellow, 300000, condensedstudent);
					}
				});
			}
		}

		// Removes callout from DOM and inserts retunr time
		// Sends POST info to returnClassroom.php
		// Requires student 
		// Called by "returnClassroom" button
		function returnClassroom(student){
			$.ajax({
				type: "POST",
				url: "returnClassroom.php",
				data: {
					student: student
				},
				success: function(result){
					z = "#" + result;

					$(z).remove();
					
				}
			});
		}


		function yellow(condensedstudent){
			z = "#" + condensedstudent;
			$(z).append(z);
			document.getElementById(condensedstudent).classList.remove('primary');
			document.getElementById(condensedstudent).classList.add('alert');
		}
	</script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
</body>
</html>