<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;

/**
 * 1. Upload interface to put image to S3, same time use SQS send message to "inbox"
 * 2. EC2 detect the SQS "inbox", if have new message then process thumbnail image from S3
 * 3. After EC2 finished then using SQS send message to "outbox"
 * 4. Download interface will receive the massage to show the link to the S3
 */

$sqs = new SqsClient([
    'version' => SQS_VERSION,
    'region'  => SQS_REGION
]);

/*$queueList = $sqs->listQueues();

foreach($queueList->get('QueueUrls') as $queueUrl){
    echo 'QueueUrl : ';
    echo $queueUrl;
    echo '<br/>';
}*/

//print_r($queueList);

// -------------------------------------------------------------- //

/*$sqs->sendMessage(array(
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
));*/
/*
$messageResult = $sqs->receiveMessage(array(
    'QueueUrl'              => $queueUrl,
    "MaxNumberOfMessages"   => 5
));

foreach($messageResult->getPath('Messages') as $messages){
    //print_r($messages);
    echo 'Message : ';
    echo $messages['Body'];
    echo '<br/>';
    echo $messages['ReceiptHandle'];
    echo '<br/>';
}
*/
?>