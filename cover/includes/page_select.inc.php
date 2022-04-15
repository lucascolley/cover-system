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
                    // write absences for date to a file to be read by match.py
                    include_once 'dbh.inc.php';
                    include_once 'functions.inc.php';
                    $absentTeachers = getAbsences($conn, $date);
                    $datefile = "datefiles/" . $date . ".csv";
                    $outfile = fopen($datefile, "w");
                    foreach ($absentTeachers as $teacher) {
                        fputcsv($outfile, $teacher);
                    }
                    fclose($outfile);
                // run match.py
                    // go to generate cover after match.py is ran
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
