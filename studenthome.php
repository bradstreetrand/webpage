<?php
// Include config file
require_once 'config.php';

// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}

 // Define variables and initialize with SESSION values
    $username = $_SESSION['username'];
    
    // Prepare a select statement
    $sql = "SELECT student FROM student WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            
            // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, 's', $param_username);
            
            // Set parameters
                $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Bind variables to prepared statement
                mysqli_stmt_bind_result($stmt, $col1);
                // Fetches results
                $result = mysqli_stmt_fetch($stmt);
                $student = $col1;
                // Returns student number
                echo "Student Number:" . $student;

                // Close statement
                mysqli_stmt_close($stmt);

                $sql1 = "SELECT book.title, book.author, book.thumbnail, book.description, reading_log.start, reading_log.finish, reading_log.rating FROM student INNER JOIN reading_log ON student.student = reading_log.student INNER JOIN book ON reading_log.book = book.book WHERE student.student = ?";
                if ($stmt1 = mysqli_prepare($link, $sql1)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt1, 'i', $param_student);
            
                    // Set parameters
                    $param_student = $student;

                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt1)){
                        // Bind variables to prepared statement
                        mysqli_stmt_bind_result($stmt1, $col1, $col2, $col3, $col4, $col5, $col6, $col7);
                        // Fetches results
                        $result = mysqli_stmt_fetch($stmt1);

                        while ($result1 = mysqli_stmt_fetch($stmt1)) {
                            $searchResult[] =  ["title" => $col1, "author" => $col2, "thumbnail" => $col3, "description" => $col4, "start" => $col5, "finish" => $col6, "rating" => $col7];
                        }
                        
                        foreach ($searchResult as $book) {
                            echo "</br>" . $book["title"] . $book["author"] . $book["thumbnail"] . $book["description"] . $book["start"] . $book["finish"] . $book["rating"] . "</br>";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt1);
                    } else {
                        echo "SQL statement 1 not executed" . mysqli_error($link);
                    }
                } else {
                    echo "SQL statement 1 not prepared" . mysqli_error($link);
                }
            } else {
                echo "SQL statement not executed" . mysqli_error($link);
            }
        } else {
            echo "SQL statement not prepared" . mysqli_error($link);
        }
        

// Close connection
mysqli_close($link);

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students of Bradstreet Rand</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
  </head>
 <body>
 
 <?php

 ?>

 <!-- Javascript -->
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>

</body>
</html>