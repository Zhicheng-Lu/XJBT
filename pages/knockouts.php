<br>
<?php
if ($competition_id == 1) {
	include("pages/knockouts/round.php");
	include("pages/knockouts/games.php");

	$fixture = array("A3", "B2", "B4", "A1", "B3", "A2", "A4", "B1");
	$rounds = array(array("name"=>"1_4", "name_ENG"=>"Quarter Final", "name_CHI"=>"1/4 决赛", "num_teams"=>8, "fixture"=>$fixture),
					array("name"=>"semi_finals", "name_ENG"=>"Semi Final", "name_CHI"=>"半决赛", "num_teams"=>4, "fixture"=>array()));
	$final_round_name = "final";

	include("knockouts/diagram.php");

	echo '<br>';

	foreach ($rounds as $round) {
		knockout_round($conn, $dict, $competition_id, $lang, $uid, $round["name_".$lang], $round["name"], $highligh_color);
	}
	include("pages/knockouts/final.php");
}


if ($competition_id == 2) {
	include("pages/knockouts/round.php");
	include("pages/knockouts/games.php");

	$fixture = array("C2", "B1", "D2", "A1", "A2", "D1", "B2", "C1");
	$rounds = array(array("name"=>"0_1_4", "name_ENG"=>"Quarter Final", "name_CHI"=>"1/4 决赛", "num_teams"=>8, "fixture"=>$fixture),
					array("name"=>"0_semi_final", "name_ENG"=>"Semi Final", "name_CHI"=>"半决赛", "num_teams"=>4, "fixture"=>array()));
	$final_round_name = "0_final";

	include("knockouts/diagram.php");

	echo '<br>';

	foreach ($rounds as $round) {
		knockout_round($conn, $dict, $competition_id, $lang, $uid, $round["name_".$lang], $round["name"], $highligh_color);
	}
	include("pages/knockouts/final.php");

	echo '<br>';
	$fixture = array("C2", "B1", "D2", "A1", "A2", "D1", "B2", "C1");
	$rounds = array(array("name"=>"1_1_4", "name_ENG"=>"Quarter Final", "name_CHI"=>"1/4 决赛", "num_teams"=>8, "fixture"=>$fixture),
					array("name"=>"1_semi_final", "name_ENG"=>"Semi Final", "name_CHI"=>"半决赛", "num_teams"=>4, "fixture"=>array()));
	$final_round_name = "1_final";
	echo '
	<div class="row">
        <div class="col-lg-60 offset-lg-30 col-120">
            <div class="row">';
                foreach ($rounds as $round) {
                    draw_round($conn, $competition_id, $uid, $round["num_teams"], $round["name"], sizeof($rounds), $round["fixture"], $highligh_color);
                }
                draw_final($conn, $competition_id, $uid, $final_round_name, sizeof($rounds), $highligh_color);
    echo '
            </div>
        </div>
    </div>';

	echo '<br>';

	foreach ($rounds as $round) {
		knockout_round($conn, $dict, $competition_id, $lang, $uid, $round["name_".$lang], $round["name"], $highligh_color);
	}
	include("pages/knockouts/final.php");
}



if ($competition_id == 3) {
	include("pages/knockouts/round.php");
	include("pages/knockouts/games.php");

	$rounds = array(array("name"=>"loser_round_1", "name_ENG"=>"Eliminated Round 1", "name_CHI"=>"败者组第 1 轮"),
					array("name"=>"winner_1_4", "name_ENG"=>"Winner Quarter Final", "name_CHI"=>"胜者组 1/4 决赛"),
					array("name"=>"loser_round_2", "name_ENG"=>"Eliminated Round 2", "name_CHI"=>"败者组第 2 轮"),
					array("name"=>"loser_round_3", "name_ENG"=>"Eliminated Round 3", "name_CHI"=>"败者组第 3 轮"),
					array("name"=>"winner_semi_final", "name_ENG"=>"Winner Semi Final", "name_CHI"=>"胜者组半决赛"),
					array("name"=>"loser_round_4", "name_ENG"=>"Eliminated Round 4", "name_CHI"=>"败者组第 4 轮"),
					array("name"=>"loser_round_5", "name_ENG"=>"Eliminated Round 5", "name_CHI"=>"败者组第 5 轮"),
					array("name"=>"winner_final", "name_ENG"=>"Winner Final", "name_CHI"=>"胜者组决赛"),
					array("name"=>"loser_final", "name_ENG"=>"Eliminated Final", "name_CHI"=>"败者组决赛"),
					array("name"=>"final", "name_ENG"=>"Final", "name_CHI"=>"决赛"));

	echo '<br>';

	foreach ($rounds as $round) {
		knockout_round($conn, $dict, $competition_id, $lang, $uid, $round["name_".$lang], $round["name"], $highligh_color);
	}
}



if ($competition_id == 5 || $competition_id == 6 || $competition_id == 7) {
	include("pages/knockouts/round.php");
	include("pages/knockouts/games.php");

	$fixture = array("A1", "B4", "C2", "D3", "B1", "A4", "D2", "C3", "C1", "D4", "A2", "B3", "D1", "C4", "B2", "A3");
	$rounds = array(array("name"=>"1_8", "name_ENG"=>"1/8 Final", "name_CHI"=>"1/8 决赛", "num_teams"=>16, "fixture"=>$fixture),
					array("name"=>"1_4", "name_ENG"=>"Quarter Final", "name_CHI"=>"1/4 决赛", "num_teams"=>8, "fixture"=>array()),
					array("name"=>"semi_final", "name_ENG"=>"Semi Final", "name_CHI"=>"半决赛", "num_teams"=>4, "fixture"=>array()));
	$final_round_name = "final";

	include("knockouts/diagram.php");

	echo '<br>';

	foreach ($rounds as $round) {
		knockout_round($conn, $dict, $competition_id, $lang, $uid, $round["name_".$lang], $round["name"], $highligh_color);
	}
	include("pages/knockouts/final.php");
}
?>