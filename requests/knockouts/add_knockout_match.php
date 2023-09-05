<?php
include("../../includes/connection.php");

$competition_id = $_POST["competition_id"];
$lang = $_POST["lang"];
$uid = $_POST["uid"];
$stage = $_POST["stage"];
$game_index = $_POST["game_index"];
$player1_id = $_POST["player1_id"];
if ($player1_id == "") $player1_id = "null";
$player2_id = $_POST["player2_id"];
if ($player2_id == "") $player2_id = "null";
if (!isset($_POST["score1"]) || $_POST['score1'] == "") $score1 = "null";
else $score1 = $_POST["score1"];
if (!isset($_POST["score2"]) || $_POST['score2'] == "") $score2 = "null";
else $score2 = $_POST["score2"];
if (!isset($_POST["extra_score1"]) || $_POST['extra_score1'] == "") $extra_score1 = "null";
else $extra_score1 = $_POST["extra_score1"];
if (!isset($_POST["extra_score2"]) || $_POST['extra_score2'] == "") $extra_score2 = "null";
else $extra_score2 = $_POST["extra_score2"];
if (!isset($_POST["penalty_score1"]) || $_POST['penalty_score1'] == "") $penalty_score1 = "null";
else $penalty_score1 = $_POST["penalty_score1"];
if (!isset($_POST["penalty_score2"]) || $_POST['penalty_score2'] == "") $penalty_score2 = "null";
else $penalty_score2 = $_POST["penalty_score2"];


$sql = sprintf('SELECT * FROM matches WHERE competition_id=%s AND stage="%s" AND game_index=%s', $competition_id, $stage, $game_index);
$result = $conn->query($sql);
$flag = false;
while ($row = $result->fetch_assoc()) {
    $flag = true;
    $sql1 = sprintf('UPDATE matches SET player1_id=%s, player2_id=%s, score1=%s, score2=%s, extra_score1=%s, extra_score2=%s, penalty_score1=%s, penalty_score2=%s WHERE competition_id=%s AND stage="%s" AND game_index=%s', $player1_id, $player2_id, $score1, $score2, $extra_score1, $extra_score2, $penalty_score1, $penalty_score2, $competition_id, $stage, $game_index);
    $conn->query($sql1);
}
if (!$flag) {
    $sql = sprintf('INSERT INTO matches(competition_id,game_index,stage,player1_id,score1,score2,player2_id,extra_score1,extra_score2,penalty_score1,penalty_score2) VALUES(%s, %s, "%s", %s, %s, %s, %s, %s, %s, %s, %s)', $competition_id, $game_index, $stage, $player1_id, $score1, $score2, $player2_id, $extra_score1, $extra_score2, $penalty_score1, $penalty_score2);
    $conn->query($sql);
}

if (isset($_POST["game_index_"])) {
    $game_index = $_POST["game_index_"];
    $player1_id = $_POST["player2_id"];
    if ($player1_id == "") $player1_id = "null";
    $player2_id = $_POST["player1_id"];
    if ($player2_id == "") $player2_id = "null";
    if (!isset($_POST["score1_"]) || $_POST['score1_'] == "") $score1 = "null";
    else $score1 = $_POST["score1_"];
    if (!isset($_POST["score2_"]) || $_POST['score2_'] == "") $score2 = "null";
    else $score2 = $_POST["score2_"];
    if (!isset($_POST["extra_score1_"]) || $_POST['extra_score1_'] == "") $extra_score1 = "null";
    else $extra_score1 = $_POST["extra_score1_"];
    if (!isset($_POST["extra_score2_"]) || $_POST['extra_score2_'] == "") $extra_score2 = "null";
    else $extra_score2 = $_POST["extra_score2_"];
    if (!isset($_POST["penalty_score1_"]) || $_POST['penalty_score1_'] == "") $penalty_score1 = "null";
    else $penalty_score1 = $_POST["penalty_score1_"];
    if (!isset($_POST["penalty_score2_"]) || $_POST['penalty_score2_'] == "") $penalty_score2 = "null";
    else $penalty_score2 = $_POST["penalty_score2_"];
    $sql = sprintf('SELECT * FROM matches WHERE competition_id=%s AND stage="%s" AND game_index=%s', $competition_id, $stage, $game_index);
    $result = $conn->query($sql);
    $flag = false;
    while ($row = $result->fetch_assoc()) {
        $flag = true;
        $sql1 = sprintf('UPDATE matches SET player1_id=%s, player2_id=%s, score1=%s, score2=%s, extra_score1=%s, extra_score2=%s, penalty_score1=%s, penalty_score2=%s WHERE competition_id=%s AND stage="%s" AND game_index=%s', $player1_id, $player2_id, $score1, $score2, $extra_score1, $extra_score2, $penalty_score1, $penalty_score2, $competition_id, $stage, $game_index);
        $conn->query($sql1);
    }
    if (!$flag) {
        $sql = sprintf('INSERT INTO matches(competition_id,game_index,stage,player1_id,score1,score2,player2_id,extra_score1,extra_score2,penalty_score1,penalty_score2) VALUES(%s, %s, "%s", %s, %s, %s, %s, %s, %s, %s, %s)', $competition_id, $game_index, $stage, $player1_id, $score1, $score2, $player2_id, $extra_score1, $extra_score2, $penalty_score1, $penalty_score2);
        $conn->query($sql);
    }
}

header(sprintf('Location: ../../index.php?tab=home&lang=%s&uid=%s&competition_id=%s', $lang, $uid, $competition_id));
?>