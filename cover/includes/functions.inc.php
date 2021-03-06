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
    // parameterised SQL
    $sql = "INSERT INTO users (usersEmail, usersPwd, usersAdmin) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../create_user.php?error=stmtfailed");
        exit();
    }

    // password hashing implemented
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
    // get user info for inputted email
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
    // check if user to be deleted exists
    $emailExists = emailExists($conn, $userEmail);
    if ($emailExists === false) {
        header("location: ../delete_user.php?error=nouser");
        exit();
    }

    // parameterised SQL
    $sql = "DELETE FROM users WHERE usersEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../delete_user.php?error=stmtfailed");
        exit();
    }

    // get info for admin
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
    // parameterised SQL
    $sql = "UPDATE users SET usersPwd=? WHERE usersEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../change_pwd.php?error=stmtfailed");
        exit();
    }

    // get user info for email
    $emailExists = emailExists($conn, $email);
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
    // import rows of users.csv into users array
    $file = fopen("import/users.csv", "r");
    $users = [];
    $x = 0;
    while (! feof($file)) {
        $users[$x] = fgetcsv($file);
        $x++;
    }
    fclose($file);

    // separate the column headings from the rest of the array
    $columns = array_shift($users);
    $lessonID = 1;
    foreach ($users as $user) {
        if ($user[0] != "") {
            $staffCode = $user[0];
            $email = $user[1];
            $title = $user[2];
            $forename = $user[3];
            $surname = $user[4];
            // parameterised SQL
            // import relevant data into users table
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

            // get the lessons from the imported csv
            $lessons = [];
            $user = array_slice($user, 5);
            foreach ($user as $period) {
                array_push($lessons, $period);
            }
            // format lessons into desired form
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
                // parameterised SQL
                // import relevant data into lessons table
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
    // get all teachers' info from the users table
    $sql = "SELECT `usersTitle`, `usersForename`, `usersSurname`, `usersStaffCode`, `usersSLT`
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
        $teacher[] = $row["usersSLT"];
        $teachers[] = $teacher;
    }
    return $teachers;

    mysqli_stmt_close($stmt);
}

function updateAbsences($conn, $date, $absentTeachers)
{
    // format date
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6);
    $date = $year . "-" . $month . "-" . $day;
    // delete all absences currently in table so that they can be replaced
    $sql = "DELETE FROM `absences` WHERE `absenceDate`='" . $date . "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../cover.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    // insert absences as inputted
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
    // format date
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
    // get teacher's info for each one that is absent
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

function updateAbsentPeriods($conn, $date, $absentTeachers)
{
    // format date
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6);
    $date = $year . "-" . $month . "-" . $day;
    foreach ($absentTeachers as $teacher) {
        $staffCode = substr($teacher[0], -3);
        $p1 = $teacher[1];
        $p2 = $teacher[2];
        $p3 = $teacher[3];
        $p4 = $teacher[4];
        $p5 = $teacher[5];
        $p6 = $teacher[6];
        $sql = "UPDATE absences SET p1=?,p2=?,p3=?,p4=?,p5=?,p6=?
                WHERE staffCode=? AND absenceDate=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../cover.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "iiiiiiss", $p1, $p2, $p3, $p4, $p5, $p6, $staffCode, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function getFreeTeachers($conn, $date)
{
    $dates = array();
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6);
    $date = $year . "-" . $month . "-" . $day;
    $date = new DateTime($date);
    $originalDate = $date;
    // object-oriented methods to handle date
    $day = $originalDate->format('D');
    $date->modify("Monday this week");
    $formattedDate = $date->format('Y-m-d');
    // check whether it is week A or B on timetable for date
    $sql = "SELECT `week` FROM `dates` WHERE `monday`=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../cover.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $formattedDate);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $week);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $sql = "SELECT `users`.`usersStaffCode`, `lessons`.`period`
            FROM `users`, `lessons`
            WHERE `users`.`usersEmail` = `lessons`.`teacherEmail`
            AND `lessons`.`week` = ? AND `lessons`.`day` = ?
            AND (`lessons`.`classCode` = 'free' OR `lessons`.`classCode` = 'COVER');";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../cover.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "is", $week, $day);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $teachers = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $teacher = array();
        $teacher[] = $row["usersStaffCode"];
        $teacher[] = $row["period"];
        $teachers[] = $teacher;
    }
    $results = array();
    $results[] = $teachers;
    $results[] = $week;
    $results[] = $day;
    return $results;
}

