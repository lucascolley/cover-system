<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"]; ?>
                <h1></h1>
                <head>
                </head>
                <?php
                // output results of match.py
                // give user option to edit manually
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
