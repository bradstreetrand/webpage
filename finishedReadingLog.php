<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$book = $_POST['bookNumber'];
$student = $_POST['studentNumber'];
$rating = $_POST['rating'];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// ** Changes number of copies by - 1
	// Prepare an update statement
		$sql = "UPDATE book SET copies = copies + 1 WHERE book = ?";
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind paramaters
			mysqli_stmt_bind_param($stmt, 'i', $param_book);
				$param_book = $book;
			// Execute sql statement
			if(mysqli_stmt_execute($stmt)){
	            // Return success
	            $sql1 = "UPDATE reading_log SET finish = CURDATE(), rating = ? WHERE (student = ? AND book = ?)";
	            if($stmt1 = mysqli_prepare($link, $sql1)){
	            	// Bind paramaters
					mysqli_stmt_bind_param($stmt1, 'iii', $param_rating, $param_student, $param_book);
						$param_rating = $rating;
						$param_student = $student;
						$param_book = $book;
					// Execute sql statement
					if(mysqli_stmt_execute($stmt1)){
			            // Return success
						echo "</div><div><a class='button' href='studentPageTeacher.php?student=" . $student . "'>Go to student page</a></div>";
						echo "<div> Success! Book returned.</div>";
			           } else {
			           	echo "<div class='callout alert'> Statement 1 Execute failure". mysqli_error($link) . "</div>";
			           	echo "</div><div><a class='button' href='studentPageTeacher.php?student=" . $student . "'>Go to student page</a></div>";
			           }
	            } else {
	            	echo "<div class='callout alert'> Statement 1 Prepare failure". mysqli_error($link) . "</div>";
	            }

		    } else{
	            echo "<div class='callout alert'> Statement Execute failure". mysqli_error($link) . "</div>";
		    }
		// Close statement
	   		 mysqli_stmt_close($stmt);
		} else {
			echo "<div class='callout alert'> Statement Prepare failure". mysqli_error($link) . "</div>";
		} 
}
 else {
	echo "no POST";
}
// Close connection
mysqli_close($link);
?>
