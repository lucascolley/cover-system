<?php

  if (isset($_POST["submit"])) {
      $date = $_POST["date"];
      echo $date;

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';
      $absentTeachers = getAbsences($conn, $date);

      for ($teacher_count = 0; $teacher_count < count($absentTeachers); $teacher_count++) {
          for ($period_count = 1; $period_count < 7; $period_count++) {
              $period = $teacher_count . "p" . $period_count;
              if (isset($_POST[$period])) {
                  $absentTeachers[$teacher_count][$period_count] = 1;
              } else {
                  $absentTeachers[$teacher_count][$period_count] = 0;
              }
          }
      }

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      updateAbsentPeriods($conn, $date, $absentTeachers);

      header("location: ../next_page.php?date=" . $date);
      exit();
  } else {
      header("location: ../create_cover.php");
      exit();
  }
