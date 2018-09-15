<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$student = $_POST['student'];
$location = $_POST['location'];

if($student !== "undefined"){ 

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		// Prepare an INSERT sql statement
		$sql = "INSERT INTO classroom_signout SET student = ? , location = ?, leave_time = NOW()";

		if($stmt = mysqli_prepare($link, $sql)){
			
			// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'ss', $param_student, $param_location);
	        
	        // Set parameters
				$param_student = $student;
				$param_location = $location;

			// Removes spaces from student
				$condensedStudent = str_replace(' ', '', $student);
				
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				echo "<div id='" . $condensedStudent . "' class='grid-x align-center align-middle callout primary'> <div class='cell small-6 medium-offset-2'><h1>". $student . "</h1></div><h4>" . $location . "</h4><div class='cell small-4'><input type='button' name='returnClassroom' onclick='returnClassroom(&quot;" . $student . "&quot;)' value='I have returned'/></div>";
				 	// Close statement
		 			mysqli_stmt_close($stmt);
			} else {
				echo "Execute failure" . mysqli_error($link);
			}
		} else {
			echo "SQL statement not prepared";
		}
	} else {
		echo "No POST data";
	}
} else {
	echo "Select your name.";
}

// Close connection
mysqli_close($link);
?>