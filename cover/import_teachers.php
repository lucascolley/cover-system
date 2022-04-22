<?php
  include_once 'header.php'
?>
      <section class="import_teachers-form">
        <br>
        <h1>Import Teachers</h1>
        <br>
        <h3>Place csv at 'cover/includes/import/users.csv' and hit go!</h3>
        <br>
        <form action="includes/import_teachers.inc.php" method="post">
          <button type="submit" name="submit">Go!</button>
        </form>
        <br>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "stmtfailed") {
                echo "<p>Statement Failed</p>";
            } elseif ($_GET["error"] == "none") {
                echo "<p>Teachers imported successfully!</p>";
            }
        }
        ?>
      </section>


<?php
  include_once 'footer.php'
?>
