<?php
  include_once 'header.php'
?>
      <?php
          echo "<section class='change_pwd-form'>";

      if (isset($_SESSION["userEmail"])) {
          ?>
          <br>
          <h1>Change Password!</h1>
          <br>
          <form action='includes/change_pwd.inc.php' method='post'>
           <input type='hidden' name='email' value='". $_SESSION['userEmail'] ."'>
           <input type='password' name='pwd' placeholder='current password...'>
           <input type='password' name='newpwd' placeholder='new password...'>
           <input type='password' name='newpwdrepeat' placeholder='repeat new password...'>
           <button type='submit' name='submit'>Change Password</button>
          </form>
          </section>
          <br>

          <?php
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
