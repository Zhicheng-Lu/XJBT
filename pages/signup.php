<div class="row" id="signup_modal_body">
	<input type="hidden" id="signup_name">
    <input type="hidden" id="signup_eaid">
</div>

<div style="width: 100%; text-align: center; margin-top: 30px;">
    <table style="max-width: 400px; margin: auto;">
        <thead>
            <tr>
                <th style="width: 10%;"><?php echo $dict["rank"][$lang]; ?></th>
                <th style="width: 38%;"><?php echo $dict["name"][$lang]; ?></th>
                <th style="width: 38%;"><?php echo $dict["group_alias"][$lang]; ?></th>
                <th style="width: 14%;"><?php echo $dict["points"][$lang]; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            // get all competitions
            $competitions = array();
            $sql = sprintf('SELECT C.competition_id, competition_name_%s AS competition_name FROM competitions AS C LEFT JOIN matches AS M ON C.competition_id=M.competition_id WHERE M.stage="champion" ORDER BY C.competition_id DESC', strtolower($lang));
            $results = $conn->query($sql);
            while ($row = $results->fetch_assoc()) {
                array_push($competitions, $row["competition_id"]);
            }
            // get all users
            $users = array();
            $sql = sprintf('SELECT U.user_id, U.group_alias, P.player_name FROM players AS P LEFT JOIN users AS U ON P.user_id=U.user_id WHERE P.competition_id=%s', $competition_id);
            $results = $conn->query($sql);
            while ($row = $results->fetch_assoc()) {
                array_push($users, array("user_id"=>$row["user_id"], "group_alias"=>$row["group_alias"], "player_name"=>$row["player_name"], "weighted_points"=>0, "total_points"=>0, "average_points"=>0));
                foreach ($competitions as $competition) {
                    $users[sizeof($users)-1][$competition] = array("performance"=>"", "points"=>0);
                }
            }
            $order_competition_id = "-1";
            $order = "down";
            include("requests/get_points.php");
            foreach ($users as $user_index => $usr) {
                echo '
            <tr>
                <td>'.($user_index+1).'</td>
                <td>'.$usr["player_name"].'</td>
                <td>'.$usr["group_alias"].'</td>
                <td>'.$usr["weighted_points"].'</td>
            </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
if ($user["user_id"] == 1) {
    include("pages/group_draw/group_draw.php");
}
?>

<script type="text/javascript">
	open_signup_modal();
	function open_signup_modal(competition_id, action) {
		var name = document.getElementById("signup_name").value;
		var eaid = document.getElementById("signup_eaid").value;

		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("signup_modal_body").innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("POST", "requests/signup.php", true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send("competition_id=<?php echo $competition_id;?>&lang=<?php echo $lang;?>&user_id=<?php echo ($user["user_id"]=="")?"-1":$user["user_id"];?>&name=" + name + "&eaid=" + eaid + "&action=" + action + "&page=competition");
    }
</script>