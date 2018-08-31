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
	<div class="large-4 cell">
		<p>Enter ISBN number</p>
		<input type="text" name="isbn" id="isbnSearch" />
		<input type="button" name="isbnSearchInput" onclick="isbnSearchLocal('ISBN:' + document.getElementById('isbnSearchInput').value)" value="Search by ISBN"/>
	</div>
	<div class="large-4 cell">
		<p>Enter Author & Title</p>
		<form>
			Author: <input type="text" name="authorSearchInput" id="authorSearchInput">
			Title: <input type="text" name="titleSearchInput" id="titleSearchInput">
		</form>
		<input type="button" name="authorSearch" onclick="authorTitleSearchLocal(document.getElementById('authorSearchInput').value , document.getElementById('titleSearchInput').value )" value="Search by Author & Title"/>
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

		// Uses input fields to enter into book database
		// Sends POST request to inputNewBook.php - returns string
		// Called from "authorsearch"
		function inputNewBook(author, title, isbn, description, thumbnail, self_link) {
			$.ajax({
				type: "POST",
				url: "inputNewBook.php",
				data: {
					author: author,
					title: title,
					isbn: isbn,
					description: description,
					thumbnail: thumbnail,
					self_link: self_link
				},
				success: function(result){
					$("#messageBlock").html(result)
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