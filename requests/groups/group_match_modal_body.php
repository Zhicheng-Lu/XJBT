<?php
include("../../includes/connection.php");

$competition_id = $_POST["competition_id"];
$player1 = $_POST["player1"];
$player2 = $_POST["player2"];

// p1
$sql = 'SELECT * FROM players WHERE player_id='.$player1;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $player1_name = $row["player_name"];
}

// p2
$sql = 'SELECT * FROM players WHERE player_id='.$player2;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $player2_name = $row["player_name"];
}

$score1 = "";
$score2 = "";
$sql = 'SELECT * FROM matches WHERE competition_id='.$competition_id.' AND stage="group" AND player1_id='.$player1.' AND player2_id='.$player2;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $score1 = $row["score1"];
    $score2 = $row["score2"];
}

echo '
<input type="hidden" name="competition_id" value="'.$competition_id.'">
<input type="hidden" name="player1" value="'.$player1.'">
<input type="hidden" name="player2" value="'.$player2.'">
<input type="hidden" name="uid" value="'.$_POST['uid'].'">
<input type="hidden" name="lang" value="'.$_POST["lang"].'">
<div class="row">
    <div class="col-60">
        <b>'.$player1_name.'</b><br><br>
        比分：<input type="number" name="score1" value="'.$score1.'">
    </div>
    <div class="col-60">
        <b>'.$player2_name.'</b><br><br>
        比分：<input type="number" name="score2" value="'.$score2.'">
    </div>
</div>
';

?>