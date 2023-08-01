<?php
if  ($_POST['btn'] == "account_password") {
	$sql = sprintf('SELECT * FROM users WHERE user_id=%s', $_POST['user_id']);
	$results = $conn->query($sql);
	while ($row = $results->fetch_assoc()) {
		$pwd = $row["password"];
	}

	if ($pwd == $_POST['old_password']) {
		$sql = sprintf('UPDATE users SET password="%s" WHERE user_id=%s', $_POST["new_password"], $_POST['user_id']);
		$conn->query($sql);
		echo '
		<script type="text/javascript">
			window.location.href = "'.sprintf('index.php?tab=%s&lang=%s', "account", $_POST["lang"]).'";
		</script>';
	}
	else {
		echo '
		<script type="text/javascript">
			alert("'.$dict["profile_password_error"][$lang].'");
			window.location.href = "'.sprintf('index.php?tab=%s&lang=%s', "account", $_POST["lang"]).'";
		</script>';
	}
}

if  ($_POST['btn'] == "account_profile") {
	$sql = sprintf('UPDATE users SET group_alias="%s", name="%s" WHERE user_id=%s', $_POST["group_alias"], $_POST["name"], $_POST['user_id']);
	$results = $conn->query($sql);
	echo '
	<script type="text/javascript">
		window.location.href = "'.sprintf('index.php?tab=%s&lang=%s', "account", $_POST["lang"]).'";
	</script>';
}
?>