function getAbsentLessons($conn, $absentTeachers, $week, $day)
{
    $absentLessons = array();
    foreach ($absentTeachers as $teacher) {
        $staffCode = substr($teacher[0], -3);
        for ($period = 1; $period <= 6; $period++) {
            if ($teacher[$period] == 1) {
                // cross-table parameterised sql
                $sql = "SELECT `lessons`.`lessonID`, `lessons`.`classCode`,
                        `lessons`.`period`, `lessons`.`room`
                        FROM `users`, `lessons`
                        WHERE `users`.`usersStaffCode` = ?
                        AND `users`.`usersEmail` = `lessons`.`teacherEmail`
                        AND `lessons`.`classCode` <> 'free'
                        AND `lessons`.`classCode` <> 'P6'
                        AND `lessons`.`classCode` <> 'COVER'
                        AND `lessons`.`week` = ? AND `lessons`.`day` = ?
                        AND `lessons`.`period` = ?;";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("location: ../cover.php?error=stmtfailed");
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "sisi", $staffCode, $week, $day, $period);
                mysqli_stmt_execute($stmt);
                $resultData = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                $row = mysqli_fetch_assoc($resultData);
                if ($row["period"] <> '') {
                    $lesson = array();
                    $lesson[] = $staffCode;
                    $lesson[] = $row["lessonID"];
                    $lesson[] = $row["classCode"];
                    $lesson[] = $row["period"];
                    $lesson[] = $row["room"];
                    $absentLessons[] = $lesson;
                }
            }
        }
    }
    return $absentLessons;
}

function insertCovers($conn, $date, $matches)
{
    // format date
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6);
    $date = $year . "-" . $month . "-" . $day;
    // delete covers already set for that date to be replaced
    $sql = "DELETE FROM `covers` WHERE `coverDate`='" . $date . "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../cover.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    foreach ($matches as $match) {
        $lessonID = $match['lessonID'];
        $coverStaffCode = $match['coverStaffCode'];
        $period = $match['period'];
        $room = $match['room'];
        $staffCode = $match['staffCode'];
        $classCode = $match['classCode'];
        $sql = "INSERT INTO `covers` (`coverDate`, `lessonID`, `coverStaffCode`,
                `period`, `room`, `staffCode`, `classCode`)
                VALUES (?, ?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../cover.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param(
            $stmt,
            "sisisss",
            $date,
            $lessonID,
            $coverStaffCode,
            $period,
            $room,
            $staffCode,
            $classCode
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function getCovers($conn, $date)
{
    // format date
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6);
    $date = $year . "-" . $month . "-" . $day;
    $sql = "SELECT `lessonID`, `coverStaffCode`,
            `period`, `room`, `staffCode`, `classCode`
            FROM `covers` WHERE `coverDate`=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../cover.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $date);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    // return false if there is no cover set for that date
    if ($resultData->num_rows === 0) {
        return false;
    }

    $covers = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $cover = array();
        $cover[] = $row["lessonID"];
        $cover[] = $row["coverStaffCode"];
        $cover[] = $row["period"];
        $cover[] = $row["room"];
        $cover[] = $row["staffCode"];
        $cover[] = $row["classCode"];
        $covers[] = $cover;
    }
    return $covers;
}

function updateSLT($conn, $SLT)
{
    // set all SLT fields to 0 so that they can be re-set
    $sql = "UPDATE `users` SET `usersSLT`=0;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../setSLT.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    foreach ($SLT as $teacher) {
        $staffCode = substr($teacher, 0, 3);
        $sql = "UPDATE `users` SET `usersSLT`=1 WHERE `usersStaffCode`=?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../set_SLT.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $staffCode);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
