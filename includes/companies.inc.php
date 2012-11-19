<script>
function filter_company(){
    var xhr=new XMLHttpRequest();
    if(xhr){
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    document.getElementById("company_results").innerHTML=xhr.responseText;
                }
            }
    }
        xhr.open("GET","scripts/php/async_data.php?action=get_companies&name="+document.getElementById("company_name_filter").value+"&type="+document.getElementById("company_type_filter").value+"&location="+document.getElementById("company_location_filter").value);
        xhr.send(null);
}
}
</script>
<input type="text" id="company_name_filter" placeholder="Filter by name" onkeyup="filter_company();"> &nbsp;
<input type="text" id="company_type_filter" placeholder="Filter by type" onkeyup="filter_company();"> &nbsp;
<input type="text" id="company_location_filter" placeholder="Filter by Location" onkeyup="filter_company();"> &nbsp;
<div id="company_results"></div>
