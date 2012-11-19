<?
include("basic_functions.php");
switch($_GET['action']){
case "my_cash": $money=get_user_data(Array('money'));echo $money['money'].",".get_invested_money();break;
case "get_news": echo get_news();break;
case  "get_companies":get_companies();break;
    //TODO THIS IS INCOMPLETE
case "rank_table": return rank_table();break;
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
function get_companies(){
    $name=$_GET["name"];
    $type=$_GET["type"];
    $location=$_GET["location"];
    include("connect.php");
    $query=mysqli_query($connect,'select * from company where name like "%'.$name.'%" and company_type like "%'.$type.'%" and cid in (select cid from company_locations where location like "%'.$location.'%")');
    while($row=mysqli_fetch_array($query)){
        echo "<h1>".$row['name']."</h1>";
        echo "<strong>".$row['company_type']."</strong><br>";
        echo "Worth: <strong>".$row['worth']."</strong><br>";
        echo "<p>".$row['history']."</p>";
        echo "<strong>Company Centers</strong><br>";
        $query2=mysqli_query($connect,"select location from company_locations where cid=".$row['cid']);
        while($row2=mysqli_fetch_array($query2)){
            echo $row2['location']."<br>";
        }
        echo "<hr/>";
    }
}
function rank_table(){
    return 0;
}
?>
