<?php
$dict = array();

$dict["toggle_lang"] = array("CHI"=>"ENG", "ENG"=>"CHI");
$dict["home"] = array("CHI"=>"首页", "ENG"=>"Home");
$dict["rank"] = array("CHI"=>"排名", "ENG"=>"Rank");
$dict["toggle_lang_display"] = array("CHI"=>"ENG", "ENG"=>"中文");
$dict["login"] = array("CHI"=>"登录", "ENG"=>"Login");
// $dict["account"] = array("CHI"=>"账号", "ENG"=>"Account");
$dict["email"] = array("CHI"=>"邮箱", "ENG"=>"Email");
$dict["password"] = array("CHI"=>"密码", "ENG"=>"Password");
$dict["confirm_password"] = array("CHI"=>"确认密码", "ENG"=>"Re Password");
$dict["old_password"] = array("CHI"=>"旧密码", "ENG"=>"Old Password");
$dict["new_password"] = array("CHI"=>"新密码", "ENG"=>"New Password");
$dict["profile_password_error"] = array("CHI"=>"修改密码失败！", "ENG"=>"Password modification failed!");
$dict["group_alias"] = array("CHI"=>"群昵称", "ENG"=>"Group Alias");
$dict["name"] = array("CHI"=>"名称", "ENG"=>"Name");
$dict["register"] = array("CHI"=>"注册", "ENG"=>"Register");
$dict["register_succ"] = array("CHI"=>"注册成功！", "ENG"=>"Successfully registered!");
$dict["register_error"] = array("CHI"=>"该邮箱已注册！", "ENG"=>"The email address is already in use!");
$dict["login_error"] = array("CHI"=>"邮箱或密码不正确！", "ENG"=>"The email or password is wrong!");
$dict["logout"] = array("CHI"=>"登出", "ENG"=>"Logout");
$dict["modify_password"] = array("CHI"=>"修改密码", "ENG"=>"Modify Password");
$dict["modify_profile"] = array("CHI"=>"修改资料", "ENG"=>"Modify Profile");

$dict["all_users"] = array("CHI"=>"所有玩家", "ENG"=>"All Players");
$dict["all_competitions"] = array("CHI"=>"所有赛事", "ENG"=>"All Competitions");
$dict["competition"] = array("CHI"=>"赛事", "ENG"=>"Competition");
$dict["champion"] = array("CHI"=>"冠军", "ENG"=>"Champion");
$dict["performance"] = array("CHI"=>"成绩", "ENG"=>"Performance");
$dict["group"] = array("CHI"=>"组", "ENG"=>"Group");
$dict["player"] = array("CHI"=>"玩家", "ENG"=>"Player");
$dict["win"] = array("CHI"=>"胜", "ENG"=>"W");
$dict["draw"] = array("CHI"=>"平", "ENG"=>"D");
$dict["lose"] = array("CHI"=>"负", "ENG"=>"L");
$dict["score"] = array("CHI"=>"进球", "ENG"=>"GS");
$dict["conceed"] = array("CHI"=>"失球", "ENG"=>"GA");
$dict["difference"] = array("CHI"=>"净胜", "ENG"=>"GD");
$dict["points"] = array("CHI"=>"积分", "ENG"=>"Pts");

$dict["winner"] = array("CHI"=>"晋级", "ENG"=>"Winner");
$dict["extra_time"] = array("CHI"=>"加时", "ENG"=>"E.T.");
$dict["penalty"] = array("CHI"=>"点球", "ENG"=>"P.K.");

$dict["total_points"] = array("CHI"=>"总积分", "ENG"=>"Total Pts");
$dict["weighted_points"] = array("CHI"=>"加权积分", "ENG"=>"Weighted Pts");
$dict["average"] = array("CHI"=>"平均", "ENG"=>"Average");
$dict["rank_explanation"] = array("CHI"=>"*每届基础积分：胜3分，平1分（不包含加时、加赛、点球）。第三届败者组积分减半：胜1.5负，平0.5分。<br>
											*每届成绩附加分：小组赛2分，16强5分，8强10分，4强20分，亚军30分，冠军40分。第三届小组赛2分，败者组第1轮4分、第2轮8分、第3轮12分、第4轮16分、第5轮20分，败者组决赛25分，亚军30分，冠军40分。<br>
											*积分及总积分：每届积分<i>p</i><sub><i>i</i></sub>为每届基础分+每届成绩附加分。总积分&Sigma;<i>p</i><sub><i>i</i></sub>为各届积分之和。<br>
											*权重：最新一届权重<i>w</i><sub>0</sub>=1，往前依次减半，例如<i>w</i><sub>1</sub>=0.5。权重之和&Sigma;<i>w</i><sub><i>i</i></sub>不计算未参与比赛。<br>
											*加权积分：&Sigma;(<i>w</i><sub><i>i</i></sub>&times;<i>p</i><sub><i>i</i></sub>)&div;(&Sigma;<i>w</i><sub><i>i</i></sub>+0.5)。新一届XJBT杯的分档将基于该积分。",
									"ENG"=>"*Basic Points: 3 points for win, 1 point for draw (extra time, extra match and penalty shootout excluded). Eliminated rounds in 3rd Competition worth half of the points: 1.5 points for win, 0.5 point for draw.<br>
											*Performance Bonus Points: 2 points for Group Stage, 5 points for Last 16, 10 points for Last 8, 20 points for Last 4, 30 points for Runner-up, 40 points for Champioin. As for 3rd Competition, 2 points for Group Stage, 4 points for Eliminated Round 1, 8 points for Round 2, 12 points for Round 3, 16 points for Round 4, 20 points for Round 5, 25 points for Eliminated Final, 30 points for Runner-up, 40 points for Champion.<br>
											*Points and Total Points: points <i>p</i><sub><i>i</i></sub> for each competition is the sum of Basic Points and Performance Bonus Points. Total Points &Sigma;<i>p</i><sub><i>i</i></sub> is the sum of points for all competitions.<br>
											*Weight: weight for the latest competition <i>w</i><sub>0</sub>=1, and the weight is halved each time for previous competitions, for example, <i>w</i><sub>1</sub>=0.5. Sum of weights does not include skipped competition.<br>
											*Weighted Points: &Sigma;(<i>w</i><sub><i>i</i></sub>&times;<i>p</i><sub><i>i</i></sub>)&div;(&Sigma;<i>w</i><sub><i>i</i></sub>+0.5). The Weighted Points will be used for pot allocation of new competition.");

$dict["signup"] = array("CHI"=>"报名", "ENG"=>"Sign Up");
$dict["confirm_modify"] = array("CHI"=>"确认修改", "ENG"=>"Confirm");
$dict["withdraw"] = array("CHI"=>"取消报名", "ENG"=>"Withdraw");

$dict["group_stage"] = array("CHI"=>"小组赛阶段", "ENG"=>"Group Stage");
$dict["knockouts_stage"] = array("CHI"=>"淘汰赛阶段", "ENG"=>"Knockouts Stage");
$dict["closed"] = array("CHI"=>"结束", "ENG"=>"Closed");
$dict["modify_signup_info"] = array("CHI"=>"修改资料", "ENG"=>"Modify Info");
$dict["advanced"] = array("CHI"=>"晋级", "ENG"=>"Adv");
$dict["winners_group"] = array("CHI"=>"胜者组", "ENG"=>"WG");
$dict["eliminated_group"] = array("CHI"=>"败者组", "ENG"=>"EG");
?>