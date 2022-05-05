<?php
  include_once 'header.php'
?>
      <section class="set_SLT-form">
      <?php
      if (isset($_SESSION["userEmail"])) {
          if ($_SESSION["admin"] !== 0) {
              ?>
              <?php
              require_once 'includes/dbh.inc.php';
              require_once 'includes/functions.inc.php';
              $teachers = getTeachers($conn);
              $teachersSLT = array();
              // remove SLT from the teachers array and append to their own array
              $i = 0;
              foreach ($teachers as $teacher) {
                  $SLT = $teacher[4];
                  if ($SLT == 1) {
                      $teachersSLT[] = $teacher;
                      unset($teachers[$i]);
                  }
                  $i++;
              }
              // re-set the indexes of teachers array after removals
              $teachers = array_values($teachers);
              ?>
              <br>
              <h1>Select SLT Teachers</h1>
              <br>
              <head>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                <script>
                function selectAll() // selects all entries in listbox2 when form is submitted
                {
                    selectBox = document.getElementById("listbox2");

                    for (var i = 0; i < selectBox.options.length; i++)
                    {
                         selectBox.options[i].selected = true;
                    }
                }
                $(function() {
                  // convert php arrays into json
                  var teachers = <?php echo json_encode($teachers); ?>;
                  var teachersSLT = <?php echo json_encode($teachersSLT); ?>;
                  // parse json and append entries for each array to the respective listboxes
                  $.each(teachers, function (i, teacher) {
                      var entry = teacher[0] + ' ' + teacher[1] + ' ' + teacher[2] + ' ' + teacher[3];
                      $("#listbox1").append('<option>' + entry + '</option>');
                  });
                  $.each(teachersSLT, function (i, teacher) {
                      var entry = teacher[0] + ' ' + teacher[1] + ' ' + teacher[2] + ' ' + teacher[3];
                      $("#listbox2").append('<option>' + entry + '</option>');
                  });
                });
                $(function() { // button 1 moves selected elements in listbox1 to listbox2
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
                $(function() { // button 2 moves selected elements in listbox2 to listbox1
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
                  $(function() { // button 3 moves all elements in listbox2 to listbox1
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
              <form action="includes/set_SLT.inc.php" method="post">
                <table border="1">
                  <tr>
                    <th>Non-SLT</th>
                    <th>Transfer Selection(s)</th>
                    <th>SLT</th>
                  </tr>
                  <tr>
                    <td>
                      <select id="listbox1" multiple="multiple" size="10"></select>
                    </td>
                    <td>
                      <div class="transfer-buttons">
                        <input type="button" style="width: 32%;" id="but1" value=">"/>
                        <input type="button" style="width: 32%;" id="but2" value="<"/>
                        <input type="button" style="width: 32%;" id="but3" value="<<<"/>
                      </div>
                      <br>
                      <button type="submit" style="width: 100%;" name="submit" value=Submit onclick="selectAll();">Submit</button>
                    </td>
                    <td>
                      <select id="listbox2" name="selectName[]" multiple="multiple" size="10"></select>
                    </td>
                  </tr>
                </table>
                <br>
              </form>
              <?php
              if (isset($_GET["error"])) {
                  if ($_GET["error"] = 'none') {
                      echo "<p>SLT Teachers Set Successfully!</p>";
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
