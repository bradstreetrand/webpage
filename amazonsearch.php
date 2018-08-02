<?php
   if(($_POST && isset($_POST['isbn'])) || ($_POST && isset($_POST['author'], $_POST['title']))) {

      $isbn = $_POST['isbn'];
      $title = $_POST['title'];
      $author = $_POST['author'];

      if(!$isbn && !$title) {
         $errorMsg = "Please enter an ISBN number or Title and Author";
      } elseif (isset($isbn)) {
         $Keywords = $isbn;
      } else {
         $Keywords = $title . " " .$author;
         ItemSearch($Keywords);
      }
   }

//Enter your IDs
define("Access_Key_ID", "");
define("Associate_tag", "");

//Set up the operation in the request
function ItemSearch($Keywords){

//Set the values for some of the parameters
$Operation = "ItemSearch";
//$ResponseGroup = "ItemAttributes,Offers";
$SearchIndex = "Books";
//User interface provides values
//for and $Keywords

//Define the request
$request=
     "http://webservices.amazon.com/onca/xml"
   . "?Service=AWSECommerceService"
   . "&AssociateTag=" . Associate_tag
   . "&AWSAccessKeyId=" . Access_Key_ID
   . "&Operation=" . $Operation
   . "&SearchIndex=" . $SearchIndex
   . "&Keywords=" . $Keywords
   . "&Signature=" . "";
 //  . "&ResponseGroup=" . $ResponseGroup;

//Catch the response in the $response object
$response = file_get_contents($request);
$parsed_xml = simplexml_load_string($response);
printSearchResults($parsed_xml, $SearchIndex);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

   <form method="POST" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" accept-charset="UTF-8">
      <input type="text" size="48" name="isbn" value="<?PHP if(isset($_POST['isbn'])) echo htmlspecialchars($_POST['isbn']); ?>">
      <input type="text" size="48" name="title" value="<?PHP if(isset($_POST['title'])) echo htmlspecialchars($_POST['title']); ?>">
      <input type="text" size="48" name="author" value="<?PHP if(isset($_POST['author'])) echo htmlspecialchars($_POST['author']); ?>">
      <input type="submit" name="SearchAmazon" value="Search">
   </form>

   <?php
      echo $Keywords;

   ?>
</body>
</html>

