<script>
var news=['Loading','News'];
var news_pointer=0;
var stock_rates=['Company','10000',5.5]
var stock_pointer=0;
function news_parser(){
    $("#news_display").fadeOut(0);
    document.getElementById("news_display").innerHTML="<strong>"+news[news_pointer%news.length]+"</strong>: "+news[(news_pointer+1)%news.length];
    $("#news_display").fadeIn(500);
    news_pointer=news_pointer+2;
    if(JSON.stringify(news)==JSON.stringify(['Loading','News']))
        setTimeout("news_parser();",1000)
    else
        setTimeout("news_parser();",10000)
}
function stock_rates_parser(){
    $('#stock_rates').fadeOut(0);
    var output=stock_rates[stock_pointer%stock_rates.length]+": Rs."+stock_rates[(stock_pointer+1)%stock_rates.length];
    if(stock_rates[(stock_pointer+2)%stock_rates.length]<0)
        output=output+" <strong style='font-weight:bold;color:red;font-size:1.8em'>&#8595;</strong>"+stock_rates[(stock_pointer+2)%stock_rates.length]+"%";
    else
        output=output+" <strong style='font-weight:bold;color:green;font-size:1.8em'>&#8593;</strong>"+stock_rates[(stock_pointer+2)%stock_rates.length]+"%";
    document.getElementById("stock_rates").innerHTML=output;
    $('#stock_rates').fadeIn(500);
    stock_pointer+=3;
    if(JSON.stringify(stock_rates)==JSON.stringify(['Company','10000',5.5]))
        setTimeout("stock_rates_parser();",1000);
    else
        setTimeout("stock_rates_parser();",5000);
}
function update_stock_rates(){
}
function update_news(){
    var xhr_news=new XMLHttpRequest();
    if(xhr_news){
        xhr_news.onreadystatechange=function(){
            if(xhr_news.readyState==4){
                if(xhr_news.status==200){
                    news=eval(xhr_news.responseText);
                }
            }
        };
        xhr_news.open("GET","scripts/php/async_data.php?action=get_news");
        xhr_news.send(null);
    }
    setTimeout("update_news();",60000);
}
function update_stock_news(){
    var xhr=new XMLHttpRequest();
    if(xhr){
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    console.log(xhr.responseText);
                    stock_rates=eval(xhr.responseText);
                }
            }
        };
        xhr.open("GET","scripts/php/async_data.php?action=get_stock_news");
        xhr.send(null);
    }
    setTimeout("update_stock_news();",10000);

}
</script>
<div class="footer">
<h3>NEWS</h3></td><td><div id="news_display" style="width:800px;font-size:1.1em;"></div>
<strong style="color:gray;font-size:1.3em">STOCK RATES:</strong>&nbsp;&nbsp;&nbsp; <a id="stock_rates" style="font-size:2em;"></a>
</div>
<div class="footer_timer">
<?
    $time_status=get_time_status();
    echo '<h1><a id="timer">'.$time_status['time'].'</a></h1>';
    echo '<script>setTimeout("update_timer()",0);</script>';
?>
</div>
