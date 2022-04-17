<?php

// Complex user-defined algorithm

function score($lessons, $teachers) // for each lesson, give each teacher a score
{
    $lessons_scores = [];
    foreach ($lessons as $lesson) {
        $staffCode = $lesson[0];
        $classCode = $lesson[1];
        $period = $lesson[2];
        $room = $lesson[3];
        $lessonID = $staffCode . $period;
        $scores = [];
        foreach ($teachers as $teacher) {
            if ($teacher[1] == $period) {
                $staffCode = $teacher[0];
                $score = 0;
                // calculate teacher score
                $score = random_int(0, 100);
                $scores[$staffCode] = $score;
            }
        }
        $lessons_scores[$lessonID] = $scores;
    }
    return $lessons_scores;
}

function matchlessons($lessons_scores)
{
    $matches = [];
    // match lessons based on maximising total score
    return $matches;
}

function mainMatch($lessons, $teachers)
{
    $lessons_scores = score($lessons, $teachers);
    $matches = matchlessons($lessons_scores);
    return $matches;
}
