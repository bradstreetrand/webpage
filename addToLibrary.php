<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$title = $description = $isbn = $author = $thumbnail = $copies = $self_link = "";

// Processing form data when form is submitted
$data = json_decode($_POST['bookObj'], TRUE);
if($_SERVER["REQUEST_METHOD"] == "POST"){

	$copies = 1;

	// Set variables from POST data
	if (isset($data)) {
		$title = $data["items"][0]["volumeInfo"]["title"];
		$description = $data["items"][0]["volumeInfo"]["description"];
		$isbn = $data["items"][0]["volumeInfo"]["industryIdentifiers"][0]["identifier"];
		$author = $data["items"][0]["volumeInfo"]["authors"][0];
		$thumbnail = $data["items"][0]["volumeInfo"]["imageLinks"]["thumbnail"];
		//$copies = $data["items"][0]["volumeInfo"]["copies"];
		$self_link = $data["items"][0]["selfLink"];
	} else {
		echo "no go";
	}

	//Checks database for possible duplicates

	// Prepare a select statement
	$sql = "SELECT title, author, isbn FROM book WHERE (title = ? AND author = ?) OR isbn = ? ";
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, 'sss', $title, $author, $isbn);
	        // Set parameters
			$param_title = $title;
			$param_author = $author;
			$param_isbn = $isbn;
			
			// Attempt to execute the prepared statement
			if($duplicateCheck = mysqli_stmt_execute($stmt)){
				if (!$duplicateCheck) {
					// ** Inserts new item into database **
					// Prepare an insert statement
				    if (isset($title, $description, $isbn, $author, $thumbnail, $copies, $self_link)) {
				    	$sql = "INSERT INTO book (title, description, isbn, author, thumbnail, copies, self_link) VALUES (?, ?, ?, ?, ?, ?, ?)";
				     
					    if($stmt = mysqli_prepare($link, $sql)){
					        // Bind variables to the prepared statement as parameters
					        mysqli_stmt_bind_param($stmt, "sssssis", $param_title, $param_description, $param_isbn, $param_author, $param_thumbnail, $param_copies, $param_self_link);
					        
					        // Set parameters
					        $param_title = $title;
					        $param_description = $description;
					        $param_isbn = $isbn;
					        $param_author = $author;
					        $param_thumbnail = $thumbnail;
					        $param_copies = $copies;
					        $param_self_link = $self_link;
					        
					        // Attempt to execute the prepared statement
					        if(mysqli_stmt_execute($stmt)){
					            // Return success
					            echo "success";
					             
					        } else{
					             echo "failure". mysqli_error($link);
					        }
					    }
					}
				} else {
					// ** Returns option to add or adjust copies
					echo "add or adjust";
				}
			} else {
	             echo "failure". mysqli_error($link);
			}
		}


	
     
		 // Close statement
   		 mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
}

?>
