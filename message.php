<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Membersite Demo</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="styles/foundation.css">
		<link rel="stylesheet" href="styles/style.css">

	</head>
	<body>

		<?php include_once("template_pageTop.php"); ?>

		<div id="main-contents" class="row">			

			<?php

			$message = "";
			$msg = preg_replace('#[^a-z 0-9.:_()]#i', '', $_GET['msg']);

			if($msg == "activation_failure"){
				$message = '<h2>Activation Error</h2> Sorry there seems to have been an issue activating your account at this time. We have already notified ourselves of this issue and we will contact you via email when we have identified the issue.';
			} else if($msg == "activation_success"){
				$message = '<h2>Activation Success</h2> Your account is now activated. <a href="login.php">Click here to log in</a>';
			} else {
				$message = $msg;
			}

			?>

			<div><?php echo $message; ?></div>

		</div><!--end main contents-->

		<?php include_once("template_pageBottom.php"); ?>


	</body>
</html>