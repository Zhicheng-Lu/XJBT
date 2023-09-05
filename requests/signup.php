<?php
include("../includes/translation.php");
include("../includes/connection.php");

$competition_id = $_POST["competition_id"];
$lang = $_POST["lang"];
$user_id = $_POST["user_id"];
$name = $_POST["name"];
$eaid = $_POST["eaid"];
$action = $_POST["action"];
$page = $_POST["page"];

$sql = sprintf('SELECT competition_status FROM competitions WHERE competition_id=%s', $competition_id);
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
	$competition_status = $row["competition_status"];
}

$signed = False;
if ($page == "competitions") {
	$class_name = "col-xl-60 col-120";
}
else {
	$class_name = "col-xxl-30 col-xl-30 col-lg-40 col-md-60 col-120";
}

if ($action == "signup") {
	$sql = sprintf('INSERT INTO players(user_id, competition_id, ea_id, player_name, group_index) VALUES(%s, %s, "%s", "%s", -1)', $user_id, $competition_id, $eaid, $name);
	$conn->query($sql);
}
elseif ($action == "modify") {
	$sql = sprintf('UPDATE players SET ea_id="%s", player_name="%s" WHERE user_id=%s AND competition_id=%s', $eaid, $name, $user_id, $competition_id);
	$conn->query($sql);
}
elseif ($action == "withdraw") {
	$sql = sprintf('DELETE FROM players  WHERE user_id=%s AND competition_id=%s', $user_id, $competition_id);
	$conn->query($sql);
}

if ($user_id == -1) {
	if ($page == "competitions") {
		echo '
<div class="col-120" style="text-align: center;">
	<button class="my-button" onclick="document.getElementById(\'signup_modal\').style.display=\'none\';open_login_modal();">'.$dict["login"][$lang].'/'.$dict["register"][$lang].'</button>
	<input type="hidden" id="signup_name">
	<input type="hidden" id="signup_eaid">
</div>';
	}
}
else {
	$group_alias = "";
	$sql = sprintf('SELECT * FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.user_id=%s AND P.competition_id=%s', $user_id, $competition_id);
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$signed = True;
		$name = $row["player_name"];
		$eaid = $row["ea_id"];
		$group_alias = $row["group_alias"];
	}

	echo '
<div class="col-120" style="text-align: center; margin-bottom: 20px;">
	'.$dict["name"][$lang].'&nbsp;&nbsp;
	<input type="text" id="signup_name" style="width: 80%%;" value="'.$name.'">
</div>
<div class="col-120" style="text-align: center; margin-bottom: 20px;">
	EAID&nbsp;&nbsp;
	<input type="text" id="signup_eaid" style="width: 80%%;" value="'.$eaid.'">
</div>';

	if (!$signed) {
		echo '
<div class="col-120" style="text-align: center;">
	<button class="my-button" onclick="open_signup_modal('.$competition_id.', \'signup\');">'.$dict["signup"][$lang].'</button>
</div>';
	}
	else {
		echo '
<div class="col-120" style="text-align: center;">
	<button class="my-button" onclick="open_signup_modal('.$competition_id.', \'modify\');">'.$dict["confirm_modify"][$lang].'</button>
</div>';

		if ($competition_status == "signup") {
			echo '
<div class="col-120" style="text-align: center; margin-top: 10px;">
	<button class="my-button" style="background-color: red; color: white; border: none;" onclick="open_signup_modal('.$competition_id.', \'withdraw\');">'.$dict["withdraw"][$lang].'</button>
</div>';
		}

		echo '
<div class="'.$class_name.'" style="margin-top: 20px; vertical-align: middle; border: #AAAAAA 1px solid; border-radius: 5px;">
    <div class="row">
        <div class="col-110">
            1. '.$name.' <br>'.$dict["group_alias"][$lang].': '.$group_alias.'<br>EA ID: '.$eaid.'
        </div>
    </div> 
</div>';
	}
}

if ($signed) $counter = 2;
else $counter = 1;

$sql = sprintf('SELECT * FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id=%s AND P.user_id<>%s ORDER BY P.player_id ASC', $competition_id, $user_id);
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
	echo '
<div class="'.$class_name.'" style="margin-top: 20px; vertical-align: middle; border: #AAAAAA 1px solid; border-radius: 5px;">
    <div class="row">
        <div class="col-110">
            '.$counter.'. '.$row["player_name"].' <br>'.$dict["group_alias"][$lang].': '.$row["group_alias"].'<br>EA ID: '.$row["ea_id"].'
        </div>
    </div> 
</div>';
	
	$counter += 1;
}
?>