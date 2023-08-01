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
if ($competition_status == "register") {

}
elseif ($competition_status == "knockouts") {

}
else {
	include("pages/groups.php");
	include("pages/knockouts.php");
}
?>