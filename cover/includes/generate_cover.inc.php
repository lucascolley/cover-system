<?php
  if (isset($_POST["submit"])) {
      $date = $_POST["date"];
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

      // insert final covers into database
      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      insertCovers($conn, $date, $matches); //

      // pass through to next page
      // header("location: ../select_periods.php?date=" . $date);
      exit();
  } else {
      header("location: ../cover.php");
      exit();
  }
