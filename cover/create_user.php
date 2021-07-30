<?php
  include_once 'header.php'
?>
      <section class="create_user-form">
        <?php
        if (isset($_SESSION["userEmail"])) {
          if ($_SESSION["admin" !== 0]) {
            echo "<h1>Create a User</h1>";
            echo "<form action='includes/create_user.inc.php' method='post'>";
            echo "  <input type='text' name='email' placeholder='email...'><br>";
            echo "  <input type='password' name='pwd' placeholder='password...'><br>";
            echo "  <input type='password' name='pwdrepeat' placeholder='repeat password...'><br>";
            echo "  <label for='admin'> Admin?</label>";
            echo "  <input type='checkbox' id='admin' name='admin' value='1'><br>";
            echo "  <button type='submit' name='submit'>Create User</button>";
            echo "</form>";
            if (isset($_GET["error"])) {
              if ($_GET["error"] == "emptyinput") {
              echo "<p>Please fill in all fields!</p>";
              }
              else if ($_GET["error"] == "invalidemail") {
                echo "<p>Invalid email!</p>";
              }
              else if ($_GET["error"] == "passwordmismatch") {
                echo "<p>Repeat password does not match!</p>";
              }
              else if ($_GET["error"] == "emailtaken") {
                echo "<p>An account with this email already exists!</p>";
              }
              else if ($_GET["error"] == "none") {
                echo "<p>User created successfully!</p>";
              }
            }
          }
          else {
            header("location: ./index.php");
            exit();
          }
        }
        else {
          header("location: ./login.php");
          exit();
        }
        ?>
      </section>


<?php
  include_once 'footer.php'
?>
