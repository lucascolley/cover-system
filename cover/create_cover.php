<?php
  include_once 'header.php'
?>
      <section class="create_cover-form">
        <?php
        // Server-side scripting using request and response objects
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                if (isset($_GET["date"])) {
                    $date = $_GET["date"];
                    require_once 'includes/dbh.inc.php';
                    require_once 'includes/functions.inc.php';
                    $teachers = getTeachers($conn);
                    $absentTeachers = getAbsences($conn, $date);
                    $absentStaffCodes = array();
                    // separate the staff codes for the absent teachers
                    foreach ($absentTeachers as $absentTeacher) {
                        $name = $absentTeacher[0];
                        $absentStaffCode = substr($name, -3, 3);
                        $absentStaffCodes[] = $absentStaffCode;
                    }
                    // remove absent teachers from the teachers array
                    $i = 0;
                    foreach ($teachers as $teacher) {
                        $staffCode = $teacher[0];
                        if (in_array($staffCode, $absentStaffCodes)) {
                            unset($teachers[$i]);
                        }
                        $i++;
                    }
                    $teachers = array_values($teachers);
                    // separate the names of the absent teachers
                    $fullNames = array();
                    foreach ($absentTeachers as $absentTeacher) {
                        $name = $absentTeacher[0];
                        $fullName = substr($name, 0, -4);
                        $fullNames[] = $fullName;
                    }
                    // rejoin the absent teachers parts in table entry format
                    $i = 0;
                    foreach ($absentTeachers as $absentTeacher) {
                        $absentTeachers[$i] = $absentStaffCodes[$i] . " ";
                        $absentTeachers[$i] .= $fullNames[$i];
                        $i++;
                    }
                    ?>
                <br>
                <h1>Create Cover for Day</h1>
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
                    var absentTeachers = <?php echo json_encode($absentTeachers); ?>;
                    // parse json and append entries for each array to the respective listboxes
                    $.each(teachers, function (i, teacher) {
                        var entry = teacher[0] + ' ' + teacher[1] + ' ' + teacher[2] + ' ' + teacher[3];
                        $("#listbox1").append('<option>' + entry + '</option>');
                    });
                    $.each(absentTeachers, function (i, teacher) {
                        var entry = teacher
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
                <form action="includes/create_cover.inc.php" method="post">
                  <input type="hidden" id="date" name="date" value=<?php echo('"' . $date . '"');?>>
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
