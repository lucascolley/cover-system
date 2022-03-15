<?php

  if (isset($_POST["submit"])) {
    $absentTeachers = array();
    foreach ($_POST['selectName'] as $teacher)
    {
      $absentTeachers[] = $teacher;
    }
    echo '<pre>'; print_r($absentTeachers); echo '</pre>';
  } else {
      header("location: ../create_cover.php");
      exit();
  }

?>
