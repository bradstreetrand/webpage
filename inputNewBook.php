<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$title = $_POST['title'];
$author = $_POST['author'];
$isbn = $_POST['isbn'];
$description = $_POST['description'];
$thumbnail = $_POST['thumbnail'];
$self_link =$_POST['self_link'];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// Prepare an INSERT sql statement
	$sql = "INSERT INTO book SET title = ? , author = ?, isbn = ?, description = ?, thumbnail = ?, self_link = ?, copies = 1 ";

	if($stmt = mysqli_prepare($link, $sql)){
		
		// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, 'ssssss', $title, $author, $isbn, $description, $thumbnail, $self_link);
        
        // Set parameters
			$param_title = $title;
			$param_author = $author;
			$param_isbn = $isbn;
			$param_description = $description;
			$param_thumbnail = $thumbnail;
			$param_self_link = $self_link;

		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			echo 'Book Inputted Successfully';
		} else {
			echo "Execute failure" . mysqli_error($link);
		}
	} else {
		echo "SQL statement not prepared";
	}

	echo $title . $author . $isbn . $description . $thumbnail . $self_link;
} else {
	echo "No POST data";
}

?>