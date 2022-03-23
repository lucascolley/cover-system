<?php
  include_once 'header.php'
?>
      <section class="select_periods-form">
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"];
                    ?>
                <h1>Select Absent Periods</h1>
                <head>
                </head>
                <form action="2" method="post">
                  <table border="1">
                  </table>
                </form>
                <?php
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
