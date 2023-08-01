<?php
include("includes/connection.php");
include("includes/translation.php");

// set language
if (isset($_GET["lang"])) {
    $lang = $_GET["lang"];
}
elseif (isset($_POST["lang"])) {
    $lang = $_POST["lang"];
}
else{
    $lang = "CHI";
}

// set tab
if (isset($_GET["tab"])) {
    $tab = $_GET["tab"];
}
else{
    $tab = "home";
}

// competition id
if (isset($_GET["competition_id"])) {
    $competition_id = $_GET["competition_id"];
}
else{
    $competition_id = 0;
}

// check if there is post request
if (isset($_POST["btn"])) {
    include("requests/request.php");
}

// check cookie (i.e., if login)
if (isset($_COOKIE['user_id'])) {
    $sql = sprintf('SELECT * FROM users WHERE user_id=%s', $_COOKIE['user_id']);
    $results = $conn->query($sql);
    while ($row = $results->fetch_assoc()) {
        $user = array("user_id"=>$row["user_id"], "email"=>$row["email"], "group_alias"=>$row["group_alias"], "name"=>$row["name"]);
    }
}
else {
    $user = array("user_id"=>"", "email"=>"", "group_alias"=>"", "name"=>"");
}

// user id
if (isset($_GET["uid"])) {
    $uid = $_GET["uid"];
}
elseif ($user["user_id"] != 0) {
    $uid = $user["user_id"];
}
else {
    $uid = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>XJBT杯友谊赛</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/swiper.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="js/custom.js"></script>
    <link rel="stylesheet" href="css/mycss.css">
    <link rel="stylesheet" href="css/grid.css">
</head>

<body>
    <!-- header -->
    <header class="site-header">
        <div class="nav-bar">
            <div class="container">
                <div class="row">
                    <div class="col-120 d-flex flex-wrap justify-content-between align-items-center">
                        <div class="site-branding d-flex align-items-center">
                           <a class="d-block" href="../index.php" rel="home"><img class="d-block" src="images/logo.png" alt="logo" style="height: 80px;"></a>
                        </div><!-- .site-branding -->

                        <!-- navigation bar -->
                        <nav class="site-navigation d-flex justify-content-end align-items-center">
                            <ul class="d-flex flex-column flex-lg-row justify-content-lg-end align-items-center">
                                <li class="<?php if ($tab=="home") echo "current-menu-item";?>"><a href="#" onclick="change_tab('home')"><i class="fa fa-home"></i> <?php echo $dict["home"][$lang] ?></a></li>

                                <li class="<?php if ($tab=="rank") echo "current-menu-item";?>"><a href="#" onclick="change_tab('rank')"><i class="fa fa-angle-double-down"></i> <?php echo $dict["rank"][$lang] ?></a></li>

                                <li><a href="#" onclick="change_lang('<?php echo $dict["toggle_lang"][$lang]; ?>');"><i class="fa fa-language"></i> <?php echo $dict["toggle_lang_display"][$lang] ?></a></li>

                                <li class="<?php if ($tab=="account") echo "current-menu-item";?>"><a href="#" onclick="<?php echo ($user["user_id"]=="")? "open_login_modal()":"change_tab('account')" ?>"><i class="fa fa-user"></i> <?php echo ($user["user_id"]=="")? $dict["login"][$lang].'/'.$dict["register"][$lang]:$user["name"] ?></a></li>
                            </ul>
                        </nav><!-- .site-navigation -->


                        <div class="hamburger-menu d-lg-none">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div><!-- .hamburger-menu -->
                    </div><!-- .col -->
                </div><!-- .row -->
            </div><!-- .container -->
        </div><!-- .nav-bar -->
    </header>

    <form id="form_page_setting">
        <input type="hidden" name="tab" value="<?php echo $tab; ?>" id="form_page_setting_tab">
        <input type="hidden" name="lang" value="<?php echo $lang; ?>" id="form_page_setting_lang">
        <input type="hidden" name="competition_id" value="<?php echo $competition_id; ?>">
        <input type="hidden" name="uid" value="<?php echo $uid; ?>">
    </form>

    <?php
    include("pages/login_modal.php");
    if ($tab == "home") include("pages/home.php");
    if ($tab == "rank") include("pages/rank.php");
    if ($tab == "account") include("pages/account.php");
    ?>

    <script type='text/javascript' src='js/jquery.js'></script>
    <script type='text/javascript' src='js/jquery.collapsible.min.js'></script>
    <script type='text/javascript' src='js/swiper.min.js'></script>
    <script type='text/javascript' src='js/jquery.countdown.min.js'></script>
    <script type='text/javascript' src='js/circle-progress.min.js'></script>
    <script type='text/javascript' src='js/jquery.countTo.min.js'></script>
    <script type='text/javascript' src='js/jquery.barfiller.js'></script>
    <script type='text/javascript' src='js/custom.js'></script>
    <script type="text/javascript">
        function change_lang(lang_name) {
            document.getElementById("form_page_setting_lang").value = lang_name;
            document.getElementById("form_page_setting").submit();
        }

        function change_tab(tab_name) {
            document.getElementById("form_page_setting_tab").value = tab_name;
            document.getElementById("form_page_setting").submit();
        }

        function open_login_modal() {
            document.getElementById("login_modal").style.display = "block";
        }

        function close_login_modal() {
            document.getElementById("login_modal").style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            var modals = document.getElementsByClassName("modal");
            for (var i = modals.length - 1; i >= 0; i--) {
                var modal = modals[i];
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>
</body>
</html>