<?php
header("Content-type: text/html; charset=utf-8");
require('config.php');
require('../vendor/autoload.php');
use Aws\Sqs\SqsClient;

$sqs = new SqsClient([
    'version' => SQS_VERSION,
    'region'  => SQS_REGION
]);

$messageResult = $sqs->receiveMessage(array(
    'QueueUrl'              => SQS_OUTBOX,
    'MessageAttributeNames' => array('s3path','s3bucket','filename','smallfilename')
));

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
					<li class="active"><a href="filedownload.php">Show Result<span class="sr-only">(current)</span></a></li>
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
    	    <table class="table">
                <tr>
                    <th>Bucket</th>
                    <th>File Name</th>
                    <th>Normal Link</th>
                    <th>Small Link</th>
                </tr>
            <?php 
            print_r($messageResult);
            if($messageResult->getPath('Messages') != ''){
                foreach($messageResult->getPath('Messages') as $messages){
                    $attr = array();
                    if($messages['Body'] == 'Resize file'){
                        $messageId = $messages['MessageId'];
                        foreach($messages['MessageAttributes'] as $key => $value){
                            $attr[$key] = $value['StringValue'];
                        }
                    }
            
                    echo '<tr>';
                    echo '<td>'.$attr['s3bucket'].'</td>';
                    echo '<td>'.$attr['filename'].'</td>';
                    echo '<td><a href="'.$attr['s3path'].$attr['s3bucket'].'/'.$attr['filename'].'">Link</a></td>';
                    echo '<td><img src="'.$attr['s3path'].$attr['s3bucket'].'/'.$attr['smallfilename'].'"/></td>';
                    echo '</tr>';
                }
            }
            ?>
    	    </table>
    	
    	</div>
    	<div class="col-md-1"></div>
	</div>
</body>
</html>