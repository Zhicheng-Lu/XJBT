<br><br><br><br>

<div class="section">
    <div class="container" style="text-align: center;">
    	<?php
        if ($user["user_id"] == "") {
            echo '
        <button class="my-button" onclick="open_login_modal();">'.$dict["login"][$lang].'/'.$dict["register"][$lang].'</button>';
        }
        $sql = 'SELECT COUNT(competition_id) AS num_competitions FROM matches WHERE stage="champion"';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $num_competitions = $row["num_competitions"];
        }
        ?>
        <table id="rank_table" style="margin-top: 40px; margin-left: auto; margin-right: auto; table-layout: fixed; width: <?php echo 340+200*$num_competitions; ?>px;"></table>
        <p style="margin-top: 15px; text-align: left; width: <?php echo 340+200*$num_competitions; ?>px; font-size: 8pt;"><?php echo $dict["rank_explanation"][$lang] ?></p>
   	</div>
</div>

<script type="text/javascript">
	function get_rank(order_competition_id, order, user_id, lang) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("rank_table").innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("POST", "requests/get_rank.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("order_competition_id=" + order_competition_id + "&order=" + order + "&user_id=" + user_id + "&lang=" + lang);
    }

    get_rank(-1, "down", "<?php echo $user["user_id"]; ?>", "<?php echo $lang;?>");
</script>