<?php

// Complex user-defined algorithm

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
                // calculate teacher score
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
    $results = score($lessons, $teachers);
    $lessons_scores = $results[0];
    $processed_lessons = $results[1];
    $matches = matchlessons($lessons_scores, $processed_lessons);
    $coverMatches = [];
    foreach ($matches as $match) {
        foreach ($match as $lessonID => $coverStaffCode) {
            $coverMatch = [];
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
