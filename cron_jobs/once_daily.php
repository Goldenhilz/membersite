<?php

$dir = dirname(__DIR__);

// require_once("/home/backcou2/public_html/dev1/demo/membersite/php_includes/db_conx.php");

require_once($dir . "/php_includes/db_conx.php");

// This block deletes all accounts that do not activate after 1 day
$sql = "SELECT id, username FROM users WHERE signup<=CURRENT_DATE - INTERVAL 1 DAY AND activated='0'";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);

if ($numrows > 0) {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	  $id = $row['id'];
	  $username = $row['username'];
	  // $userFolder = "/home/backcou2/public_html/dev1/demo/membersite/users/$username";
	  $userFolder = $dir . "/users/$username";

	  if (is_dir($userFolder)) {
	    rmdir($userFolder);
	  }
	  mysqli_query($db_conx, "DELETE FROM users WHERE id='$id' AND username='$username' AND activated='0' LIMIT 1");
	  mysqli_query($db_conx, "DELETE FROM useroptions WHERE username='$username' LIMIT 1");
	}
}

echo "\n>>>once_dayly.php completed<<<<   " . date('l jS \of F Y h:i:s A');

?>
