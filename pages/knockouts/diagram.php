    <div class="row">
        <div class="col-lg-60 offset-lg-30 col-120">
            <div class="row">
                <?php
                foreach ($rounds as $round) {
                    draw_round($conn, $competition_id, $uid, $round["num_teams"], $round["name"], sizeof($rounds), $round["fixture"], $highligh_color);
                }
                draw_final($conn, $competition_id, $uid, $final_round_name, sizeof($rounds), $highligh_color);
                ?>
            </div>
        </div>
    </div>
    
    
    <?php
    function draw_round($conn, $competition, $uid, $num_teams, $round, $num_rounds, $fixture, $highligh_color) {
        $total_height = 720;
        if ($num_rounds == 2) {
            $width1 = 10;
            $width2 = 28;
            $width3 = 13;
        }
        if ($num_rounds == 3) {
            $width1 = 6;
            $width2 = 21;
            $width3 = 10;
        }

        $teams = array();
        for ($i = 0; $i < $num_teams; $i++) {
            $teams[$i] = array("id"=>"", "name"=>"", "group_alias"=>"", "user_id"=>"", "score"=>"", "p_score"=>"");
        }
        
        $sql = sprintf('SELECT * FROM matches AS M LEFT JOIN players AS P ON M.player1_id=P.player_id LEFT JOIN users AS U ON P.user_id=U.user_id WHERE M.competition_id=%s AND stage="%s" ORDER BY game_index', $competition, $round);
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $game_index = $row["game_index"];
            $teams[$game_index-1]["id"] = $row["player1_id"];
            $teams[$game_index-1]["user_id"] = $row["user_id"];
            $teams[$game_index-1]["name"] = $row["player_name"];
            $teams[$game_index-1]["group_alias"] = $row["group_alias"];
            if ($teams[$game_index-1]["score"] == "") $teams[$game_index-1]["score"] = 0;
            $teams[$game_index-1]["score"] += $row["score1"];
            
            if ($game_index % 2 == 1) {
                if ($teams[$game_index]["score"] == "") $teams[$game_index]["score"] = 0;
                $teams[$game_index]["score"] += $row["score2"];
            }
            else{
                $teams[$game_index-2]["score"] += $row["score2"];
                $teams[$game_index-1]["score"] += $row["extra_score1"];
                $teams[$game_index-2]["score"] += $row["extra_score2"];
                $teams[$game_index-1]["p_score"] = $row["penalty_score1"];
                $teams[$game_index-2]["p_score"] = $row["penalty_score2"];
                if ($row["score1"] == "") {
                    $teams[$game_index-1]["score"] = "";
                    $teams[$game_index-2]["score"] = "";
                }
            }
        }
        
        $height = $total_height / $num_teams;
        if (sizeof($fixture) > 0) {
            echo '
        <div class="col-'.$width1.' no-padding" style="height: '.$total_height.'px; vertical-align: top;">';
        
            for ($i = 0; $i < $num_teams; $i++) {
                echo '
            <div style="width: 100%; height: '.$height.'px; text-align: right; line-height: '.$height.'px">
                <b>'.$fixture[$i].'</b>
            </div>';
            }

            echo '
        </div>';
        }
        
        
        
        echo '
        <div class="col-'.$width2.' no-padding" style="height: '.$total_height.'px; vertical-align:;">';
        
        for ($i = 0; $i < $num_teams; $i++) {
            if ($teams[$i]["group_alias"] == "" || strpos($teams[$i]["name"], $teams[$i]["group_alias"]) !== False || strpos($teams[$i]["group_alias"], $teams[$i]["name"]) !== False) {
                $display_name = $teams[$i]["name"];
            }
            else {
                $display_name = $teams[$i]["name"].' ('.$teams[$i]["group_alias"].')';
                if (strlen($display_name) > 10) $display_name = $teams[$i]["group_alias"];
            }
            echo '
            <div style="width: 100%; height: '.$height.'px; text-align: right; position: relative; cursor: pointer; color: '.(($uid!=0&&$teams[$i]["user_id"]==$uid)?$highligh_color:"black").'; font-weight: '.(($uid!=0&&$teams[$i]["user_id"]==$uid)?"bold":"normal").';" onclick="open_knockout_match_modal(\''.$round.'\', '.(-$i-1).');">
                <div style="margin: 0; position: absolute; top: 50%; right: 0%; transform: translate(0%, -50%); font-size: 12px; width: 100%; min-height: 20px; border: 1px solid black;">'.$display_name.'</div>
            </div>';
        }
        
        echo '
        </div>';
        
        
        echo '
	<div class="col-'.$width3.' no-padding" style="height: '.$total_height.'; vertical-align: top;">';
        
        for ($i = 0; $i < $num_teams/2; $i++) {
            $score1 = $teams[2*$i]["score"];
            $score2 = $teams[2*$i+1]["score"];
            if ($teams[2*$i]["p_score"] != "") $score1 = $score1.' ('.$teams[2*$i]["p_score"].')';
            if ($teams[2*$i+1]["p_score"] != "") $score2 = $score2.' ('.$teams[2*$i+1]["p_score"].')';
            
            $color1 = "black";
            $color2 = "black";
            if ($score1 != "" || $score2 != "") {
                if ($teams[2*$i]["score"] > $teams[2*$i+1]["score"] || $teams[2*$i]["p_score"] > $teams[2*$i+1]["p_score"]) {
                    $color1 = "black";
                    $color2 = "#E3E3E3";
                }
                else {
                    $color1 = "#E3E3E3";
                    $color2 = "black";
                }
            }
            
	    echo '
	    <div style="width: 100%; height: '.($total_height/$num_teams*2).'px;">
	        <div style="width: 100%; height: '.($total_height/$num_teams/2-22).'px;"></div>
                <div style="width: 100%; height: 22px; padding-left: 3px;">'.$score1.'</div>
                <div class="left_circle">
	            <div style="width: 100%; height: 50%; border-top: 1px solid '.$color1.'; border-right: 1px solid '.$color1.';"></div>
	            <div style="width: 100%; height: 50%; border-bottom: 1px solid '.$color2.'; border-right: 1px solid '.$color2.';"></div>
	        </div>
                
	        <div class="line"></div>
                <div style="width: 100%; height: 25%; margin-top: -10px; padding-left: 3px;">'.$score2.'</div>
	    </div>';
        }
        
        echo '
	</div>';
    }
    
    
    
    function draw_final($conn, $competition, $uid, $final_round_name, $num_rounds, $highligh_color) {
        $total_height = 720;
        if ($num_rounds == 2) {
            $width = 28;
        }
        if ($num_rounds == 3) {
            $width = 21;
        }

        $teams = array(array("id"=>"", "user_id"=>"", "group_alias"=>"", "name"=>"", "score"=>"", "p_score"=>""), array("id"=>"", "user_id"=>"", "group_alias"=>"", "name"=>"", "score"=>"", "p_score"=>""));
        
        $sql = sprintf('SELECT player1_id, U1.group_alias AS group_alias1, P1.player_name AS name1,P1.user_id AS user_id1,score1,extra_score1,penalty_score1,player2_id, U2.group_alias AS group_alias2, P2.player_name AS name2,P2.user_id AS user_id2,score2,extra_score2,penalty_score2 FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN users AS U1 ON P1.user_id=U1.user_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id LEFT JOIN users AS U2 ON P2.user_id=U2.user_id WHERE M.competition_id=%s AND stage="%s" ORDER BY game_index', $competition, $final_round_name);
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $teams[0]["id"] = $row["player1_id"];
            $teams[0]["user_id"] = $row["user_id1"];
            $teams[0]["group_alias"] = $row["group_alias1"];
            $teams[0]["name"] = $row["name1"];
            if ($teams[0]["score"] == "") $teams[0]["score"] = 0;
            $teams[0]["score"] += $row["score1"];
            $teams[0]["score"] += $row["extra_score1"];
            $teams[0]["p_score"] = $row["penalty_score1"];
            $teams[1]["id"] = $row["player2_id"];
            $teams[1]["user_id"] = $row["user_id2"];
            $teams[1]["group_alias"] = $row["group_alias2"];
            $teams[1]["name"] = $row["name2"];
            if ($teams[1]["score"] == "") $teams[1]["score"] = 0;
            $teams[1]["score"] += $row["score2"];
            $teams[1]["score"] += $row["extra_score2"];
            $teams[1]["p_score"] = $row["penalty_score2"];

            if ($row["score1"] == "") {
                $teams[0]["score"] = "";
                $teams[1]["score"] = "";
            }
        }
        
        $score1 = $teams[0]["score"];
        $score2 = $teams[1]["score"];

        // display names for two players in final
        if ($teams[0]["group_alias"] == "" || strpos($teams[0]["name"], $teams[0]["group_alias"]) !== False || strpos($teams[0]["group_alias"], $teams[0]["name"]) !== False) {
            $display_name0 = $teams[0]["name"];
        }
        else {
            $display_name0 = $teams[0]["name"].' ('.$teams[0]["group_alias"].')';
            if (strlen($display_name0) > 10) $display_name0 = $teams[0]["group_alias"];
        }
        if ($teams[1]["group_alias"] == "" || strpos($teams[1]["name"], $teams[1]["group_alias"]) !== False || strpos($teams[1]["group_alias"], $teams[1]["name"]) !== False) {
            $display_name1 = $teams[1]["name"];
        }
        else {
            $display_name1 = $teams[1]["name"].' ('.$teams[1]["group_alias"].')';
            if (strlen($display_name1) > 10) $display_name1 = $teams[1]["group_alias"];
        }

        $win_name = "";
        if ($score1 !== "" && $score2 !== "") {
            if ($teams[0]["score"] > $teams[1]["score"] || $teams[0]["p_score"] > $teams[1]["p_score"]) {
                $win_name = $display_name0;
            }
            else {
                $win_name = $display_name1;
            }
        }
        if ($teams[0]["p_score"] != "") $score1 = $score1.' ('.$teams[0]["p_score"].')';
        if ($teams[1]["p_score"] != "") $score2 = $score2.' ('.$teams[1]["p_score"].')';
        
        echo '
        <div class="col-'.$width.' no-padding" style="height: '.$total_height.'px; vertical-align: top;" onclick="open_knockout_match_modal(\'final\', 1);">
            <div style="width: 100%; height: 160px;"></div>
            <div style="width: 100%; height: 40px; text-align: right; position: relative; cursor: pointer; color: '.(($uid!=0&&$teams[0]["user_id"]==$uid)?$highligh_color:"black").'; font-weight: '.(($uid!=0&&$teams[0]["user_id"]==$uid)?"bold":"normal").';">
                <div style="margin: 0; position: absolute; top: 50%; right: 0%; transform: translate(0%, -50%); font-size: 12px; width: 100%; min-height: 20px; border: 1px solid black;">'.$display_name0.'</div>
            </div>
            <div style="width: 100%; height: 25px; text-align: right;">'.$score1.'</div>
            <div style="width: 100%; height: 270px; text-align: center; position: relative; cursor: pointer;">
                <img src="images/trophy.png" style="height: 120px;">
                <div style="margin: 0; position: absolute; top: 50%; right: 0%; transform: translate(0%, -50%); font-size: 12px; width: 100%; min-height: 20px; border: 1px solid #D4AF37; color: #D4AF37;">'.$win_name.'</div>
            </div>
            <div style="width: 100%; height: 25px; text-align: right;">'.$score2.'</div>
            <div style="width: 100%; height: 40px; text-align: right; position: relative; cursor: pointer; color: '.(($teams[1]["user_id"]==$uid)?$highligh_color:"black").'; font-weight: '.(($teams[1]["user_id"]==$uid)?"bold":"normal").';">
                <div style="margin: 0; position: absolute; top: 50%; right: 0%; transform: translate(0%, -50%); font-size: 12px; width: 100%; min-height: 20px; border: 1px solid black;">'.$display_name1.'</div>
            </div>
            <div style="width: 100%; height: 160px;"></div>
        </div>';
    }
    ?>
    
    <style type="text/css">
        .left_circle {
                width: 35%;
                height: 50%;
                display: inline-block;
                margin: 0px;
        }

        .right_circle {
                width: 35%;
                height: 50%;
                display: inline-block;
        }
        .line {
                width: 50%;
                height: 25%;
                border-top: 1px solid black;
                display: inline-block;
                margin: 0px;
        }
        .team_image {
                height: <?php echo ($font + 3) ?>px;
                width: <?php echo ($font + 3) ?>px;
        }
    </style>