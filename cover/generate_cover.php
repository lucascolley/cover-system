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
                $keys = array_column($matches, 'period');
                array_multisort($keys, SORT_ASC, $matches);
                // output matching results
                // echo "<pre>"; //
                // print_r($matches); //
                // echo "</pre>"; //
                ?>
                <br />
                <table>
                  <tr>
                    <?php
                    foreach($matches[0] as $heading => $cell) {
                        echo('<th>' . $heading . '</th>');
                    }
                    ?>
                  </tr>

                  <?php foreach($matches as $match) {
                    echo('<tr>');
                    foreach($match as $heading => $cell) {
                      echo('<td>' . $cell . '</td>');
                    }
                    echo('</tr>');
                  } ?>
                </table>
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
