<?php

// Complex user-defined algorithm

include_once "dbh.inc.php";
include_once "functions.inc.php";

function getNumCovers($conn, $staffCode)
{
    //
}

function getDepartments($conn, $staffCode)
{
    //
}

function checkSLT($conn, $staffCode)
{
    //
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
                $numCovers = getNumCovers($conn, $coverStaffCode);
                // Analyse teacher departments
                $departments = getDepartments($conn, $coverStaffCode);
                // Check if teacher is SLT
                $SLT = checkSLT($conn, $coverStaffCode);
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
