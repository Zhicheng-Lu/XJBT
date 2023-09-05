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
        if ($competition_id == 0) {
            include("pages/competitions.php");
        }
        else {
            include("pages/competition.php");
        }
        ?>
    </div>
</div>

