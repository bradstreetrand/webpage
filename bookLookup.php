<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$title = $_POST['title'];
$author = $_POST['author'];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Prepare a select statement
	$sql = "SELECT * FROM book WHERE title = ? AND author = ?";

	if($stmt = mysqli_prepare($link, $sql)){
		
		// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, 'ss', $title, $author);
        
        // Set parameters
			$param_title = $title;
			$param_author = $author;
		
		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			// Bind variables to prepared statement
			mysqli_stmt_bind_result($stmt, $col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8 );
			// Assigns results to array $searchResult
			while (mysqli_stmt_fetch($stmt)) {
				$searchResult =  ["book" => $col1, "title" => $col2, "description" => $col3, "isbn" => $col4, "author" => $col5, "thumbnail" => $col6, "copies" => $col7, "self_link" => $col8];
			}
			// Returns an encoded JSON object
			echo json_encode($searchResult);
		} else {
			echo "SQL statement not executed";
		}
	} else {
		echo "SQL statement not prepared";
	}
} else {
	echo "POST data not set";
}

?>