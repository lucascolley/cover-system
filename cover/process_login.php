<?php echo file_get_contents("html/header.html"); ?>
<?php $name  = $_POST['email']; ?>
<?php $pass  = $_POST['password']; ?>
    <?php echo $name; ?>
    <br>
    <?php echo $pass; ?>
		<br>
<?php echo file_get_contents("html/footer.html"); ?>
