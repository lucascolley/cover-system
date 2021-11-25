<?php
  include_once 'header.php'
?>
      <section class="import_teachers-form">
        <h1>Import Teachers</h1>
        <form action="includes/import_teachers.php" method="post">
          <button type="submit" name="submit">Import Teachers</button>
        </form>
        <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "stmtfailed") {
            echo "<p>Statement Failed</p>";
          }
          else if ($_GET["error"] == "none") {
            echo "<p>Teachers imported successfully!</p>";
          }
        }
        ?>
      </section>


<?php
  include_once 'footer.php'
?>