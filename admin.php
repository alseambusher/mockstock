<?//ini_set('display_errors', 'On');?>
<!doctype html>
<html>
<head>
<?include('config.php');?>
<?include('includes/bootstrap.inc.php');?>
<?include('scripts/php/basic_functions.php');?>
<link rel="stylesheet" href="css/style.css"/>
<meta http-equiv="Content-Type" content="application/xhtml+xm; charset=utf-8" />
<title>Mock stock</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<script type="text/javascript" src="scripts/js/misc.js"></script>
<style type="text/css">
      body {
      	padding:15px;
      }
</style>
</head>
<body>
<div class="navbar navbar-fixed-top shadow">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?echo $config['base_url'];?>">Mockstock</a>
        </div>
      </div>
    </div>
    <!-- Navigation bar done -->

<br><br>
<div class="container" >
<!-- everything should be here -->
<div style="padding-left:10px;padding-right:50px;">
<div class="board">
    <div>
        <script>
        function ranks(){
            var xhr=new XMLHttpRequest();
            if(xhr){
                xhr.onreadystatechange=function(){
                    if(xhr.readyState==4){
                        if(xhr.status==200){
                            document.getElementById("rank_table").innerHTML=xhr.responseText;
                        }
                    }
                }
            <?if(!game_started()){?>
                xhr.open("GET","scripts/php/async_data.php?action=rank_game_over");
            <?}else{?>
                xhr.open("GET","scripts/php/async_data.php?action=rank_table");
            <?}?>
                xhr.send(null);
            }
            setTimeout("ranks();",5000);
        }
        </script>
<?
if(isset($_POST['game_time'])&&isset($_POST['admin_pass'])){
    $connect=mysqli_connect($config['db_host'],$config['db_username'],$config['db_password'],$config['db_database']);
    $query=mysqli_query($connect,"select * from gameconf where password='".$_POST['admin_pass']."'")or die("cant connect");
    while($row=mysqli_fetch_array($query)){
        $query2=mysqli_query($connect,"update gameconf set start_time='".$_POST['game_time']."'")or die("cant update");
    }
}
?>
<?if(!game_started()){?>
<h2>Start game</h2>
<?
    $connect=mysqli_connect($config['db_host'],$config['db_username'],$config['db_password'],$config['db_database']);
$query=mysqli_query($connect,"select start_time from gameconf");
while($row=mysqli_fetch_array($query))
    echo $row['start_time']."<br>";
if(isset($_POST['update_news'])){
    $query2=mysqli_query($connect,"select password from gameconf");
    while($row=mysqli_fetch_array($query2))
        $password=$row['password'];
    if(isset($_POST['admin_pass'])&&($_POST['admin_pass']==$password)){
    $query=mysqli_query($connect,"delete from news");
    for($i=0;$i<$_POST['news_count'];$i++){
        if(isset($_POST['news_time_'.$i])&&isset($_POST['news_title_'.$i])&&isset($_POST['news_description_'.$i])&&(!isset($_POST['news_delete_'.$i]))){
            if(($_POST['news_time_'.$i]!="")&&($_POST['news_title_'.$i]!="")&&($_POST['news_description_'.$i]!="")){
                $query=mysqli_query($connect,"insert into news values('".$_POST['news_time_'.$i]."','".htmlentities($_POST['news_description_'.$i],ENT_QUOTES)."','".htmlentities($_POST['news_title_'.$i],ENT_QUOTES)."')")or die("cent inser");
            }
        }
    }
    }
    header("Location:admin.php");
}
?>
        <form method="post" action="admin.php">
        <input type="text" placeholder='Set game Start time' name='game_time'><br>
        <input type="password" placeholder="admin password" name='admin_pass'><br>
        <input type="submit" value="start game" class="btn btn-success"><br>
        </form>
<hr />
<h2>Stock Record</h2>
<?
if(isset($_POST['get_stock_record'])&&isset($_POST['get_stock_record_time'])){
    $query=mysqli_query($connect,"select password from gameconf");
    while($row=mysqli_fetch_array($query))
        $password=$row['password'];
    if(isset($_POST['get_stock_record_time_password'])&&($_POST['get_stock_record_time_password']==$password)){
        $query=mysqli_query($connect,"select * from stock_record inner join company on company.cid=stock_record.cid where stock_record.time='".$_POST['get_stock_record_time']."'");
        echo $_POST['get_stock_record_time']."<br>";
        echo "<form action='admin.php' method='POST'>";
        while($row=mysqli_fetch_array($query)){
            echo $row['name']."<br>";
            echo "<input type='text' name='".$row['cid']."' value='".$row['price_per_share']."'><br>";
        }
        echo "<input type='hidden' name='stock_record_time' value='". $_POST['get_stock_record_time']."'>";
        echo "<input type='submit' class='btn btn-primary' name='stock_record_update' value='update'>";
        echo "<hr />";
    }
}
if(isset($_POST['stock_record_update'])){
    for($i=1;$i<=9;$i++){
        if(isset($_POST[$i])&&($_POST[$i]!="")){
            $query=mysqli_query($connect,"update stock_record set price_per_share='".$_POST[$i]."' where time='".$_POST['stock_record_time']."' and cid='".$i."'");
        }
    }
}
?>
<form action="admin.php" method="post">
<input type="text" placeholder="time" name="get_stock_record_time"><br>
<input type="password" placeholder="Admin password" name="get_stock_record_time_password"><br>
<input type="submit" name="get_stock_record" value="Get record" class="btn btn-primary">
</form>
<hr/>

<h2>News</h2>
<script>
function add_news(){
    var count=document.getElementById("news_count").value;
    document.getElementById("news_table").innerHTML=document.getElementById("news_table").innerHTML+"<tr><td><input type='text'  placeholder='time' name='news_time_"+count+"'></td><td><input type='text' name='news_title_"+count+"'><br><textarea name='news_description_"+count+"'></textarea></td><td><input type='checkbox' name='news_delete_"+count+"'></td></tr>";
    document.getElementById("news_count").value=parseInt(document.getElementById("news_count").value)+1;

}
</script>
<form method="post" action="admin.php">
<table class="table" id="news_table">
<tr><th>Time</th><th>News</th><th>Delete</th></tr>
<?
$query=mysqli_query($connect,"select * from news order by time");
$news_count=0;
while($row=mysqli_fetch_array($query)){
    echo "<tr><td><input type='text' value='".$row['time']."' name='news_time_".$news_count."'></td>
        <td><input type='text' value='".$row['title']."' name='news_title_".$news_count."'><br>
        <textarea name='news_description_".$news_count."'>".$row['description']."</textarea></td>
        <td><input type='checkbox' name='news_delete_".$news_count."'></td></tr>";
    $news_count=$news_count+1;
}
echo "</table>";
echo "<input type='hidden' value='".$news_count."' name='news_count' id='news_count'>";
?>
<input type="password" name="admin_pass" placeholder="Admin password"><br>
<a class="btn btn-success" onclick="add_news();"> Add news</a>
<input type="submit" class="btn btn-primary" value="update" name="update_news">
</form>
<hr />
<?
if(isset($_POST['change_password'])&&isset($_POST['change_password_new'])&&isset($_POST['change_password_new'])&&($_POST['change_password_new'])){
    $query=mysqli_query($connect,"select password from gameconf");
    while($row=mysqli_fetch_array($query))
        $password=$row['password'];
    if($_POST['change_password_old']!=$password)
        echo "Old password not valid<br>";
    else{
        if(strlen($_POST['change_password_new'])>=6){
            $query=mysqli_query($connect,"update gameconf set password='".$_POST['change_password_new']."'");
            echo "update successfull";
        }
        else
            echo "password must be minimum 6 characters<br>";
    }
}
?>
<h2>Change password</h2>
<form action="admin.php" method="post">
<input type="password" name="change_password_old" placeholder="old password"><br>
<input type="password" name="change_password_new" placeholder="new password"><br>
<input type="submit" name="change_password" value="Change" class="btn btn-danger">
</form>
<hr />
<?}?>
<h2>Ranking</h2>
        <div id="rank_table">
        </div>
    </div>
