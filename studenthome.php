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

include "studentCheckedOutBookSearch.php";
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students of Bradstreet Rand</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" type="text/css" href="vendor/DataTables/datatables.css">
    <link rel="stylesheet" type="text/css" href="css/library.css">
  </head>
<body>
    <div data-sticky-container>
      <div class="title-bar" data-sticky data-options="marginTop:0;" style="width:100%">
        <div class="title-bar-left">
            <ul class="menu align-left">
              <li><a style="color: #FFFFFFFF"><?php echo $firstName . " " . $lastName; ?></a></li>
              <li><a href="#">Check Out a Book</a></li>
              <li><a href="#">Return a Book</a></li>
              <li><a href="#">Find a Book</a></li>
            </ul>
        </div>
        <div class=" grid-x align-right">
            <input class="cell small-8" type="text" name="fulltextsearchinput" id="fulltextsearchinput" placeholder="Search For a Book" />
            <a style="margin-left:1rem" class="button" onclick="fullTextSearch(document.getElementById('fulltextsearchinput').value)">Search</a>
        </div>
      </div>
    </div>

<ul class="accordion" data-accordion>
  <li class="accordion-item " data-accordion-item>
    <!-- Accordion tab title -->
    <a href="#" class="accordion-title">Accordion 1</a>

    <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
    <div class="accordion-content" data-tab-content>
      <p>Panel 1. Lorem ipsum dolor</p>
      <a href="#">Nowhere to Go</a>
    </div>
  </li>
  <!-- ... -->
</ul>

<div>
    <?php //print_r($studentCheckedOutBooks) ?>
</div>
 <div id="json">
 </div>

<table id="myTable" class="display" style="word-wrap:break-word;">
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

 <!-- Javascript -->
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="vendor/DataTables/datatables.js"></script>
    <script src="js/app.js"></script>
    <script type="text/javascript">
        var dataSet = <?php echo json_encode($studentCheckedOutBooks);?>;
        //$("#json").html(dataSet);

        //var data = "<?php $array = [1,2,3,4,5]; json_encode($array)?>"

        $(document).ready(function() {
            $('#myTable').DataTable( {
                scrollY: 600,
                scrollCollapse: true,
                data: dataSet,
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

    </script>
</body>
</html>