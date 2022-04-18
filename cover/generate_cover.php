<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_POST["matches"])) { ?>
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
                  $matches = unserialize($_POST["matches"]);
                  $keys = array_column($matches, 'period');
                  array_multisort($keys, SORT_ASC, $matches);
                  // output matching results
                  // echo "<pre>"; //
                  // print_r($matches); //
                  // echo "</pre>"; //
                  ?>
                  <br />
                  <form action='includes/generate_cover.inc.php' method='post'>
                    <?php
                    echo "<input type='hidden' id='matches' name='matches' value='" . $matches . "'/>"
                    ?>
                    <table>
                      <tr>
                        <?php
                        foreach($matches[0] as $heading => $cell) {
                            echo('<th>' . $heading . '</th>');
                        }
                        ?>
                      </tr>

                      <?php
                      $i = 0;
                      foreach($matches as $match) {
                          echo('<tr>');
                          $j = 0;
                          foreach($match as $heading => $cell) {
                              if ($j == 0) {
                                  // input with default value as generated
                                  // optional override
                                  echo "<td><input type='text' id='match" . $i . "' value='" . $cell . "' /></td>";
                              } else {
                                  echo('<td>' . $cell . '</td>');
                              }
                              $j++;
                          }
                          echo('</tr>');
                          $i++;
                      } ?>
                    </table>
                    <button type='submit' name='submit' value=Submit>Confirm Cover</button>
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
