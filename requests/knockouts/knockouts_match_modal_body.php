<?php
include("../../includes/connection.php");

function display_drop_down($conn, $i, $competition_id, $logged_user_id, $player_id, $display_name) {
    if ($logged_user_id != 1) echo '<b>'.$display_name.'</b>';
    else {
        echo '
        <select value="'.$player_id.'" style="width: 100%;" onchange="change_player('.$i.', event)">
            <option value=""></option>';
        $sql = sprintf('SELECT * FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id=%s', $competition_id);
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
            <option value="'.$row["player_id"].'"'.(($row["player_id"]==$player_id)?" selected":"").'>'.$row["player_name"].' ('.$row["group_alias"].')</option>';
        }
        echo '
        </select>';
    }
}

$competition_id = $_POST["competition_id"];
$lang = $_POST["lang"];
$uid = $_POST["uid"];
$stage = $_POST["stage"];
$game_index = $_POST["game_index"];

$temp = $game_index;
if ($game_index < 0) {
    if ((-$game_index) % 2 == 1) $game_index = 0-$game_index;
    else $game_index = -$game_index-1;
}

if (isset($_COOKIE["user_id"])) {
    $user["user_id"] = $_COOKIE["user_id"];
}
else {
    $user["user_id"] = "fuck";
}

$sql = sprintf('SELECT competition_status FROM competitions WHERE competition_id=%s', $competition_id);
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $competition_status = $row["competition_status"];
}

$p1 = "";
$p2 = "";
$u1id = "";
$u2id = "";
$p1id = "";
$p2id = "";
$score1 = "";
$score2 = "";
$extra_score1 = "";
$extra_score2 = "";
$penalty_score1 = "";
$penalty_score2 = "";
$sql = sprintf('SELECT P1.player_name AS p1, P2.player_name AS p2, U1.group_alias AS group_alias1, U2.group_alias AS group_alias2, P1.user_id AS u1id, P1.player_id AS p1id, P2.user_id AS u2id, P2.player_id AS p2id, M.score1, M.score2, M.extra_score1, M.extra_score2, M.penalty_score1, M.penalty_score2 FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN users AS U1 ON P1.user_id=U1.user_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id LEFT JOIN users AS U2 ON P2.user_id=U2.user_id WHERE M.competition_id=%s AND M.stage="%s" AND M.game_index=%s', $competition_id, $stage, $game_index);
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $p1 = $row["p1"].' ('.$row["group_alias1"].')';
    $p2 = $row["p2"].' ('.$row["group_alias2"].')';
    $u1id = $row["u1id"];
    $u2id = $row["u2id"];
    $p1id = $row["p1id"];
    $p2id = $row["p2id"];
    $score1 = $row["score1"];
    $score2 = $row["score2"];
    $extra_score1 = $row["extra_score1"];
    $extra_score2 = $row["extra_score2"];
    $penalty_score1 = $row["penalty_score1"];
    $penalty_score2 = $row["penalty_score2"];
}

// if not eligible to edit then, disable input boxes and submit button
if ($user["user_id"] == 1 || (($competition_status == "knockouts" || $competition_status == "groups") & (($u1id != "" && $user["user_id"] == $u1id) || ($u2id != "" && $user["user_id"] == $u2id)))) $eligible = True;
else $eligible = False;

echo '
<input type="hidden" name="competition_id" value="'.$competition_id.'">
<input type="hidden" name="uid" value="'.$uid.'">
<input type="hidden" name="lang" value="'.$lang.'">
<input type="hidden" name="stage" value="'.$stage.'">
<input type="hidden" name="game_index" value="'.$game_index.'">
<input type="hidden" id="player1_id" name="player1_id" value="'.$p1id.'">
<input type="hidden" id="player2_id" name="player2_id" value="'.$p2id.'">
<div class="row">
    <div class="col-120" style="margin-bottom: 20px;">
        <div class="row">
            <div class="col-60">';

display_drop_down($conn, 1, $competition_id, $user["user_id"], $p1id, $p1);
echo '
            </div>
            <div class="col-60">';

display_drop_down($conn, 2, $competition_id, $user["user_id"], $p2id, $p2);
echo '
            </div>
        </div>
    </div>
    <div class="col-60" style="margin-bottom: 20px;">
        比分：<input type="number" name="score1" value="'.$score1.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60" style="margin-bottom: 20px;">
        比分：<input type="number" name="score2" value="'.$score2.'"'.($eligible?"":" disabled").'>
    </div>';

if ($game_index % 2 == 0 || $stage == "final") {
    echo '
    <div class="col-60" style="margin-bottom: 20px;">
        加时：<input type="number" name="extra_score1" value="'.$extra_score1.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60" style="margin-bottom: 20px;">
        加时：<input type="number" name="extra_score2" value="'.$extra_score2.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60">
        点球：<input type="number" name="penalty_score1" value="'.$penalty_score1.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60">
        点球：<input type="number" name="penalty_score2" value="'.$penalty_score2.'"'.($eligible?"":" disabled").'>
    </div>';
}

echo '
</div>
';


$game_index = $temp;
if ($game_index < 0) {
    if ((-$game_index) % 2 == 1) $game_index = -$game_index + 1;
    else $game_index = -$game_index;

    $sql = sprintf('SELECT P1.player_name AS p1, P2.player_name AS p2, P1.player_id AS p1id, P2.player_id AS p2id, M.score1, M.score2, M.extra_score1, M.extra_score2, M.penalty_score1, M.penalty_score2 FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id WHERE M.competition_id=%s AND M.stage="%s" AND M.game_index=%s', $competition_id, $stage, $game_index);
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $p1id = $row["p1id"];
        $p2id = $row["p2id"];
        $score1 = $row["score1"];
        $score2 = $row["score2"];
        $extra_score1 = $row["extra_score1"];
        $extra_score2 = $row["extra_score2"];
        $penalty_score1 = $row["penalty_score1"];
        $penalty_score2 = $row["penalty_score2"];
    }

    echo '
<input type="hidden" name="game_index_" value="'.$game_index.'">
<div class="row" style="border-top: 1px solid #D3D3D3; padding-top: 20px;" >
    <div class="col-120" style="margin-bottom: 20px;">
        <div class="row">
            <div class="col-60"><b id="lag2_player2">'.$p2.'</b></div>
            <div class="col-60"><b id="lag2_player1">'.$p1.'</b></div>
        </div>
    </div>
    <div class="col-60" style="margin-bottom: 20px;">
        比分：<input type="number" name="score1_" value="'.$score1.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60" style="margin-bottom: 20px;">
        比分：<input type="number" name="score2_" value="'.$score2.'"'.($eligible?"":" disabled").'>
    </div>';

    if ($game_index % 2 == 0) {
        echo '
    <div class="col-60" style="margin-bottom: 20px;">
        加时：<input type="number" name="extra_score1_" value="'.$extra_score1.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60" style="margin-bottom: 20px;">
        加时：<input type="number" name="extra_score2_" value="'.$extra_score2.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60">
        点球：<input type="number" name="penalty_score1_" value="'.$penalty_score1.'"'.($eligible?"":" disabled").'>
    </div>
    <div class="col-60">
        点球：<input type="number" name="penalty_score2_" value="'.$penalty_score2.'"'.($eligible?"":" disabled").'>
    </div>';
    }

    echo '
</div>';
}

echo '
<div class="col-120" style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #DCDCDC;">
    <button style="height: 40px; border-radius: 5px; font-size: 20px; background-color: white; width: 40%;"'.($eligible?"":" disabled").'>确认</button>
</div>
';
?>