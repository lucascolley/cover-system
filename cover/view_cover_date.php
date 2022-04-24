<?php
  include_once 'header.php'
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"];
                    require_once 'includes/dbh.inc.php';
                    require_once 'includes/functions.inc.php';
                    $covers = getCovers($conn, $date);
                    if (!$covers) {
                        echo "<h1>No Cover List set for " . $date . "!</h1>";
                    } else {
                        // sort covers by period
                        $keys = array_column($covers, 2);
                        array_multisort($keys, SORT_ASC, $covers);
                        ?>
                        <h1>Cover List for <?php echo $date ?>:</h1>
                        <br />
                        <table>
                          <tr>
                            <th>Cover Staff Code</th>
                            <th>Period</th>
                            <th>Room</th>
                            <th>Staff Code</th>
                            <th>Class Code</th>
                          </tr>
                          <?php
                          foreach ($covers as $cover) {
                              echo('<tr>');
                              $output = false;
                              foreach ($cover as $heading => $cell) {
                                  if ($output) {
                                      echo('<td>' . $cell . '</td>');
                                  }
                                  $output = true;
                              }
                              echo('</tr>');
                          }
                    }
                    ?>
                        </table>
                    }
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
