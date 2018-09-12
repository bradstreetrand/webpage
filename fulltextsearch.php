<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with POST values
$searchString = $_POST['fulltextsearchstring'];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Prepare a select statement
	$sql = "SELECT *, MATCH(title, description, isbn, author) AGAINST (?) AS score FROM book WHERE MATCH(title, description, isbn, author) AGAINST (? IN BOOLEAN MODE)";

	if($stmt = mysqli_prepare($link, $sql)){
		
		// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, 'ss', $searchString, $searchString);
        
        // Set parameters
			$param_searchString = $searchString;
		
		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			// Bind variables to prepared statement
			mysqli_stmt_bind_result($stmt, $col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9 );
			// Assigns results to array $searchResult
			$searchResult = array ();
			while ($result = mysqli_stmt_fetch($stmt)) {
				$searchResult[] =  ["book" => $col1, "title" => $col2, "description" => $col3, "isbn" => $col4, "author" => $col5, "thumbnail" => $col6, "copies" => $col7, "self_link" => $col8, "score" => $col9];
			}
			// Orders results from high score to low score
			function cmp($a, $b){
				return ( $b["score"] > $a["score"]) ? -1 : 1;
			}

			usort($searchResult, "cmp");
			$reversedArray = array_reverse($searchResult);
			// Returns an encoded JSON object
			echo json_encode($reversedArray);
		} else {
			echo "SQL statement not executed";
		}
	} else {
		echo "SQL statement not prepared";
	}
	// Close statement
	mysqli_stmt_close($stmt);
} else {
	echo "POST data not set";
}

// Close connection
mysqli_close($link);
?>