<br><br><br><br>

<div class="section">
    <div class="container" style="text-align: center; position: sticky; top: 0; z-index: 9998;">
        <?php
        if ($user["user_id"] == "") {
            echo '
        <button class="my-button" onclick="open_login_modal();">'.$dict["login"][$lang].'/'.$dict["register"][$lang].'</button>';
        }
        ?>
        <form method="get" action="index.php">
            <input type="hidden" name="tab" value="home">
            <input type="hidden" name="lang" value="<?php echo $lang ?>">
            <br>
            
            <!-- button for select user -->
            <div class="row justify-content-center">
                <div class="col-xl-30 col-60">
                    <select name="uid" style="width: 100%;" onchange="this.form.submit();">
                        <option value="0"<?php echo ($uid==0)? " selected":"" ?>><-- <?php echo $dict["all_users"][$lang] ?> --></option>
                        <?php
                        if ($user["user_id"] != "") {
                            $sql = sprintf('SELECT * FROM users WHERE user_id=%s', $user["user_id"]);
                            $results = $conn->query($sql);
                            while ($row = $results->fetch_assoc()) {
                                $selected = ($uid==$user["user_id"])?" selected":"";
                                echo sprintf('
                        <option value="%s"%s>%s</option>
                        <option disabled>-------------------------</option>', $row["user_id"], $selected, $row["name"]);
                            }
                        }

                        $sql = sprintf('SELECT * FROM users WHERE user_id<>%s AND user_id<>0 ORDER BY name ASC', ($user["user_id"]=="")?0:$user["user_id"]);
                        $results = $conn->query($sql);
                        while ($row = $results->fetch_assoc()) {
                            $selected = ($uid==$row["user_id"])?" selected":"";
                            echo sprintf('
                        <option value="%s"%s>%s (%s)</option>', $row["user_id"], $selected, $row["name"], $row["group_alias"]);
                        }
                        ?>
                    </select>
                </div>

                <!-- button for select competition -->
                <div class="col-xl-30 col-60">
                    <select name="competition_id" style="width: 100%;" onchange="this.form.submit();">
                        <option value="0"<?php echo ($competition_id==0)? " selected":"" ?>><-- <?php echo $dict["all_competitions"][$lang] ?> --></option>
                        <?php
                        $sql = 'SELECT * FROM competitions ORDER BY competition_index ASC';
                        $results = $conn->query($sql);
                        while ($row = $results->fetch_assoc()) {
                            $selected = ($competition_id==$row["competition_id"])?" selected":"";
                            $text = ($lang=="ENG")?$row["competition_name_eng"]:$row["competition_name_chi"];
                            echo sprintf('
                        <option value="%s"%s>%s</option>', $row["competition_id"], $selected, $text);
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <br>
    <div class="container">
        <?php
        include("pages/knockouts/round.php");
        include("pages/knockouts/games.php");
        $highligh_color = "#0096FF";
        if ($competition_id == 0) {
            $competitions = True;
            include("pages/competitions.php");
        }
        else {
            $competitions = False;
            include("pages/competition.php");
        }
        ?>
    </div>
</div>


<form action="requests/groups/add_group_match.php" method="post">
    <div id="match_modal" class="modal" style="top: 5%; z-index: 9999;">
        <div class="modal-content col-xxl-40 offset-xxl-40 col-xl-60 offset-xl-30 col-lg-80 offset-lg-20 col-md-100 offset-md-10">
            <div class="modal-header">
                <span class="close" onclick="close_match_modal()">&times;</span>
            </div>
            <div class="modal-body" id="match_modal_body">
            </div>
            <div class="modal-footer justify-content-center">
                <button style="height: 40px; border-radius: 5px; font-size: 20px; background-color: white; width: 40%;">确认</button>
            </div>
        </div>
    </div>
</form>

<?php
function cmp($p1, $p2) {
    if (3*$p1["win"]+$p1["draw"] == 3*$p2["win"]+$p2["draw"]) {
        if ($p1["goal"]-$p1["concede"] == $p2["goal"]-$p2["concede"]) {
            return $p2["goal"] - $p1["goal"];
        }
                            
        return ($p2["goal"]-$p2["concede"]) - ($p1["goal"]-$p1["concede"]);
    }
                        
    return (3*$p2["win"]+$p2["draw"]) - (3*$p1["win"]+$p1["draw"]);
}
?>

<script type="text/javascript">
    function open_match_modal(player1, player2) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("match_modal_body").innerHTML = xhttp.responseText;
                document.getElementById("match_modal").style.display = "block";
            }
        };
        xhttp.open("POST", "requests/groups/group_match_modal_body.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("competition_id=<?php echo $competition_id; ?>&uid=<?php echo $uid; ?>&lang=<?php echo $lang;?>&player1=" + player1 + "&player2=" + player2);
    }
    
    function close_match_modal() {
        document.getElementById("match_modal").style.display = "none";
    }
</script>