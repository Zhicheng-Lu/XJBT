<?php
$sql = sprintf('SELECT * FROM competitions WHERE competition_id=%s', $competition_id);
$results = $conn->query($sql);
while ($row = $results->fetch_assoc()) {
	$competition_status = $row["competition_status"];
	$competition_name = $row["competition_name_".strtolower($lang)];
}

?>

<h3 style="text-align: center;"><?php echo $competition_name; ?></h3>
<br>
<?php
if ($user["user_id"] == 1) {
	echo '
<div class="col-120" style="text-align: center; margin-bottom: 10px;">
	<form method="post" action="requests/change_competition_status.php">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="uid" value="'.$uid.'">
		<input type="hidden" name="competition_id" value="'.$competition_id.'">
		<select name="competition_status" style="width: 40%;" onchange="this.form.submit();">
			<option value="signup"'.($competition_status=="signup"?" selected":"").'>'.$dict["signup"][$lang].'</option>
			<option value="groups"'.($competition_status=="groups"?" selected":"").'>'.$dict["group_stage"][$lang].'</option>
			<option value="knockouts"'.($competition_status=="knockouts"?" selected":"").'>'.$dict["knockouts_stage"][$lang].'</option>
			<option value="closed"'.($competition_status=="closed"?" selected":"").'>'.$dict["closed"][$lang].'</option>
		</select>
	</form>
</div>';}
?>

<?php
$uid_group = -1;

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
	echo sprintf('
<div class="col-120" style="text-align: center; margin-bottom: 10px;"><button class="my-button" onclick="open_signup_modal(%s, \'none\');">'.$dict["modify_signup_info"][$lang].'</button></div>', $competition_id);
	include("pages/signup_modal.php");
	include("pages/knockouts.php");
	include("pages/groups.php");
}
else {
	include("pages/groups.php");
	include("pages/knockouts.php");
}
?>