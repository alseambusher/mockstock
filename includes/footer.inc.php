<script>
function news_parser(){
}
function update_news(){
    var xhr_news=new XMLHttpRequest
    setTimeout("update_news();",60000);
}
</script>
<div class="footer">
<h3>NEWS</h3><div id="news_display"></div>
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
