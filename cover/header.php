<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en-GB" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="description" contents="Cover System">
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <title>Cover System</title>
  </head>
  <body>
    <nav id="navigation">
      <div class="wrapper">
        <ul>
          <li><a href="index.php">Home</a></li>
          <?php
            if (isset($_SESSION["userEmail"])) {
              echo "<li><a href='change_pwd.php'>Change Password</a></li>";
              echo "<li><a href='includes/logout.inc.php'>Log out</a></li>";
              if ($_SESSION["admin"] !== 0) {
                echo "<li><a href='admin_settings.php'>Admin Settings</a></li>";
              }
            }
            else {
              echo "<li><a href='login.php'>Log in</a></li>";
            }
          ?>
        </ul>
      </div>
    </nav>
    <div class="wrapper">
