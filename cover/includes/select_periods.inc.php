<?php

  if (isset($_POST["submit"])) {
      $date = $_POST["date"];

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

      // update Absences table with correct periods
      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';
      updateAbsentPeriods($conn, $date, $absentTeachers);

      // go to page where user can choose to generate cover or return home
      header("location: ../page_select.php?date=" . $date);
      exit();
  } else {
      header("location: ../create_cover.php");
      exit();
  }
