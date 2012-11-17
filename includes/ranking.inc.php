<script>
function ranks(){
    var xhr=new XMLHttpRequest();
    if(xhr){
        xhr.onreadystatechange=function(){
            if(xhr.readyState=4){
                if(xhr.status==200){
                    console.log(JSON.parse(xhr.responseText))
                }
            }
        }
        xhr.open("GET","scripts/php/async_data.php?action=rank_table");
        xhr.send(null);
    }
 }
</script>
<table  class="table">
<tr><th>Rank</th><th>Player</th><th>Cash in Hand</th><th>Cash invested</th><th>Total</th></tr>
<div id="rank_table">
<tr><td>1</td><td>Suresh Alse</td><td>1000000</td><td>9999</td><td>1009999</td></tr>
</div>
</table>
