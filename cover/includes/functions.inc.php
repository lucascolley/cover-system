<?php

function emptyInputCreateUser($email, $pwd, $pwdRepeat)
{
    $result;
    if (empty($email) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email)
{
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd, $pwdRepeat)
{
    $result;
    if ($pwd !== $pwdRepeat) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function emailExists($conn, $email)
{
    // Parameterised SQL
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
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $email, $pwd, $admin)
{
    // Parameterised SQL
    $sql = "INSERT INTO users (usersEmail, usersPwd, admin) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../create_user.php?error=stmtfailed");
        exit();
    }

    // Hashing
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $email, $hashedPwd, $admin);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../create_user.php?error=none");
}

function emptyInputLogin($email, $pwd)
{
    $result;
    if (empty($email) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function loginUser($conn, $email, $pwd)
{
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
    } elseif ($checkPwd === true) {
        session_start();
        $_SESSION["userEmail"] = $emailExists["usersEmail"];
        $_SESSION["admin"] = $emailExists["usersAdmin"];
        header("location: ../index.php");
        exit();
    }
}

function deleteUser($conn, $userEmail, $adminEmail, $pwd)
{
    $emailExists = emailExists($conn, $userEmail);

    if ($emailExists === false) {
        header("location: ../delete_user.php?error=nouser");
        exit();
    }

    // Parameterised SQL
    $sql = "DELETE FROM users WHERE usersEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../delete_user.php?error=stmtfailed");
        exit();
    }

    $emailExists = emailExists($conn, $adminEmail);
    $pwdHashed = $emailExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../delete_user.php?error=wronglogin");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../delete_user.php?error=none");
}

function emptyInputDeleteUser($email, $pwd)
{
    $result;
    if (empty($email) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function emptyInputChangePwd($pwd, $newPwd, $newPwdRepeat)
{
    $result;
    if (empty($pwd) || empty($newPwd) || empty($newPwdRepeat)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function changePwd($conn, $email, $pwd, $newPwd)
{
    // Parameterised SQL
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

function importTeachers($conn)
{
    $file = fopen("import/users.csv", "r");
    $users = [];
    $x = 0;
    while (! feof($file)) {
        $users[$x] = fgetcsv($file);
        $x++;
    }
    fclose($file);

    $columns = array_shift($users);
    $lessonID = 1;
    foreach ($users as $user) {
        if ($user[0] != "") {
            $staffCode = $user[0];
            $email = $user[1];
            $title = $user[2];
            $forename = $user[3];
            $surname = $user[4];
            // Parameterised SQL
            $sql = "INSERT INTO users
      (usersEmail, usersTitle, usersForename, usersSurname, usersStaffCode)
      VALUES (?, ?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("location: ../import_teachers.php?error=stmtfailed");
                exit();
            }
            mysqli_stmt_bind_param(
                $stmt,
                "sssss",
                $email,
                $title,
                $forename,
                $surname,
                $staffCode
            );
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $lessons = [];
            $user = array_slice($user, 5);
            foreach ($user as $period) {
                array_push($lessons, $period);
            }
            $i = 5;
            foreach ($lessons as $lesson) {
                $time = $columns[$i];
                $week = $time[0];
                $day = $time[1] . $time[2] . $time[3];
                $period = $time[4];
                $lesson = explode(",", $lesson);
                $class_code = $lesson[0];
                $class_code = str_replace("'", '', $class_code);
                $class_code = str_replace("[", '', $class_code);
                $class_code = str_replace("]", '', $class_code);
                $class_code = substr($class_code, 0, -4);
                if ($class_code == '') {
                    $class_code = 'free';
                }
                $room = $lesson[1];
                $room = str_replace("'", '', $room);
                $room = str_replace("]", '', $room);
                $room = str_replace(" ", '', $room);
                $room = substr($room, 0, 3);
                // Parameterised SQL
                $sql = "INSERT INTO lessons
        (lessonID, teacherEmail, classCode, week, day, period, room)
        VALUES (?, ?, ?, ?, ?, ?, ?);";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("location: ../import_teachers.php?error=stmtfailed");
                    exit();
                }
                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssss",
                    $lessonID,
                    $email,
                    $class_code,
                    $week,
                    $day,
                    $period,
                    $room
                );
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $i++;
                $lessonID++;
            }
        }
    }
    header("location: ../import_teachers.php?error=none");
}

function emptyInputDate($date)
{
    $result;
    if (empty($date)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function getTeachers($conn)
{
    $sql = "SELECT `usersTitle`, `usersForename`, `usersSurname`, `usersStaffCode`
          FROM `users` WHERE `usersTeacher`=1 AND `usersEmail`<>'teacher';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../cover.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $teachers = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $teacher = array();
        $teacher[] = $row["usersStaffCode"];
        $teacher[] = $row["usersTitle"];
        $teacher[] = $row["usersForename"];
        $teacher[] = $row["usersSurname"];
        $teachers[] = $teacher;
    }
    return $teachers;

    mysqli_stmt_close($stmt);
}

function updateAbsences($conn, $date, $absentTeachers)
{
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6);
    $date = $year . "-" . $month . "-" . $day;
    foreach ($absentTeachers as $teacher) {
        $staffCode = substr($teacher, 0, 3);
        $sql = "INSERT INTO absences (staffCode, absenceDate) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../cover.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $staffCode, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function getAbsences($conn, $date)
{
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6);
    $date = $year . "-" . $month . "-" . $day;
    $sql = "SELECT staffCode, p1, p2, p3, p4, p5, p6 FROM absences
          WHERE absenceDate='" . $date . "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../cover.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $absentTeachers = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $teacher = array();
        $staffCode = $row["staffCode"];
        $sql = "SELECT usersTitle, usersForename, usersSurname FROM users
              WHERE usersStaffCode='" . $staffCode . "';";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../cover.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_execute($stmt);

        $resultData2 = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        $row2 = mysqli_fetch_assoc($resultData2);
        $name = $row2["usersTitle"] . " " . $row2["usersForename"] . " ";
        $name .= $row2["usersSurname"] . " " . $staffCode;
        $teacher[] = $name;
        $teacher[] = $row["p1"];
        $teacher[] = $row["p2"];
        $teacher[] = $row["p3"];
        $teacher[] = $row["p4"];
        $teacher[] = $row["p5"];
        $teacher[] = $row["p6"];
        $absentTeachers[] = $teacher;
    }

    return $absentTeachers;
}
