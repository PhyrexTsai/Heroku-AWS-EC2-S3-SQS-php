<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;

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

$messageResult = $sqs->receiveMessage(array(
    'QueueUrl'              => SQS_OUTBOX,
    "MaxNumberOfMessages"   => 1
));

foreach($messageResult->getPath('Messages') as $messages){
    //print_r($messages);
    echo 'Message : ';
    echo $messages['Body'];
    echo '<br/>';
    echo $messages['ReceiptHandle'];
    echo '<br/>';
}

?>