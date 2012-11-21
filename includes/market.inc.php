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
    var xhr=new XMLHttpRequest();
    if(xhr){
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    document.getElementById("market_company").innerHTML=xhr.responseText;
                }
            }
        }
        xhr.open("GET","scripts/php/company.php?action=get_market_company&cid="+cid);
        xhr.send(null);
    }
}
function market_error(msg){
    document.getElementById("error").innerHTML=msg;
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
</div>
</td></tr>
</table>
<!--<input type="number" max="10" min="0">-->
