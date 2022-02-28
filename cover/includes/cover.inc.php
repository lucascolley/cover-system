<?php

  if (isset($_POST["submit"])) {
      $date = $_POST["date"];

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      if (emptyInputDate($date) !== false) {
          header("location: ../cover.php?error=emptyinput");
          exit();
      }

      header("location: ../create_cover.php?date=" . $date);
  } else {
      header("location: ../cover.php");
      exit();
  }
