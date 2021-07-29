<?php

  if (isset($_POST["submit"])) {

    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];
    $admin = $_POST["admin"];
    if ($admin !== '1') {
      $admin = 0;
    }

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputCreateUser($email, $pwd, $pwdRepeat) !== false) {
      header("location: ../create_user.php?error=emptyinput");
      exit();
    }
    if (invalidEmail($email) !== false) {
      header("location: ../create_user.php?error=invalidemail");
      exit();
    }
    if (pwdMatch($pwd, $pwdRepeat) !== false) {
      header("location: ../create_user.php?error=passwordmismatch");
      exit();
    }
    if (emailExists($conn, $email) !== false) {
      header("location: ../create_user.php?error=emailtaken");
      exit();
    }

    createUser($conn, $email, $pwd, $admin);

  }
  else {
    header("location: ../create_user.php");
    exit();
  }

?>
