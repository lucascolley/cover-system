<?php
  include_once 'header.php'
?>
      <section class="cover-form">
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) { ?>
              
              <?php
              if (isset($_GET["error"])) {
                  if ($_GET["error"] == "emptyinput") {
                      echo "<p>Please fill in all fields!</p>";
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
