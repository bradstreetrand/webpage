<html class="no-js" lang="en" dir="ltr">
<!-- Head Info-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bradstreet Rand</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" type="text/css" href="css/library.css">
  </head>

<body>
<!-- Top Navigation Bar -->
	<div class="top-bar">
		<ul class="dropdown menu" data-dropdown-menu>
			<li><a href="index.html">index.html</a><ul class="menu"><li>Home Page</li></ul></li>
			<li><a href="students.php"> students.php</a><ul class="menu"><li>Sign in for students - redirects to studenthome.php</li></ul></li>
			<li><a href="classroomsignout.php">classroomsignout.php</a><ul class="menu"><li>Sign out for leaving classroom</li></ul></li>
			<li><a href="register_students.php">register_students.php</a><ul class="menu"><li>Register student accounts</li></ul></li>
			<li><a href="build-library.php">build-library.php</a><ul class="menu"><li>Add books to library by searching Google Books</li></ul></li>
			<li><a href="https://follett.franklinboe.org/common/servlet/presenthomeform.do?l2m=Home&tm=Home">Franklin Park Library</a></li>
		</ul>
	</div>

<!-- Modal for Announcements -->
    <div class="reveal" id="announcementModal" data-reveal></div>
<!-- Search Results appear here-->
	<div id="messageBlock"></div>



	

<!-- Search Boxes for Full Text Search -->
	<div class="grid-x callout">
		<h1>Search Books</h1>
		<!-- Form for searching database using Full-Text search
			 Calls fullTextSearch() -->
		<div class="large-4 cell">
			<p>Full Text Search</p>
			<input type="text" name="fulltextsearchinput" id="fulltextsearchinput" />
			<input type="button" name="fulltextsearchbutton" onclick="fullTextSearch(document.getElementById('fulltextsearchinput').value)" value="Perform Search"/>
		</div>
		<!-- Form for searching by author and title
			 Calls authorTitleSearchLocal() -->
		<div class="large-4 cell">
			<p>Enter Author & Title</p>
			<form>
				Author: <input type="text" name="authorSearchInput" id="authorSearchInput">
				Title: <input type="text" name="titleSearchInput" id="titleSearchInput">
			</form>
			<input type="button" name="authorSearch" onclick="authorTitleSearchLocal(document.getElementById('authorSearchInput').value , document.getElementById('titleSearchInput').value )" value="Search by Author & Title"/>
		</div>
	</div>
<!-- Fields for inputting new book -->
	<div class="callout secondary">
		<h2>Input New Book</h2>
		<p>Enter Information</p>
			<form>
				Author: <input type="text" name="authorCreateInput" id="authorCreateInput">
				Title: <input type="text" name="titleCreateInput" id="titleCreateInput">
				ISBN: <input type="text" name="isbnCreateInput" id="isbnCreateInput">
				Description: <input type="text" name="descriptionCreateInput" id="descriptionCreateInput">
				Thumbnail URL: <input type="text" name="thumbnailCreateInput" id="thumbnailCreateInput">
				Google Books Self_link: <input type="text" name="self_linkCreateInput" id="self_linkCreateInput">
			</form>
			<input type="button" name="inputNewBook" onclick="inputNewBook(document.getElementById('authorCreateInput').value , document.getElementById('titleCreateInput').value, document.getElementById('isbnCreateInput').value, document.getElementById('descriptionCreateInput').value, document.getElementById('thumbnailCreateInput').value, document.getElementById('self_linkCreateInput').value)" value="Input New Book"/>
	</div>

