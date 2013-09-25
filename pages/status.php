<?php
/**
 * @file
 * Overview of configuration and status.
 */

echo '<div class="row-fluid">';
echo   '<div class="span1"></div>';
echo   '<div class="span10">';
echo      '<h3>Amazon Services Information</h3>';
echo   '</div>';
echo   '<div class="span1"></div>';
echo '</div>';

echo '<div class="row-fluid">';
echo    '<div class="span1"></div>';
echo    '<div class="span10">';
echo    '<table class="table table-bordered table-hover responsive-utilities">';
echo        '<thead>';
echo            '<tr>';
echo                '<th>No.</th>';
echo                '<th>Services</th>';
echo                '<th>Status / Configuration</th>';
echo            '</tr>';
echo        '</thead>';
echo        '<tbody>';

// Check local config.inc.php for completeness.
if (!AWS_S3_BUCKET) {
/*	
  echo renderMsg('error', array(
    'heading' => 'S3 Bucket name missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the bucket in AWS_S3_BUCKET.',
  ));
 */ 
  echo '<tr class="error">';
  echo    '<td>1</td>';
  echo    '<td>Amazon S3</td>';
  echo    '<td>S3 Bucket name missing! Edit config.inc.php in siteroot and specify the name of the bucket in AWS_S3_BUCKET. </td>';
  echo '</tr>';
}
else {
/*	
  echo renderMsg('success', array(
    'heading' => 'S3 Bucket name found:',
    'body' => AWS_S3_BUCKET,
  ));
 */ 
  echo '<tr class="success">';
  echo    '<td>1</td>';
  echo    '<td>Amazon S3</td>';
  echo    '<td>S3 Bucket name: <strong>'.AWS_S3_BUCKET.'</strong></td>';
  echo '</tr>';
}

if (!AWS_SQS_QUEUE) {
/*	
  echo renderMsg('error', array(
    'heading' => 'SQS Queue missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the queue in AWS_SQS_QUEUE.',
  ));
 */ 
  echo '<tr class="error">';
  echo    '<td>2</td>';
  echo    '<td>Amazon SQS</td>';
  echo    '<td>SQS Queue missing! Edit config.inc.php in siteroot and specify the name of the bucket in AWS_SQS_QUEUE. </td>';
  echo '</tr>';
}
else {
/*	
  echo renderMsg('success', array(
    'heading' => 'SQS Queue name found:',
    'body' => AWS_SQS_QUEUE,
  ));
 */ 
  echo '<tr class="success">';
  echo    '<td>2</td>';
  echo    '<td>Amazon SQS</td>';
  echo    '<td>SQS Queue name: <strong>'.AWS_SQS_QUEUE.'</strong></td>';
  echo '</tr>';
}

if (!AWS_SDB_DOMAIN) {
/*	
  echo renderMsg('error', array(
    'heading' => 'SimpleDB domain missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the domain in AWS_SDB_DOMAIN.',
  ));
 */ 
  echo '<tr class="error">';
  echo    '<td>3</td>';
  echo    '<td>Amazon SDB</td>';
  echo    '<td>SimpleDB domain missing! Edit config.inc.php in siteroot and specify the name of the bucket in AWS_SDB_DOMAIN.</td>';
  echo '</tr>';
}
else {
/*	
  echo renderMsg('success', array(
    'heading' => 'SimpleDB domain name found:',
    'body' => AWS_SDB_DOMAIN,
  ));
 */ 
  echo '<tr class="success">';
  echo    '<td>3</td>';
  echo    '<td>Amazon SDB</td>';
  echo    '<td>SimpleDB domain name: <strong>'.AWS_SDB_DOMAIN.'</strong></td>';
  echo '</tr>';
}

if (!AWS_SNS_TOPIC) {
/*	
  echo renderMsg('error', array(
    'heading' => 'Simple Notification Service topic name missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the topic in AWS_SNS_TOPIC.',
  ));
 */ 
  echo '<tr class="error">';
  echo    '<td>4</td>';
  echo    '<td>Amazon SNS</td>';
  echo    '<td>Simple Notification Service Topic name missing! Edit config.inc.php in siteroot and specify the name of the bucket in AWS_SNS_TOPIC.</td>';
  echo '</tr>';
}
else {
/*
  echo renderMsg('success', array(
    'heading' => 'Simple Notification Service topic name found:',
    'body' => AWS_SNS_TOPIC,
  ));
 */
  echo '<tr class="success">';
  echo    '<td>4</td>';
  echo    '<td>Amazon SNS</td>';
  echo    '<td>Simple Notification Service topic name: <strong>'.AWS_SNS_TOPIC.'</strong></td>';
  echo '</tr>';
}

