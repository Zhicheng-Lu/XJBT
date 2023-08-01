            <div class="row justify-content-center">
                <div class="col-120">
                    <center><h3><?php echo ($lang=="ENG")? "Final":"决赛"; ?></h3></center>
                </div>
                
                <?php
                $sql = sprintf('SELECT game_index, P1.player_name AS p1, P1.user_id AS user_id1, M.score1, M.score2, P2.player_name AS p2, P2.user_id AS user_id2, M.extra_score1, M.extra_score2, M.penalty_score1, M.penalty_score2 FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id WHERE M.competition_id=%s AND M.stage="%s"', $competition_id, $final_round_name);
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($row["score1"] > $row["score2"] || $row["extra_score1"] > $row["extra_score2"] || $row["penalty_score1"] > $row["penalty_score2"]) {
                        $winner = $row["p1"];
                    }
                    else {
                        $winner = $row["p2"];
                    }
                
                    echo '
                <div class="col-xxl-30 col-xl-40 col-md-60 border-div" style="margin-top: 15px; border: 1px solid #DDDDDD; border-radius: 5px;'.(($uid!=0&&($uid==$row["user_id1"]||$uid==$row["user_id2"]))?" color: ".$highligh_color."; font-weight: bold;":"").';">
                    <div class="row" style="cursor: pointer; width: 100%; padding-top: 7px; padding-bottom: 7px; border-bottom: 1px solid #DDDDDD;" onclick="open_knockout_match_modal(\'final\', 1)">
                        <div style="width: 100%;">
                            <div class="row">
                                <div class="col-48" style="text-align: right;">'.$row["p1"].'</div>
                                <div class="col-8 no-padding" style="text-align: center;">'.$row["score1"].'</div>
                                <div class="col-6 no-padding" style="text-align: center;">-</div>
                                <div class="col-8 no-padding" style="text-align: center;">'.$row["score2"].'</div>
                                <div class="col-48" style="text-align: left;">'.$row["p2"].'</div>
                            </div>
                        </div>
                        
                        <div class="col-120" style="text-align: right;">';
                            if ($row["extra_score1"] != "") {
                                echo '
                            （'.$dict["extra_time"][$lang].' '.$row["extra_score1"].' - '.$row["extra_score2"];
                                if ($row["penalty_score1"] != "") {
                                    echo '， '.$dict["penalty"][$lang].' '.$row["penalty_score1"].' - '.$row["penalty_score2"];
                                }
                                echo '）';
                            }
                            else {
                                echo '
                            <b style="color: white;">1</b>';
                            }
                        
                        echo '
                        </div>
                    </div>
                    
                    <div class="row" style="cursor: pointer; width: 100%; padding-top: 7px; padding-bottom: 7px;" onclick="open_knockout_match_modal(\'final\', 1)">
                        <div style="width: 100%;">
                            &nbsp;'.$dict["champion"][$lang].'：'.(is_null($row["score1"])? "":$winner).'
                        </div>
                    </div>
                </div>';
                }
                ?>
            </div>