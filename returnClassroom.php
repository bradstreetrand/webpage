<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$student = $_POST['student'];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// Prepare an UPDATE sql statement
	$sql = "UPDATE classroom_signout SET return_time = NOW() WHERE student = ? AND return_time IS NULL";

	if($stmt = mysqli_prepare($link, $sql)){
		
		// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, 's', $param_student);
        
        // Set parameters
			$param_student = $student;

		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			// Removes spaces from student
			$condensedStudent = str_replace(' ', '', $student);
			echo $condensedStudent;
			 	// Close statement
	 			mysqli_stmt_close($stmt);
		} else {
			echo "Execute failure" . mysqli_error($link);
		}
	} else {
		echo "SQL statement not prepared" . mysqli_error($link);
	}
} else {
	echo "No POST data";
}

// Close connection
mysqli_close($link);
?>