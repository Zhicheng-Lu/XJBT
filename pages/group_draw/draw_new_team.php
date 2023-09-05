<?php
include("../../includes/connection.php");

$competition = $_POST["competition"];
$id = $_POST["id"];
$group_index = $_POST["group_index"];

$sql = 'UPDATE players SET group_index='.$group_index.' WHERE player_id='.$id;
$conn->query($sql);


$sql = 'SELECT * FROM players WHERE player_id='.$id;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo '<br><br><br>'.$row["player_name"].' ('.$row["ea_id"].')';
}
?>