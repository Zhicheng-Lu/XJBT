<br><br><br><br><br>

<div class="section">
    <div class="container" style="text-align: center;">
        <form action="index.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user["user_id"]; ?>">
            <label style="width: 100px;"><?php echo $dict["email"][$lang]; ?>: </label>
            <input type="email" value="<?php echo $user["email"] ?>" style="border: none; width: 180px;" readonly><br><br>
            <label style="width: 100px;"><?php echo $dict["old_password"][$lang]; ?>: </label>
            <input type="password" name="old_password" maxlength="30" style="width: 180px;"  required><br><br>
            <label style="width: 100px;"><?php echo $dict["new_password"][$lang]; ?>: </label>
            <input type="password" id="profile_password" name="new_password" maxlength="30" style="width: 180px;" oninput="check_profile_password()" required><br><br>
            <label style="width: 100px;"><?php echo $dict["confirm_password"][$lang]; ?>: </label>
            <input type="password" id="profile_confirm_password" maxlength="30" style="width: 165px;" oninput="check_profile_password()" required> <i id="check_profile_password_result" class="fa fa-close" style="color: red; width: 13px;"></i><br><br>
            <input type="hidden" name="lang" value="<?php echo $lang; ?>">
            <button id="profile_button" name="btn" value="account_password" class="my-button" style="margin-bottom: 20px; width: 60%;" disabled><?php echo $dict["modify_password"][$lang]; ?></button>
        </form>

        <br>

        <form action="index.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user["user_id"]; ?>">
            <label style="width: 100px;"><?php echo $dict["group_alias"][$lang]; ?>: </label>
            <input type="text" name="group_alias" maxlength="30" value="<?php echo $user["group_alias"] ?>" style="width: 180px;"  required><br><br>
            <label style="width: 100px;"><?php echo $dict["name"][$lang]; ?>: </label>
            <input type="text" name="name" maxlength="30" value="<?php echo $user["name"] ?>" style="width: 180px;"  required><br><br>
            <input type="hidden" name="lang" value="<?php echo $lang; ?>">
            <button name="btn" value="account_profile" class="my-button" style="margin-bottom: 20px; width: 60%;"><?php echo $dict["modify_profile"][$lang]; ?></button>
        </form>
    </div>
</div>


<div class="section">
    <div class="container" style="text-align: center;">
    	<form action="index.php" method="post">
            <input type="hidden" name="tab" value="<?php echo $tab; ?>">
            <input type="hidden" name="lang" value="<?php echo $lang; ?>">
    		<button name="btn" value="logout" class="my-button" style="background-color: #f44336; border: none; color: white; width: 60%;"><?php echo $dict["logout"][$lang]; ?></button>
    	</form>
    </div>
</div>

<script type="text/javascript">
    // check if password match, if not disable submit button
    function check_profile_password() {
        pwd = document.getElementById("profile_password").value;
        confirm_pwd = document.getElementById("profile_confirm_password").value;

        if (pwd == confirm_pwd && pwd != "") {
            document.getElementById("profile_button").disabled = false;
            document.getElementById("check_profile_password_result").classList.remove('fa-close');
            document.getElementById("check_profile_password_result").classList.add('fa-check');
            document.getElementById("check_profile_password_result").style.color = "green";
        }
        else {
            document.getElementById("profile_button").disabled = true;
            document.getElementById("check_profile_password_result").classList.remove('fa-check');
            document.getElementById("check_profile_password_result").classList.add('fa-close');
            document.getElementById("check_profile_password_result").style.color = "red";
        }
    }
</script>