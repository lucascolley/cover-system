<?php

  if (isset($_POST["submit"])) {
      $userEmail = $_POST["userEmail"];
      $adminEmail = $_POST["adminEmail"];
      $pwd = $_POST["pwd"];

      if ($userEmail == $adminEmail) {
          header("location: ../delete_user.php?error=selfdelete");
          exit();
      }

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      if (emptyInputDeleteUser($userEmail, $pwd) !== false) {
          header("location: ../delete_user.php?error=emptyinput");
          exit();
      }

      deleteUser($conn, $userEmail, $adminEmail, $pwd);
  } else {
      header("location: ../delete_user.php");
      exit();
  }
