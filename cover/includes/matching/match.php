<?php

// Complex user-defined algorithm

function score($lessons, $teachers) // for each lesson, give each teacher a score
{
    $lessons_scores = [];
    foreach ($lessons as $lesson) {
        $scores = [];
        foreach ($teachers as $teacher) {
            $score = 0;
            // calculate teacher score
            $scores[$teacher] = $score;
        }
        $lessons_scores[$lesson] = $scores;
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
