<?php
$key = getenv('AWS_ACCESS_KEY_ID')?: die('No "AWS_ACCESS_KEY_ID" config var in found in env!');
$secret = getenv('AWS_SECRET_ACCESS_KEY')?: die('No "AWS_SECRET_ACCESS_KEY" config var in found in env!');
$default_bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');

define("DATA_SEPARATOR", "@#@");
define("AWS_ACCESS_KEY_ID", $key);
define("AWS_SECRET_ACCESS_KEY", $secret);
define("S3_BUCKET", $default_bucket);
define("SQS_VERSION", "latest");
define("SQS_REGION", "us-west-2");
define("SQS_INBOX", "https://sqs.us-west-2.amazonaws.com/521301825182/inbox");
define("SQS_OUTBOX", "https://sqs.us-west-2.amazonaws.com/521301825182/outbox");
?>