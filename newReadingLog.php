<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$book = $_POST['bookNumber'];
$student = $_POST['studentNumber'];

// Check if studentNumber is filled
if($student == 0){
	echo "<div class='callout alert'>Please choose a student</div>";
} else {

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	// Prepare an UPDATE sql statement 
	$sql0 = "UPDATE book SET copies = copies - 1 WHERE book = ?";

	if ($stmt0 = mysqli_prepare($link, $sql0)) {
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt0, 'i', $book);
	        
        // Set parameters
		$param_book = $book;

		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt0)){
	
			// Prepare an INSERT sql statement
			$sql = "INSERT INTO reading_log SET book = ? , student = ? , start = CURDATE()";

			if($stmt = mysqli_prepare($link, $sql)){
				
				// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, 'ss', $book, $student);
		        
		        // Set parameters
					$param_book = $book;
					$param_student = $student;

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Returns button for viewing student page and a string acknowleding successful input
					echo 'Reading Log Inputted Successfully';
				} else {
					echo "Execute failure " . mysqli_error($link);
				}
			} else {
				echo "SQL statement 1 not prepared " . mysqli_error($link);
			}
		// Close statement
	 	mysqli_stmt_close($stmt);
		} else {
			echo "Execute failure " . mysqli_error($link);
		}
	}  else {
		echo "SQL statement 0 not prepared " . mysqli_error($link);
	}


} else {
	echo "No POST data";
}

}

// Close connection
mysqli_close($link);
?>