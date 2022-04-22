<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            ?>
            <br>
            <table>
              <td><a href='create_user.php' class='button'>Create User</a></td>
              <td><a href='delete_user.php' class='button'>Delete User</a></td>
              <td><a href='import_teachers.php' class='button'>Import Teachers</a></td>
              <td><a href='set_SLT.php' class='button'>Set SLT Teachers</a></td>
            </table>
            <?php
        } else {
            header("location: ./login.php");
            exit();
        }
        ?>
      </section>
<?php
  include_once 'footer.php'
?>
