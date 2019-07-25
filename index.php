<?php
//error_reporting(E_ERROR | E_PARSE);
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

// Your Account SID and Auth Token from twilio.com/console
$account_sid = $_ENV["TWILIO_ACCOUNT_SID"];
$auth_token = $_ENV["TWILIO_AUTH_TOKEN"];
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with Voice capabilities
	$twilio_number = $_ENV["TWILIO_NUMBER"];

$msg = NULL; $err = NULL;
$client = new Client($account_sid, $auth_token);

if(isset($_POST['submit'])) {
	
	// Where to make a voice call (your cell phone?)
	$to_number = $_POST['pre'].$_POST['phone'];
	
try {
$call = $client->account->calls->create(  
    $to_number,
    $twilio_number,
    array(
        "url" => "http://demo.twilio.com/docs/voice.xml"
    )
);
 $msg = "Started Call ". $call->sid;
}catch (Exception $e) {
	$err = "Error : " . $e->getMessage(); 
}

}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="dist/img/twilio.svg">
	

    <title>Twilio Call</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	
    <!-- Custom styles for this template -->
    <link href="dist/css/custom.css" rel="stylesheet">
	<!-- jQuery -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	
	<!-- Bootstrap -->
	<script src="dist/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
	<script>
	$(document).ready(function(){
		$('#inputPhone').mask('0000000000');
	});
	</script>
  </head>

  <body>
	<div class="row container-fluid">
	
	<div class="col-4 text-center">
	 <?php
	  if($msg)
	  {?>
	  <div class="alert alert-primary" role="alert">
		 <?php echo $msg; ?>
		</div>
	  <?php } if($err){ ?>
	  <div class="alert alert-danger" role="alert">
		 <?php echo $err; ?>
		</div>
	  <?php } ?>
    <form class="form-signin" method="POST">
      <img class="mb-4" src="dist/img/twilio.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Make Twilio Call</h1>
	  
      <div class="input-group mb-3">
	  <div class="input-group-prepend">
		<div class="input-group-text">
		  <i class="fa fa-phone-square" aria-hidden="true" style="color:#007bff;"></i>
		</div>		
	  </div>
	  <select class="form-control col-3" id="inputGroupSelect01" name="pre">
			<option value="+91"> +91 </option>
			<option value="+1"> +1 </option>
		  </select>
	  
      <input type="text" id="inputPhone" name="phone" class="form-control item" placeholder="Phone Number" required>
	  </div>
		<br>
      <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Call</button>
    </form>
	</div>
	<div class="col-8">
	<div class="table-wrapper-scroll-y my-custom-scrollbar">
	<h5 class="text-center mb-5">Call Logs</h5>
	<table class="table table-hover">
		<tr class="thead-dark text-center">
		<th>Date</th>
		<th>Time</th>
		<th>From</th>
		<th>To</th>
		<th>Status</th>
		<th>Duration</th>
		<th>Price</th>
		</tr>
	<?php
		$calls = $client->calls->read(array(), 20);
		
		/*echo "<pre>";
		print_r($calls);
		echo "</pre>";*/
		
		foreach ($calls as $record) { 
		
		$obj = $record->dateCreated;
		$json = json_encode($obj);
		
		?>
			
		<tr class="table-light">
			<td> <?php echo substr($json, 9 , -55); ?> </td>
			<td> <?php echo substr($json, 20 , -47); ?> </td>
			<td> <?php echo $record->from; ?> </td>
			<td> <?php echo $record->to; ?> </td>
			<td> <?php echo $record->status; ?> </td>
			<td> <?php echo $record->duration. " Sec"; ?> </td>
			<td> <?php echo '$ ' .substr($record->price,-7,-2); ?> </td>
		</tr>	
	<?php	}
	?>
	</table>
	
	</div>
	
	</div>
	</div>
  </body>
</html>
