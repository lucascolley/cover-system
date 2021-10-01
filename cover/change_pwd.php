<?php
  include_once 'header.php'
?>
      <?php
          echo "<section class='change_pwd-form'>";

      if (isset($_SESSION["userEmail"])) {
          echo "<h1>Change Password!</h1>";
          echo "<form action='includes/change_pwd.inc.php' method='post'>";
          echo " <input type='hidden' name='email' value='". $_SESSION['userEmail'] ."'>";
          echo " <input type='password' name='pwd' placeholder='current password...'>";
          echo " <input type='password' name='newpwd' placeholder='new password...'>";
          echo " <input type='password' name='newpwdrepeat' placeholder='repeat new password...'>";
          echo " <button type='submit' name='submit'>Change Password</button>";
          echo "</form>";
          echo "</section>";

        if (isset($_GET["error"])) {
          if ($_GET["error"] == "emptyinput") {
          echo "<p>Please fill in all fields!</p>";
          }
          else if ($_GET["error"] == "passwordmismatch") {
            echo "<p>Repeat Password does not match!</p>";
          }
          else if ($_GET["error"] == "wronglogin") {
            echo "<p>Incorrect Password!</p>";
          }
        }
      }
      else {
        header("location: ./login.php");
        exit();
      }
      ?>
<?php
  include_once 'footer.php'
?>
