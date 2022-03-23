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
                <h1>Tick Boxes for Absent Periods</h1>
                <head>
                </head>
                <?php
                // get array of absent teachers
                require_once 'includes/dbh.inc.php';
                require_once 'includes/functions.inc.php';
                $absentTeachers = getAbsences($conn, $date);
                print_r($absentTeachers);

                $cols = 7;

                $output = "<form action='includes/select_periods.inc.php' method='post'>";
                $output .= "<table border='1'>\n<tr>\n<th>Absent Teachers</th>\n";
                $output .= "<th>P1</th>\n<th>P2</th>\n<th>P3</th>\n<th>P4</th>\n";
                $output .= "<th>P5</th>\n<th>P6</th>\n</tr>";

                $period_count = 0;

                for ($i = 0; $i < count($absentTeachers); $i++) {
                    $teacherName = $absentTeachers[i][0];
                    if ($period_count == 0) {
                        $output .= "<tr>\n<td>" . $teacherName . "</td>\n";
                    }
                    else {
                      $period = "p" . $period_count;
                      // pre-check boxes where absent in database
                      $checked = " checked";
                      $output .= "<input type='checkbox' id=" . $period . " name= " . $period . " value=" . $period . ">\n";
                    }

                    $period_count++;

                    // end the row if we've generated the expected number of columns
                    // or if we're at the end of the array
                    if ($period_count == $cols || $i == (count($absentTeachers) - 1)) {
                        $output .= "</tr>\n";
                        $period_count = 0;
                    }
                }

                $output .= "</table>\n</form>\n";
                echo $output
                ?>
                <form action="includes/select_periods.inc.php" method="post">
                  <table border="1">
                    <tr>
                      <th>Absent Teachers</th>
                      <th>P1</th>
                      <th>P2</th>
                      <th>P3</th>
                      <th>P4</th>
                      <th>P5</th>
                      <th>P6</th>
                    </tr>
                    <tr>
                      <td>
                        <!--teacher name-->
                      </td>
                      <td>
                        <input type="checkbox" id="p1" name="p1" value="p1">
                      </td>
                      <td>
                        <input type="checkbox" id="p2" name="p2" value="p2">
                      </td>
                      <td>
                        <input type="checkbox" id="p3" name="p3" value="p3">
                      </td>
                      <td>
                        <input type="checkbox" id="p4" name="p4" value="p4">
                      </td>
                      <td>
                        <input type="checkbox" id="p5" name="p5" value="p5">
                      </td>
                      <td>
                        <input type="checkbox" id="p6" name="p6" value="p6">
                      </td>
                    </tr>
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
