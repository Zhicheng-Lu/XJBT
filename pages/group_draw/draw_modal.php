<?php
include("../../includes/connection.php");

$competition_id = $_POST["competition_id"];
$lang = $_POST["lang"];

// get all competitions
$competitions = array();
$sql = sprintf('SELECT C.competition_id FROM competitions AS C LEFT JOIN matches AS M ON C.competition_id=M.competition_id WHERE M.stage="champion" ORDER BY C.competition_id DESC');
$results = $conn->query($sql);
while ($row = $results->fetch_assoc()) {
    array_push($competitions, $row["competition_id"]);
}
// get all users
$users = array();
$sql = sprintf('SELECT U.user_id, U.group_alias, P.player_id, P.player_name FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id=%s', $competition_id);
$results = $conn->query($sql);
while ($row = $results->fetch_assoc()) {
    array_push($users, array("user_id"=>$row["user_id"], "player_id"=>$row["player_id"], "group_alias"=>$row["group_alias"], "player_name"=>$row["player_name"], "weighted_points"=>0, "total_points"=>0, "average_points"=>0));
    foreach ($competitions as $competition) {
        $users[sizeof($users)-1][$competition] = array("performance"=>"", "points"=>0);
    }
}
$order_competition_id = "-1";
$order = "down";
include("../../requests/get_points.php");
$rank = array(array_slice($users,0,4), array_slice($users,4,4), array_slice($users,8));
?>

<div class="modal-header">
    <h3>小组赛抽签</h3>
    <span class="close" onclick="close_group_draw_modal()">&times;</span>
</div>
<div class="modal-body">
    <table style="width: 100%;">
        <tr>
            <th style="width: 10%; min-width: 100px;">档次</th>
            <th style="width: 90%;">玩家</th>
        </tr>
        <?php
        $current_pot = -1;
        $current_pot_participants = array();
        $num_current_pot = 0;
        for ($i = 0; $i < 3; $i++) {
            echo '
        <tr>
            <td style="text-align: center;">第 '.($i + 1).' 档次</td>
            <td>
                <div class="row">';
            
            foreach ($rank[$i] as $usr) {
                $sql = 'SELECT * FROM players WHERE competition_id='.$competition_id.' AND user_id="'.$usr["user_id"].'"';
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $name = $row["player_name"];
                    $eaid = $row["ea_id"];
                    if ($current_pot == -1 || $i == $current_pot) {
                        if ($row["group_index"] == -1) {
                            array_push($current_pot_participants, $row["player_id"]);
                            $current_pot = $i;
                            $num_current_pot += 1;
                        }
                    }
                    if ($row["group_index"] == -1) $opacity = 1;
                    else $opacity = 0.3;
                }
                echo '
                    <div class="col-md-60 col-120" style="opacity: '.$opacity.';">'.$name.' ('.$usr["group_alias"].', ['.$eaid.'])</div>';
            }
            
            echo '
                </div>
            </td>
        </tr>';
        }
        ?>
    </table>
    
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-60 col-120" style="margin-bottom: 40px;">
            <div class="row">
                <?php
                shuffle($current_pot_participants);
                foreach ($current_pot_participants as $participant) {
                    echo '
                <div class="col-30">
                    <img src="images/ball.png" style="width: 50px; height: 50px; cursor: pointer;" onclick="draw_new_team('.$participant.', '.((20-$num_current_pot) % 4).')">
                </div>';
                }
                ?>
            </div>
        </div>
        <div class="col-md-60 col-120">
            <?php
            for ($i = 0; $i < 4; $i++) {
                if ((20-$num_current_pot) % 4 == $i) $color = "red";
                else $color = "black";
            
                echo '
            <div class="row" style="margin-bottom: 40px;">
                <div class="col-15" style="color: '.$color.'">'.chr($i+65).'</div>
                <div class="col-105">';
                
                $in_the_group = array();
                $sql = 'SELECT * FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id='.$competition_id.' AND P.group_index='.$i;
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    array_push($in_the_group, array("name"=>$row["player_name"], "group_alias"=>$row["group_alias"], "eaid"=>$row["ea_id"], "player_id"=>$row["player_id"]));
                }
                
                for ($j = 0; $j < 3; $j++) {
                    foreach ($rank[$j] as $usr) {
                        foreach ($in_the_group as $participant) {
                            if ($usr["player_id"] == $participant["player_id"]) {
                                echo '
                    <div>'.$participant["name"].' ('.$participant["group_alias"].', ['.$participant["eaid"].'])'.'</div>';
                            }
                        }
                    }
                }
                
                echo '
                </div>
            </div>';
            }
            ?>
        </div>
    </div>
</div>