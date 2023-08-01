<?php
// rank
function rank($teams, $matches) {


    // compute the information for each team, e.g. win, draw, etc.
    foreach ($teams as $team_name=>$team) {
    	$win = 0;
    	$draw = 0;
    	$lose = 0;
    	$score = 0;
    	$concede = 0;
    	$away_score = 0;

    	foreach ($matches as $match) {
    		// if match not completed, skip
    		if ($match["score1"] == "" || $match["score2"] == "") {
    			continue;
    		}

    		if ($match["team1"] == $team_name) {
    			if ($match["score1"] > $match["score2"]) $win++;
    			if ($match["score1"] == $match["score2"]) $draw++;
    			if ($match["score1"] < $match["score2"]) $lose++;
    			$score += $match["score1"];
    			$concede += $match["score2"];
    		}

    		if ($match["team2"] == $team_name) {
    			if ($match["score2"] > $match["score1"]) $win++;
    			if ($match["score1"] == $match["score2"]) $draw++;
    			if ($match["score2"] < $match["score1"]) $lose++;
    			$score += $match["score2"];
    			$concede += $match["score1"];
    			$away_score += $match["score2"];
    		} 
    	}

    	$teams[$team_name]["team_name"] = $team_name;
    	$teams[$team_name]["win"] = $win;
    	$teams[$team_name]["draw"] = $draw;
    	$teams[$team_name]["lose"] = $lose;
    	$teams[$team_name]["score"] = $score;
    	$teams[$team_name]["concede"] = $concede;
    	$teams[$team_name]["difference"] = $score - $concede;
    	$teams[$team_name]["point"] = 3 * $win + $draw;
    	$teams[$team_name]["away_score"] = $away_score;
    }

    usort($teams, "cmp1");

    // team1 and team2 same point
    if ($teams[0]["point"] == $teams[1]["point"] && $teams[1]["point"] != $teams[2]["point"]) {
    	$teams = compare_two_teams($matches, $teams, 0, 1);
    }
    // team2 and team3 same point
    if ($teams[0]["point"] != $teams[1]["point"] && $teams[1]["point"] == $teams[2]["point"] && $teams[2]["point"] != $teams[3]["point"]) {
    	$teams = compare_two_teams($matches, $teams, 1, 2);
    }
    // team3 and team4 same point
    if ($teams[1]["point"] != $teams[2]["point"] && $teams[2]["point"] == $teams[3]["point"]) {
    	$teams = compare_two_teams($matches, $teams, 2, 3);
    }
    // team1, team2 and team3 same point
    if ($teams[0]["point"] == $teams[1]["point"] && $teams[1]["point"] == $teams[2]["point"] && $teams[2]["point"] != $teams[3]["point"]) {
    	$teams = compare_three_teams($matches, $teams, 0, 1, 2);
    }
    // team2, team3 and team4 same point
    if ($teams[0]["point"] != $teams[1]["point"] && $teams[1]["point"] == $teams[2]["point"] && $teams[2]["point"] == $teams[3]["point"]) {
    	$teams = compare_three_teams($matches, $teams, 1, 2, 3);
    }

    return $teams;
}


// comparison
function cmp1($a, $b) {
	if ($a["point"] == $b["point"]) {
		if ($a["difference"] == $b["difference"]) {
			return ($b["score"] - $a["score"]);
		}
		return ($b["difference"] - $a["difference"]);
	}
	return ($b["point"] - $a["point"]);
}

// if three teams get the same point
function compare_three_teams($matches, $teams, $index1, $index2, $index3) {
	$three_teams = array(array("index"=>$index1, "team_name"=>$teams[$index1]["team_name"], "point"=>0, "difference"=>0, "score"=>0, "away_score"=>0), array("index"=>$index2, "team_name"=>$teams[$index2]["team_name"], "point"=>0, "difference"=>0, "score"=>0, "away_score"=>0), array("index"=>$index3, "team_name"=>$teams[$index3]["team_name"], "point"=>0, "difference"=>0, "score"=>0, "away_score"=>0));
	$combinations = array(array(0, 1), array(1, 0), array(0, 2), array(2, 0), array(1, 2), array(2, 1));

	foreach ($matches as $match) {
		foreach ($combinations as $combination) {
			$team_index1 = $combination[0];
			$team_index2 = $combination[1];
			$team1 = $three_teams[$team_index1]["team_name"];
			$team2 = $three_teams[$team_index2]["team_name"];
			if ($match["team1"] == $team1 && $match["team2"] == $team2) {
				$score1 = $match["score1"];
				$score2 = $match["score2"];
				if ($score1 > $score2) {
					$three_teams[$team_index1]["point"] += 3;
				}
				if ($score1 == $score2) {
					$three_teams[$team_index1]["point"] += 1;
					$three_teams[$team_index2]["point"] += 1;
				}
				if ($score1 < $score2) {
					$three_teams[$team_index2]["point"] += 3;
				}
				$three_teams[$team_index1]["difference"] += ($score1 - $score2);
				$three_teams[$team_index2]["difference"] += ($score2 - $score1);
				$three_teams[$team_index1]["score"] += $score1;
				$three_teams[$team_index2]["score"] += $score2;
				$three_teams[$team_index2]["away_score"] += $score2;
			}
		}
	}

	$swap = array();
	$swap[$index1] = $teams[$index1];
	$swap[$index2] = $teams[$index2];
	$swap[$index3] = $teams[$index3];

	usort($three_teams, "cmp1");
	$teams[$index1] = $swap[$three_teams[0]["index"]];
	$teams[$index2] = $swap[$three_teams[1]["index"]];
	$teams[$index3] = $swap[$three_teams[2]["index"]];

	return $teams;
}

// if two teams get the same point
function compare_two_teams($matches, $teams, $index1, $index2) {
	$team1 = $teams[$index1]["team_name"];
	$score1 = 0;
	$away_score1 = 0;
	$team2 = $teams[$index2]["team_name"];
	$score2 = 0;
	$away_score2 = 0;
	foreach ($matches as $match) {
		if ($match["team1"] == $team1 && $match["team2"] == $team2) {
			$score1 += $match["score1"];
			$score2 += $match["score2"];
			$away_score2 += $match["score2"];
		}
		if ($match["team1"] == $team2 && $match["team2"] == $team1) {
			$score1 += $match["score2"];
			$score2 += $match["score1"];
			$away_score1 += $match["score2"];
		}
	}
	
	// if swap is required
	if ($score2 > $score1) {
		$teams = swap($teams, $index1, $index2);
	}
	return $teams;
}

// swap
function swap($array, $index1, $index2) {
	$swap = $array[$index1];
	$array[$index1] = $array[$index2];
	$array[$index2] = $swap;
	return $array;
}
?>