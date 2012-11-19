//this has all the onload functions
window.onload=function(){
    my_investments_chart();
    my_worth_chart();//worth pie chart
    global_worth_chart();//worth pie chart
    dashboard_update();
    update_news();
    news_parser();
    stock_rates_parser();
    ranks();
    switch_window(document.getElementById("url_tab").value);
    filter_company();
}
