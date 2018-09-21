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

    include "allStudentBooks.php";
    include "studentCurrentBooks.php";
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<!-- Head Info-->  
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students of Bradstreet Rand</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/jquery-bar-rating-master/dist/themes/fontawesome-stars.css">
    <link rel="stylesheet" type="text/css" href="vendor/DataTables/datatables.css">
    <link rel="stylesheet" type="text/css" href="css/library.css">
  </head>

<body>
<!-- Title Bar-->
    <div data-sticky-container id="topBar">
      <div class="title-bar grid-x" data-sticky data-options="marginTop:0;" >
        <div class="title-bar-left cell small-7">
            <ul class="menu align-left">
              <li><a style="color: #FFFFFFFF;"><?php echo $firstName . " " . $lastName; ?></a></li>
              <li><a href="https://follett.franklinboe.org/cataloging/servlet/presentadvancedsearchredirectorform.do?l2m=Library%20Search&tm=TopLevelCatalog">Franklin Park School Library</a></li>
              <li><a href="http://www.franklintwp.org">Franklin Township Library</a></li>
            </ul>
        </div>
        <div class="align-right cell small-5 grid-x">
            <input class="call small-8" type="text" name="fulltextsearchinput" id="fulltextsearchinputTopBar" placeholder="Search For a Book" />
            <a style="margin-left:1rem" class="button" onclick="fullTextSearch(document.getElementById('fulltextsearchinputTopBar').value)">Search</a>
        </div>
      </div>
    </div>

<!-- Off Canvas Data for Rating-->
    <div class="off-canvas-wrapper">
        <div class="off-canvas position-left card-container" id="offCanvas" data-off-canvas>
            <div id="bookRatingHeader" class="card-divider">
                <h3>How did you like the book?</h3>
            </div>
            <div id="bookRatingWrapper" class="text-center">
            <select id="bookRatingSelect" class="card-section">
                <option value="1" data-html="not good">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5" data-html="good">5</option>
            </select>
            </div>
        <div id="bookRatingDetails" style='padding-top:1.5rem' >
        </div>
        </div>
        
    </div>

<!-- Modal for Announcements -->
    <div class="reveal" id="announcementModal" data-reveal>
        <button class="close-button" data-close aria-label="CLose modal" type="button"><span aria-hidden="true">&times;</span></button>
    </div>

<!-- Results for book search-->
    <div class="reveal" id="bookSearchResults" data-reveal>
        
    </div>

<!-- Table for Current Books -->
    <div id="currentBooksContainer" class="grid-x grid-margin-x card">
        <div class="cell  card-section" >
            <div class="card-divider">
            <h4>Current Books</h4></div>
            <div class="card-section">
            <table id="currentBooks" class="display hover" style="word-wrap:break-word;">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Start</th>
                        <th>Finished?</th>
                    </tr>
                </thead>
                
            </table>
            </div>
        </div>
    </div>

<!-- Table for Reading Log -->
    <div id="readingLogContainer" class="grid-x grid-margin-x card">
        <div class="cell card-section">
            <div class="card-divider">
                <h4>All Books </br><h5 class='subheader' style="padding-top: .1rem; padding-left: .3rem">Your Full Reading Log</h5></h4>
            </div>
            <div class="card-section">
            <table id="readingLog" class="display hover" style="word-wrap:break-word;">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Description</th>
                        <th>start</th>
                        <th>finish</th>
                        <th>rating</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Description</th>
                        <th>start</th>
                        <th>finish</th>
                        <th>rating</th>
                    </tr>      
                </tfoot>
            </table>
            </div>
        </div>
    </div>

