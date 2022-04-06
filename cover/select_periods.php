<?php
  include_once 'header.php'
?>
      <section class="select_periods-form">
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"]; ?>
                <h1>Tick Boxes for Absent Periods</h1>
                <head>
                </head>
                <?php
                // get array of absent teachers
                    require_once 'includes/dbh.inc.php';
                    require_once 'includes/functions.inc.php';
                    $absentTeachers = getAbsences($conn, $date);

                    $output = "\n<form action='includes/select_periods.inc.php' method='post'>\n";
                    $output .= "<input type='hidden' id='date' name='date' value='" . $date . "'>\n";
                    $output .= "<input type='hidden' id='teachers' name='teachers' value='" . $absentTeachers . "'>\n";
                    $output .= "<table border='1'>\n<tr>\n<th>Absent Teachers</th>\n";
                    $output .= "<th>P1</th>\n<th>P2</th>\n<th>P3</th>\n<th>P4</th>\n";
                    $output .= "<th>P5</th>\n<th>P6</th>\n</tr>";

                    for ($teacher_count = 0; $teacher_count < count($absentTeachers); $teacher_count++) {
                        $teacherName = $absentTeachers[$teacher_count][0];
                        $output .= "<tr>\n<td>" . $teacherName . "</td>\n";
                        for ($period_count = 1; $period_count < 7; $period_count++) {
                            $period = $teacher_count . "p" . $period_count;
                            // pre-check boxes where absent in database
                            if ($absentTeachers[$teacher_count][$period_count] == 1) {
                                $checked = " checked";
                            } else {
                                $checked = "";
                            }
                            $output .= "<td>\n<input type='checkbox' id=" . $period;
                            $output .= " name=" . $period . " value=" . $period;
                            $output .= $checked . ">\n</td>\n";
                        }
                        $output .= "</tr>\n";
                        $period_count = 0;
                    }

                    $output .= "</table>\n";
                    $output .= "<button type='submit' name='submit' value=Submit>Submit</button>";
                    $output .= "\n</form>\n";

                    echo $output; ?>
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
