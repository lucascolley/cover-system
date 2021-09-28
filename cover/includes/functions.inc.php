<?php

function emptyInputCreateUser($email, $pwd, $pwdRepeat) {
  $result;
  if (empty($email) || empty($pwd) || empty($pwdRepeat)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function invalidEmail($email) {
  $result;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function pwdMatch($pwd, $pwdRepeat) {
  $result;
  if ($pwd !== $pwdRepeat) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function emailExists($conn, $email) {
  $sql = "SELECT * FROM users WHERE usersEmail = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../create_user.php?error=stmtfailed");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($resultData)) {
    return $row;
  }
  else {
    $result = false;
    return $result;
  }

  mysqli_stmt_close($stmt);
}

function createUser($conn, $email, $pwd, $admin) {
  $sql = "INSERT INTO users (usersEmail, usersPwd, admin) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../create_user.php?error=stmtfailed");
    exit();
  }

  $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

  mysqli_stmt_bind_param($stmt, "sss", $email, $hashedPwd, $admin);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("location: ../create_user.php?error=none");
  }

function emptyInputLogin($email, $pwd) {
  $result;
  if (empty($email) || empty($pwd)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function loginUser($conn, $email, $pwd) {
  $emailExists = emailExists($conn, $email);

  if ($emailExists === false) {
    header("location: ../login.php?error=wronglogin");
    exit();
  }

  $pwdHashed = $emailExists["usersPwd"];
  $checkPwd = password_verify($pwd, $pwdHashed);

  if ($checkPwd === false) {
    header("location: ../login.php?error=wronglogin");
    exit();
  }
  else if ($checkPwd === true) {
    session_start();
    $_SESSION["userEmail"] = $emailExists["usersEmail"];
    $_SESSION["admin"] = $emailExists["admin"];
    header("location: ../index.php");
    exit();
  }

}

function deleteUser($conn, $email, $pwd) {

  $emailExists = emailExists($conn, $email);

  if ($emailExists === false) {
    header("location: ../delete_user.php?error=wronglogin");
    exit();
  }

  $sql = "DELETE FROM users WHERE usersEmail=?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../delete_user.php?error=stmtfailed");
    exit();
  }

  $pwdHashed = $emailExists["usersPwd"];
  $checkPwd = password_verify($pwd, $pwdHashed);

  if ($checkPwd === false) {
    header("location: ../delete_user.php?error=wronglogin");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("location: ../delete_user.php?error=none");
  }

function emptyInputDeleteUser($email, $pwd) {
  $result;
  if (empty($email) || empty($pwd)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function emptyInputChangePwd($pwd, $newPwd, $newPwdRepeat) {
  $result;
  if (empty($pwd) || empty($newPwd) || empty($newPwdRepeat)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function changePwd($conn, $email, $pwd, $newPwd) {
  $emailExists = emailExists($conn, $email);

  $sql = "UPDATE users SET usersPwd=? WHERE usersEmail=?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../change_pwd.php?error=stmtfailed");
    exit();
  }

  $pwdHashed = $emailExists["usersPwd"];
  $checkPwd = password_verify($pwd, $pwdHashed);

  if ($checkPwd === false) {
    header("location: ../change_pwd.php?error=wronglogin");
    exit();
  }

  $newHashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);

  mysqli_stmt_bind_param($stmt, "ss", $newHashedPwd, $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("location: ../change_pwd.php?error=none");
}

?>
