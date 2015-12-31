<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;
use Aws\S3\S3Client;

$s3 = new S3Client([
    'version' => S3_VERSION,
    'region'  => S3_REGION
]);

$sqs = new SqsClient([
    'version' => SQS_VERSION,
    'region'  => SQS_REGION
]);

$messageResult = $sqs->receiveMessage(array(
    'QueueUrl'              => SQS_OUTBOX,
    'MaxNumberOfMessages'   => 10,
    'MessageAttributeNames' => array('s3path','s3bucket','filename','smallfilename')
));
$message = "";
if($messageResult->getPath('Messages') != ''){
    foreach($messageResult->getPath('Messages') as $messages){
        $attr = array();
        $receiptHandle = $messages['ReceiptHandle']; 
        foreach($messages['MessageAttributes'] as $key => $value){
            $attr[$key] = $value['StringValue'];
            $message = $attr['filename']." Resize Done.<br/>";
        }
        $sqs->deleteMessage(array(
            'QueueUrl'              => SQS_OUTBOX,
            'ReceiptHandle'         => $receiptHandle
        ));
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>AWS EC2 S3 SQS</title>
<script	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script	src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
	integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
	crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
	integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
	crossorigin="anonymous"></script>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="collapse navbar-collapse"
				id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="fileupload.php">Upload</a></li>
					<li class="active"><a href="filedownload.php">Resize Files<span class="sr-only">(current)</span></a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="row">
    	<div class="col-md-1"></div>
    	<div class="col-md-10"><h2>Resize Files</h2></div>
    	<div class="col-md-1"></div>
    </div>
	<div class="row">
        <div class="col-md-1"></div>
    	<div class="col-md-10">  
        	<?php if($message != ''){ ?>
        	<p class="bg-warning"><?=$message?></p>
        	<?php } ?>   	   
    	    <table class="table">
                <tr>
                    <th>Bucket</th>
                    <th>File Name</th>
                    <th>Normal Link</th>
                    <th>Small Link</th>
                </tr>
            <?php 
            $result = $s3->listBuckets();
            foreach ($result['Buckets'] as $bucket) {
                // Each Bucket value will contain a Name and CreationDate
                if($s3->doesObjectExist($bucket['Name'], SMALLIMAGELIST_FILE)){
                    $txtfile = $s3->getObject([
                        'Bucket'    => $bucket['Name'],
                        'Key'       => SMALLIMAGELIST_FILE
                    ]);
                    $txtbody = $txtfile['Body'];
                    $lines = explode(PHP_EOL, $txtbody);
                    foreach($lines as $key){
                        if(trim($key) != ''){
                            $tag = preg_split("/######/", $key);
                            echo '<tr>';
                            echo '<td>'.$bucket['Name'].'</td>';
                            echo '<td>'.$tag[1].'</td>';
                            echo '<td><a href="'.S3_PATH.$bucket['Name'].'/'.$tag[1].'">Link</a></td>';
                            echo '<td><img src="'.S3_PATH.$bucket['Name'].'/'.$tag[2].'"/></td>';
                            echo '</tr>';
                        }
                    }
                }
            }
            ?>
    	    </table>
    	
    	</div>
    	<div class="col-md-1"></div>
	</div>
</body>
</html>