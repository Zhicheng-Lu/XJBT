<?php
include("../includes/connection.php");

$champion = $_POST["champion"];
$competition_id = $_POST["competition_id"];


if ($champion == "") {
	$sql = sprintf('DELETE FROM matches WHERE competition_id=%s AND stage="champion"', $competition_id);
}
else {
	$flag = False;
	$sql = sprintf('SELECT * FROM matches WHERE competition_id=%s AND stage="champion"', $competition_id);
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$flag = True;
	}

	if ($flag) {
		$sql = sprintf('UPDATE matches SET player1_id=%s WHERE competition_id=%s AND stage="champion"', $champion, $competition_id);
	}
	else {
		$sql = sprintf('INSERT INTO matches(competition_id, game_index, stage, player1_id) VALUE(%s, 1, "champion", %s)', $competition_id, $champion);
	}
}

$conn->query($sql);

header(sprintf('Location: ../index.php?tab=home&lang=%s&uid=%s', $_POST['lang'], $_POST['uid']));
?>