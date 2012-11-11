<script type="text/javascript">
var my_investments;
function my_investments_chart(){
        my_investments= new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'line',
                marginRight: 130,
                marginBottom: 25
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
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
            series: [{
                name: 'Tokyo',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: 'New York',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: 'Berlin',
                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: 'London',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }, {
                name: 'London',
                data: [5.9, 3.2, 0.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }, {
                name: 'London',
                data: [0.9, 1.2, 2.7, 3.5, 4.9, 5.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }, {
                name: 'London',
                data: [9.9, 8.2, 7.7, 5.5, 4.9, 3.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }, {
                name: 'London',
                data: [7.9, 3.2, 2.7, 0.5, 19.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }, {
                name: 'London',
                data: [0.9, 0.2, 0.7, 0.5, 0.9, 5.2, 7.0, 6.6, 4.2, 0.3, 6.6, 4.8]
            }]
        });
}
</script>
<script>
function dashboard_update(){
    //TODO
    setTimeout("dashboard_update();",10000);
}
</script>
<div style="background-color:#dddddd;padding:10px;">
<h3>Cash in bank : <a>1000000</a> Rs<br>
Cash invested: <a>0</a> Rs</h3>
</div>
<div id="container" style="width: 800px; height: 400px; margin-left: 0 "></div>
