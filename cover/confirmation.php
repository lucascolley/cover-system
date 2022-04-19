<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"]; ?>
                    <br />
                    <h1>Cover List for <?php echo $date ?>:</h1>
                    <head>
                    </head>
                    <?php
                    // getCovers
                    ?>
                    <br />
                    <table>
                      <tr>
                        <?php
                        // $i = 0;
                        // foreach ($matches[0] as $heading => $cell) {
                        //     if ($i <> 0) {
                        //         echo('<th>' . $heading . '</th>');
                        //     }
                        //     $i++;
                        // } ?>
                      </tr>

                      <?php
                      // $i = 0;
                      // foreach ($matches as $match) {
                      //     echo('<tr>');
                      //     $j = 0;
                      //     foreach ($match as $heading => $cell) {
                      //         if ($j == 0) {
                      //             null;
                      //         } elseif ($j == 1) {
                      //             // input with default value as generated
                      //             $output = "<td><input type='text' id='match";
                      //             $output .= ($i . "' name='match" . $i);
                      //             $output .= "' value='" . $cell . "' /></td>";
                      //             echo $output;
                      //         } else {
                      //             echo('<td>' . $cell . '</td>');
                      //         }
                      //         $j++;
                      //     }
                      //     echo('</tr>');
                      //     $i++;
                      // } ?>
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
