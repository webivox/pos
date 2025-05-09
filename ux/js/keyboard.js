

$(document).on("click", ".okeyboard", function() {

	$("#keyboard_main").slideDown(100);
	
	var inputid=$(this).attr('id');
	
	
	$("#keyboard_id").val("#"+inputid);
	
	$(this).val('');
	
});

$(document).on("click", "#keyboard_main a", function() {
	
	
	var inputid=$(this).attr('rel');
	var hidid=$("#keyboard_id").val();
	var currentval=$(hidid).val();
	
	if(inputid=='dochange')
	{
		
		$(hidid).trigger("change");
	}
	else if(inputid=='d')
	{
		$(hidid).val(currentval.slice(0,-1));
		$(hidid).autocomplete("search");
		
	}
	else if(inputid=='c')
	{
		$(hidid).val('');
		$(hidid).autocomplete("search");
		
	}
	else
	{
	
		$(hidid).val(currentval+inputid);
		$(hidid).focus();
		
		var currentvala=$(hidid).val();
		
		$(hidid).autocomplete("search");
	
	}
	
});


$(document).on("click", "#keyboard_close", function() {
	
	$("#keyboard_main").hide(100);	
	
});
