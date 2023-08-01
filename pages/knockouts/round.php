    <?php
    function knockout_round($conn, $dict, $competition, $lang, $uid, $title, $stage, $highligh_color) {
        echo '
            <div class="row justify-content-center">';
            
        echo '
                <h3>'.$title.'</h3>';
        
        echo '    
            </div>
            <div class="row justify-content-center" style="margin-bottom: 40px;">';
            
        games($conn, $dict, $competition, $lang, $uid, $stage, $highligh_color);
            
        echo '
            </div>';
    }
    ?>
    
    <script type="text/javascript">
        function open_knockout_match_modal(stage, game_index) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("knockout_match_modal_body").innerHTML = xhttp.responseText;
                    document.getElementById("knockout_match_modal").style.display = "block";
                }
            };
            xhttp.open("POST", "knockouts/knockouts_match_modal_body.php", true);
            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhttp.send("competition=" + <?php echo $competition; ?> + "&stage=" + stage + "&game_index=" + game_index);
        }
        
        function close_knockout_match_modal() {
            document.getElementById("knockout_match_modal").style.display = "none";
        }
    </script>
    
    <form action="knockouts/add_knockout_match.php" method="post">
        <div id="knockout_match_modal" class="modal" style="top: 5%; z-index: 9999;">
            <div class="modal-content col-xxl-40 offset-xxl-40 col-xl-60 offset-xl-30 col-lg-80 offset-lg-20 col-md-100 offset-md-10">
                <div class="modal-header">
                    <span class="close" onclick="close_knockout_match_modal()">&times;</span>
                </div>
                <div class="modal-body" id="knockout_match_modal_body">
                </div>
                <div class="modal-footer justify-content-center">
                    <button style="height: 40px; border-radius: 5px; font-size: 20px; background-color: white; width: 40%;">确认</button>
                </div>
            </div>
        </div>
    </form>