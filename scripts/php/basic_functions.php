<?
/*
FUNCTIONS IN THIS FILE
get_user_info(type,uid=-1)
get_user_id($email)
sql_inject_clean($string)
isLogin()
get_username(uid=-1)
get_user_data(uid=-1,columns)
game_started()
get_time_status()
*/
function get_user_info($type=-1,$uid){
	include("connect.php");
	if($type==-1){//send all
		$query=mysqli_query($connect,"select * from users where id='".$uid."'") or die("cant get");
		while($row=mysqli_fetch_array($query))
			return $row;
	}
	else{
		$query=mysqli_query($connect,"select ".$type." from users where id='".$uid."'") or die("cant get");
		while($row=mysqli_fetch_array($query))
			return $row[$type];
	}
	return -1;//failed
}
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
function get_user_data($uid=-1,$column_array){
    $columns='';
    foreach($column as $column_array){
        if($columns=='')
            $columns=$column;
        else
            $columns=$columns.",".$column;
    }
    if(isLogin()&&$uid==-1)
		$uid=$_SESSION['uid'];
	include("connect.php");
	$query=mysqli_query($connect,"select ".$columns." user_name from users where uid=".$uid.";");
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
?>
