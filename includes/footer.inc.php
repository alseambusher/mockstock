<script>
var news=['Loading','News'];
var news_pointer=0;
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
</script>
<div class="footer">
<h3>NEWS</h3><div id="news_display" style="width:800px;"></div>
<div style="border:1px solid dark;width:100%"></div>
<h3>STOCK RATES</h3>blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah
</div>
<div class="footer_timer">
<?
    $time_status=get_time_status();
    echo '<h1><a id="timer">'.$time_status['time'].'</a></h1>';
    echo '<script>setTimeout("update_timer()",0);</script>';
?>
</div>
