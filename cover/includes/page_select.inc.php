<?php
  session_start();
?>
      <section>
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"]; ?>
                    <h1></h1>
                    <head>
                    </head>
                    <?php
                    // get absences for date
                    include_once 'dbh.inc.php';
                    include_once 'functions.inc.php';
                    $absentTeachers = getAbsences($conn, $date);
                    // get list of teachers with their lessons for date
                    $results = getFreeTeachers($conn, $date);
                    $freeTeachers = $results[0];
                    $week = $results[1];
                    $day = $results[2];
                    // get list of lessons that need to be covered
                    $absentLessons = getAbsentLessons($conn, $absentTeachers, $week, $day);
                    // match cover teaches to the lessons that need cover
                    include_once 'matching/match.php';
                    $matches = mainMatch($absentLessons, $freeTeachers);
                    echo "<pre>"; //
                    print_r($matches); //
                    echo "</pre>"; //
                    // go to generate cover to present $matches
                    //code...
                } else {
                    header("location: ../cover.php");
                }
            } else {
                header("location: ../index.php");
                exit();
            }
        } else {
            header("location: ../login.php");
            exit();
        }
        ?>
      </section>
