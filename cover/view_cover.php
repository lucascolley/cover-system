<?php
  include_once 'header.php'
?>
      <?php
      if (isset($_SESSION["userEmail"])) {
          ?>
          <br>
          <h1>Choose Date to view cover for:</h1>
          <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>jQuery UI Datepicker - Default functionality</title>
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
            <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
            <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
            <script>
            $( function() {
                $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
              } );
            </script>
          </head>
          <body>
            <br>
            <form action='includes/view_cover.inc.php' method='post'>
              Enter Date: <input type='text' name='date' id='datepicker'
                                 value=<?php echo "'" . date('d-m-Y') . "'"?>><br>
              <button type='submit' name='submit'>View Cover</button>
            </form>
          </body>
      <?php
      } else {
          header("location: ./login.php");
          exit();
      }
      ?>
      </section>
<?php
  include_once 'footer.php'
?>
