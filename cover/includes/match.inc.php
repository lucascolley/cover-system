<?php

// Complex user-defined algorithm

include_once "functions.inc.php";

function matchingDepartment($classCode, $departments)
{
    // exception handling for special lessons
    if ($classCode == 'EPQ') {
        $matching = false;
    } elseif ($classCode == 'Pastoral P') {
        $matching = false;
    } else {
        $i = 1;
        $department = 'default';
        for ($i = 0; $i < 10; $i++) {
            if ($classCode[$i] == '/') {
                $department = substr($classCode, $i + 1, 2);
                break;
            }
        }
        if (in_array($department, $departments)) {
            $matching = true;
        }
    }
    // returns true if lesson subject is one of the teacher's departments
    return $matching;
}

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
    // returns the number of covers that are in the database for a teacher
    return $numCovers;
}

function getDepartments($staffCode)
{
    include "dbh.inc.php";
    // get all the class codes for a teacher's regular lessons
    $sql = "SELECT `lessons`.`classCode` FROM `lessons`, `users`
          WHERE `lessons`.`teacherEmail`=`users`.`usersEmail`
          AND `lessons`.`classCode`<>'free'
          AND `lessons`.`classCode`<>'P6'
          AND `lessons`.`classCode`<>'COVER'
          AND `lessons`.`classCode`<>'Pastoral P'
          AND `users`.`usersStaffCode`='" . $staffCode . "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../page_select.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    // get the subject codes from the class codes
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
    // returns all of the departments the teacher is in (the subjects they teach)
    return $departments;
}

function checkSLT($staffCode) // returns true if the teacher has been assigned as SLT
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
        } else {
            return false;
        }
    }
}

function conflicts($lessons, $teachers)
{
    // removes absent teachers from the teachers array
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
    return $teachers;
}

function score($lessons, $teachers) // for each lesson, give each teacher a score
{
    $lessons_scores = [];
    $processed_lessons = [];
    foreach ($lessons as $lesson) {
        // process lessons into a structured array with key of lessonID
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
                // analyse number of covers completed
                $numCovers = getNumCovers($coverStaffCode);
                // analyse teacher departments
                $departments = getDepartments($coverStaffCode);
                // check if teacher is of same department as lesson
                $matchingDepartment = matchingDepartment($classCode, $departments);
                // check if teacher is SLT
                $SLT = checkSLT($coverStaffCode);
                // calculate teacher score
                $score = 10;
                // decrease score for teachers who have already completed covers
                $score -= $numCovers;
                // increase score if the teacher is of the same department as the lesson
                if ($matchingDepartment) {
                    $score = $score + 2;
                }
                // half the score if the teacher is SLT (they should rarely cover)
                if ($SLT) {
                    $score /= 2;
                }
                $scores[$coverStaffCode] = $score;
            }
        }
        $lessons_scores[$lessonID] = $scores;
    }
    // returns scores for every teacher for all lessons that need cover, and the processed lessons
    $results = [$lessons_scores, $processed_lessons];
    return $results;
}

function matchlessons($lessons_scores, $processed_lessons)
{
    // match lessons based on maximising total score
    // ITERATIONS constant can be increased to increase optimality or decreased to make program faster
    $ITERATIONS = 10;
    $bestMatches = array();
    $bestScore = 0;
    for ($i = 0; $i < $ITERATIONS; $i++) {
        // shuffle the order which lessons are matched in each iteration
        // with enough iterations any favour given to a suboptimal solution based on order becomes negligible
        $keys = array_keys($lessons_scores);
        shuffle($keys);
        $shuffled = array();
        foreach ($keys as $key) {
          $shuffled[$key] = $lessons_scores[$key];
        }
        $lessons_scores_shuffled = $shuffled;
        $matches = array();
        $matchesScore = 0;
        $chosen = array();
        foreach ($lessons_scores_shuffled as $lessonID => $scores) {
            $match = array();
            $maxCoverStaffCode = "";
            $maxScore = 0;
            // chooses best available teacher score to cover
            foreach ($scores as $coverStaffCode => $score) {
                if ($score > $maxScore) {
                    // checks if the teacher has already been assigned cover for that period
                    $token = $processed_lessons[$lessonID][2] . $coverStaffCode;
                    if (!in_array($token, $chosen)) {
                        $maxCoverStaffCode = $coverStaffCode;
                        $maxScore = $score;
                    }
                }
            }
            // adds token for cover teacher for period so that they are not double booked
            $token = $processed_lessons[$lessonID][2] . $maxCoverStaffCode;
            $chosen[] = $token;
            // add this match to matches and sum total score for matches
            $matches[$lessonID] = $maxCoverStaffCode;
            $matchesScore += $maxScore;
        }
        // mark match as the best match if this iteration has the best score so far
        if ($matchesScore > $bestScore) {
            $bestScore = $matchesScore;
            $bestMatches = $matches;
        }
    }
    // returns the matches with the best sum of scores out of all iterations
    return $bestMatches;
}

function mainMatch($lessons, $teachers)
{
    // remove absent teachers from array of possible cover teachers
    $teachers = conflicts($lessons, $teachers);
    // get scores for each teacher, for each lesson based on how suitable they are for cover
    $results = score($lessons, $teachers);
    $lessons_scores = $results[0];
    $processed_lessons = $results[1];
    // match each lesson to a cover teacher
    $matches = matchlessons($lessons_scores, $processed_lessons);
    // make array of matches more structured with all attributes of lesson
    $coverMatches = [];
    foreach ($matches as $lessonID => $coverStaffCode) {
        $coverMatch = [];
        $coverMatch['lessonID'] = $lessonID;
        // insert code for external cover where no teachers were available to be assigned
        if ($coverStaffCode == "") {
            $coverMatch['coverStaffCode'] = 'EXT';
        } else {
            $coverMatch['coverStaffCode'] = $coverStaffCode;
        }
        $coverMatch['period'] = $processed_lessons[$lessonID][2];
        $coverMatch['room'] = $processed_lessons[$lessonID][3];
        $coverMatch['staffCode'] = $processed_lessons[$lessonID][0];
        $coverMatch['classCode'] = $processed_lessons[$lessonID][1];
        $coverMatches[] = $coverMatch;
    }

    // count the maximum amount of external covers which are needed in one period
    // this is roughly the amount of external supply teachers which will need to be hired
    $EXT = array();
    $EXT[1] = 0;
    $EXT[2] = 0;
    $EXT[3] = 0;
    $EXT[4] = 0;
    $EXT[5] = 0;
    $EXT[6] = 0;
    foreach ($coverMatches as $coverMatch) {
        if ($coverMatch['coverStaffCode'] == 'EXT') {
            switch ($coverMatch['period']) {
                case 1:
                    $EXT[1] += 1;
                    break;
                case 2:
                    $EXT[2] += 1;
                    break;
                case 3:
                    $EXT[3] += 1;
                    break;
                case 4:
                    $EXT[4] += 1;
                    break;
                case 5:
                    $EXT[5] += 1;
                    break;
                case 6:
                    $EXT[6] += 1;
                    break;
            }
        }
    }
    $EXTmax = max($EXT);
    // sort the coverMatches array by period
    $keys = array_column($coverMatches, 'period');
    array_multisort($keys, SORT_ASC, $coverMatches);
    // returns the structured coverMatches array and the number of external supply required
    $coverMatches = [$coverMatches, 'numEXT' => $EXTmax];
    return $coverMatches;
}
