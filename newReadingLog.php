<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$book = $_POST['bookNumber'];
$student = $_POST['studentNumber'];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// Prepare an INSERT sql statement
	$sql = "INSERT INTO reading_log SET book = ? , student = ? , start = CURDATE() ";

	if($stmt = mysqli_prepare($link, $sql)){
		
		// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, 'ss', $book, $student);
        
        // Set parameters
			$param_book = $book;
			$param_student = $student;

		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			echo 'Reading Log Inputted Successfully';
		} else {
			echo "Execute failure" . mysqli_error($link);
		}
	} else {
		echo "SQL statement not prepared";
	}

	echo $book . $student;
 	// Close statement
	 mysqli_stmt_close($stmt);
} else {
	echo "No POST data";
}

// Close connection
mysqli_close($link);
?>