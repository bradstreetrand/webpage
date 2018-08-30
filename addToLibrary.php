<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$title = $description = $isbn = $author = $thumbnail = $copies = $self_link = "";

// Processing form data when form is submitted
//$data = json_decode($_POST['bookObj'], TRUE);
$data = $_POST['bookObj'];
if($_SERVER["REQUEST_METHOD"] == "POST"){

	$copies = 1;

	// Set variables from POST data
		if (isset($data)) {
			$title = $data["volumeInfo"]["title"];
			$description = $data["volumeInfo"]["description"];
			$isbn = $data["volumeInfo"]["industryIdentifiers"][0]["identifier"];
			$author = $data["volumeInfo"]["authors"][0];
			$thumbnail = $data["volumeInfo"]["imageLinks"]["thumbnail"];
			//$copies = $data["items"][0]["volumeInfo"]["copies"];
			$self_link = $data["selfLink"];
		} else {
			echo "no go";
			print_r($data);
		}

	//Checks database for possible duplicates
	// Prepare a select statement
		$sql = "SELECT title, author, isbn, copies, book FROM book WHERE (title = ? AND author = ?) OR isbn = ? ";

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
						mysqli_stmt_bind_result($stmt, $col1, $col2, $col3, $col4, $col5);
					// Assigns results to array $duplicateCheck
						while (mysqli_stmt_fetch($stmt)) {
							$duplicateCheck =  ["title" => $col1, "author" => $col2, "isbn" => $col3, "copies" => $col4, "book" => $col5];
						}
					// Checks if duplicates are found
					// If no duplicates are found ** Inserts new item into database **
						if (empty($duplicateCheck)) {
							
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
							            echo "Success! " . $title . " has been entered successfully.";
							             
							        } else{
							             echo "failure". mysqli_error($link);
							        }
							    }
							}
						} 
					// If duplicates are found ** Increases copies by 1 **
						else {
							// ** Changes number of copies by 1
							// Prepare an update statement
								$sql = "UPDATE book SET copies = ? WHERE book = ?";
								if($stmt2 = mysqli_prepare($link, $sql)){
									mysqli_stmt_bind_param($stmt2, 'ii', $param_copies, $param_book);
									$copies = $duplicateCheck["copies"] + 1;
									$param_copies = $copies;
									$param_book = $duplicateCheck["book"];
									if(mysqli_stmt_execute($stmt2)){
							            // Return success
							            echo "<div class='callout success'> Success! Copies of <mark style='font-size: 1.3rem;' >" . $title . "</mark> now set to " . $copies . "</div>";
								    } else{
							            echo "<div class='callout alert'> failure". mysqli_error($link) . "</div>";
								    }
								} else {
									echo "statement not prepared";
								} 
								 // Close statement
	   		 					mysqli_stmt_close($stmt2);				
						}
				} else {
		             echo "failure". mysqli_error($link);
				}
			}


		
	     
			 // Close statement
	   		 mysqli_stmt_close($stmt);
	    
	    // Close connection
	    mysqli_close($link);
} else {
	echo "no POST";
}

?>