<!-- Javascript -->
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="vendor/DataTables/datatables.js"></script>
    <script src="vendor/jquery-bar-rating-master/jquery.barrating.js" ></script>
    <script src="js/app.js"></script>
    <script type="text/javascript">
        
    // Sets global variables    
        var studentCurrentBooks = <?php echo json_encode($studentCurrentBooks);?>;
        var allStudentBooks = <?php echo json_encode($allStudentBooks);?>;
        var selectedBookNumber = "";
        var student = <?php echo $student ?>;
        var selectedBook = "";

    // fullTextSearch - Uses input field to search database to find book
        // Sends POST request to fulltextsearch.php - returns JSON formatted string
        // Called from "fulltextsearchbutton"
        function fullTextSearch(fulltextsearchstring) {
            $.ajax({
                type: "POST",
                url: "fulltextsearch.php",
                data: {
                    fulltextsearchstring: fulltextsearchstring
                },
                success: function(result){
                    var resultArray = JSON.parse(result);
                    var arrayLength = resultArray.length;
                    var messageHTML = "";
                    for (var i = 0; i < arrayLength; i++){
                        messageHTML += "<div class = 'callout'><div class='media-object'><div class='media-object-section' <div class='thumbnail'><img src="
                        + resultArray[i]["thumbnail"] + "></div><div class='media-object-section text-center'> <h2>"
                        + resultArray[i]["title"] + " </h2><h3 class='subheader'>"
                        + resultArray[i]["author"] + "</h3><div><button name='newReadingLogButton' class='button success' onclick='newReadingLog(" 
                        + resultArray[i]["book"] + ")'>Add to Reading Log</button></div></div></div></div><div>" 
                        + resultArray[i]["description"] + "<div>ISBN: "
                        + resultArray[i]["isbn"] + "</div>" 
                        + resultArray[i]["score"] + "</div><div>Current count in library: <span class='stat'>"
                        + resultArray[i]["copies"] + "</span><div>";
                    }
                    messageHTML += "<button class='close-button' data-close aria-label='Close modal' type='button'><span aria-hidden='true'>&times;</span></button>";
                    $("#bookSearchResults").html(messageHTML);
                    $('#bookSearchResults').foundation('open');
                }
            });
        }
        
    // newReadingLog - Adds new reading log entry using book # and student #
        // Sends POST request to newReadingLog.php - returns string
        // Called from "newReadingLogButton" created by fullTextSearch
        // Requires INT for bookNumber 
        function newReadingLog(bookNumber){
            studentNumber = <?php echo $student ?>
            
            $.ajax({
                type: "POST",
                url: "newReadingLog.php",
                data: {
                    studentNumber: studentNumber,
                    bookNumber: bookNumber
                },
                success: function(result){
                    modalHTML = result;
                    modalHTML += "<button class='close-button' onclick='location.reload()' data-close aria-label='Close modal' type='button'><span aria-hidden='true'>&times;</span></button>"
                    $('#announcementModal').html(modalHTML);
                    $('#announcementModal').foundation('open');
                }
            });
        }

    // DataTable - Creates current books table by using DataTables with results from studentCurrentBooks.php
        $(document).ready(function() {
            $('#currentBooks').DataTable( {
                scrollY: 300,
                scrollCollapse: true,
                data: studentCurrentBooks,
                columns: [
                    {data: 'title'},
                    {data: 'author'},
                    {data: 'start'},
                    {data: 'finished'}
                ],
               
            });
        } );

    // DataTable - Creates reading log table by using DataTables with results from allStudentBooks.php
        $(document).ready(function() {
            $('#readingLog').DataTable( {
                scrollY: 600,
                scrollCollapse: true,
                data: allStudentBooks,
                autoWidth: false,
                columns: [
                    {data: 'title', width: '20%'},
                    {data: 'author', width: '10%'},
                    {data: 'description', width: '55%'},
                    {data: 'start', width: '5%'},
                    {data: 'finish', width: '5%'},
                    {data: 'rating', width: '5%'},
                ],
               
            });

        } );
     
    // returnBook - Reveals left-sided off-canvas for student to rate the book
        // Called from studentCurrentBooks.php
        // Requires book number and student number
        function returnBook(bookNumber, studentNumber){
            for(i=0; studentCurrentBooks.length > i; i+=1 ) {
                if (studentCurrentBooks[i]['book'] === bookNumber) {
                    selectedBook = studentCurrentBooks[i];
                    selectedBookHTML = "<div class = 'callout' ><div class='media-object'><div class='media-object-section'> <div class='thumbnail'><img src="
                        + studentCurrentBooks[i]["thumbnail"] + "></div><div class='media-object-section text-center'> <h3>"
                        + studentCurrentBooks[i]["title"] + " </h3><h4 class='subheader'>"
                        + studentCurrentBooks[i]["author"] + "</h4></div></div></div><div>" 
                        + studentCurrentBooks[i]["description"] + "</div></div>";
                    selectedBookNumber = bookNumber;
                }
            }; 

            $('#bookRatingDetails').html(selectedBookHTML);
            $('#offCanvas').foundation('open', event);
        }

    // barrating - Converts the select code into stars
        $(function() {
            $('#bookRatingSelect').barrating({
                theme: 'fontawesome-stars',
                initialRating: 5,
                onSelect: function(value, text, event) {
                    // rating was selected by a user
                    if (typeof(event) !== 'undefined') {
                        var rating = $("#bookRatingSelect").val();
                        $.ajax({
                            url: 'finishedReadingLog.php',
                            type: 'post',
                            data: { 
                                bookNumber: selectedBookNumber,
                                studentNumber: student,
                                rating: rating,
                            },
                            success: function(result){
                                $('#offCanvas').foundation('close');
                                modalHTML = result;
                                modalHTML += " You have returned <em>" + selectedBook["title"] + "</em> by " + selectedBook["author"];
                                modalHTML += "<button class='close-button' onclick='location.reload()' data-close aria-label='Close modal' type='button'><span aria-hidden='true'>&times;</span></button>"
                                $('#announcementModal').html(modalHTML);
                                $('#announcementModal').foundation('open');
                            }
                        });
                    };

                }
            });
        });

    </script>
</body>
</html>

<?php // Close connection
mysqli_close($link);?>