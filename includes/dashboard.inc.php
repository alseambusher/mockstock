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
            renderTo: 'my_investments_chart',
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
        series:my_investments_data,
        exporting: {
            enabled: false
        }
    });
}
function my_worth_chart(){
    Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function(color) {
            return {
                radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
        });
    var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'my_worth_pie',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'My Assets'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['Firefox',   45.0],
                    ['IE',       26.8],
                    {
                        name: 'Chrome',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['Safari',    8.5],
                    ['Opera',     6.2],
                    ['Others',   0.7]
                ]
            }],
            credits:{
                enabled:false
            },
            exporting: {
                enabled: false
            }
        });
}
function global_worth_chart(){
    var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'global_worth_pie',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Global Assets'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['Firefox',   45.0],
                    ['IE',       26.8],
                    {
                        name: 'Chrome',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['Safari',    8.5],
                    ['Opera',     6.2],
                    ['Others',   0.7]
                ]
            }],
            credits:{
                enabled:false
            },
            exporting: {
                enabled: false
            }
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
                    document.getElementById("investments_table").innerHTML="<tr><th>Company</th><th>Price per Share</th></tr>";
                    for(var i=0;i<my_investments_data.length;i++){
                        var tr=document.createElement("tr");
                        var td=document.createElement("td");
                        td.innerHTML=my_investments_data[i].name;
                        var td2=document.createElement("td");
                        td2.innerHTML=my_investments_data[i].data[my_investments_data[i].data.length-1];
                        tr.appendChild(td);
                        tr.appendChild(td2);
                        document.getElementById("investments_table").appendChild(tr);
                    }
                }
            }
        }
        xhr_my_inverstments.open('GET','scripts/php/charts.php?chart=my_investments');
        xhr_my_inverstments.send(null);
    }
    //TODO worth not implemented on async_data.php
    //update money with me and money invested
    var xhr_money_worth=new XMLHttpRequest();
    if(xhr_money_worth){
        xhr_money_worth.onreadystatechange=function(){
            if(xhr_money_worth.readyState==4){
                if(xhr_money_worth.status==200){
                    document.getElementById("my_cash").innerHTML=xhr_money_worth.responseText.split(',')[0];
                    document.getElementById("my_worth").innerHTML=xhr_money_worth.responseText.split(',')[1];
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
<h3>Cash in bank &nbsp;: <a id="my_cash">0</a> Rs<br>
Cash invested: <a id="my_worth">0</a> Rs</h3>
</div>
<table class="table">
    <tr>
        <td><div id="my_investments_chart" style="width: 800px; height: 400px; margin-left: 0 "></div></td>
        <td >
            <table class="table" id="investments_table" >
                <tr><th>Company</th><th>Price per Share</th></tr>
            </table>
        </td>
    </tr>
</table>
<table class="table">
<tr>
    <td><span id="my_worth_pie" style="width: 500px; height: 400px; margin-left: 0;display:inline; "></span></td>
    <td><span id="global_worth_pie" style="width: 500px; height: 400px; display:inline; "></span></td>
</tr>
</table>
