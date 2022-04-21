<?php

// Complex user-defined algorithm

include_once "functions.inc.php";

function getNumCovers($staffCode)
{
    include "dbh.inc.php";
    $sql = "SELECT `lessonID` FROM `covers`
            WHERE `coverStaffCode`='" . $staffCode . "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../page_select.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    $numCovers = 0;
    while ($row = mysqli_fetch_assoc($resultData)) {
        $numCovers++;
    }
    return $numCovers;
}

function getDepartments($staffCode)
{
    include "dbh.inc.php";
    $sql = "SELECT `lessons`.`classCode` FROM `lessons`, `users`
          WHERE `lessons`.`teacherEmail`=`users`.`usersEmail`
          AND `lessons`.`classCode`<>'free'
          AND `lessons`.`classCode`<>'P6'
          AND `users`.`usersStaffCode`='" . $staffCode . "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../page_select.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    $departments = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $classCode = $row['classCode'];
        $i = 1;
        for ($i = 0; $i < 10; $i++) {
            if ($classCode[$i] == '/') {
                $department = substr($classCode, $i + 1, 2);
                break;
            }
        }
        if (!in_array($department, $departments)) {
           $departments[] = $department;
        }
    }
    return $departments;
}

function checkSLT($staffCode)
{
    include "dbh.inc.php";
    $sql = "SELECT `usersSLT` FROM `users`
            WHERE `usersStaffCode`='" . $staffCode . "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../page_select.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    while ($row = mysqli_fetch_assoc($resultData)) {
        if ($row['usersSLT'] == 1) {
            return true;
        }
        else {
            return false;
        }
    }
}

function conflicts($lessons, $teachers)
{
    foreach ($lessons as $lesson) {
        $i = 0;
        foreach ($teachers as $teacher) {
            $staffCode = $teacher[0];
            if ($lesson[0] == $staffCode) {
                unset($teachers[$i]);
            }
            $i++;
        }
    }
    $results = [$lessons, $teachers];
    return $results;
}

function score($lessons, $teachers) // for each lesson, give each teacher a score
{
    $lessons_scores = [];
    $processed_lessons = [];
    foreach ($lessons as $lesson) {
        $staffCode = $lesson[0];
        $lessonID = $lesson[1];
        $classCode = $lesson[2];
        $period = $lesson[3];
        $room = $lesson[4];
        $attributes = [$staffCode, $classCode, $period, $room];
        $processed_lessons[$lessonID] = $attributes;
        $scores = [];
        foreach ($teachers as $teacher) {
            if ($teacher[1] == $period) {
                $coverStaffCode = $teacher[0];
                $score = 0;
                // Analyse number of covers completed
                $numCovers = getNumCovers($coverStaffCode);
                // Analyse teacher departments
                $departments = getDepartments($coverStaffCode);
                // Check if teacher is SLT
                $SLT = checkSLT($coverStaffCode);
                // calculate teacher score
                // SLT (-), Covers (-), Matching department (+)
                $score = random_int(0, 100); //
                $scores[$coverStaffCode] = $score;
            }
        }
        $lessons_scores[$lessonID] = $scores;
    }
    $results = [$lessons_scores, $processed_lessons];
    return $results;
}

function matchlessons($lessons_scores, $processed_lessons)
{
    $matches = [];
    $chosen = [];
    // match lessons based on maximising total score
    foreach ($lessons_scores as $lessonID => $scores) {
        $match = [];
        $maxCoverStaffCode = "";
        $maxScore = 0;
        foreach ($scores as $coverStaffCode => $score) {
            if ($score > $maxScore) {
                $token = $processed_lessons[$lessonID][2] . $coverStaffCode;
                if (!in_array($token, $chosen)) {
                    $maxCoverStaffCode = $coverStaffCode;
                    $maxScore = $score;
                }
            }
        }
        $token = $processed_lessons[$lessonID][2] . $maxCoverStaffCode;
        $chosen[] = $token;
        $match[$lessonID] = $maxCoverStaffCode;
        $matches[] = $match;
    }
    return $matches;
}

function mainMatch($lessons, $teachers)
{
    $results = conflicts($lessons, $teachers);
    $lessons = $results[0];
    $teachers = $results[1];
    $results = score($lessons, $teachers);
    $lessons_scores = $results[0];
    $processed_lessons = $results[1];
    $matches = matchlessons($lessons_scores, $processed_lessons);
    $coverMatches = [];
    foreach ($matches as $match) {
        foreach ($match as $lessonID => $coverStaffCode) {
            $coverMatch = [];
            $coverMatch['lessonID'] = $lessonID;
            $coverMatch['coverStaffCode'] = $coverStaffCode;
            $coverMatch['period'] = $processed_lessons[$lessonID][2];
            $coverMatch['room'] = $processed_lessons[$lessonID][3];
            $coverMatch['staffCode'] = $processed_lessons[$lessonID][0];
            $coverMatch['classCode'] = $processed_lessons[$lessonID][1];
            $coverMatches[] = $coverMatch;
        }
    }
    return $coverMatches;
}
