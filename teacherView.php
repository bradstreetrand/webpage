<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bradstreet Rand</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
  </head>

<body>
<div id="messageBlock"></div>

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
<!-- Location for search results -->
<div class="grid-x gri-margin-x grid-padding-x">
	<div id="searchResults0" class="cell medium-6 large-4 ">
		<p>Results will appear here.</p>
	</div>
	<div id="searchResults1" class="cell medium-6 large-4 "></div>
	<div id="searchResults2" class="cell medium-6 large-4 "></div>
	<div id="searchResults3" class="cell medium-6 large-4 "></div>
	<div id="searchResults4" class="cell medium-6 large-4 "></div>
</div>

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


	<script type="text/javascript">
		// Searches local database for book and returns all information about the book
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

		// Uses input field to search database to find book
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
						+ resultArray[i]["author"] + "</h3><div class='grid-x'><div class='cell small-4' id='studentDropDown" + i + "'></div><div class='cell small-4'><button name='newReadingLogButton' class='button warning' onclick='newReadingLog(" 
						+ resultArray[i]["book"] + ", "
						+ i + ")'>Add to Reading Log</button></div><div id='newReadingLogMessage"
						+ i + "'></div></div></div></div></div><div>" 
						+ resultArray[i]["description"] + "<div>ISBN: "
						+ resultArray[i]["isbn"] + "</div></div><div>Current count in library: <span class='stat'>"
						+ resultArray[i]["copies"] + "</span></div>";
					}
					$("#messageBlock").html(messageHTML);
					$(function(){
						
						for (var i = 0; i < arrayLength; i++){
							$("#studentDropDown"+i).load("studentdropdown.php");
						}
					});
				}
			});
		}

		// Adds new reading log entry using book # and student #
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
					$("#newReadingLogMessage"+i).html(result);
				}
			});
		}

	</script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>