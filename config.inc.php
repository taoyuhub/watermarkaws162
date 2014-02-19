<?php
/**
 * @file
 * Local configuration; requires editing.
 */

/***
define('UARWAWS_S3_BUCKET' , 'taoyu.test.watermark');
define('UARWAWS_SDB_DOMAIN' , 'watermarkedimages');
define('UARWAWS_SQS_QUEUE' , 'images-to-watermark');
define('UARWAWS_SNS_TOPIC' , 'watermark-bad-upload');
*/

define('AWS_S3_BUCKET' , 'taoyu.test.watermark');
define('AWS_SDB_DOMAIN' , 'watermarkedimages');
define('AWS_SQS_QUEUE' , 'images-to-watermark');
define('AWS_SNS_TOPIC' , 'watermark-bad-upload');