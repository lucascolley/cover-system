<?php
  include_once 'header.php'
?>
      <section class="create_user-form">
        <?php
        if (isset($_SESSION["userEmail"])) {
            if ($_SESSION["admin"] !== 0) {
                ?>
                <br>
                <h1>Create a User</h1>
                <br>
                <form action='includes/create_user.inc.php' method='post'>
                  <input type='text' name='email' placeholder='email...'><br>
                  <input type='password' name='pwd' placeholder='password...'><br>
                  <input type='password' name='pwdrepeat' placeholder='repeat password...'><br>
                  <br>
                  <label for='admin'> Admin?</label>
                  <input type='checkbox' id='admin' name='admin' value='1'><br>
                  <br>
                  <button type='submit' name='submit'>Create User</button>
                </form>
                <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"] == "emptyinput") {
                        echo "<p>Please fill in all fields!</p>";
                    } elseif ($_GET["error"] == "invalidemail") {
                        echo "<p>Invalid email!</p>";
                    } elseif ($_GET["error"] == "passwordmismatch") {
                        echo "<p>Repeat password does not match!</p>";
                    } elseif ($_GET["error"] == "emailtaken") {
                        echo "<p>An account with this email already exists!</p>";
                    } elseif ($_GET["error"] == "none") {
                        echo "<p>User created successfully!</p>";
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
