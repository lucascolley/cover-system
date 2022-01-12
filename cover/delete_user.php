<?php
  include_once 'header.php'
?>
      <section class="delete_user-form">
        <h1>Delete a User</h1>
        <form action="includes/delete_user.inc.php" method="post">
          <input type="text" name="email" placeholder="email...">
          <input type="password" name="pwd" placeholder="password...">
          <button type="submit" name="submit">Delete User</button>
        </form>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p>Please fill in all fields!</p>";
            } elseif ($_GET["error"] == "wronglogin") {
                echo "<p>Invalid credentials!</p>";
            } elseif ($_GET["error"] == "none") {
                echo "<p>User deleted successfully!</p>";
            }
        }
        ?>
      </section>


<?php
  include_once 'footer.php'
?>