<!-- Javascript -->

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
    <script type="text/javascript">
		// authorTitleSearchLocal - Searches local database for book and returns all information about the book
			// Sends POST request to bookLookup.php - returns string
			// Called from "authorsearch"
			function authorTitleSearchLocal(author, title) {
				searchLocalAuthor = author;
				searchLocalTitle = title;
				$.ajax({
					type: "POST",
					url: "bookLookup.php",
					data: {author: searchLocalAuthor, title : searchLocalTitle},
					success: function(result){
						if (typeof(result) == 'string' ) {
							bookObj = JSON.parse(result)
							$("#searchResults0").html(
								"<div class='resultItem'><div class='media-object'><div class='media-object-section' <div class='thumbnail'><img src=" +
									bookObj["thumbnail"] + "></div><div class='media-object-section text-center'> <h2>" +
									bookObj["title"] + " </h2><h3 class='subheader'>" +
									bookObj["author"] + " </h3></div></div><div>" + 
									bookObj["description"] + 
									bookObj["isbn"] + "<div class= 'callout'> Number of copies: " +
									bookObj["copies"] + "</div>");
						}

					}
				});
			}

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
							+ resultArray[i]["author"] + "</h3><div class='grid-x'><div class='cell small-4' id='studentDropDown" + i + "'></div><div class='cell small-4'><button name='newReadingLogButton' class='button success' onclick='newReadingLog(" 
							+ resultArray[i]["book"] + ", "
							+ i + ")'>Add to Reading Log</button></div><div class='cell small-4'><button name='finishedReadingLogButton' class='button alert' onclick='finishedReadingLog(" 
							+ resultArray[i]["book"] + ", "
							+ i + ")'>Finished Reading</button><select id='bookRatingDropDown" 
							+ i + "'><option value='0'>Rating</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select></div><div id='ReadingLogMessage"
							+ i + "'></div></div></div></div></div><div>" 
							+ resultArray[i]["description"] + "<div>ISBN: "
							+ resultArray[i]["isbn"] + "</div>" 
							+ resultArray[i]["score"] + "</div><div>Current count in library: <span class='stat'>"
							+ resultArray[i]["copies"] + "</span><button name='addCopy' class='button' onclick='addCopy("
							+ resultArray[i]["book"] + ")'>Add another copy</button><div>";
						}
						$("#messageBlock").html(messageHTML);
						$(function(){
							$.ajax({
								type: "GET",
								url: "studentdropdown.php",
								success: function(result){
									studentDropDown = result;
									for (var i = 0; i < arrayLength; i++){
										$("#studentDropDown"+i).html(studentDropDown);
									}
								}
							})
							
						});
					}
				});
			}

		// newReadingLog - Adds new reading log entry using book # and student #
			// Sends POST request to newReadingLog.php - returns string
			// Called from "newReadingLogButton" created by fullTextSearch
			// Requires INT for bookNumber and INT for studentNumber and INT for i
			function newReadingLog(bookNumber, i){
				studentNumber = document.getElementById("studentDropDown" + i ).getElementsByClassName("studentDropDown")[0].selectedIndex;
				
				$.ajax({
					type: "POST",
					url: "newReadingLog.php",
					data: {
						studentNumber: studentNumber,
						bookNumber: bookNumber
					},
					success: function(result){
						messageHTML = result;
						messageHTML += "</div><div><a class='button' href='studentPageTeacher.php?student=" + studentNumber + "'>Go to student page</a></div>";
						$("#ReadingLogMessage"+i).html(messageHTML);
					}
				});
			}

		// finishedReadingLog - Closes out reading log entry using book #, student #, and rating
			// Sends POST request to finishedReadingLog.php - returns string
			// Called from "finishedReadingLogButton" created by fullTextSearch
			// Requires INT for bookNumber and INT for studentNumber and INT for i and INT for rating
			function finishedReadingLog(bookNumber, i){
				// Sets studentNumber and rating from the drop down menus
				studentNumber = document.getElementById("studentDropDown" + i ).getElementsByClassName("studentDropDown")[0].selectedIndex;
				rating = document.getElementById("bookRatingDropDown" + i).selectedIndex;

				$.ajax({
					type: "POST",
					url: "finishedReadingLog.php",
					data: {
						studentNumber: studentNumber,
						bookNumber: bookNumber,
						rating: rating
					},
					success: function(result){
						$("#ReadingLogMessage"+i).html(result);
					}
				})

			}

		// addCopy - Add another copy of book to library
			// Sends POST to addCopy.php - returns string
			// Called from "addCopy" created by fullTextSearch()
			// Requires bookNumber as INT
			function addCopy(bookNumber){
				$.ajax({
					type: "POST",
					url: "addCopy.php",
					data: {
						bookNumber: bookNumber,
					},
					success: function(result){
						$('#announcementModal').html(result + "<button class='close-button' data-close aria-label='Close modal' type='button'><span aria-hidden='true'>&times;</span></button>");
						$('#announcementModal').foundation('open');
					}
				});
			}

	</script>
  </body>
</html>