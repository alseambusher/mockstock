<?
include("basic_functions.php");
switch($_GET['chart']){
case "my_investments":my_investments();break;
case "all_investments":all_investments();break;
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
            $query2=mysqli_query($connect,"select stock_record.price_per_share from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<curtime() and cid=".$row['cid']);
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
            $query2=mysqli_query($connect,"select stock_record.price_per_share from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<curtime() and cid=".$row['cid']);
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

/*echo "[['1','2','3','4','5','6','7','8','9','10','11','12','13','14'],
    [{
                    name: 'Tokyo',
                    data: [0.0, 0.9, 0.5, 4.5, 8.2, 1.5, 5.2, 6.5, 3.3, 8.3, 3.9, 0.6,1,0.3]
                }, {
                    name: 'New York',
                    data: [0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5,10,1]
                }, {
                    name: 'Berlin',
                    data: [1.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0,10,10.8]
                }, {
                    name: 'London',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8,10,5.6]
                }]
                ]";*/

?>
