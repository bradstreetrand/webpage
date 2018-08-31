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
	<div class="large-4 cell">
		<p>Enter ISBN number</p>
		<input type="text" name="isbn" id="isbnInput" />
		<input type="button" name="isbnSearch" onclick="isbnSearchGB('ISBN:' + document.getElementById('isbnInput').value)" value="Search by ISBN"/>
	</div>
	<div class="large-4 cell">
		<p>Enter Author & Title</p>
		<form>
			Author: <input type="text" name="authorInput" id="authorInput">
			Title: <input type="text" name="titleInput" id="titleInput">
		</form>
		<input type="button" name="authorSearch" onclick="authorTitleSearchGB(document.getElementById('authorInput').value , document.getElementById('titleInput').value )" value="Search by Author & Title"/>
	</div>
</div>
<div class="grid-x gri-margin-x grid-padding-x">
	<div id="searchResults0" class="cell medium-6 large-4 ">
		<p>Results will appear here.</p>
	</div>
	<div id="searchResults1" class="cell medium-6 large-4 "></div>
	<div id="searchResults2" class="cell medium-6 large-4 "></div>
	<div id="searchResults3" class="cell medium-6 large-4 "></div>
	<div id="searchResults4" class="cell medium-6 large-4 "></div>
</div>


	<script type="text/javascript">
		"https://www.googleapis.com/books/v1/volumes?q="
		// Defines global variables
		var bookObj;
		var jsonBookObj;
		
		// Searches Google Books by ISBN number
		// Called from input 'isbnSearch'
		function isbnSearchGB(url) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					jsonBookObj = this.responseText
					bookObj = JSON.parse(this.responseText)
					var info = bookObj.items[0]["volumeInfo"]
					displayTest(bookObj);
				}
			}
			xhttp.open("GET", "https://www.googleapis.com/books/v1/volumes?q=" + url + '&maxResults=5', true);
			xhttp.send();
		}

		// Searches Google Books by Author and Title
		// Called from input 'authorSearch'
		function authorTitleSearchGB(author, title) {
			var url = "intitle:" + title + "+inauthor:" + author;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					jsonBookObj = this.responseText
					bookObj = JSON.parse(this.responseText)
					var info = bookObj.items[0]["volumeInfo"]
					displayTest(bookObj);
				}
			}
			xhttp.open("GET", "https://www.googleapis.com/books/v1/volumes?q=" + url + '&maxResults=5' , true);
			xhttp.send();
		}

		// Inputs the results from Google Books and surrounding html into the document
		// Called by isbnSearchGB() and authorTitleSearchGB()
		function displayTest(bookObj){
			for (var i = 0; i < 5; i++) {
				if (typeof bookObj.items[i]["volumeInfo"]["subTitle"] != 'undefined' ) {
				var subtitle = "<small>" + bookObj.items[i]["volumeInfo"]["subTitle"] + " </small>";
				} else {var subtitle ="";};
				
				document.getElementById("searchResults" + i.toString()).innerHTML = 
				"<div class='resultItem'><div class='media-object'><div class='media-object-section' <div class='thumbnail'><img src=" +
				bookObj.items[i]["volumeInfo"]["imageLinks"]["thumbnail"] + "></div><div class='media-object-section text-center'> <h2>" +
				bookObj.items[i]["volumeInfo"]["title"] +
				subtitle + " </h2><h3 class='subheader'>" +
				bookObj.items[i]["volumeInfo"]["authors"][0] + " </h3></div></div><div><input type='button' name='addToLibrary' onclick='addToDatabase(" + i + ")' value='Add to Database'/> </div><div>" + 
				bookObj.items[i]["volumeInfo"]["description"] + 
				bookObj.items[i]["volumeInfo"]["industryIdentifiers"][0]["identifier"] +
				"</div><button class='button secondary' id='bookcount' onclick='bookCountLookup( &quot;" + bookObj.items[i]["volumeInfo"]["authors"][0] + "&quot;, &quot;" + bookObj.items[i]["volumeInfo"]["title"] + "&quot;, &quot;" + bookObj.items[i]["volumeInfo"]["industryIdentifiers"][0]["identifier"] + "&quot; )'>Get Count</button> </div> <button class='button warning' id='subtractBook' onclick='subtractBook( &quot;" + bookObj.items[i]["volumeInfo"]["authors"][0] + "&quot;, &quot;" + bookObj.items[i]["volumeInfo"]["title"] + "&quot;, &quot;" + bookObj.items[i]["volumeInfo"]["industryIdentifiers"][0]["identifier"] + "&quot; )'>Subtract 1 Copy</button>" 
				;				
			}
		}

		// Adds result to database or increases the copies by 1
		// Sends POST request to addToLibrary.php - returns string
		// Called from input 'addToDatabase'
		function addToDatabase(i) {
			data = bookObj.items[i];
			$.ajax({
				type: "POST",
				url: "addToLibrary.php",
				data: {bookObj: data},
				success: function(result){
					$("#messageBlock").html(result)			}
			});

		}


		// Looks up number of copies currently in library
		// Sends POST request to bookCountLookup.php - returns numeric string
		// Called from button created by displayTest()
		function bookCountLookup(author, title, isbn) {
			countAuthor = author;
			countTitle = title;
			countIsbn = isbn;
			$.ajax({
				type: "POST",
				url: "bookCountLookup.php",
				data: {author: countAuthor, title : countTitle, isbn : countIsbn},
				success: function(result){
					$("#messageBlock").html("Number of copies: " + result)			}
			});
		}

		// Decreases the copies of a book by 1
		// Sends POST request to subtractBook.php
		// Called from button created by displayTest()
		function subtractBook(author, title, isbn) {
			subtractAuthor = author;
			subtractTitle = title;
			subtractIsbn = isbn;
			$.ajax({
				type: "POST",
				url: "subtractBook.php",
				data: {author: subtractAuthor, title : subtractTitle, isbn : subtractIsbn},
				success: function(result){
					$("#messageBlock").html(result)			}
			});

		}
		
	</script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>