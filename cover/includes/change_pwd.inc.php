<?php

  if (isset($_POST["submit"])) {
      $email = $_POST["email"];
      $pwd = $_POST["pwd"];
      $newPwd = $_POST["newpwd"];
      $newPwdRepeat = $_POST["newpwdrepeat"];

      require_once 'dbh.inc.php';
      require_once 'functions.inc.php';

      if (emptyInputChangePwd($pwd, $newPwd, $newPwdRepeat) !== false) {
          header("location: ../change_pwd.php?error=emptyinput");
          exit();
      }
      if (pwdMatch($newPwd, $newPwdRepeat) !== false) {
          header("location: ../change_pwd.php?error=passwordmismatch");
          exit();
      }

      changePwd($conn, $email, $pwd, $newPwd);
  } else {
      header("location: ../change_pwd.php");
      exit();
  }
