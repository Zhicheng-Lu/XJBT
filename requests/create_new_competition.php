<?php
include("../includes/connection.php");

$sql = sprintf('INSERT INTO competitions(competition_name_eng, competition_name_chi, competition_status) VALUE("%s", "%s", "signup")', $_POST["competition_name_eng"], $_POST["competition_name_eng"]);
$conn->query($sql);

header(sprintf('Location: ../index.php?tab=home&lang=%s&uid=%s', $_POST['lang'], $_POST['uid']));
?>