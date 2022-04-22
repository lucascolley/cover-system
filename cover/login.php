<?php
  include_once 'header.php'
?>
      <section class="login-form">
        <br>
        <h1>Login to the Cover System!</h1>
        <br>
        <form action="includes/login.inc.php" method="post">
          <input type="text" name="email" placeholder="email...">
          <input type="password" name="pwd" placeholder="password...">
          <button type="submit" name="submit">Login</button>
        </form>
      </section>

      <?php
      if (isset($_GET["error"])) {
          if ($_GET["error"] == "emptyinput") {
              echo "<p>Please fill in all fields!</p>";
          } elseif ($_GET["error"] == "wronglogin") {
              echo "<p>Invalid login information!</p>";
          }
      }

      ?>
<?php
  include_once 'footer.php'
?>
