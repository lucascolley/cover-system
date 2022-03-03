<?php
  include_once 'header.php'
?>
      <section class="create_cover-form">
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
              if (isset($_GET["error"])) {
                  if ($_GET["error"] == "emptyinput") {
                      echo "<p>Please fill in all fields!</p>";
                  }
              }
              if (isset($_GET["date"])) {
                $date = $_GET["date"];
                
              } else {
                header("location: ./cover.php");
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
