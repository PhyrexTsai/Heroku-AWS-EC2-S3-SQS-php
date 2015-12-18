<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;

$sqs = new SqsClient([
    'version' => SQS_VERSION,
    'region'  => SQS_REGION
]);

$queueList = $sqs->listQueues();

foreach($queueList->get('QueueUrls') as $queueUrl){
    echo $queueUrl;
}

print_r($queueList);

// -------------------------------------------------------------- //
$queueUrl = "https://sqs.us-west-2.amazonaws.com/521301825182/sqs";

/*$sqs->sendMessage(array(
 'QueueUrl'      => $queueUrl,
 'MessageBody'   => 'An awsome message !',
));*/

$messageResult = $sqs->receiveMessage(array(
    'QueueUrl'              => $queueUrl,
    "MaxNumberOfMessages"   => 1
));

foreach($messageResult->getPath('Messages') as $messages){
    print_r($messages);
    echo '<br/>';
    echo '<br/>';
    echo $messages['Body'];
    echo '<br/>';
    echo $messages['ReceiptHandle'];
    echo '<br/>';
}

?>