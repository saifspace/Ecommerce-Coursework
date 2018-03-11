<?php 

include './main.php';
add_review($_POST['id'], $_POST['name'], $_POST['comment'], $_POST['email']);

?>