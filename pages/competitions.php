<div class="row justify-content-center">
	<div class="col-xl-60 col-120">
		<table style="width: 100%;">
			<tr>
				<th style="width: 40%;"><?php echo $dict["competition"][$lang] ?></th>
				<th style="width: 30%;"><?php echo $dict["champion"][$lang] ?></th>
				<th style="width: 30%;"><?php echo $dict["performance"][$lang] ?></th>
			</tr>

			<?php
			$sql1 = 'SELECT * FROM competitions ORDER BY competition_index ASC';
            $results1 = $conn->query($sql1);
            while ($row1 = $results1->fetch_assoc()) {
            	$text = ($lang=="ENG")?$row1["competition_name_eng"]:$row1["competition_name_chi"];
                echo sprintf('
            <tr>
            	<td style="text-align: left; text-decoration: underline; cursor: pointer;"><a style="color: black;" href="index.php?tab=home&lang=%s&competition_id=%s&uid=%s">%s</a></td>', $lang, $row1["competition_id"], $uid, $text);

            	$sql2 = sprintf('SELECT P.player_name FROM matches AS M LEFT JOIN players AS P ON M.player1_id=P.player_id WHERE M.competition_id=%s AND stage="champion"', $row1["competition_id"]);
            	$results2 = $conn->query($sql2);
            	$flag = FALSE;
            	while ($row2 = $results2->fetch_assoc()) {
            		$flag = TRUE;
            		echo sprintf('
            	<td style="text-align: center;">%s</td>', $row2["player_name"]);
            	}
            	if (!$flag) {
            		echo '
            	<td style="text-align: center;" colspan="2">-</td>';
            	}
            	else {
            		$performance = get_performance($conn, $row1["competition_id"], $uid, $lang);
            		echo sprintf('
            	<td style="text-align: center;">%s</td>', $performance);
            	}

            	echo '
            </tr>';
            }
			?>
		</table>
	</div>
</div>

<?php
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