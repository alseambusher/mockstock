<?
include("basic_functions.php");
switch($_GET['action']){
case "my_cash": $money=get_user_data(Array('money'));echo $money['money'].",".get_invested_money();break;
case "get_news": echo get_news();break;
case "get_companies":get_companies();break;
case "rank_table": return rank_table();break;
case "get_stock_news":get_stock_news();break;
case "company_mouseover":company_mouseover();break;
}
function get_news(){
    include("connect.php");
    $query=mysqli_query($connect,"select news.* from news,gameconf where addtime(gameconf.start_time,news.time)<curtime() and addtime(gameconf.start_time,news.time)>subtime(curtime(),'00:05:00')");
    $result=array();
    while($row=mysqli_fetch_array($query)){
        $title=htmlentities($row['title'],ENT_QUOTES);
        $description=htmlentities($row['description'],ENT_QUOTES);
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
        echo "<blockquote>";
        echo "<p>".$row['history']."</p>";
        echo "<strong>Company Centers</strong><br>";
        $query2=mysqli_query($connect,"select location from company_locations where cid=".$row['cid']);
        while($row2=mysqli_fetch_array($query2))
            echo $row2['location']."<br>";
        echo "<strong>News</strong><br>";
        $query3=mysqli_query($connect,"select news.*,addtime(news.time,gameconf.start_time) game_time from news,gameconf where (description like '%".$row['name']."%' or title like '%".$row['name']."%') and time<subtime(curtime(),gameconf.start_time) order by game_time desc limit 3");
        echo "<blockquote>";
        while($row2=mysqli_fetch_array($query3))
            echo $row2['game_time'].": <strong>".$row2['title']."</strong><br><div style='width:500px'>".$row2['description']."</div><br>";
        echo "</blockquote>";
        echo "</blockquote>";
        echo "<hr/>";
    }
}
function rank_table(){
    include("connect.php");
    $query=mysqli_query($connect,"select * from ranking");
    $rank=1;
    echo '<table class="table">
            <tr><th>Rank</th><th>Player</th><th>Cash in Hand</th><th>Cash invested</th><th>Total</th></tr>';
    while($row=mysqli_fetch_array($query)){
        echo "<tr><td>".$rank."</td><td>".$row['full_name']."</td><td>".$row['cash_in_hand']."</td><td>".$row['cash_invested']."</td><td>".$row['total']."</td></tr>";
        $rank=$rank+1;
    }
    echo '</table>';
}
function get_stock_news(){
    include("connect.php");
    $query=mysqli_query($connect,"call stock_rates();") or die ("cant connect");
    $result=array();
    while($row=mysqli_fetch_array($query)){
        array_push($result,$row['name']);
        array_push($result,$row['new_price']);
        array_push($result,$row['percent']);
    }
    echo json_encode($result);
}
function company_mouseover(){
    include("connect.php");
    $result='<strong>Remaining shares:</strong> ';
    $query=mysqli_query($connect,"select company.no_shares-sum(owns_shares_of.no_of_shares) as shares_left from company,owns_shares_of where company.cid=".$_GET['cid']." and owns_shares_of.cid=company.cid");
    while($row=mysqli_fetch_array($query))
        $result=$result.''.$row['shares_left'];
    if($result=='<strong>Remaining shares:</strong> '){
        $query=mysqli_query($connect,"select company.no_shares as shares_left from company where company.cid=".$_GET['cid']);
        while($row=mysqli_fetch_array($query))
            $result=$result.''.$row['shares_left'];
    }
    $query=mysqli_query($connect,"select stock_record.price_per_share price_per_share from gameconf,stock_record where addtime(gameconf.start_time,stock_record.time)<curtime() and addtime(gameconf.start_time,stock_record.time)>subtime(curtime(),'00:05:00') and stock_record.cid=".$_GET['cid']);
    while($row=mysqli_fetch_array($query))
        $result=$result."<br><strong>Price per share:</strong> ".$row['price_per_share'];
    echo $result;
}
?>
