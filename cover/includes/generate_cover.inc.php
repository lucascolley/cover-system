<?php

  if (isset($_POST["submit"])) {
      $date = $_POST["date"];
      $matchesString = $_POST["matches"];
      $matches = unserialize($matchesString);
      // sort matches array by period
      $keys = array_column($matches, 'period');
      array_multisort($keys, SORT_ASC, $matches);
      // get matches from POST into multi-dimensional array
      $i = 0;
      foreach ($matches as $match) {
          $j = 0;
          foreach ($match as $heading => $cell) {
              if ($j == 0) {
                  $id = "match" . $i;
                  $matches[$i]['coverStaffCode'] = $_POST[$id];
              }
              $j++;
          }
          $i++;
      }

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      insertCovers($conn, $date, $matches);

      // pass through to confirmation to display covers
      header("location: ../confirmation.php?date=" . $date);
      exit();
  } else {
      header("location: ../cover.php");
      exit();
  }
