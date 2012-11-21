<script>
function company_onmouseover(cid){
    var xhr=new XMLHttpRequest();
    if(xhr){
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    document.getElementById("market_company_"+cid).setAttribute('data-content',xhr.responseText);
                    $("#market_company_"+cid).popover("show");
                }
            }
        }
        xhr.open("GET","scripts/php/async_data.php?action=company_mouseover&cid="+cid);
        xhr.send(null);
    }
}
function get_market_company(cid){
    document.getElementById("market_company").innerHTML='loading...';
    var xhr=new XMLHttpRequest();
    if(xhr){
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    $('#market_company').fadeOut(0);
                    document.getElementById("market_company").innerHTML=xhr.responseText;
                    $('#market_company').fadeIn(500);
                }
            }
        }
        xhr.open("GET","scripts/php/company.php?action=get_market_company&cid="+cid);
        xhr.send(null);
    }
    var xhr_chart=new XMLHttpRequest();
    if(xhr_chart){
        xhr_chart.onreadystatechange=function(){
            if(xhr_chart.readyState==4){
                if(xhr_chart.status==200){
                    market_investments_categories=eval(xhr_chart.responseText)[0];
                    market_investments_data=eval(xhr_chart.responseText)[1];
                    market_investments_chart();
                }
            }
        }
        xhr_chart.open("GET","scripts/php/charts.php?chart=get_market_company&cid="+cid);
        xhr_chart.send(null);
    }

}
function market_error(msg){
    document.getElementById("error").innerHTML=msg;
}
var market_investments;
var market_investments_data=[{
            name:"initial",
            data:[0.0]
}];
var market_investments_categories=['Loading'];
function market_investments_chart(){
    market_investments= new Highcharts.Chart({
        chart: {
            renderTo: 'market_investments_chart',
            zoomType: 'xy',
            type: 'line',
            marginRight: 130,
            marginBottom: 25
        },
        credits:{
            enabled:false
        },
        title: {
            text: 'Stock History of the company',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: market_investments_categories
        },
        yAxis: {
            title: {
                text: 'Price per Share'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ this.y +' Rs';
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
        },
        series:market_investments_data,
        exporting: {
            enabled: false
        }
    });
}
</script>
<a class="btn btn-info" href="?tab=market">Set market as default page</a>
<table><tr><td>
<div class="row-fluid" style="width:300px;">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <? get_market_companies();?>
        </ul>
    </div>
</div>
</td><td>
<div class="row-fluid" >
    <div class="well sidebar-nav" id="market_company">
    </div>
    <div id="market_investments_chart" style="width: 700px; height: 400px; margin-left: 0 "></div>
</div>
</td></tr>
</table>
<!--<input type="number" max="10" min="0">-->
