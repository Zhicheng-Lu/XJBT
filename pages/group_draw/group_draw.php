<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_group_draw(<?php echo $competition_id; ?>)">小组赛抽签</button>
        </div>
    </div>
</div>

<div id="group_draw_modal" class="modal" style="z-index:9999">
    <!-- Modal content -->
    <div class="modal-content col-lg-60 offset-lg-30 col-md-90 offset-md-15 col-120" id="group_draw_modal_content">
    </div>
</div>

<div id="chosen_participant_modal" class="modal" style="z-index: 19999; font-size: 40px">
    <div class="modal-content col-lg-108 offset-lg-6 col-120">
        <div class="modal-body" id="chosen_participant_modal_body" style="height: 500px; text-align: center; vertical-align: middle;">
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal -->

<script type="text/javascript">
    function open_group_draw(competition_id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("group_draw_modal_content").innerHTML = xhttp.responseText;
                document.getElementById("group_draw_modal").style.display = "block";
            }
        };
        xhttp.open("POST", "pages/group_draw/draw_modal.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("competition_id=" + competition_id + "&lang=<?php echo $lang;?>");
    }
    
    function close_group_draw_modal() {
        // document.getElementById("group_draw_modal").style.display = "none";
        location.reload();
    }
    
    function draw_new_team(id, group_index) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("chosen_participant_modal_body").innerHTML = xhttp.responseText;
                document.getElementById("chosen_participant_modal").style.display = "block";
                setTimeout(function(){
                    open_group_draw(<?php echo $competition_id; ?>);
                    document.getElementById("chosen_participant_modal").style.display = "none";
                }, 400);
            }
        };
        xhttp.open("POST", "pages/group_draw/draw_new_team.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("id=" + id + "&group_index=" + group_index + "&competition=" + <?php echo $competition_id; ?>);
    }
</script>