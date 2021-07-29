<?php

  if (isset($_POST["submit"])) {

    $email = $_POST["email"];
    $pwd = $_POST["pwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputDeleteUser($email, $pwd) !== false) {
      header("location: ../delete_user.php?error=emptyinput");
      exit();
    }

    deleteUser($conn, $email, $pwd);

  }
  else {
    header("location: ../delete_user.php");
    exit();
  }

?>
