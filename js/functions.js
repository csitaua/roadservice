function isInsured(){
	if(document.getElementById("insured").checked){
		document.getElementById('insured_at').style.display = 'none';	
		document.getElementById('extra_info_not_insured').style.display = 'none';	
	}
	else{
		document.getElementById('insured_at').style.display = 'inline';
		document.getElementById('extra_info_not_insured').style.display = 'inline';	
	}
}


function popacc(url)
{
	var newwindow;
	newwindow=window.open(url,'name');
	if (window.focus) {newwindow.focus()}
}