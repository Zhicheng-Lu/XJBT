<?php
if ($_POST['btn'] == "login" || $_POST['btn'] == "register" || $_POST['btn'] == "logout") {
	include('requests/login_request.php');
}

if ($_POST['btn'] == "account_password" || $_POST['btn'] == "account_profile") {
	include('requests/account_request.php');
}
?>