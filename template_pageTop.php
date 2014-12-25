<?php session_start();?>

<header>

  <div class="row">
    <div class="large-12 columns">
      <div class="nav-bar right">
       <ul class="button-group">
         <li><a href="index.php" class="button">Home</a></li>
         <li><a href="contact.php" class="button">Contact</a></li>
         <li><a href="about.php" class="button">About</a></li>
         <?php 
            if(isset($_SESSION["username"])){
              $username = $_SESSION["username"];
              echo '<li><a href="user.php?u=';
              echo $username;
              echo '"';
              echo ' class="button">Profile</a></li>'; 
            }
         ?>
        </ul>
      </div>
      <h1>Membersite <small>It's awesome.</small></h1>
      <hr/>
    </div>
  </div>

  <div class="clear"></div>
</header>

  <div class="row">

    <?php

     if(isset($_SESSION["username"])){
        echo '<div id="logout-box" class="right">';
        echo '<a href="logout.php">Log Out</a>';
        echo '</div>';
      } else {
        echo '<div id="signup-login-box" class="right">';
        echo '<a href="signup.php">Sign Up</a><span> / </span>';
        echo '<a href="login.php">Log In</a>';
        echo '</div>';
      }
    ?>
  </div>
