<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;

function send($message) {
    $sqs = new SqsClient([
        'version' => SQS_VERSION,
        'region'  => SQS_REGION
    ]);
    
    $result = $sqs->sendMessage(array(
        'QueueUrl'      => SQS_INBOX,
        'MessageBody'   => $message,
        'MessageAttributes' => array(
            // Associative array of custom 'String' key names
            's3bucket' => array(
                'StringValue' => 'image_name',
                // DataType is required
                'DataType' => 'String',
            ),
            // ... repeated
        ),
    ));
}
send("testing");
?>