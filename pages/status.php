<?php
/**
 * @file
 * Overview of configuration and status.
 */

// Check local config.inc.php for completeness.
if (!AWS_S3_BUCKET) {
  echo renderMsg('error', array(
    'heading' => 'S3 Bucket name missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the bucket in AWS_S3_BUCKET.',
  ));
}
else {
  echo renderMsg('success', array(
    'heading' => 'S3 Bucket name found:',
    'body' => AWS_S3_BUCKET,
  ));
}

if (!AWS_SQS_QUEUE) {
  echo renderMsg('error', array(
    'heading' => 'SQS Queue missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the queue in AWS_SQS_QUEUE.',
  ));
}
else {
  echo renderMsg('success', array(
    'heading' => 'SQS Queue name found:',
    'body' => AWS_SQS_QUEUE,
  ));
}

if (!AWS_SDB_DOMAIN) {
  echo renderMsg('error', array(
    'heading' => 'SimpleDB domain missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the domain in AWS_SDB_DOMAIN.',
  ));
}
else {
  echo renderMsg('success', array(
    'heading' => 'SimpleDB domain name found:',
    'body' => AWS_SDB_DOMAIN,
  ));
}

if (!AWS_SNS_TOPIC) {
  echo renderMsg('error', array(
    'heading' => 'Simple Notification Service topic name missing!',
    'body' => 'Edit config.inc.php in siteroot and specify the name of the topic in AWS_SNS_TOPIC.',
  ));
}
else {
  echo renderMsg('success', array(
    'heading' => 'Simple Notification Service topic name found:',
    'body' => AWS_SNS_TOPIC,
  ));
}

// Check if ImageMagick is installed.
if (!extension_loaded('imagick')) {
  echo renderMsg('error', array(
    'heading' => 'ImageMagick is not installed!',
    'body' => 'Images cannot be uploaded and processed without ImageMagick.',
  ));
}
else {
  echo renderMsg('success', 'ImageMagick is installed.');
}

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
}
