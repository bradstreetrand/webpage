<?php
// Include config file
require_once 'config.php';

// Processing form data when GET is submitted
if($_SERVER["REQUEST_METHOD"] == "GET"){

	// Prepare a select statement
	$sql = "SELECT student, first_name, last_name FROM student";

	if($stmt = mysqli_prepare($link, $sql)){
		
		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			// Bind variables to prepared statement
			mysqli_stmt_bind_result($stmt, $col1, $col2, $col3);
			
			// Begins HTML string for return
			$dropDownHTML = "<select class='studentDropDown'><option value='undefined'>Choose a student</option>";

			// Adds options to the HTML String by assigning results from sql results
			while ($result = mysqli_stmt_fetch($stmt)) {
				$dropDownHTML .= "<option value='";
				$dropDownHTML .= $col1;
				$dropDownHTML .= "'>";
				$dropDownHTML .= $col2;
				$dropDownHTML .= " ";
				$dropDownHTML .= $col3;
				$dropDownHTML .= "</option>";
			};

			// Closes HTML string
			$dropDownHTML .= "</select>";

			// Returns HTML string
			echo $dropDownHTML;
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
