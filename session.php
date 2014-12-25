<?php
// session_start();
$_SESSION['message'] = "php_message";
echo $_SESSION['message'] . "<br>";
?>

<script>
  // sessionStorage.message = "javascript_message";
  document.write("<br>");
  var message = <?php echo $_SESSION['message'];?>;
  // document.write(<?php echo $_SESSION['message']?>);
</script>

<?php
// session_destroy();
?>
