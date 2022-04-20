<?php

  if (isset($_POST["submit"])) {
      $date = $_POST["date"];

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      if (emptyInputDate($date) !== false) {
          header("location: ../view_cover.php?error=emptyinput");
          exit();
      }

      header("location: ../view_cover_date.php?date=" . $date);
  } else {
      header("location: ../view_cover.php");
      exit();
  }
