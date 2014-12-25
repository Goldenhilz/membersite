<?php

session_start();

include_once("php_includes/check_login_status.php");

$userid = $_SESSION['userid'];

include_once("php_includes/db_conx.php");

$sql = "SELECT * FROM users WHERE id='$userid' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);

while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
      $useremail = $row["email"];
      $userpass = $row["password"];
      $usergender = $row["gender"];
      $usercountry = $row["country"];
    }

$username = $_SESSION["username"];

?>


<?php

// Ajax calls this REGISTRATION code to execute
if (isset($_POST["u"])) {

// CONNECT TO THE DATABASE
include_once("php_includes/db_conx.php");

// GATHER THE POSTED DATA INTO LOCAL VARIABLES
$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
$e = mysqli_real_escape_string($db_conx, $_POST['e']);
$p = $_POST['p'];
$g = preg_replace('#[^a-z]#', '', $_POST['g']);
$c = preg_replace('#[^a-z ]#i', '', $_POST['c']);

// GET USER IP ADDRESS
$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));

// FORM DATA ERROR HANDLING
if($e == "" || $p == "" || $g == "" || $c == ""){
// echo '**' . $e . '##' .  $p . '##' . $g . '##' . $c . '**';  
echo "The form submission is missing values.";
exit();
}
else {
// END FORM DATA ERROR HANDLING
// Begin Insertion of data into the database
// Hash the password and apply your own mysterious unique salt
$p_hash = md5($p);

// Add user info into the database table for the main site table
$sql = "UPDATE `users` SET `email` = '$e', `password` = '$p_hash' ,`gender` = '$g', `country` = '$c', `ip` = '$ip', `lastlogin` = now() WHERE `username` = \"$u\"";
$query = mysqli_query($db_conx, $sql); 
$uid = mysqli_insert_id($db_conx);

// Establish their row in the useroptions table
$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
$query = mysqli_query($db_conx, $sql);

// Email the user their edited profile link
$to = "$e";  
$from = "info@ken-shoufer.com";
$subject = 'Membersite Account Edited';
$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Member Site Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:20px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.dev1.ken-shoufer.com/demo/membersite/"><img src="http://www.dev1.ken-shoufer.com/demo/membersite/images/logo.png" width="36" height="36" alt="http://www.dev1.ken-shoufer.com/demo/membersite/" style="border:none; float:left; padding: 0 20px;"></a>Membersite Account Edited</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Your account was successfully edited!!<br /></div></body></html>';
$headers = "From: $from\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";

if (mail($to, $subject, $message, $headers)) {
  echo '**Edit Profile SUCCESSFUL**'; 
  exit();
} else {
  // echo '<META HTTP-EQUIV="Refresh" Content="0; URL=message.php?msg=Edit Profile successful, but no confirmation email was sent.">';   
}

exit();
}
exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="styles/foundation.css">
  <link rel="stylesheet" href="styles/style.css">

  
  <script src="js/main.js"></script>
  <script src="js/ajax.js"></script>

  <script>

    function cancelEdit () {
      window.location.replace("index.php");  
    }

    function restrict(elem){
      var tf = _(elem);
      var rx = new RegExp;
      if(elem == "email"){
      rx = /[' "]/gi;
      } else if(elem == "username"){
      rx = /[^a-z0-9]/gi;
      }
      tf.value = tf.value.replace(rx, "");
    }

    function emptyElement(x){
      _(x).innerHTML = "";
    }

    function getUserProfile() {
      var u = _("username").value;
      if(u != ""){
      _("unamestatus").innerHTML = 'checking ...';
      var ajax = ajaxObj("POST", "edit_profile.php");
      ajax.onreadystatechange = function() {
      if(ajaxReturn(ajax) == true) {
         _("unamestatus").innerHTML = ajax.responseText;
      }
      }
      ajax.send("getUserProfile="+u);
      }
    }

    function editUserProfile() {

      var u = "<?php echo $_SESSION["username"] ?>";
      var e = _("email").value;
      var p1 = _("pass1").value;
      var c = _("countries").value;
      var g = _("gender").value;
      var status = _("status");

      if(u == "" || e == "" || p1 == "" || c == "" || g == ""){
        status.innerHTML = "Fill out all of the form data";
      } else {
          _("editProfileBtn").style.display = "none";
          status.innerHTML = 'please wait ...';
          var ajax = ajaxObj("POST", "edit_profile.php");
          ajax.onreadystatechange = function() {

            if(ajaxReturn(ajax) == true) {

              if(ajax.responseText != "editprofile_success"){
                status.innerHTML = ajax.responseText;
                _("editProfileBtn").style.display = "inline";
                } else {
                window.scrollTo(0,0);
                _("editprofileform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
              }
            }
          }

          ajax.send("u="+u+"&e="+e+"&p="+p1+"&c="+c+"&g="+g);
       }
    }

  </script>

</head>

<body>

  <?php include_once("template_pageTop.php"); ?>

  
    <div id="main-contents" class="row">
    <div class="form-container">
  <h3>Edit Profile</h3>

  <form name="editProfileForm" id="editProfileForm" onsubmit="return false;" autocomplete="off">
    <div>Username:  <span id="username"><?php echo $username; ?></span></div>
    <br>
    <div>Email Address:</div>
    <input value=<?php echo $useremail ?> id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
    <div>New/Old Password:</div>
    <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="16">
    <div>Gender:</div>
     <select id="gender" onfocus="emptyElement('status')">
        <option value="m">Male</option>
       <option value="f">Female</option>
    </select>
    <div>Country:</div>
    <input value=<?php echo $usercountry ?> type="text" name="country" id="countries">
    <br /><br />
    <button class="button" id="editProfileBtn" onclick="editUserProfile()">Submit</button>
    <button class="button" id="cancel" onclick="cancelEdit()">Cancel</button>
    <span id="status"></span>
  </form>
</div>
  </div>
    <script>
  
  var usergender = '<?php echo $usergender; ?>';
  var s_index;

  if ( usergender == "m") {
    s_index = 0;
  } else {
    s_index = 1;
  }

  document.getElementById("gender").selectedIndex = s_index;


</script>

<script src="js/jquery.js"></script>
<script src="js/jquery.md5.js"></script>
<script src="js/typeahead.js"></script>
<script src="js/global.js"></script>



  <?php include_once("template_pageBottom.php"); ?>

</body>

</html>
