<?
include("basic_functions.php");
switch($_GET['action']){
    case "get_market_company":get_market_company();break;
}
function get_market_company(){
    include("connect.php");
    //$query=mysqli_query($connect,"select * from company");
    echo "<table class='table'>";
    echo "<tr><td>Number of shares you own: </td><td><input type='number' max='100' min='0' step='1' value='0'></td><td><a type='submit' name='sell' class='btn btn-danger'>Sell</a></td></tr>";
    echo "<tr><td>Buy Shares: </td><td><input type='number' max='100' min='0' step='1' value='0'></td><td><a type='submit' name='buy' class='btn btn-primary'>Buy</a></td></tr>";
    echo "<a id='error' style='color:red'></a>";

}
