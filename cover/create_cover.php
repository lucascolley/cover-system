<?php
  include_once 'header.php'
?>
      <section class="create_cover-form">
        <?php
        // Server-side scripting using request and response objects
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
              if (isset($_GET["error"])) {
                  if ($_GET["error"] == "emptyinput") {
                      echo "<p>Please fill in all fields!</p>";
                  }
              }
              if (isset($_GET["date"])) {
                $date = $_GET["date"];
                require_once 'includes/dbh.inc.php';
                require_once 'includes/functions.inc.php';
                $teachers = getTeachers($conn);
                ?>
                <h1>Create Cover for Day</h1>
                <head>
                  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                  <script>
                  function selectAll()
                  {
                      selectBox = document.getElementById("listbox2");

                      for (var i = 0; i < selectBox.options.length; i++)
                      {
                           selectBox.options[i].selected = true;
                      }
                  }
                  $(function() {
                    var teachers = <?php echo json_encode($teachers); ?>;
                    $.each(teachers, function (i, teacher) {
                        var entry = teacher[0] + ' ' + teacher[1] + ' ' + teacher[2] + ' ' + teacher[3];
                        $("#listbox1").append('<option>' + entry + '</option>');
                    });
                  });
                  $(function() {
                    $( "#but1" ).click(function()
                    {
                    $("#listbox1 option:selected").each(function()
                      {
                        $(this).remove().appendTo("#listbox2");
                        $("#listbox2").append($("#listbox2 option")
                                          .remove().sort(function(a, b) {
                            var at = $(a).text(),
                                bt = $(b).text();
                            return (at > bt) ? 1 : ((at < bt) ? -1 : 0);
                        }));
                      });
                    });
                  });
                  $(function() {
                      $( "#but2" ).click(function()
                      {
                        $("#listbox2 option:selected").each(function()
                        {
                          $(this).remove().appendTo("#listbox1");
                          $("#listbox1").append($("#listbox1 option")
                                            .remove().sort(function(a, b) {
                              var at = $(a).text(),
                                  bt = $(b).text();
                              return (at > bt) ? 1 : ((at < bt) ? -1 : 0);
                          }));
                        });
                      });
                    });
                    $(function() {
                        $( "#but3" ).click(function()
                        {
                          $("#listbox2 option").each(function()
                          {
                            $(this).remove().appendTo("#listbox1");
                            $("#listbox1").append($("#listbox1 option")
                                              .remove().sort(function(a, b) {
                                var at = $(a).text(),
                                    bt = $(b).text();
                                return (at > bt) ? 1 : ((at < bt) ? -1 : 0);
                            }));
                          });
                        });
                      });
                  </script>
                </head>
                <form action="includes/create_cover.inc.php" method="post">
                  <table border="1">
                    <tr>
                      <th>Present Teachers</th>
                      <th>Transfer Selection(s)</th>
                      <th>Absent Teachers</th>
                    </tr>
                    <tr>
                      <td>
                        <select id="listbox1" multiple="multiple" size="10"></select>
                      </td>
                      <td>
                        <input type="button" id="but1" value=">"/>
                        <input type="button" id="but2" value="<"/>
                        <input type="button" id="but3" value="<<<"/>
                        <button type="submit" name="submit" value=Submit onclick="selectAll();">Submit</button>
                      </td>
                      <td>
                        <select id="listbox2" name="selectName[]" multiple="multiple" size="10"></select>
                      </td>
                    </tr>
                  </table>
                </form>
                <?php
              } else {
                header("location: ./cover.php");
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
