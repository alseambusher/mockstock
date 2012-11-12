<script type="text/javascript">
var my_investments;
var my_investments_data=[{
            name:"initial",
            data:[0.0]
}];
var my_investments_categories=['Loading'];
function my_investments_chart(){
    my_investments= new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            /*events:{
                load:function(){
                    setInterval(function(){
                        var xhr=new XMLHttpRequest();
                        if(xhr){
                            xhr.onreadystatechange=function(){
                                if(xhr.readyState==4){
                                    if(xhr.status==200){
                                        this.series=eval(xhr.responseText);
                                    }
                                }
                            }
                            xhr.open('GET','scripts/php/charts.php?chart=my_investments');
                            xhr.send(null);
                        }
                    },1000);
                }
        },*/
            type: 'line',
            marginRight: 130,
            marginBottom: 25
        },
        credits:{
            enabled:false
        },
        title: {
            text: 'My Investments',
            x: -20 //center
        },
        subtitle: {
            text: 'Companies on which I have invested',
            x: -20
        },
        xAxis: {
            categories: my_investments_categories
        },
        yAxis: {
            title: {
                text: 'Worth'
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
        series:my_investments_data
    });
}
function dashboard_update(){
    //TODO
    //make a async request too charts.php and update data
    //my_investments.showLoading();
    var xhr_my_inverstments=new XMLHttpRequest();
    //update my_investment graph
    if(xhr_my_inverstments){
        xhr_my_inverstments.onreadystatechange=function(){
            if(xhr_my_inverstments.readyState==4){
                if(xhr_my_inverstments.status==200){
                    my_investments_data=eval(xhr_my_inverstments.responseText)[1];
                    my_investments_categories=eval(xhr_my_inverstments.responseText)[0];
                    my_investments_chart();
                }
            }
        }
        xhr_my_inverstments.open('GET','scripts/php/charts.php?chart=my_investments');
        xhr_my_inverstments.send(null);
    }
    //TODO worth not implemented on async_data.php
    //update money with me
    var xhr_money_worth=new XMLHttpRequest();
    if(xhr_money_worth){
        xhr_money_worth.onreadystatechange=function(){
            if(xhr_money_worth.readyState==4){
                if(xhr_money_worth.status==200){
                    console.log("alse");
                    console.log(xhr_money_worth.responseText);
                    document.getElementById("my_cash").innerHTML=eval(xhr_money_worth.responseText)[0];
                    document.getElementById("my_worth").innerHTML=eval(xhr_money_worth.responseText)[1];
                }
            }
        }
        xhr_money_worth.open("GET","scripts/php/async_data.php?action=my_cash");
        xhr_money_worth.send(null);
    }
    setTimeout("dashboard_update();","20000");
}
</script>
<div style="background-color:#dddddd;padding:10px;">
<h3>Cash in bank : <a id="my_cash">1000000</a> Rs<br>
Cash invested: <a id="my_worth">0</a> Rs</h3>
</div>
<div id="container" style="width: 800px; height: 400px; margin-left: 0 "></div>
