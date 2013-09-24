<?php

require_once '../config.inc.php';
require_once '../util.inc.php';
require_once 'AWSSDKforPHP/sdk.class.php';

$sdb = new $AmazonSDB();

// Build select query.
$query  = 'SELECT * ';
// SimpleDB requires `, not " when specifying the domain.
$query .= 'FROM `' . UARWAWS_SDB_DOMAIN . '` ';
$query .= 'WHERE watermark = "y" ';

// Execute select query.
$sdb->set_region(AmazonSDB::REGION_OREGON);

$select_response = $sdb->select($query);

if ($select_response->isOK()) {
	
	if (count($select_response->body->SelectResult->Item)) {
    // Display in a fluid row.
       echo '<br><br>count = '.count($select_response->body->SelectResult->Item).'<br><br>';
	   var_dump($select_response->body->SelectResult->Item);


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
