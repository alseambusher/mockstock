<?
include("basic_functions.php");
switch($_GET['chart']){
case "my_investments":my_investments();break;
case "all_investments":all_investments();break;
case "my_worth_chart":my_worth_chart();break;
case "global_worth_chart":global_worth_chart();break;
case "get_market_company":get_market_company();break;
}
function my_investments(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query($connect,"select owns_shares_of.cid,company.name from owns_shares_of inner join company on company.cid=owns_shares_of.cid where owns_shares_of.uid=".$_SESSION['uid']);
        $result_data="";
        while($row=mysqli_fetch_array($query)){
            if($result_data=="")
                $result_data=$result_data."{ ";
            else
                $result_data=$result_data.",{ ";
            $query2=mysqli_query($connect,"select stock_record.price_per_share from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<curtime() and cid=".$row['cid']." order by time");
            $data_count=0;//has the number of elements in y-axis
            $result_data=$result_data."name:'".$row['name']."', data:[";
            while($row2=mysqli_fetch_array($query2)){
                $result_data=$result_data.$row2['price_per_share'].',';
                $data_count=$data_count+1;
            }
            substr_replace($result_data,"",-1);//remove , in the end
            $result_data=$result_data."]} ";
        }
        $result_data='['.$result_data.']';
        $y_axis="[";
        for($i=0;$i<$data_count;$i++)
            $y_axis=$y_axis."'".$i."',";
        substr_replace($y_axis,"",-1);//remove , in the end
        $y_axis=$y_axis."]";
        $result_data='['.$y_axis.",".$result_data.']';
        if($result_data=='[[],[]]')
            $result_data="[[1],[{name:'',data:[0]}]]";
        echo $result_data;
    }
}
function all_investments(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query($connect,"select cid,name from company");
        $result_data="";
        while($row=mysqli_fetch_array($query)){
            if($result_data=="")
                $result_data=$result_data."{ ";
            else
                $result_data=$result_data.",{ ";
            $query2=mysqli_query($connect,"select stock_record.price_per_share from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<curtime() and cid=".$row['cid']." order by time");
            $data_count=0;//has the number of elements in y-axis
            $result_data=$result_data."name:'".$row['name']."', data:[";
            while($row2=mysqli_fetch_array($query2)){
                $result_data=$result_data.$row2['price_per_share'].',';
                $data_count=$data_count+1;
            }
            substr_replace($result_data,"",-1);//remove , in the end
            $result_data=$result_data."]} ";
        }
        $result_data='['.$result_data.']';
        $y_axis="[";
        for($i=0;$i<$data_count;$i++)
            $y_axis=$y_axis."'".$i."',";
        substr_replace($y_axis,"",-1);//remove , in the end
        $y_axis=$y_axis."]";
        $result_data='['.$y_axis.",".$result_data.']';
        if($result_data=='[[],[]]')
            $result_data="[[1],[{name:'',data:[0]}]]";
        echo $result_data;
    }
}

function my_worth_chart(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query($connect,"select owns_shares_of.no_of_shares*stock_record.price_per_share cash_invested,company.name from owns_shares_of,stock_record,gameconf,company where owns_shares_of.uid=".$_SESSION['uid']." and owns_shares_of.cid=stock_record.cid and company.cid=owns_shares_of.cid and addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00')");
        $result="";
        while($row=mysqli_fetch_array($query)){
            $result=$result."['".$row['name']."',".$row['cash_invested']."],";
        }
        $query=mysqli_query($connect,"select money from users where uid=".$_SESSION['uid']);
        while($row=mysqli_fetch_array($query))
                $result=$result."['Cash in hand',".$row['money']."]";
        echo "[".$result."]";
    }
}
function global_worth_chart(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query($connect,"select company.no_shares*stock_record.price_per_share cash_invested,company.name from stock_record,gameconf,company where company.cid=stock_record.cid and addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00')");
        $result="";
        while($row=mysqli_fetch_array($query)){
            if($result=="")
                $result=$result."['".$row['name']."',".$row['cash_invested']."]";
            else
                $result=$result.",['".$row['name']."',".$row['cash_invested']."]";
        }
        $query=mysqli_query($connect,"select money from users where uid=".$_SESSION['uid']);
        echo "[".$result."]";
    }
}
function get_market_company(){
    if(isLogin()){
        include("connect.php");
        $query=mysqli_query($connect,"select stock_record.price_per_share from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<curtime() and cid=".$_GET['cid']." order by time");
        $data_count=0;//has the number of elements in y-axis
        $result_data="{name:'Price per share', data:[";
        while($row=mysqli_fetch_array($query)){
            $result_data=$result_data.$row['price_per_share'].',';
            $data_count=$data_count+1;
        }
        substr_replace($result_data,"",-1);//remove , in the end
        $result_data=$result_data."]} ";
        $result_data='['.$result_data.']';
        $y_axis="[";
        for($i=0;$i<$data_count;$i++)
            $y_axis=$y_axis."'".$i."',";
        substr_replace($y_axis,"",-1);//remove , in the end
        $y_axis=$y_axis."]";
        $result_data='['.$y_axis.",".$result_data.']';
        echo $result_data;
    }
}

?>
