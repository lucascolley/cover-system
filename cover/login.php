<?php echo file_get_contents("html/header.html"); ?>
    <h1>Login to the Cover System!</h1>
    <FORM ACTION=process_login.php METHOD=POST>
       email: <INPUT TYPE=TEXT NAME="email"><BR>
       password: <INPUT TYPE=PASSWORD NAME="password">
      <P><INPUT TYPE=SUBMIT VALUE="submit">
    </FORM>
    <br>
<?php echo file_get_contents("html/footer.html"); ?>
