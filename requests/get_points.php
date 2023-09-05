<?php
// get performance of each user
foreach ($users as $user_index=>$usr) {
	$total_weighted_points = 0;
	$weight = 1;
	$total_weights = 0.5;
	$num_participants = 0;
	foreach ($competitions as $c_id) {
		$sql = sprintf('SELECT M.stage, P1.user_id AS user_id1, P2.user_id AS user_id2, score1, score2 FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id WHERE M.competition_id=%s AND (P1.user_id=%s OR P2.user_id=%s)', $c_id, $usr["user_id"], $usr["user_id"]);
		$results = $conn->query($sql);
		while ($row = $results->fetch_assoc()) {
			$score1 = $row["score1"];
			$score2 = $row["score2"];
			if ($score1 == $score2 && $score1 != "") {
				if ($c_id != 2 && $row["stage"] == "group") $users[$user_index][$c_id]["points"] += 0.5;
				elseif (strpos($row["stage"], "loser")!==FALSE) $users[$user_index][$c_id]["points"] += 0.5;
				elseif ($row["stage"]=="1_1_4" || $row["stage"]=="1_semi_final" || $row["stage"]=="1_final") {}
				else $users[$user_index][$c_id]["points"] += 1;
			}
			if (($score1 > $score2 && $row["user_id1"] == $usr["user_id"]) || ($score1 < $score2 && $row["user_id2"] == $usr["user_id"])) {
				if ($c_id != 2 && $row["stage"] == "group") $users[$user_index][$c_id]["points"] += 1.5;
				elseif (strpos($row["stage"], "loser")!==FALSE) $users[$user_index][$c_id]["points"] += 1.5;
				elseif ($row["stage"]=="1_1_4" || $row["stage"]=="1_semi_final" || $row["stage"]=="1_final") {}
				else $users[$user_index][$c_id]["points"] += 3;
			}
		}

		// performance
		$stages = array(array("value"=>"group", "ENG"=>"Group Stage", "CHI"=>"小组赛", "points"=>2),
						array("value"=>"1_8", "ENG"=>"Last 16", "CHI"=>"16强", "points"=>5),
						array("value"=>"1_4", "ENG"=>"Quarter Final", "CHI"=>"8强", "points"=>10),
						array("value"=>"0_1_4", "ENG"=>"Quarter Final", "CHI"=>"8强", "points"=>10),
						array("value"=>"semi_final", "ENG"=>"Semi Final", "CHI"=>"4强", "points"=>20),
						array("value"=>"semi_finals", "ENG"=>"Semi Final", "CHI"=>"4强", "points"=>20),
						array("value"=>"0_semi_final", "ENG"=>"Semi Final", "CHI"=>"4强", "points"=>20),
						array("value"=>"loser_round_1", "ENG"=>"Eliminated Round 1", "CHI"=>"败者组第 1 轮", "points"=>4),
						array("value"=>"loser_round_2", "ENG"=>"Eliminated Round 2", "CHI"=>"败者组第 2 轮", "points"=>8),
						array("value"=>"loser_round_3", "ENG"=>"Eliminated Round 3", "CHI"=>"败者组第 3 轮", "points"=>12),
						array("value"=>"loser_round_4", "ENG"=>"Eliminated Round 4", "CHI"=>"败者组第 4 轮", "points"=>16),
						array("value"=>"loser_round_5", "ENG"=>"Eliminated Round 5", "CHI"=>"败者组第 5 轮", "points"=>20),
						array("value"=>"loser_final", "ENG"=>"Eliminated Final", "CHI"=>"败者组决赛", "points"=>25),
						array("value"=>"final", "ENG"=>"Runner-up", "CHI"=>"亚军", "points"=>30),
						array("value"=>"0_final", "ENG"=>"Runner-up", "CHI"=>"亚军", "points"=>30),
						array("value"=>"champion", "ENG"=>"Champion", "CHI"=>"冠军", "points"=>40));
		$temp_points = 0;
		foreach ($stages as $stage) {
			$sql = sprintf('SELECT * FROM matches AS M LEFT JOIN players AS P1 ON M.player1_id=P1.player_id LEFT JOIN players AS P2 ON M.player2_id=P2.player_id WHERE M.competition_id=%s AND M.stage="%s" AND (P1.user_id=%s OR P2.user_id=%s)', $c_id, $stage["value"], $usr["user_id"], $usr["user_id"]);
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$users[$user_index][$c_id]["performance"] = $stage[$lang];
				$temp_points = $stage["points"];
				break;
			}
		}
		$users[$user_index][$c_id]["points"] += $temp_points;

		// total
		$users[$user_index]["total_points"] += $users[$user_index][$c_id]["points"];
		$total_weighted_points += $users[$user_index][$c_id]["points"] * $weight;
		if ($users[$user_index][$c_id]["points"] > 0) {
			$total_weights += $weight;
			$num_participants += 1;
		}
		$weight /= 2;
	}
	$users[$user_index]["weighted_points"] = round($total_weighted_points / $total_weights, 2);
	$users[$user_index]["average_points"] = ($num_participants==0)? "-":round($users[$user_index]["total_points"] / $num_participants, 2);
}

usort($users, function($u1, $u2) use ($order_competition_id, $order) {
	if ($order == "down") $multiplier = 10000;
	else $multiplier = -10000;
	if ($order_competition_id == "-1") {
		$num1 = $u1["weighted_points"];
		$num2 = $u2["weighted_points"];
	}
	elseif ($order_competition_id == "0") {
		$num1 = $u1["total_points"];
		$num2 = $u2["total_points"];
	}
	elseif ($order_competition_id == "-2") {
		$num1 = $u1["average_points"];
		$num2 = $u2["average_points"];
	}
	else {
		$num1 = $u1[$order_competition_id]["points"];
		$num2 = $u2[$order_competition_id]["points"];
	}

	if ($num1 == $num2) {
		if ($u1["weighted_points"] == $u2["weighted_points"]) {
			return $multiplier * ($u2["total_points"] - $u1["total_points"]);
		}
		return $multiplier * ($u2["weighted_points"] - $u1["weighted_points"]);
	}
	return $multiplier * ($num2 - $num1);
});
?>