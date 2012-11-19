<?
include("basic_functions.php");
switch($_GET['action']){
case "my_cash": $money=get_user_data(Array('money'));echo $money['money'].",".get_invested_money();break;
case "get_news": echo get_news();break;
case "get_companies":get_companies();break;
case "rank_table": return rank_table();break;
//TODO fix for first 5 minutes
case "get_stock_news":get_stock_news();break;
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
    include("connect.php");
    $query=mysqli_query($connect,"select users.money cash_in_hand,sum(owns_shares_of.no_of_shares*stock_record.price_per_share) cash_invested,sum(owns_shares_of.no_of_shares*stock_record.price_per_share)+users.money as total, concat(users.first_name,' ',users.last_name) as full_name from owns_shares_of,stock_record,gameconf,users where addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00') and owns_shares_of.cid=stock_record.cid and owns_shares_of.uid=users.uid group by users.uid order by total desc");
    $rank=1;
    echo '<table  class="table">
            <tr><th>Rank</th><th>Player</th><th>Cash in Hand</th><th>Cash invested</th><th>Total</th></tr>';
    while($row=mysqli_fetch_array($query)){
        echo "<tr><td>".$rank."</td><td>".$row['full_name']."</td><td>".$row['cash_in_hand']."</td><td>".$row['cash_invested']."</td><td>".$row['total']."</td></tr>";
        $rank=$rank+1;
    }
    echo '</table>';
}
function get_stock_news(){
    include("connect.php");
    $query=mysqli_query($connect,"select new.price_per_share new_price,(old.price_per_share-new.price_per_share)/100 as percent,company.name name from (select stock_record.price_per_share,stock_record.cid from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<curtime() and addtime(gameconf.start_time,stock_record.time)>subtime(curtime(),'00:05:00')) as new,(select stock_record.price_per_share,stock_record.cid from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<subtime(curtime(),'00:05:00')and addtime(gameconf.start_time,stock_record.time)>subtime(curtime(),'00:10:00')) as old inner join company on old.cid=company.cid where new.cid=old.cid") or die ("cant connect");
    $result=array();
    while($row=mysqli_fetch_array($query)){
        array_push($result,$row['name']);
        array_push($result,$row['new_price']);
        array_push($result,$row['percent']);
    }
    echo json_encode($result);
}
?>
