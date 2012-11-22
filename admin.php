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
    $connect=mysqli_connect("localhost","root","alse","mockstock");
    $query=mysqli_query($connect,"select * from gameconf where password='".$_POST['admin_pass']."'")or die("cant connect");
    while($row=mysqli_fetch_array($query)){
        $query2=mysqli_query($connect,"update gameconf set start_time='".$_POST['game_time']."'")or die("cant update");
    }
}
?>
<?if(!game_started()){?>
<h2>Start game</h2>
<?
    $connect=mysqli_connect("localhost","root","alse","mockstock");
$query=mysqli_query($connect,"select start_time from gameconf");
while($row=mysqli_fetch_array($query))
    echo $row['start_time']."<br>";
?>
        <form method="post" action="admin.php">
        <input type="text" placeholder='Set game Start time' name='game_time'><br>
        <input type="password" placeholder="admin password" name='admin_pass'><br>
        <input type="submit" value="start game" class="btn btn-success"><br>
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
