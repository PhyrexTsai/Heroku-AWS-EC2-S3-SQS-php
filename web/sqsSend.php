<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;

$sqs = new SqsClient([
    'version' => SQS_VERSION,
    'region'  => SQS_REGION
]);

$sqs->sendMessage(array(
    'QueueUrl'      => SQS_INBOX,
    'MessageBody'   => 'Submit a message to Thumbnail image',
    'MessageAttributes' => array(
        // Associative array of custom 'String' key names
        'thumbnail' => array(
            'StringValue' => 'image_name',
            // DataType is required
            'DataType' => 'String',
        ),
        // ... repeated
    ),
));

?>