<?php
  include_once 'header.php'
?>
      <section class="create_user-form">
        <h1>Create a User</h1>
        <form action="includes/create_user.inc.php" method="post">
          <input type="text" name="email" placeholder="email...">
          <input type="password" name="pwd" placeholder="password...">
          <input type="password" name="pwdrepeat" placeholder="repeat password...">
          <button type="submit" name="submit">Create User</button>
        </form>
        <?php
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
        ?>
      </section>


<?php
  include_once 'footer.php'
?>
