<?
include("basic_functions.php");
switch($_GET['action']){
    //TODO THIS IS INCOMPLETE
case "my_cash": $money=get_user_data(Array('money'));echo "['".$money['money']."','100000']";break;
case "get_news": echo get_news();break;
}
function get_news(){
    include("connect.php");
    $query=mysqli_query($connect,"select news * from news,gameconf where addtime(gameconf.start_time,news.time)<curtime() and addtime(gameconf.start_time,news.time)>subtime(curtime(),'00:05:00')");
    $result="['";
    while($row=mysqli_fetch_array($query)){
        if($result=="['")
            $result=$result."['".$row['title']."','".$row['description']."']";
        else
            $result=$result.",['".$row['title']."','".$row['description']."']";
    }
    return $result;
}
?>
