<?php
  include_once 'header.php'
?>
      <section>
        <?php
          if (isset($_SESSION["userEmail"])) {
              echo "<br />";
              echo "<p>You are logged in as: " . $_SESSION["userEmail"] . "</p>";
          }
        ?>
        <br>
        <p>Welcome to the Cover System!</p>
      </section>
<?php
  include_once 'footer.php'
?>
