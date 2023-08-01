            <?php
            function games($conn, $dict, $competition, $lang, $uid, $stage, $highligh_color) {
                if ($stage == "final") {
                    $promoted_title = $dict["champion"][$lang];
                }
                else $promoted_title = $dict["winner"][$lang];

                $sql = sprintf('SELECT game_index, P1.player_name AS p1, P1.user_id AS user_id1, M.score1, M.score2, P2.player_name AS p2, P2.user_id AS user_id2, M.extra_score1, M.extra_score2, M.penalty_score1, M.penalty_score2 FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id WHERE M.competition_id=%s AND M.stage="%s" ORDER BY M.game_index ASC', $competition, $stage);
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $game_index = $row["game_index"];
                        
                    if ($game_index % 2 == 1) {
                        $score1_1 = $row["score1"];
                        $score1_2 = $row["score2"];
                    }
                    else {
                        $score2_1 = $row["score1"];
                        $score2_2 = $row["score2"];
                        
                        if (is_null($score2_1)) {
                            $total_score1 = null;
                            $total_score2 = null;
                        }
                        else {
                            $total_score1 = $score2_1 + $score1_2 + $row["extra_score1"];
                            $total_score2 = $score2_2 + $score1_1 + $row["extra_score2"];
                        }
        
                        if ($total_score1 > $total_score2 || ($total_score1 == $total_score2 && $row["penalty_score1"] > $row["penalty_score2"])) {
                            $win_team = $row["p1"];
                            $win_score = $total_score1;
                            $lose_team = $row["p2"];
                            $lose_score = $total_score2;
                        }
                        else {
                            $win_team = $row["p2"];
                            $win_score = $total_score2;
                            $lose_team = $row["p1"];
                            $lose_score = $total_score1;
                        }
                            
                        echo '
                <div class="col-xxl-30 col-xl-40 col-md-60 border-div" style="margin-top: 15px; border: 1px solid #DDDDDD; border-radius: 5px;'.(($uid!=0&&($uid==$row["user_id1"]||$uid==$row["user_id2"]))?" color: ".$highligh_color."; font-weight: bold;":"").';">
                    <div class="row" style="cursor: pointer; width: 100%; padding-top: 7px; padding-bottom: 7px;" onclick="open_knockout_match_modal(\''.$stage.'\', '.($game_index-1).')">
                        <div style="width: 100%;">
                            <div class="row">
                                <div class="col-52" style="text-align: right;">'.$row["p2"].'</div>
                                <div class="col-6 no-padding" style="text-align: center;">'.$score1_1.'</div>
                                <div class="col-4 no-padding" style="text-align: center;">-</div>
                                <div class="col-6 no-padding" style="text-align: center;">'.$score1_2.'</div>
                                <div class="col-52" style="text-align: left;">'.$row["p1"].'</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" style="cursor: pointer; width: 100%; padding-top: 7px; padding-bottom: 7px; border-bottom: 1px solid #DDDDDD;" onclick="open_knockout_match_modal(\''.$stage.'\', '.$game_index.')">
                        <div style="width: 100%;">
                            <div class="row">
                                <div class="col-52" style="text-align: right;">'.$row["p1"].'</div>
                                <div class="col-6 no-padding" style="text-align: center;">'.$score2_1.'</div>
                                <div class="col-4 no-padding" style="text-align: center;">-</div>
                                <div class="col-6 no-padding" style="text-align: center;">'.$score2_2.'</div>
                                <div class="col-52" style="text-align: left;">'.$row["p2"].'</div>
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
                    
                    <div class="row" style="cursor: pointer; width: 100%; padding-top: 7px; padding-bottom: 7px; border-bottom: 1px solid #DDDDDD;" onclick="open_knockout_match_modal(\'1_4\', '.$game_index.')">
                        <div style="width: 100%;">
                            <div class="row">
                                <div class="col-52" style="text-align: right;">'.$win_team.'</div>
                                <div class="col-6 no-padding" style="text-align: center;">'.$win_score.'</div>
                                <div class="col-4 no-padding" style="text-align: center;">-</div>
                                <div class="col-6 no-padding" style="text-align: center;">'.$lose_score.'</div>
                                <div class="col-52" style="text-align: left;">'.$lose_team.'</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" style="cursor: pointer; width: 100%; padding-top: 7px; padding-bottom: 7px;" onclick="open_knockout_match_modal(\'1_4\', '.$game_index.')">
                        <div style="width: 100%;">
                            &nbsp;'.$promoted_title.'：'.(is_null($total_score1)? "":$win_team).'
                        </div>
                    </div>
                </div>';
                    }
                }
            }
            ?>