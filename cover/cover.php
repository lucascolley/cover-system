<?php
  include_once 'header.php'
?>
      <section class="cover-form">
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) { ?>
              <html lang="en">
              <h1>Create Cover for Day</h1>
              <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>jQuery UI Datepicker - Default functionality</title>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
                <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
                <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                <script>
                $( function() {
                    $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
                  } );
                </script>
              </head>
              <body>
                <form action='includes/cover.inc.php' method='post'>
                  Enter Date: <input type='text' name='date' id='datepicker'><br>
                  <button type='submit' name='submit'>Select Cover Lessons</button>
                </form>
              </body>
              </html>

              <?php
              if (isset($_GET["error"])) {
                  if ($_GET["error"] == "emptyinput") {
                      echo "<p>Please fill in all fields!</p>";
                  }
              }
            } else {
                header("location: ./index.php");
                exit();
            }
        } else {
            header("location: ./login.php");
            exit();
        }
        ?>
      </section>


<?php
  include_once 'footer.php'
?>
