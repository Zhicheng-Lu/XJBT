<div id="login_modal" class="modal" style="top: 5%; z-index: 9999;">
    <div class="modal-content" style="width: 400px; margin: 0 auto;">
        <div class="modal-header">
            <span class="close" onclick="close_login_modal()">&times;</span>
        </div>
        <div class="modal-body" id="login_modal_body">
            <div style="margin-top: 10px; border-bottom: 1px solid black; text-align: center;">
                <h3><?php echo $dict["login"][$lang]; ?></h3><br>
                <form action="index.php" method="post">
                    <label style="width: 100px;"><?php echo $dict["email"][$lang]; ?>: </label>
                    <input type="email" name="email" maxlength="30"><br><br>
                    <label style="width: 100px;"><?php echo $dict["password"][$lang]; ?>: </label>
                    <input type="password" name="password" maxlength="30" required><br><br>
                    <input type="hidden" name="tab" value="<?php echo $tab; ?>">
                    <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                    <input type="hidden" name="competition_id" value="<?php echo $competition_id; ?>">
                    <button name="btn" value="login" class="my-button" style="margin-bottom: 30px;"><?php echo $dict["login"][$lang]; ?></button>
                </form>
            </div>

            <div style="margin-top: 20px; text-align: center;">
                <h3><?php echo $dict["register"][$lang]; ?></h3><br>
                <form action="index.php" method="post">
                    <label style="width: 100px;"><?php echo $dict["email"][$lang]; ?>: </label>
                    <input type="email" name="email" maxlength="30" style="width: 180px;"><br><br>
                    <label style="width: 100px;"><?php echo $dict["password"][$lang]; ?>: </label>
                    <input type="password" id="register_password" name="password" maxlength="30" style="width: 180px;" oninput="check_register_password()" required><br><br>
                    <label style="width: 100px;"><?php echo $dict["confirm_password"][$lang]; ?>: </label>
                    <input type="password" id="register_confirm_password" maxlength="30" style="width: 165px;" oninput="check_register_password()" required> <i id="check_register_password_result" class="fa fa-close" style="color: red; width: 13px;"></i><br><br>
                    <label style="width: 100px;"><?php echo $dict["group_alias"][$lang]; ?>: </label>
                    <input type="text" name="group_alias" maxlength="30" style="width: 180px;" required><br><br>
                    <label style="width: 100px;"><?php echo $dict["name"][$lang]; ?>: </label>
                    <input type="text" name="name" maxlength="30" style="width: 180px;" required><br><br>
                    <input type="hidden" name="tab" value="<?php echo $tab; ?>">
                    <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                    <input type="hidden" name="competition_id" value="<?php echo $competition_id; ?>">
                    <button id="register_button" name="btn" value="register" class="my-button" style="margin-bottom: 20px;" disabled><?php echo $dict["register"][$lang]; ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function check_register_password() {
        pwd = document.getElementById("register_password").value;
        confirm_pwd = document.getElementById("register_confirm_password").value;

        if (pwd == confirm_pwd && pwd != "") {
            document.getElementById("register_button").disabled = false;
            document.getElementById("check_register_password_result").classList.remove('fa-close');
            document.getElementById("check_register_password_result").classList.add('fa-check');
            document.getElementById("check_register_password_result").style.color = "green";
        }
        else {
            document.getElementById("register_button").disabled = true;
            document.getElementById("check_register_password_result").classList.remove('fa-check');
            document.getElementById("check_register_password_result").classList.add('fa-close');
            document.getElementById("check_register_password_result").style.color = "red";
        }
    }
</script>