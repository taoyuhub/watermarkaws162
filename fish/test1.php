<?php

require_once 'AWSSDKforPHP/sdk.class.php';

$ec2 = new AmazonEC2();
$ec2->set_region(AmazonEC2::REGION_OREGON);
$ec2_describe_response = $ec2->describe_instances();

var_dump($ec2_describe_response);