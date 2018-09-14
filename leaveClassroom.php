<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$student = $_POST['student'];
$location = $_POST['location'];

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

		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			echo "<div id='" . $student . "' class='grid-x'><h2>". $student . "</h2><div class='cell small-4'><input type='button' name='returnClass' onclick='returnClass(" . $student . ")' value='I have returned'/>";
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

// Close connection
mysqli_close($link);
?>