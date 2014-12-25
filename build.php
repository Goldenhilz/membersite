<?php session_start();?>

<?php 

$username = $_SESSION["username"];
echo '<li><a href="user.php?u=';
echo $username;
echo '"';
echo ' class="button">Profile</a></li>'; 


?>