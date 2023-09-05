<?php
include("../includes/connection.php");
include("../includes/translation.php");

$order_competition_id = $_POST["order_competition_id"];
$order = $_POST["order"];
$user_id = $_POST["user_id"];
$lang = $_POST["lang"];
?>

<tr>
	<td style="width: 40px;" rowspan="2"><?php echo $dict["rank"][$lang];?></td>
	<td style="width: 150px;" rowspan="2"><?php echo $dict["player"][$lang];?></td>
	<?php
	// parameters for weighted total button
	$parameters = "-1, ";
	if ($order_competition_id == -1) {
		if ($order == "down") $parameters = $parameters."'up', ";
		else $parameters = $parameters."'down', ";
	}
	else {
		$parameters = $parameters.sprintf("'%s', ", $order);
	}
	$parameters = $parameters.sprintf("%s, '%s'", $user_id, $lang);
	?>
	<td style="width: 80px; cursor: pointer;" rowspan="2" onclick="get_rank(<?php echo $parameters;?>)"><?php echo $dict["weighted_points"][$lang];?>&nbsp;<i class="fa fa-<?php echo ($order_competition_id==-1)?"toggle-".$order:"sort"; ?>"></i></td>
	<?php
	// parameters for total button
	$parameters = "0, ";
	if ($order_competition_id == 0) {
		if ($order == "down") $parameters = $parameters."'up', ";
		else $parameters = $parameters."'down', ";
	}
	else {
		$parameters = $parameters.sprintf("'%s', ", $order);
	}
	$parameters = $parameters.sprintf("%s, '%s'", $user_id, $lang);
	?>
	<td style="width: 70px; cursor: pointer;" rowspan="2" onclick="get_rank(<?php echo $parameters;?>)"><?php echo $dict["total_points"][$lang];?>&nbsp;<i class="fa fa-<?php echo ($order_competition_id==0)?"toggle-".$order:"sort"; ?>"></i></td>
	<?php
	// parameters for average button
	$parameters = "-2, ";
	if ($order_competition_id == -2) {
		if ($order == "down") $parameters = $parameters."'up', ";
		else $parameters = $parameters."'down', ";
	}
	else {
		$parameters = $parameters.sprintf("'%s', ", $order);
	}
	$parameters = $parameters.sprintf("%s, '%s'", $user_id, $lang);
	?>
	<td style="width: 70px; cursor: pointer;" rowspan="2" onclick="get_rank(<?php echo $parameters;?>)"><?php echo $dict["average"][$lang];?>&nbsp;<i class="fa fa-<?php echo ($order_competition_id==-2)?"toggle-".$order:"sort"; ?>"></i></td>
	<?php
	// get and print all competitions
	$competitions = array();
	$sql = sprintf('SELECT C.competition_id, competition_name_%s AS competition_name FROM competitions AS C LEFT JOIN matches AS M ON C.competition_id=M.competition_id WHERE M.stage="champion" ORDER BY C.competition_id DESC', strtolower($lang));
	$results = $conn->query($sql);
	while ($row = $results->fetch_assoc()) {
		array_push($competitions, $row["competition_id"]);
		echo sprintf('
	<td style="width: 200px;" colspan="2">%s</td>', $row["competition_name"]);
	}
	?>
</tr>
<tr>
	<?php
	foreach ($competitions as $competition) {
		// parameters for button
		$parameters = $competition.", ";
		if ($order_competition_id == $competition) {
			if ($order == "down") $parameters = $parameters."'up', ";
			else $parameters = $parameters."'down', ";
		}
		else {
			$parameters = $parameters.sprintf("'%s', ", $order);
		}
		$parameters = $parameters.sprintf("%s, '%s'", $user_id, $lang);
		$icon = ($order_competition_id==$competition)?"toggle-".$order:"sort";
		echo sprintf('
	<td style="width: 130px;">%s</td>
	<td style="width: 70px; cursor: pointer;" onclick="get_rank(%s)">%s&nbsp;<i class="fa fa-%s"></i></td>', $dict["performance"][$lang], $parameters, $dict["points"][$lang], $icon);
	}
	?>
</tr>

<?php
// get all users
$users = array();
$sql = 'SELECT user_id, name, group_alias FROM users WHERE user_id<>0';
$results = $conn->query($sql);
while ($row = $results->fetch_assoc()) {
	array_push($users, array("user_id"=>$row["user_id"], "name"=>$row["name"], "group_alias"=>$row["group_alias"], "weighted_points"=>0, "total_points"=>0, "average_points"=>0));
	foreach ($competitions as $competition) {
		$users[sizeof($users)-1][$competition] = array("performance"=>"", "points"=>0);
	}
}

include("get_points.php");

// display in table
foreach ($users as $user_index=>$user) {
	echo '
<tr style="color: '.(($user["user_id"]==$user_id)?"#0096FF":"black").';">
	<td>'.($user_index+1).'</td>
	<td>'.$user["name"].'（'.$user["group_alias"].'）</td>
	<td>'.$user["weighted_points"].'</td>
	<td>'.$user["total_points"].'</td>
	<td>'.$user["average_points"].'</td>';

	foreach ($competitions as $competition_id) {
		echo '
	<td>'.$user[$competition_id]["performance"].'</td>
	<td>'.(($user[$competition_id]["points"]==0)?"":$user[$competition_id]["points"]).'</td>';
	}

	echo '
</tr>';
}
?>