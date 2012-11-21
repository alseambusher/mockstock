//this has all the onload functions
window.onload=function(){
    Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function(color) {
            return {
                radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
    });
    my_investments_chart();
    my_worth_chart();//worth pie chart
    global_worth_chart();//worth pie chart
    dashboard_update();
    update_news();
    news_parser();
    stock_rates_parser();
    update_stock_news();
    ranks();
    switch_window(document.getElementById("url_tab").value);
    filter_company();
}
