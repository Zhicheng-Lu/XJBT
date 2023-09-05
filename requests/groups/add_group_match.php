<?php
include("../../includes/connection.php");

$competition_id = $_POST["competition_id"];
$player1 = $_POST["player1"];
$player2 = $_POST["player2"];
$score1 = $_POST["score1"];
$score2 = $_POST["score2"];

if ($score1 == '' and $score2 == '') {
    $sql = 'DELETE FROM matches WHERE competition_id='.$competition_id.' AND game_index=0 AND stage="group" AND player1_id='.$player1.' AND player2_id='.$player2;
    $conn->query($sql);

    $sql = 'DELETE FROM matches WHERE competition_id='.$competition_id.' AND game_index=0 AND stage="group" AND player1_id='.$player2.' AND player2_id='.$player1;
    $conn->query($sql);
}

if ($score1 != '' and $score2 != '') {
    $sql = 'INSERT INTO matches(competition_id,game_index,stage,player1_id,score1,score2,player2_id) VALUES('.$competition_id.', 0, "group", '.$player1.', '.$score1.', '.$score2.', '.$player2.') ON DUPLICATE KEY UPDATE score1='.$score1.', score2='.$score2;
    $conn->query($sql);

    $sql = 'INSERT INTO matches(competition_id,game_index,stage,player1_id,score1,score2,player2_id) VALUES('.$competition_id.', 0, "group", '.$player2.', '.$score2.', '.$score1.', '.$player1.') ON DUPLICATE KEY UPDATE score1='.$score2.', score2='.$score1;
    $conn->query($sql);
}

header(sprintf('Location: ../../index.php?tab=home&lang=%s&competition_id=%s&uid=%s', $_POST['lang'], $competition_id, $_POST['uid']));
?>