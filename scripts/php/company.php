<?
include("basic_functions.php");
switch($_GET['action']){
    case "get_market_company":get_market_company();break;
}
function get_market_company(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query($connect,"select company.* from company where cid=".$_GET['cid']);
        while($row=mysqli_fetch_array($query)){
            echo '<h1>'.$row['name']."</h1>";
            echo '<strong>'.$row['company_type'].'</strong>';
        }
        $query=mysqli_query($connect,"select price_per_share from stock_record,gameconf where addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00') and cid=".$_GET['cid']);
        while($row=mysqli_fetch_array($query))
            $price_per_share=$row['price_per_share'];
        echo "<table class='table'>";
        echo "<tr><td>Number of shares you own: </td><td><input type='number' max='100' min='0' step='1' value='0' onchange='document.getElementById(\"sell_price\").innerHTML=this.value*".$price_per_share."'><br>+<a id='sell_price'style='color:green;'>0</a></td><td><a type='submit' name='sell' class='btn btn-danger'>Sell</a></td></tr>";
        echo "<tr><td>Buy Shares: </td><td><input type='number' max='100' min='0' step='1' value='0'onchange='document.getElementById(\"buy_price\").innerHTML=this.value*".$price_per_share."'><br>-<a style='color:red;' id='buy_price'>0</a></td><td><a type='submit' name='buy' class='btn btn-primary'>Buy</a></td></tr>";
        echo "<a id='error' style='color:red'></a>";
    }
}