<!-- rank ends here -->
</div>
</div>
</div>
<!-- container closed -->
<!-- this is the modal for everything without a form-->
<div class="modal hide fade" id="modal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" onclick="toggle_mode();">×</button>
    <h3 id='modal_header'>Default Header</h3>
  </div>
  <div class="modal-body" id='modal_body' >
  Default body
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" >Close</a>
  </div>
</div>
<script type='text/javascript'>
	function load_modal(header,body){
		$('#modal').modal('show');
		document.getElementById("modal_header").innerHTML=header;
		document.getElementById("modal_body").innerHTML=body;
	}
	function close_modal(){
		$('#modal').modal('hide');
	}
</script>

<!-- this is a modal for everything with a form -->
<div class="modal hide fade" id="form_modal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" onclick="toggle_mode();">×</button>
    <h3 id='form_modal_header'>Default Header</h3>
  </div>
  <form method='post' action='' id='form_modal_action'>
  	<div class="modal-body" id='form_modal_body' >
  	Default body
  	</div>
  	<div class="modal-footer">
    	<a href="#" class="btn" data-dismiss="modal">Close</a>
	<input type='submit' class='btn btn-primary' value='Submit' id='form_modal_submit'>
  	</div>
   </form>
</div>
<script type='text/javascript'>
	function load_form_modal(header,action,body,submit){
		$('#form_modal').modal('show');
		document.getElementById("form_modal_header").innerHTML=header;
		document.getElementById("form_modal_action").action=action;
		document.getElementById("form_modal_body").innerHTML=body;
		document.getElementById("form_modal_submit").value=submit;
	}
	function close_form_modal(){
		$('#form_modal').modal('hide');
	}
</script>


<script type="text/javascript">
	function alerts(){
		if("<?echo $_GET["error"]; ?>")
			load_modal("Error!!!","<?echo $_GET['error'];?>");
		else if("<?echo $_GET["message"]; ?>")
			load_modal("<?echo $_GET['message_head'];?>","<?echo $_GET['message'];?>");
	}
	setTimeout("alerts();","0");
</script>
</body>
</html>
