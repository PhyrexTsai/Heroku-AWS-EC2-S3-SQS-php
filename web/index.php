<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;

$sqs = new SqsClient([
    'version' => SQS_VERSION,
    'region'  => SQS_REGION
]);

$queueUrl = "https://sqs.us-west-2.amazonaws.com/521301825182/sqs";

echo $sqs->getRegion();

/*$sqs->sendMessage(array(
    'QueueUrl'      => $queueUrl,
    'MessageBody'   => 'An awsome message !',
));*/

$result = $sqs->receiveMessage(array(
    'QueueUrl'              => $queueUrl,
    "MaxNumberOfMessages"   => 1
));

foreach($result->getPath('Messages') as $messages){
    print_r($messages);
}

?>