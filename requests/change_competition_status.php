<?php
include("../includes/connection.php");

$competition_id = $_POST['competition_id'];
$competition_status = $_POST['competition_status'];

$sql = sprintf('UPDATE competitions SET competition_status="%s" WHERE competition_id=%s', $competition_status, $competition_id);
$conn->query($sql);

header(sprintf('Location: ../index.php?tab=home&lang=%s&competition_id=%s&uid=%s', $_POST['lang'], $competition_id, $_POST['uid']));
?>