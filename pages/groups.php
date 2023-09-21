<?php
if ($competition_id == 1 || $competition_id == 5 || $competition_id == 6 || $competition_id == 7) {
    $advanced = [$dict["advanced"][$lang], $dict["advanced"][$lang], $dict["advanced"][$lang], $dict["advanced"][$lang]];
}
else if ($competition_id == 2) {
    $advanced = [$dict["advanced"][$lang], $dict["advanced"][$lang]];
}
else if ($competition_id == 3) {
    $advanced = [$dict["winners_group"][$lang], $dict["winners_group"][$lang], $dict["winners_group"][$lang], $dict["winners_group"][$lang],
                $dict["eliminated_group"][$lang], $dict["eliminated_group"][$lang], $dict["eliminated_group"][$lang], $dict["eliminated_group"][$lang]];
}
else if($competition_id == 4) {
    $advanced = [];
}
?>

<div class="row">
    <?php
    $sql = sprintf('SELECT MAX(group_index) AS num_groups FROM players WHERE competition_id=%s', $competition_id);
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $num_groups = $row["num_groups"];
    }

    // get all groups, current user first
    $u_group_index = 0;
    $sql = sprintf('SELECT group_index FROM players WHERE competition_id=%s AND user_id=%s', $competition_id, $uid);
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $u_group_index = $row["group_index"];
    }
    $groups = array();
    if ($u_group_index >= 0) {
        array_push($groups, $u_group_index);
    }
    for ($i=0; $i<=$num_groups; $i++) {
        if ($i != $u_group_index) array_push($groups, $i);
    }

    foreach ($groups AS $group_index) {
        if ($competitions && $group_index != $uid_group) continue;

        echo '
    <div class="'.($competitions?'col-120':'col-lg-60 col-120').'" style="margin-bottom: 50px;">
        <b>'.chr(65+$group_index).' '.$dict["group"][$lang].'</b>
        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <th style="width: 30%;">'.$dict["player"][$lang].'</th>
                <th style="width: 10%; text-align: center;">'.$dict["win"][$lang].'</th>
                <th style="width: 10%; text-align: center;">'.$dict["draw"][$lang].'</th>
                <th style="width: 10%; text-align: center;">'.$dict["lose"][$lang].'</th>
                <th style="width: 10%; text-align: center;">'.$dict["score"][$lang].'</th>
                <th style="width: 10%; text-align: center;">'.$dict["conceed"][$lang].'</th>
                <th style="width: 10%; text-align: center;">'.$dict["difference"][$lang].'</th>
                <th style="width: 10%; text-align: center;">'.$dict["points"][$lang].'</th>
            </tr>';
        
        $players = array();  
        $sql = sprintf('SELECT * FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id=%s AND (P.group_index='.$group_index.' OR P.group_index='.(-2-$group_index).')', $competition_id);
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $eaid = $row["ea_id"];
            $player_name = $row["player_name"];
            array_push($players, array("player_id"=>$row["player_id"], "user_id"=>$row["user_id"], "group_alias"=>$row["group_alias"], "eaid"=>$eaid, "player_name"=>$player_name, "group_index"=>$row["group_index"], "win"=>0, "draw"=>0, "loss"=>0, "goal"=>0, "concede"=>0));
        }

        foreach ($players AS $player_index=>$player) {
            $sql = sprintf('SELECT * FROM matches AS M WHERE M.competition_id=%s AND M.stage="group" AND M.player1_id=%s AND M.stage="group"', $competition_id, $player["player_id"]);
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $players[$player_index]["goal"] += $row["score1"];
                $players[$player_index]["concede"] += $row["score2"];
                if ($row["score1"] > $row["score2"]) {
                    $players[$player_index]["win"] += 1;
                }
                if ($row["score1"] == $row["score2"] && $row["score1"] != "") {
                    $players[$player_index]["draw"] += 1;
                }
                if ($row["score1"] < $row["score2"]) {
                    $players[$player_index]["loss"] += 1;
                }
            }

            if ($competition_id != 2) continue;
            $sql = sprintf('SELECT * FROM matches AS M WHERE M.competition_id=%s AND M.stage="group" AND M.player2_id=%s AND M.stage="group"', $competition_id, $player["player_id"]);
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $players[$player_index]["goal"] += $row["score2"];
                $players[$player_index]["concede"] += $row["score1"];
                if ($row["score1"] < $row["score2"]) {
                    $players[$player_index]["win"] += 1;
                }
                if ($row["score1"] == $row["score2"] && $row["score1"] != "") {
                    $players[$player_index]["draw"] += 1;
                }
                if ($row["score1"] > $row["score2"]) {
                    $players[$player_index]["loss"] += 1;
                }
            }
        }
        
        usort($players, "cmp");
        if ($competition_id == 2 && $group_index == 0) {
            $temp = $players[2];
            $players[2] = $players[1];
            $players[1] = $players[0];
            $players[0] = $temp;
        }

        foreach ($players AS $player_index=>$player) {
            if ($player["group_index"] == $group_index) {
                echo '
            <tr style="color: '.(($uid!=0&&$uid==$player["user_id"])?$highligh_color:"black").'; font-weight: '.(($uid!=0&&$uid==$player["user_id"])?"bold":"normal").';">
                <td style="cursor: pointer;" onclick="alert(\''.$player["eaid"].'\');">'.$player["player_name"].' ('.$player["group_alias"].') ['.$player["eaid"].']</td>
                <td style="text-align: center;">'.$player["win"].'</td>
                <td style="text-align: center;">'.$player["draw"].'</td>
                <td style="text-align: center;">'.$player["loss"].'</td>
                <td style="text-align: center;">'.$player["goal"].'</td>
                <td style="text-align: center;">'.$player["concede"].'</td>
                <td style="text-align: center;">'.($player["goal"]-$player["concede"]).'</td>
                <td style="text-align: center; position: relative;">'.(3*$player["win"]+$player["draw"]).(isset($advanced[$player_index])?'<span style="position: absolute; right:0px; top: 0px; font-size: 6pt; background-color: #04AF70; color: white;">'.$advanced[$player_index].'</span>':"").'</td>
            </tr>';
            }
        }
        foreach ($players AS $player_index=>$player) {
            if ($player["group_index"] != $group_index) {
                echo '
            <tr>
                <td style="cursor: pointer; text-decoration: line-through;" onclick="alert(\''.$player["eaid"].'\');">'.$player["player_name"].' ('.$player["eaid"].')</td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
            </tr>';
            }
        }
            
        echo '
        </table>
        
        
        
        
        
        
        <div class="row">';
        
        $players = array();    
        $sql = sprintf('SELECT * FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id=%s AND P.group_index=%s', $competition_id, $group_index);
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $eaid = $row["ea_id"];
            $player_name = $row["player_name"];
            if ($row["user_id"] == $uid) array_unshift($players, array("player_id"=>$row["player_id"], "user_id"=>$row["user_id"], "group_alias"=>$row["group_alias"], "eaid"=>$eaid, "player_name"=>$player_name));
            else array_push($players, array("player_id"=>$row["player_id"], "user_id"=>$row["user_id"], "group_alias"=>$row["group_alias"], "eaid"=>$eaid, "player_name"=>$player_name));
        }

        foreach($players as $player_index1=>$player1) {
            if ($competitions && $uid != $player1["user_id"]) continue;

            echo '
            <div class="col-120" style="margin-top: 20px; color: '.((!$competitions&&$uid!=0&&$uid==$player1["user_id"])?$highligh_color:"black").'">
                <b>'.$player1["player_name"].' ('.$player1["group_alias"].')</b>
            </div>';
            foreach($players as $player_index2=>$player2) {
                if ($player_index1 != $player_index2) {
                    $score1 = "";
                    $score2 = "";
                    $sql = sprintf('SELECT * FROM matches WHERE competition_id=%s AND stage="group" AND player1_id=%s AND player2_id=%s', $competition_id, $player1["player_id"], $player2["player_id"]);
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $score1 = $row["score1"];
                        $score2 = $row["score2"];
                    }

                    if ($user["user_id"] == 1 || (($user["user_id"] == $player1["user_id"] || $user["user_id"] == $player2["user_id"]) && $competition_status == "groups")) {
                        $eligible = True;
                    }
                    else $eligible = False;

                    if ($player1["group_alias"] == "" || strpos($player1["player_name"], $player1["group_alias"]) !== False || strpos($player1["group_alias"], $player1["player_name"]) !== False) {
                        $display_name1 = $player1["player_name"];
                    }
                    else {
                        $display_name1 = $player1["player_name"].' ('.$player1["group_alias"].')';
                    }
                    if ($player2["group_alias"] == "" || strpos($player2["player_name"], $player2["group_alias"]) !== False || strpos($player2["group_alias"], $player2["player_name"]) !== False) {
                        $display_name2 = $player2["player_name"];
                    }
                    else {
                        $display_name2 = $player2["player_name"].' ('.$player2["group_alias"].')';
                    }

                    echo '
            <div class="col-lg-60 col-120" style="cursor: '.($eligible?"pointer":"auto").';"'.($eligible?' onclick="open_match_modal('.$player1["player_id"].', '.$player2["player_id"].')"':"").'>
                <div class="row">
                    <div class="col-50" style="text-align: right; padding-left: 4px; padding-right: 4px;'.($eligible?' text-decoration: underline;':"").'">'.$display_name1.'</div>
                    <div class="col-8 no-padding" style="text-align: center;">'.$score1.'</div>
                    <div class="col-4 no-padding" style="text-align: center;">-</div>
                    <div class="col-8 no-padding" style="text-align: center;">'.$score2.'</div>
                    <div class="col-50" style="text-align: left; padding-left: 4px; padding-right: 4px;'.($eligible?' text-decoration: underline;':"").'">'.$display_name2.'</div>
                </div>
            </div>';

            		if ($competition_id != 2) continue;
            		$score1 = "";
                    $score2 = "";
                    $sql = sprintf('SELECT * FROM matches WHERE competition_id=%s AND stage="group" AND player1_id=%s AND player2_id=%s', $competition_id, $player2["player_id"], $player1["player_id"]);
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $score1 = $row["score1"];
                        $score2 = $row["score2"];
                    }
                    echo '
            <div class="col-lg-60 col-120" style="cursor: pointer;">
                <div class="row">
                    <div class="col-50" style="text-align: right; padding-left: 4px; padding-right: 4px;">'.$player2["player_name"].'</div>
                    <div class="col-8 no-padding" style="text-align: center;">'.$score1.'</div>
                    <div class="col-4 no-padding" style="text-align: center;">-</div>
                    <div class="col-8 no-padding" style="text-align: center;">'.$score2.'</div>
                    <div class="col-50" style="text-align: left; padding-left: 4px; padding-right: 4px;">'.$player1["player_name"].'</div>
                </div>
            </div>';
                }
            }
        }
        
        echo '
        </div>
    </div>';
    }
    ?>
</div>

