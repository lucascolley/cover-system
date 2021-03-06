<?php
  include_once 'header.php'
?>
      <section class="delete_user-form">
      <?php
      if (isset($_SESSION["userEmail"])) {
          if ($_SESSION["admin"] !== 0) {
              ?>
              <br>
              <h1>Delete a User</h1>
              <br>
              <form action="includes/delete_user.inc.php" method="post">
                <input type="text" name="userEmail" placeholder="user email...">
                <?php
                echo "<input type='hidden' name='adminEmail' value='" . $_SESSION["userEmail"] . "'>";
                ?>
                <input type="password" name="pwd" placeholder="admin password...">
                <button type="submit" name="submit">Delete User</button>
              </form>
              <br>
              <?php
              if (isset($_GET["error"])) {
                  if ($_GET["error"] == "emptyinput") {
                      echo "<p>Please fill in all fields!</p>";
                  } elseif ($_GET["error"] == "selfdelete") {
                      echo "<p>You cannot delete your own account!</p>";
                  } elseif ($_GET["error"] == "nouser") {
                      echo "<p>No user matches that email!</p>";
                  } elseif ($_GET["error"] == "wronglogin") {
                      echo "<p>Invalid admin credentials!</p>";
                  } elseif ($_GET["error"] == "none") {
                      echo "<p>User deleted successfully!</p>";
                  }
              }
          } else {
              header("location: ./index.php");
              exit();
          }
      } else {
          header("location: ./login.php");
          exit();
      }
      ?>
      </section>
<?php
  include_once 'footer.php'
?>
