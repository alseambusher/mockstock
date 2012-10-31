<?ini_set('display_errors', 'On');?>
<?
switch($_GET['action']){
	case 'login':login();break;
	case 'logout':logout();
}
function login(){
	include("connect.php");
	$query=mysqli_query($connect,"select * from gameconf where password='".md5($_POST['password'])."'");
	if(mysqli_num_rows($query)==1){
		session_start();
		$_SESSION['backend']=md5('backend_key');
	}
}
function logout(){
	include("connect.php");
	session_start();
	if(isset($_SESSION['backend'])&&($_SESSION['backend']==md5('backend_key'))){
		$_SESSION=array();
		session_destroy();
		header("Location:".$config['base_url']."/backend");
	}
}
?>
