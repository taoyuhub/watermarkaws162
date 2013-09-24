<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Image Watermark Studio with Amazon Web Services</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="../css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="../css/main.css">

        <script src="../js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>

<?php

require_once '../config.inc.php';
require_once '../util.inc.php';
require_once 'AWSSDKforPHP/sdk.class.php';

$sdb = new AmazonSDB();

// Build select query.
$query  = 'SELECT * ';
// SimpleDB requires `, not " when specifying the domain.
$query .= 'FROM `' . UARWAWS_SDB_DOMAIN . '` ';
$query .= 'WHERE watermark = "y" ';

// Execute select query.
$sdb->set_region(AmazonSDB::REGION_OREGON);

$select_response = $sdb->select($query);

if ($select_response->isOK()) {
	$total = count($select_response->body->SelectResult->Item);
	$count=0;
	if ($total) {
    // Display in a fluid row.
		echo '<div class="carousel slide" id="watermarkedImgs"> ';
		  echo '<div class=”carousel-inner”> ';
		  foreach ($select_response->body->SelectResult->Item as $item) {
			// CFSimpleXML and SimpleDB makes it a little difficult to just access
			// attributes by key / value, so I'm just arbitrarily adding them all
			// to an array.
echo '<br><br>';
var_dump($item);
echo '<br><br>';			
			$item_attributes = array();
			foreach ($item->Attribute as $attribute) {
			  $attribute_stdClass = $attribute->to_stdClass();
			  $item_attributes[$attribute_stdClass->Name] = $attribute_stdClass->Value;
			}
			// Render image with height and width.
			if ($count == $total-1) {
			  echo '<div class="item active">';
			} else {
			  echo '<div class="item">';
			}
			echo '<img alt="' . $item->Name . '" src="https://s3-us-west-2.amazonaws.com/' . UARWAWS_S3_BUCKET . '/' . $item->Name . '" height="' . $item_attributes['height'] . '" width=' . $item_attributes['width'] . '"/>';
			echo '</div>';
			
			$count++;
		  }   //for each image record
		  echo '</div>';
		  
		  echo '<a href="#watermarkedImgs" class="left carousel-control" data-slide="prev">&lsaquo;</a>';
		  echo '<a href="#watermarkedImgs" class="right carousel-control" data-slide="next">&rsaquo;</a>';
		echo '</div>';


  }
  // No items.
  else {
    echo renderMsg('info', array(
      'heading' => 'No watermarked images found.',
      'body' => 'If you have uploaded an image, remember to process it.',
    ));
  }
}
else {
	echo 'Error: '.getAwsError($select_response);
}
?>

         <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
        <script src="../js/vendor/bootstrap.min.js"></script>
        <script src="../js/main.js"></script>
    </body>
</html>