<?
/*
FUNCTIONS IN THIS FILE
get_user_id($email)
sql_inject_clean($string)
isLogin()
get_username(uid=-1)
get_user_data(columns,$uid=-1)
game_started()
get_time_status()
get_invested_money($uid=-1)
get_market_companies();//used only in market and is not usable anywhere else
*/
function get_user_id($email){
	include("connect.php");
	$query=mysqli_query($connect,"select uid from users where email='".$email."'");
	while($row=mysqli_fetch_array($query))
		return $row['uid'];
	return -1;//failed
}
function sql_inject_clean($string){
	include("connect.php");
	return mysqli_real_escape_string($connect,$string);
}
function islogin(){
	session_start();
	if(isset($_SESSION['uid']))
		if($_SESSION['uid']!=NULL)
			return true;
	return false;
}
function get_username($uid=-1){
	if(isLogin()&&$uid==-1)
		$uid=$_SESSION['uid'];
	include("connect.php");
	$query=mysqli_query($connect,"select concat(first_name,' ',last_name) user_name from users where uid=".$uid.";");
	while($row=mysqli_fetch_array($query))
		return $row['user_name'];
	return -1;
}
function get_user_data($column_array,$uid=-1){
    $columns='';
    foreach($column_array as $column){
        if($columns=='')
            $columns=$column;
        else
            $columns=$columns.",".$column;
    }
    if(isLogin()&&$uid==-1)
		$uid=$_SESSION['uid'];
	include("connect.php");
	$query=mysqli_query($connect,"select ".$columns." from users where uid='".$uid."'");
	while($row=mysqli_fetch_array($query))
		return $row;
	return -1;
}
function game_started(){
	include("connect.php");
    $query=mysqli_query($connect,"call get_time_status()");
    while($row=mysqli_fetch_array($query))
        if($row['game_status']=="Game ends in")
		    return true;
	return false;
}
function get_time_status(){
    include("connect.php");
    $query=mysqli_query($connect,"call get_time_status()");
    while($row=mysqli_fetch_array($query))
        return $row;
}
function get_invested_money($uid=-1){
    include("connect.php");
    if($uid==-1){
        session_start();
        $uid=$_SESSION['uid'];
    }
    $query=mysqli_query($connect,"select sum(owns_shares_of.no_of_shares*stock_record.price_per_share) as investment from owns_shares_of,stock_record,gameconf where addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00') and owns_shares_of.uid=".$uid." and owns_shares_of.cid=stock_record.cid");
    while($row=mysqli_fetch_array($query))
        return $row['investment'];
    return 0;
}
function get_market_companies(){
    include("connect.php");
    $query=mysqli_query($connect,"select cid,name from company order by name");
    while($row=mysqli_fetch_array($query))
            echo "<li><a id='market_company_".$row['cid']."' rel='popover' data-content='Loading...' title='".$row['name']."' onmouseover='company_onmouseover(\"".$row['cid']."\");' href='#'>".$row['name']."</a></li>";

}
function console_log($msg){
}
?>
