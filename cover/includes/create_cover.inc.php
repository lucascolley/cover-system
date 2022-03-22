<?php

  if (isset($_POST["submit"])) {
      $date = $_POST["date"];
      $absentTeachers = array();
      foreach ($_POST['selectName'] as $teacher) {
          $absentTeachers[] = $teacher;
      }
  // update absences table with staff codes for current date
  // pass date through to select_periods
  } else {
      header("location: ../create_cover.php");
      exit();
  }
