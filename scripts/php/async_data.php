<?
include("basic_functions.php");
switch($_GET['action']){
    //TODO THIS IS INCOMPLETE
case "my_cash": $money=get_user_data(Array('money'));echo "['".$money['money']."','100000']";break;
}
?>
