<?php
  // // get array of absent teachers and periods from post
  // // update the absences table
  // // pass date through
  // if (isset($_POST["submit"])) {
  //     $date = $_POST["date"];
  //     $absentTeachers = array();
  //     foreach ($_POST['selectName'] as $teacher) {
  //         $absentTeachers[] = $teacher;
  //     }
  //
  //     // update absences table with staff codes for chosen date
  //     require_once 'dbh.inc.php';
  //     require_once 'functions.inc.php';
  //
  //     updateAbsences($conn, $date, $absentTeachers);
  //
  //     // pass date through to select_periods
  //     header("location: ../select_periods.php?date=" . $date);
  //     exit();
  // } else {
  //     header("location: ../create_cover.php");
  //     exit();
  // }
