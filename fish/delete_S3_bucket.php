<?php

require_once 'AWSSDKforPHP/sdk.class.php';

$s3 = new AmazonS3();

$s3->set_region(AmazonS3::REGION_OREGON);

$bucket = 'tao.test.watermark';
$s3->delete_all_object_versions($bucket);
$s3->delete_all_objects($bucket);