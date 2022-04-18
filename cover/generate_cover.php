<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_POST["matches"])) { ?>
                <h1></h1>
                <head>
                </head>
                <?php
                $matches = unserialize($_POST["matches"]);
                // output matching results
                echo "<pre>"; //
                print_r($matches); //
                echo "</pre>"; //
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
