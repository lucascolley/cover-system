<?php

  if (isset($_POST["submit"])) {

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      importTeachers($conn);
      
  } else {
      header("location: ../import_teachers.php");
      exit();
  }
