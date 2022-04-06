<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"];
                $output = "<h1>Generate cover for ";
                $output .= $date;
                $output .= " or return to homepage?</h1>";
                echo $output; ?>
                <head>
                </head>
                <?php
                $output = "<a href='generate_cover.php?date=";
                $output .= $date;
                $output .= "' class='button'>Generate Cover</a>\n";
                $output .= "<a href='index.php' class='button'>Home</a>";
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
