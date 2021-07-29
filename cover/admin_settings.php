<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
          echo "<a href='create_user.php' class='button'>Create User</a>";
          echo "<a href='delete_user.php' class='button'>Delete User</a>";
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
