<?php
$sql = sprintf('SELECT * FROM competitions WHERE competition_id=%s', $competition_id);
$results = $conn->query($sql);
while ($row = $results->fetch_assoc()) {
	$competition_status = $row["competition_status"];
	$competition_name = $row["competition_name_".strtolower($lang)];
}

$highligh_color = "#0096FF";
?>

<h3 style="text-align: center;"><?php echo $competition_name; ?></h3>
<br>

<?php
if ($competition_status == "signup") {
	include("pages/signup.php");
}
elseif ($competition_status == "groups") {
	echo sprintf('
<div class="col-120" style="text-align: center; margin-bottom: 10px;"><button class="my-button" onclick="open_signup_modal(%s, \'none\');">'.$dict["modify_signup_info"][$lang].'</button></div>', $competition_id);
	include("pages/signup_modal.php");
	include("pages/groups.php");
	include("pages/knockouts.php");
}
elseif ($competition_status == "knockouts") {

}
else {
	include("pages/groups.php");
	include("pages/knockouts.php");
}
?>