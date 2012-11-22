<?
include("basic_functions.php");
switch($_GET['action']){
case "get_market_company":get_market_company();break;
case "buy":buy();break;
case "sell":sell();break;
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
        $query=mysqli_query($connect,"select no_of_shares from owns_shares_of where cid=".$_GET['cid']." and uid=".$_SESSION['uid']);
        $num=0;
        while($row=mysqli_fetch_array($query))
            $num=$row['no_of_shares'];
        echo "<table class='table'>";
        echo "<tr><td>Number of shares you own: </td><td><input id='num_shares_sell'type='number' max='1000' min='0' step='1' value='".$num."' onchange='document.getElementById(\"sell_price\").innerHTML=this.value*".$price_per_share."'><br>+<a id='sell_price'style='color:green;'>0</a></td><td><a type='submit' name='sell' class='btn btn-danger'
            onclick='load_form_modal(\"Are you sure you want to sell?\",\"scripts/php/company.php?action=sell&cid=".$_GET['cid']."\",\"Transaction details:<br>Number of shares: \"+document.getElementById(\"num_shares_sell\").value+\"<br>Price per share: Rs.".$price_per_share."<br>Total: Rs.\"+document.getElementById(\"sell_price\").innerHTML+\"<input type=\\\"hidden\\\" name=\\\"num_shares\\\" value=\\\"\"+document.getElementById(\"num_shares_sell\").value+\"\\\">\",\"Sell\");'
            >Sell</a></td></tr>";
        echo "<tr><td>Buy Shares: </td><td><input id='num_shares_buy' type='number' max='1000' min='0' step='1' value='0'onchange='document.getElementById(\"buy_price\").innerHTML=this.value*".$price_per_share."'><br>-<a style='color:red;' id='buy_price'>0</a></td><td><a type='submit' name='buy' class='btn btn-primary'
        onclick='load_form_modal(\"Are you sure you want to buy?\",\"scripts/php/company.php?action=buy&cid=".$_GET['cid']."\",\"Transaction details:<br>Number of shares: \"+document.getElementById(\"num_shares_buy\").value+\"<br>Price per share: Rs.".$price_per_share."<br>Total: Rs.\"+document.getElementById(\"buy_price\").innerHTML+\"<input type=\\\"hidden\\\" name=\\\"num_shares\\\" value=\\\"\"+document.getElementById(\"num_shares_buy\").value+\"\\\">\",\"Buy\");'
            >Buy</a></td></tr>";
        echo "<a id='error' style='color:red'></a>";
    }
}
function buy(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query($connect,"select money from users where uid=".$_SESSION['uid']);
        while($row=mysqli_fetch_array($query))
            $money=$row['money'];
        $query=mysqli_query($connect,"select price_per_share from stock_record,gameconf where addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00') and cid=".$_GET['cid']);
        while($row=mysqli_fetch_array($query))
            $price_per_share=$row['price_per_share'];
        if($price_per_share*$_POST['num_shares']>$money){
            header("Location:".$config['base_url']."/?error=You don't have enough money to perform transaction");
            return;
        }
        $query=mysqli_query($connect,"select company.no_shares-sum(owns_shares_of.no_of_shares) as remaining_shares from company,owns_shares_of where company.cid=owns_shares_of.cid and company.cid=".$_GET['cid']);
        $remaining_shares=100000000;
        while($row=mysqli_fetch_array($query))
            $remaining_shares=$row['remaining_shares'];
        if($remaining_shares==NULL){
            $query=mysqli_query($connect,"select no_shares from company where cid=".$_GET['cid']);
            while($row=mysqli_fetch_array($query))
                $remaining_shares=$row['no_shares'];
        }
        if($_POST['num_shares']>$remaining_shares){
            header("Location:".$config['base_url']."/?error=company doesnt offer so many shares, Transaction failed. ");
            return;
        }
        $query=mysqli_query($connect,"insert into buy_sell (no_of_shares,isbuy,uid,cid) values(".$_POST['num_shares'].",1,".$_SESSION['uid'].",".$_GET['cid'].")");
        header("Location:".$config['base_url']."/?message_head=Success&message=Successfully completed transaction");
    }
}
function sell(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query("select no_of_shares from owns_shares_of where cid=".$_GET['cid']);
        $no_shares=10000000;
        while($row=mysqli_fetch_array($query))
            $no_shares=$row['no_of_shares'];
        if(($no_shares==-1)||($no_shares<$_POST['num_shares'])){
            header("Location:".$config['base_url']."/?error=You dont have enough shares to sell, Transaction failed. ");
            return;
        }
        $query=mysqli_query($connect,"insert into buy_sell (no_of_shares,isbuy,uid,cid) values(".$_POST['num_shares'].",0,".$_SESSION['uid'].",".$_GET['cid'].")");
        header("Location:".$config['base_url']."/?message_head=Success&message=Successfully completed transaction");
    }
}