// Check if ImageMagick is installed.
if (!extension_loaded('imagick')) {
/*	
  echo renderMsg('error', array(
    'heading' => 'ImageMagick is not installed!',
    'body' => 'Images cannot be uploaded and processed without ImageMagick.',
  ));
 */ 
  echo '<tr class="error">';
  echo    '<td>5</td>';
  echo    '<td>ImageMagick</td>';
  echo    '<td>ImageMagick is not installed! Images cannot be uploaded and processed without ImageMagick.</td>';
  echo '</tr>';
}
else {
  //echo renderMsg('success', 'ImageMagick is installed.');
  
  echo '<tr class="success">';
  echo    '<td>5</td>';
  echo    '<td>ImageMagick</td>';
  echo    '<td>ImageMagick is installed</td>';
  echo '</tr>';
}

echo       '</tbody>';
echo     '</table>';
echo   '</div>';
echo   '<div class="span1">';
echo   '</div>';
echo '</div>';

// Try to connect to Amazon EC2.
try {
  $ec2 = new AmazonEC2();
}
catch (Exception $e) {
  echo renderMsg('error', array(
    'heading' => 'Cannot connect to Amazon EC2!',
    'body' => $e->getMessage(),
  ));
  return;
}

// Get a list of all instances from EC2.
$ec2->set_region(AmazonEC2::REGION_OREGON);

$ec2_describe_response = $ec2->describe_instances();

if (!$ec2_describe_response->isOK()) {
  echo renderMsg('error', array(
    'heading' => 'Unable to get instance descriptions from EC2!',
    'body' => getAwsError($ec2_describe_response),
  ));
}
else {
/*	
  $info = '';
  foreach ($ec2_describe_response->body->reservationSet->item as $item) {
    $info .= '<dl>';
    $info .= "<dt>{$item->instancesSet->item->tagSet->item->value}</dt>";
    $info .= "<dd>Key: {$item->instancesSet->item->keyName}</dd>";
	$info .= "<dd>DNS: {$item->instancesSet->item->dnsName}</dd>";
    $info .= "<dd>Type: {$item->instancesSet->item->instanceType}</dd>";
	$info .= "<dd>State: {$item->instancesSet->item->instanceState->name}</dd>";
    $info .= '</dl>';
  }
  echo renderMsg('info', array(
    'heading' => 'Amazon EC2 Instance(s):',
    'body' => $info,
  ));
 */ 
  /*     ------------------------------------- */
  echo '<div class="row-fluid">';
  echo   '<div class="span1"></div>';
  echo   '<div class="span10">';
  echo      '<h3>Amazon EC2 Instances</h3>';
  echo   '</div>';
  echo   '<div class="span1"></div>';
  echo '</div>';

  echo '<div class="row-fluid">';
  echo   '<div class="span1"></div>';
  echo   '<div class="span10">';
  echo      '<table class="table table-bordered table-hover responsive-utilities">';
  echo          '<thead>';
  echo            '<tr>';
  echo               '<th>No.</th><th>Name</th><th>Type</th><th>State</th><th>DNS</th>';  
  echo            '</tr>';
  echo          '</thead>';
  echo          '<tbody>';
	
  $count=1;			
  foreach ($ec2_describe_response->body->reservationSet->item as $item) {
	  if ($item->instancesSet->item->instanceState->name == "running") {
	     echo '<tr class="success">';
	  } else {
	  	 echo '<tr class="warning">';
	  }
      echo    '<td>'.$count.'</td>';
      echo    '<td><strong>'.$item->instancesSet->item->tagSet->item->value.'</strong></td>';  //instance name
      echo    '<td>'.$item->instancesSet->item->instanceType.'</td>';			//instance type
	  if ($item->instancesSet->item->instanceState->name == "running") {			//instance state
         echo    '<td><img align="left" src="../img/status_green.gif" alt="stopped icon">'.$item->instancesSet->item->instanceState->name.'</td>';
	  } else {   //state is "Stopped"
		 echo    '<td><img align="left" src="../img/status_red.gif" alt="stopped icon">'.$item->instancesSet->item->instanceState->name.'</td>';
	  }
      echo    '<td>'.$item->instancesSet->item->dnsName.'</td>';				//instance DNS
	  echo   '</tr>';
	  $count++;                
  }
  
  echo          '</tbody>';
  echo       '</table>';
  echo    '</div>';
  echo   '<div class="span1">';
  echo  '</div>';
  echo '</div>';
}
