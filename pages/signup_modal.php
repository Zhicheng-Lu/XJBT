<div id="signup_modal" class="modal row" style="top: 5%; z-index: 9999;">
    <div class="modal-content col-lg-40 col-md-80" style="margin: 0 auto;">
        <div class="modal-header">
            <span class="close" onclick="close_signup_modal()">&times;</span>
        </div>
        <div class="modal-body row" id="signup_modal_body">
        	<input type="text" id="signup_name">
        	<input type="text" id="signup_eaid">
        </div>
    </div>
</div>

<script type="text/javascript">
	function open_signup_modal(competition_id, action) {
		var name = document.getElementById("signup_name").value;
		var eaid = document.getElementById("signup_eaid").value;

		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            	document.getElementById("signup_modal").style.display = "block";
                document.getElementById("signup_modal_body").innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("POST", "requests/signup.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("competition_id=" + competition_id + "&lang=<?php echo $lang;?>&user_id=<?php echo ($user["user_id"]=="")?"-1":$user["user_id"];?>&name=" + name + "&eaid=" + eaid + "&action=" + action + "&page=competitions");
    }

    function close_signup_modal() {
        document.getElementById("signup_modal").style.display = "none";
    }
</script>