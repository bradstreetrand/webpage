<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$title = $_POST['title'];
$author = $_POST['author'];
$isbn = $_POST['isbn'];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Prepare a select statement
		$sql = "SELECT copies FROM book WHERE (title = ? AND author = ?) OR isbn = ? ";

		if($stmt = mysqli_prepare($link, $sql)){
				
			// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'sss', $title, $author, $isbn);
	        
	        // Set parameters
				$param_title = $title;
				$param_author = $author;
				$param_isbn = $isbn;
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Bind variables to prepared statement
				mysqli_stmt_bind_result($stmt, $col1);
				while (mysqli_stmt_fetch($stmt)) {
					$bookCount =  ["count" => $col1];
						}
				// returns results
				echo $bookCount['count'] ;
			} else {
				echo "sql error";
			}
		} 
	} else {
			echo "0";
		}
?>