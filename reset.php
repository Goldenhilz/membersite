<?php
   session_start();
   $_SESSION["message"] = "Your password has been reset. Please log in.";

   header("location: login.php")//?message=password_reset");


?>