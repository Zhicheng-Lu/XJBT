<div class="row justify-content-center">
	<div class="col-xl-60 col-120">
		<table style="width: 100%;">
			<tr>
				<th style="width: 40%;"><?php echo $dict["competition"][$lang] ?></th>
				<th style="width: 30%;"><?php echo $dict["champion"][$lang] ?></th>
				<th style="width: 30%;"><?php echo $dict["performance"][$lang] ?></th>
			</tr>

			<?php
			$sql1 = 'SELECT * FROM competitions ORDER BY competition_id ASC';
            $results1 = $conn->query($sql1);
            $max_competition_id = 0;
            while ($row1 = $results1->fetch_assoc()) {
            	$text = ($lang=="ENG")?$row1["competition_name_eng"]:$row1["competition_name_chi"];
                echo sprintf('
            <tr>
            	<td style="text-align: left; text-decoration: underline; cursor: pointer;"><a style="color: black;" href="index.php?tab=home&lang=%s&competition_id=%s&uid=%s">%s</a></td>', $lang, $row1["competition_id"], $uid, $text);

                // close competition
            	if ($row1["competition_status"] == "closed") {
            		$sql2 = sprintf('SELECT P.player_name, P.player_id FROM matches AS M LEFT JOIN players AS P ON M.player1_id=P.player_id WHERE M.competition_id=%s AND stage="champion"', $row1["competition_id"]);
	            	$results2 = $conn->query($sql2);
	            	$flag = FALSE;
	            	while ($row2 = $results2->fetch_assoc()) {
	            		$flag = TRUE;
	            		$champion_id = $row2["player_id"];
	            		$champion_name = $row2["player_name"];
	            	}

	            	if ($user["user_id"] == 1) {
            			echo '
	            <td style="text-align: center;">
	            	<form method="post" action="requests/modify_champion.php">
	            		<input type="hidden" name="lang" value="'.$lang.'">
						<input type="hidden" name="uid" value="'.$uid.'">
						<input type="hidden" name="competition_id" value="'.$row1["competition_id"].'">
	            		<select name="champion" onchange="this.form.submit();" style="width: 150px;">
	            			<option value=""></option>';
				        $sql3 = sprintf('SELECT * FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id=%s', $row1["competition_id"]);
				        $result3 = $conn->query($sql3);
				        while ($row3 = $result3->fetch_assoc()) {
				            echo '
		            		<option value="'.$row3["player_id"].'"'.(($row3["player_id"]==$champion_id)?" selected":"").'>'.$row3["player_name"].' ('.$row3["group_alias"].')</option>';
				        }

				        echo '
            			</select>
            		</form>
            	</td>';

            			$performance = get_performance($conn, $row1["competition_id"], $uid, $lang);
            			echo sprintf('
	            <td style="text-align: center;">%s</td>', $performance);
            		}

	            	elseif (!$flag) {
	            		echo '
	            <td style="text-align: center;" colspan="2">-</td>';
	            	}
	            	else {
	            		$performance = get_performance($conn, $row1["competition_id"], $uid, $lang);
	            		echo sprintf('
	            <td style="text-align: center;">%s</td>
	            <td style="text-align: center;">%s</td>', $champion_name, $performance);
	            	}

	            	$max_competition_id = $row1["competition_id"];
            	}
            	// ongoing competition
            	else if ($row1["competition_status"] == "signup") {
            		echo sprintf('
            	<td style="text-align: center;" colspan="2"><i class="fa fa-plus-circle" style="cursor: pointer;" onclick="open_signup_modal(%s, \'none\')"></i></td>', $row1["competition_id"]);
            		$max_competition_id = -1;
            	}
            	else if ($row1["competition_status"] == "groups") {
            		echo sprintf('
            	<td style="text-align: center;" colspan="2">%s</td>', $dict["group_stage"][$lang]);
            		$max_competition_id = -1;
            	}
            	else if ($row1["competition_status"] == "knockouts") {
            		echo sprintf('
            	<td style="text-align: center;" colspan="2">%s</td>', $dict["knockouts_stage"][$lang]);
            		$max_competition_id = -1;
            	}

            	echo '
            </tr>';
            }

            if ($max_competition_id >= 0 && $user["user_id"] == 1) {
        		echo '
        	<tr>
        		<td colspan="3" style="text-align: center;">
        			<form method="post" action="requests/create_new_competition.php">
        				<input type="hidden" name="lang" value="'.$lang.'">
						<input type="hidden" name="uid" value="'.$uid.'">
        				<label style="width: 80px;">English: </label><input type="text" name="competition_name_eng" placeholder="N-th XJBT Friendlies" required /><br>
        				<label style="width: 80px;">中文: </label><input type="text" name="competition_name_chi" placeholder="第N届XJBT杯友谊赛" required /><br>
        				<button class="my-button">'.$dict["confirm"][$lang].'</button>
        			</form>
        	</tr>';
        	}
			?>
		</table>
	</div>
</div>


<?php
$sql_competitions = 'SELECT * FROM competitions ORDER BY competition_id ASC';
$result_competitions = $conn->query($sql_competitions);
while ($row_competitions = $result_competitions->fetch_assoc()) {
	
	
	$competition_id = $row_competitions["competition_id"];
	$competition_status = $row_competitions["competition_status"];

	$flag = False;
	$sql = sprintf('SELECT * FROM players WHERE competition_id=%s AND user_id=%s AND user_id<>0', $competition_id, $uid);
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$flag = True;
		$uid_group = $row["group_index"];
	}

	if ($flag) {
		echo '
<br>
<h3 style="text-align: center;">'.$row_competitions["competition_name_".strtolower($lang)].'</h3>
<br>
<div class="row">
	<div class="col-lg-60 col-120">';
		include("pages/groups.php");
		echo '
	</div>
	<div class="col-lg-60 col-120">';
		include("pages/knockouts.php");
		echo '
	</div>
</div>';
	}
}

include("pages/signup_modal.php");

function get_performance($conn, $competition_id, $uid, $lang) {
	if ($uid == 0 || $competition_id == 4) return "-";

	$performance = "-";

	$stages = array(array("value"=>"group", "ENG"=>"Group Stage", "CHI"=>"小组赛"),
					array("value"=>"1_8", "ENG"=>"Last 16", "CHI"=>"16强"),
					array("value"=>"1_4", "ENG"=>"Quarter Final", "CHI"=>"8强"),
					array("value"=>"0_1_4", "ENG"=>"Quarter Final", "CHI"=>"8强"),
					array("value"=>"semi_final", "ENG"=>"Semi Final", "CHI"=>"4强"),
					array("value"=>"semi_finals", "ENG"=>"Semi Final", "CHI"=>"4强"),
					array("value"=>"0_semi_final", "ENG"=>"Semi Final", "CHI"=>"4强"),
					array("value"=>"loser_round_1", "ENG"=>"Eliminated Round 1", "CHI"=>"败者组第 1 轮"),
					array("value"=>"loser_round_2", "ENG"=>"Eliminated Round 2", "CHI"=>"败者组第 2 轮"),
					array("value"=>"loser_round_3", "ENG"=>"Eliminated Round 3", "CHI"=>"败者组第 3 轮"),
					array("value"=>"loser_round_4", "ENG"=>"Eliminated Round 4", "CHI"=>"败者组第 4 轮"),
					array("value"=>"loser_round_5", "ENG"=>"Eliminated Round 5", "CHI"=>"败者组第 5 轮"),
					array("value"=>"loser_final", "ENG"=>"Eliminated Final", "CHI"=>"败者组决赛"),
					array("value"=>"final", "ENG"=>"Runner-up", "CHI"=>"亚军"),
					array("value"=>"0_final", "ENG"=>"Runner-up", "CHI"=>"亚军"),
					array("value"=>"champion", "ENG"=>"Champion", "CHI"=>"冠军"));

	foreach ($stages as $stage) {
		$sql = sprintf('SELECT * FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id WHERE M.competition_id=%s AND M.stage="%s" AND (P1.user_id=%s OR P2.user_id=%s)', $competition_id, $stage["value"], $uid, $uid);
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$performance = $stage[$lang];
			break;
		}
	}

	return $performance;
}
?>