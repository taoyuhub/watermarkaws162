<?php

require_once 'AWSSDKforPHP/sdk.class.php';

$ec2 = new AmazonEC2();
$ec2->set_region(AmazonEC2::REGION_OREGON);
$ec2_describe_response = $ec2->describe_instances();


foreach($ec2_describe_response->body->reservationSet->item as $item) {
	echo '<br><br>'.'-------------'.'<br><br>';
	var_dump($item);
}
