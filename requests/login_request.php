<?php
if  ($_POST['btn'] == "login") {
	$sql = sprintf('SELECT user_id FROM users WHERE email="%s" AND password="%s"', $_POST["email"], $_POST["password"]);
	$results = $conn->query($sql);
	$flag = FALSE;
	while ($row = $results->fetch_assoc()) {
		$flag = TRUE;
		$uid = $row["user_id"];
		setcookie("user_id", $row["user_id"], time() + (86400*60), "/");
	}
	if ($flag) {
		echo '
		<script type="text/javascript">
			window.location.href = "'.sprintf('index.php?tab=%s&lang=%s&competition_id=%s&uid=%s', $_POST["tab"], $_POST["lang"], $_POST["competition_id"], $uid).'";
		</script>';
	}
	else {
		echo '
		<script type="text/javascript">
			alert("'.$dict["login_error"][$lang].'");
			window.location.href = "'.sprintf('index.php?tab=%s&lang=%s&competition_id=%s&uid=%s', $_POST["tab"], $_POST["lang"], $_POST["competition_id"], $uid).'";
		</script>';
	}
}

if ($_POST['btn'] == "register") {
	$sql = sprintf('INSERT INTO users(email, password, group_alias, name) VALUES("%s", "%s", "%s", "%s")', $_POST["email"], $_POST["password"], $_POST["group_alias"], $_POST["name"]);
	$results = $conn->query($sql);
	if ($results === TRUE) {
		echo '
		<script type="text/javascript">
			alert("'.$dict["register_succ"][$lang].'");
			window.location.href = "'.sprintf('index.php?tab=%s&lang=%s&competition_id=%s', $_POST["tab"], $_POST["lang"], $_POST["competition_id"]).'";
		</script>';
	}
	else {
		echo '
		<script type="text/javascript">
			alert("'.$dict["register_error"][$lang].'");
			window.location.href = "'.sprintf('index.php?tab=%s&lang=%s&competition_id=%s', $_POST["tab"], $_POST["lang"], $_POST["competition_id"]).'";
		</script>';
	}
}

if ($_POST['btn'] == "logout") {
	setcookie("user_id", "", time()-3600, "/");
	echo '
		<script type="text/javascript">
			window.location.href = "'.sprintf('index.php?tab=%s&lang=%s', "home", $_POST["lang"]).'";
		</script>';
}
?>