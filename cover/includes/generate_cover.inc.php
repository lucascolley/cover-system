<?php
  if (isset($_POST["submit"])) {
      $matchesString = $_POST["matches"];
      $matches = unserialize($matchesString);
      $i = 0;
      foreach($matches as $match) {
          $j = 0;
          foreach($match as $heading => $cell) {
              if ($j == 0) {
                  $id = "match" . $i;
                  $matches[$i]['coverStaffCode'] = $_POST[$id];
              }
              $j++;
          }
          $i++;
      }

      echo "<pre>";
      print_r($matches);
      echo "</pre>";

      // // update absences table with staff codes for chosen date
      // require_once 'dbh.inc.php';
      // require_once 'functions.inc.php';
      //
      // updateAbsences($conn, $date, $absentTeachers);
      //
      // // pass date through to select_periods
      // header("location: ../select_periods.php?date=" . $date);
      // exit();
  } else {
      header("location: ../cover.php");
      exit();
  }
