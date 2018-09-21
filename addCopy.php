<?php
// Include config file
require_once 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Set variables from POST data
	$book = $_POST["bookNumber"];

	// Prepare an Update statement
	$sql = "UPDATE book SET copies = copies + 1 WHERE book = ?";
	if ($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, 'i', $param_book);
		$param_book = $book;
		if(mysqli_stmt_execute($stmt)){
	        // Return success
	            echo "<div class='callout success'> Success! Copies increased by 1</div>";
		    } else{
	            echo "<div class='callout alert'> failure". mysqli_error($link) . "</div>";
		    }
		// Close statement
	   	mysqli_stmt_close($stmt);

	} else {
		echo "SQL statement not prepared" . mysqli_error($link);
	}

} else {
	echo "no POST data";
}

// Close connection
mysqli_close($link);

?>