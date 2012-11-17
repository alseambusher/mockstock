function update_timer(){
	var cur_time=document.getElementById("timer").innerHTML.split(":");
	var _final;
	var cur_hour=parseInt(parseFloat(cur_time[0]));
	var cur_min=parseInt(parseFloat(cur_time[1]));
	var cur_sec=parseInt(parseFloat(cur_time[2]));

	if((cur_sec>0)&&(cur_sec<=10)){
		cur_sec=cur_sec-1;
		_final=cur_hour+':'+cur_min+':0'+cur_sec;
	}
	else if(cur_sec>0){
		cur_sec=cur_sec-1;
		_final=cur_hour+':'+cur_min+':'+cur_sec;
	}
	else{
		cur_sec=59;
		if(cur_min>0){
			cur_min=cur_min-1;
			_final=cur_hour+':'+cur_min+':'+cur_sec;
		}
		else{
			cur_min=59;
			cur_hour=cur_hour-1;
			_final=cur_hour+':'+cur_min+':'+cur_sec;
		}
		//cur_min=cur_min-1;
		//_final=cur_min+':59'
	}
	if(cur_hour<0){
		document.getElementById("timer").innerHTML='refresh page';
		return;
	}
	document.getElementById("timer").innerHTML=_final;
	setTimeout("update_timer();",1000);
}
function switch_window(id){
    var elements=['dashboard','ranking','market'];
    for(var i=0;i<elements.length;i++){
        $("#"+elements[i]).fadeOut(100);
        document.getElementById(elements[i]+"_button").setAttribute("class","");
    }
    document.getElementById(id+"_button").setAttribute("class","active");
    $("#"+id).fadeIn(500);
}
