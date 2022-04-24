<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_POST["matches"])) {
                    $date = $_POST["date"]; ?>
                    <br />
                    <h1>Review Suggested Cover Teachers:</h1>
                    <br />
                    <h3>You can override the generated choices here.</h3>
                    <br />
                    <h3>Please note: Ensure there are no errors if you choose
                    to override choices as this is the final confirmation.</h3>
                    <head>
                    </head>
                    <?php
                    $matchesString = $_POST["matches"];
                    $numEXT = $_POST["numEXT"];
                    $matches = unserialize($matchesString);
                    // sort matches array by period
                    $keys = array_column($matches, 'period');
                    array_multisort($keys, SORT_ASC, $matches);
                    if ($numEXT <> 0) {
                        echo ("<br /><h4>Warning: A minimum of " . $numEXT);
                        echo (" External Supply Teachers are required, ");
                        echo ("these have been denoted with code EXT.</h4>");
                    }
                    ?>
                    <br />
                    <form action='includes/generate_cover.inc.php' method='post'>
                      <input type="hidden" id="date" name="date" value=<?php echo('"' . $date . '"'); ?>>
                      <?php
                      echo "<input type='hidden' id='matches' name='matches' value='" . $matchesString . "'/>"
                      ?>
                      <table>
                        <tr>
                          <th>Cover Staff Code</th>
                          <th>Period</th>
                          <th>Room</th>
                          <th>Staff Code</th>
                          <th>Class Code</th>
                        </tr>
                        <?php
                        $i = 0;
                        foreach ($matches as $match) {
                            echo('<tr>');
                            $j = 0;
                            foreach ($match as $heading => $cell) {
                                if ($j == 0) {
                                    null;
                                } elseif ($j == 1) {
                                    // input with default value as generated
                                    $output = "<td><input type='text' id='match";
                                    $output .= ($i . "' name='match" . $i);
                                    $output .= "' value='" . $cell . "' /></td>";
                                    echo $output;
                                } else {
                                    echo('<td>' . $cell . '</td>');
                                }
                                $j++;
                            }
                            echo('</tr>');
                            $i++;
                        } ?>
                      </table>
                      <br>
                      <button class="button" type='submit' name='submit' value=Submit>Confirm Cover</button>
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
