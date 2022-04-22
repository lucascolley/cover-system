<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"];
                    $output = "<br>";
                    $output .= "<h1>Generate cover for ";
                    $output .= $date;
                    $output .= " or return to homepage?</h1>";
                    $output .= "<br>";
                    echo $output; ?>
                    <head>
                    </head>
                    <?php
                    $output = "<table>\n";
                    $output .= "<td><a href='includes/page_select.inc.php?date=";
                    $output .= $date;
                    $output .= "' class='button'>Generate Cover</a></td>";
                    $output .= "<td><a href='index.php' class='button'>Home</a></td>";
                    $output .= "</table>";
                    echo $output;
                    ?>
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
