<?
include("basic_functions.php");
switch($_GET['action']){
    //TODO THIS IS INCOMPLETE
case "my_cash": $money=get_user_data(Array('money'));echo $money['money'].",".get_invested_money();break;
case "get_news": echo get_news();break;
}
function get_news(){
    include("connect.php");
    $query=mysqli_query($connect,"select news.* from news,gameconf where addtime(gameconf.start_time,news.time)<curtime() and addtime(gameconf.start_time,news.time)>subtime(curtime(),'00:05:00')");
    $result=array();
    while($row=mysqli_fetch_array($query)){
        $title=htmlentities($row['title']);
        $description=htmlentities($row['description']);
        array_push($result,$title);
        array_push($result,$description);
    }
    return json_encode($result);
}
?>
