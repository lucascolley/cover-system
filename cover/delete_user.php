<?php
  include_once 'header.php'
?>
      <section class="delete_user-form">
        <h1>Delete a User</h1>
        <form action="includes/delete_user.inc.php" method="post">
          <input type="text" name="userEmail" placeholder="user email...">
          <?php
          echo "<input type='hidden' name='adminEmail' value='" . $_SESSION["userEmail"] . "'>";
          ?>
          <input type="password" name="pwd" placeholder="admin password...">
          <button type="submit" name="submit">Delete User</button>
        </form>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p>Please fill in all fields!</p>";
            } elseif ($_GET["error"] == "nouser") {
                echo "<p>No user matches that email!</p>";
            } elseif ($_GET["error"] == "wronglogin") {
                echo "<p>Invalid admin credentials!</p>";
            } elseif ($_GET["error"] == "none") {
                echo "<p>User deleted successfully!</p>";
            }
        }
        ?>
      </section>


<?php
  include_once 'footer.php'
?>
