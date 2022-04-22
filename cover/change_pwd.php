<?php
  include_once 'header.php'
?>
      <?php
          echo "<section class='change_pwd-form'>";

      if (isset($_SESSION["userEmail"])) {
          echo "<br>";
          echo "<h1>Change Password!</h1>";
          echo "<br>";
          echo "<form action='includes/change_pwd.inc.php' method='post'>";
          echo " <input type='hidden' name='email' value='". $_SESSION['userEmail'] ."'>";
          echo " <input type='password' name='pwd' placeholder='current password...'>";
          echo " <input type='password' name='newpwd' placeholder='new password...'>";
          echo " <input type='password' name='newpwdrepeat' placeholder='repeat new password...'>";
          echo " <button type='submit' name='submit'>Change Password</button>";
          echo "</form>";
          echo "</section>";
          echo "<br>";

          if (isset($_GET["error"])) {
              if ($_GET["error"] == "emptyinput") {
                  echo "<p>Please fill in all fields!</p>";
              } elseif ($_GET["error"] == "passwordmismatch") {
                  echo "<p>Repeat Password does not match!</p>";
              } elseif ($_GET["error"] == "wronglogin") {
                  echo "<p>Incorrect Password!</p>";
              } elseif ($_GET["error"] == "none") {
                  echo "<p>Password Changed!</p>";
              }
          }
      } else {
          header("location: ./login.php");
          exit();
      }
      ?>
<?php
  include_once 'footer.php'
?>
