<?php

  if (isset($_POST["submit"])) {
      $date = $_POST["date"];
      $SLT = array();
      foreach ($_POST['selectName'] as $teacher) {
          $SLT[] = $teacher;
      }

      // update users table with SLT distinctions
      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      updateSLT($conn, $SLT);

      // go to confirmation
      header("location: ../set_SLT.php?error=none");
      exit();

  } else {
      header("location: ../set_SLT.php");
      exit();
  }
