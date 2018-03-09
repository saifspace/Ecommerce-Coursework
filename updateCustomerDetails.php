<?php 

include "./main.php";
update_customer_details($_POST['email'], $_POST['pass'], $_POST['firstName'], $_POST['lastName'], $_POST['address']);


?>