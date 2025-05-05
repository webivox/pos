// JavaScript Document

function mask()
{
    
	
    $(".numField").inputmask("decimal", {
        radixPoint: ".",  // Decimal separator
        groupSeparator: "",  // Thousand separator
        digits: 2,  // Decimal places
        autoGroup: true,
        rightAlign: false
    });
    
	
   
	$(".dateField").inputmask({ "alias": "dd-mm-yyyy"});
	
	
}

$(document).ready(function(e) {
	
	mask();
	
});



///Add Edit
	
$(document).on('click','.open_popup_form',function(){

	var width = parseFloat($(this).data('width'));
	var height = parseFloat($(this).data('height'));
	var url = $(this).data('url');
	
	var formheight = height-135;
	
	
	$("#popup_form_in").css({
		"width": width,
		"height": height
	});
	
	$("#popup_form_in_form").load(url, function () {
		setTimeout(function () {
			
			mask();
			
			$("#popup_form_in_form_in").css({
				"height": formheight
			});
					
			$("#popup_form_in_form .autofocus").focus();
		}, 100); // Small delay to ensure content is fully loaded
	});

	$("#popup_form").fadeIn(10);
});
	
$(document).on('click', '#popup_form_in_close',function(e) {
		
	$("#SearchFormBtn").trigger('click');
	$("#popup_form_in_form").html('');		
	$("#popup_form").fadeOut(1);
});

$(document).on("submit", "#saveForm", function (e) {
    e.preventDefault(); // Prevent the default form submission

    var url = $("#saveForm").data("url");
    var formData = new FormData(this); // Create FormData object from the form

    $.ajax({
        url: url,
        type: 'post',
        data: formData,
        dataType: 'json',
        contentType: false,  // Let the browser set the content-type for the multipart form
        cache: false,
        processData: false, // Don't let jQuery process the data
        beforeSend: function() {
            $('#saveFormBtn').attr('disabled', true).text('Saving...');
            $("#modal_loading").fadeIn(1);
        },
        success: function(json) {
            console.log(json);

            if (json['error']) {
                $('#saveFormBtn').attr('disabled', false).text('Save');
                $("#modal_loading").fadeOut(1);
                $("#modal ul").html(json['error_msg']);
                $("#modal").fadeIn(1);
            }

            if(json['success_reopen']) {
                $("#right_form_in_load").html('');
                $('#saveFormBtn').attr('disabled', false).text('Save');
                $("#modal_loading").fadeOut(1);
                var cls = json['success_class'];
                var url = json['success_reopen_url'];

                $("#right_form_in").attr('class', cls);
                $("#right_form_in_load").load(url);
                $("#right_form").fadeIn(1);
            }

            if (json['success']) {
				
				if (json['reload']) {
					
					location.reload();
					
				}
				
                $('#saveFormBtn').attr('disabled', false).text('Save');
                $("#modal_loading").fadeOut(1);
                $("#popup_form_in_close").trigger('click');
                $("#modal_success p").text(json['success_msg']);
                $("#modal_success").fadeIn(1);
                $("#popup_form_in_form").html('');
				
				
            }
        },
        error: function(xhr, status, error) {
		
            console.error("Error:", error);
        }
    });
});




///Add Edit
	
$(document).on('click', '.open_popup_form_sub', function (e) {

	
    var url = $(this).data('url');
		
	
	if(url)
	{

		var width = parseFloat($(this).data('width'));
		var height = parseFloat($(this).data('height'));
		
		
		var formheight = height-135;
		
		
		$("#popup_form_in_sub").css({
			"width": width,
			"height": height
		});
		
		$("#popup_form_in_form_sub").load(url, function () {
			setTimeout(function () {
				
				$("#popup_form_in_form_sub #popup_form_in_form_in").css({
					"height": formheight
				});
		
				$("#popup_form_in_form_sub #saveForm").attr('id','saveFormSub');
				$("#popup_form_in_form_sub #saveFormBtn").attr('form','saveFormSub');
				$("#popup_form_in_form_sub #saveFormBtn").attr('id','saveFormBtnSub');
					
				mask();
						
				$("#popup_form_in_form_sub .autofocus").focus();
			}, 100); // Small delay to ensure content is fully loaded
		});
	
		$("#popup_form_sub").fadeIn(10);
	}
});
	
$(document).on('click', '#popup_form_in_close_sub',function(e) {
		
	$("#popup_form_in_form_sub").html('');		
	$("#popup_form_sub").fadeOut(1);
});

$(document).on("submit", "#saveFormSub", function (e) {
    e.preventDefault(); // Prevent the default form submission

    var url = $("#saveFormSub").data("url");
    var formData = new FormData(this); // Create FormData object from the form

    $.ajax({
        url: url,
        type: 'post',
        data: formData,
        dataType: 'json',
        contentType: false,  // Let the browser set the content-type for the multipart form
        cache: false,
        processData: false, // Don't let jQuery process the data
        beforeSend: function() {
            $('#saveFormBtnSub').attr('disabled', true).text('Saving...');
            $("#modal_loading").fadeIn(1);
        },
        success: function(json) {
            console.log(json);

            if (json['error']) {
                $('#saveFormBtnSub').attr('disabled', false).text('Save');
                $("#modal_loading").fadeOut(1);
                $("#modal ul").html(json['error_msg']);
                $("#modal").fadeIn(1);
            }

            if(json['success_reopen']) {
                $("#right_form_in_load").html('');
                $('#saveFormBtnSub').attr('disabled', false).text('Save');
                $("#modal_loading").fadeOut(1);
                var cls = json['success_class'];
                var url = json['success_reopen_url'];

                $("#right_form_in").attr('class', cls);
                $("#right_form_in_load").load(url);
                $("#right_form").fadeIn(1);
            }

            if (json['success']) {
				
				if (json['reload']) {
					
					location.reload();
					
				}
				
                $('#saveFormBtnSub').attr('disabled', false).text('Save');
                $("#modal_loading").fadeOut(1);
                $("#popup_form_in_close_sub").trigger('click');
                $("#modal_success p").text(json['success_msg']);
                $("#modal_success").fadeIn(1);
                $("#popup_form_in_form_sub").html('');
				
				
            }
        },
        error: function(xhr, status, error) {
		
            console.error("Error:", error);
        }
    });
});



$(document).on('click','#modal_confirmation_close',function(){
	
	$("#modal_confirmation").fadeOut(10);
	
});

$(document).on("click", "#modal_in_close", function (e) { 
	
	$("#modal_in ul").html('');
	$("#modal").fadeOut(1);
	
});
	

$(document).on("click", "#modal_success_in_close", function (e) { 
	
	$("#modal_success_in_close p").text('');
	$("#modal_success").fadeOut(1);
	
});



// Close modal when pressing "Enter"
window.addEventListener("keydown", function(event) {
  if (event.key === "Enter") {
	$("#modal").fadeOut(1);
	$("#modal_success").fadeOut(1);
  }
});






$(document).on('click', '.pagination a', function() {

	var pageno = $(this).data('pageno');
	
	$("#pageno").val(pageno);
	$("#SearchFormBtn").submit();
	

});

///


$(document).ready(function(e) {
    
	$("#SearchFormBtn").submit();
	
});

$(document).on('submit', '#searchForm',function(e) {
	
	
	var url = $("#searchForm").data("url");
	
	e.preventDefault();

	$.ajax({
		url: url,
		type: 'post',
		data: $('#searchForm').serialize(),
		cache: false,
		processData: true,
		beforeSend: function() {
			$("#modal_loading").fadeIn(1);
		},
		complete: function() {
		},
		success: function(json) {
			console.log(json);
			
			$("#modal_loading").fadeOut(1);
			$("#list_content").html(json);
			setTimeout(function () {
				mask(); // Call your masking function
			}, 100);
		}
	});
		
});


$(document).on('click','#searchReset', function () {
			
	$("#pageno").val(1);
	$('#searchForm input').val('');
	$('#searchForm').trigger("reset");
	
});


$(document).ajaxComplete(function () {
	
    const customerIdField = $(this).data('sendcustomer') || '';
    let customerId = 0;

    if (customerIdField) {
        customerId = $("#" + customerIdField).find('option:selected').val() || 0;
    }

    $(".itemAjax").autocomplete({
        open: function () {
            $('.ui-menu').css({
                'max-height': '440px',
                'z-index': '9999'
            });
        },
        source: 'inventory/master_items/autoComplete/?customerId=' + customerId,
        minLength: 1,
        select: function (event, ui) {
            event.preventDefault();

            const setId = $(this).data('setid');
            const focusField = $(this).data('priceset');
			const priceset =  $(this).data('priceset') || '';
			const costset =  $(this).data('costset') || '';

            $("#" + setId).val(ui.item.value);
            $(this).val(ui.item.label);
			
			if(priceset){ $("#"+priceset).val(ui.item.price); }
			if(costset){ $("#"+costset).val(ui.item.cost); }

            if (focusField) {
                $("#" + focusField).trigger('focus').select();
            }
        },
        focus: function (event, ui) {
            event.preventDefault();
        }
    });
});




$(document).on('click', '.itemAjax', function() {
	
	$(this).data('id','');
	$(this).val('');
	
});






$(document).ajaxComplete(function(e) {
    
  
	jQuery(".supplierAjax").autocomplete({
		open: function() { 
				// After menu has been opened, set width to 100px
				$('.ui-menu').css('max-height','440px');
				$('.ui-menu').css('z-index','9999');
			} ,
		source:'suppliers/master_suppliers/autoComplete/',
		minLength:1,
		select: function(event, ui) {
			
			event.preventDefault();
			
			var setId = $(this).data('setid');
			var focusField = $(this).data('focus');
			
			$("#"+setId).val(ui.item.value);
			$(this).val(ui.item.label);
			
			
			if(focusField)
			{
				$("#"+focusField).trigger('focus');
				$("#"+focusField).select();
			}
			//supplierchoosedList(ui.item.value,ui.item.label);
		},
		focus: function(event, ui) {
			
			event.preventDefault();
		}
	});
});

$(document).on('click', '.supplierAjax', function() {
	
	$(this).data('id','');
	$(this).val('');
	
});







$(document).ajaxComplete(function(e) {
    
  
	jQuery(".customerAjax").autocomplete({
		open: function() { 
				// After menu has been opened, set width to 100px
				$('.ui-menu').css('max-height','440px');
				$('.ui-menu').css('z-index','9999');
			} ,
		source:'customers/master_customers/autoComplete/',
		minLength:1,
		select: function(event, ui) {
			
			event.preventDefault();
			
			var setId = $(this).data('setid');
			var focusField = $(this).data('focus');
			
			$("#"+setId).val(ui.item.value);
			$(this).val(ui.item.label);
			
			
			if(focusField)
			{
				$("#"+focusField).trigger('focus');
				$("#"+focusField).select();
			}
			//supplierchoosedList(ui.item.value,ui.item.label);
		},
		focus: function(event, ui) {
			
			event.preventDefault();
		}
	});
});

$(document).on('click', '.customerAjax', function() {
	
	$(this).data('id','');
	$(this).val('');
	
});






$(document).ajaxComplete(function(e) {
    
  
	jQuery(".accountAjax").autocomplete({
		open: function() { 
				// After menu has been opened, set width to 100px
				$('.ui-menu').css('max-height','440px');
				$('.ui-menu').css('z-index','9999');
			} ,
		source:'accounts/master_accounts/autoComplete/',
		minLength:1,
		select: function(event, ui) {
			
			event.preventDefault();
			
			
			var setId = $(this).data('setid');
			var focusField = $(this).data('focus');
			
			$("#"+setId).val(ui.item.value);
			$(this).val(ui.item.label);
			
			
			if(focusField)
			{
				$("#"+focusField).trigger('focus');
				$("#"+focusField).select();
			}
			//supplierchoosedList(ui.item.value,ui.item.label);
		},
		focus: function(event, ui) {
			
			event.preventDefault();
		}
	});
});



$(document).on('click', '.accountAjax', function() {
	
	$(this).data('id','');
	$(this).val('');
	
});






$(document).ajaxComplete(function(e) {
    
  
	jQuery(".locationAjax").autocomplete({
		open: function() { 
				// After menu has been opened, set width to 100px
				$('.ui-menu').css('max-height','440px');
				$('.ui-menu').css('z-index','9999');
			} ,
		source:'system/master_locations/autoComplete/',
		minLength:1,
		select: function(event, ui) {
			
			event.preventDefault();
			
			
			var setId = $(this).data('setid');
			var focusField = $(this).data('focus');
			
			$("#"+setId).val(ui.item.value);
			$(this).val(ui.item.label);
			
			
			if(focusField)
			{
				$("#"+focusField).trigger('focus');
				$("#"+focusField).select();
			}
			//supplierchoosedList(ui.item.value,ui.item.label);
		},
		focus: function(event, ui) {
			
			event.preventDefault();
		}
	});
});



$(document).on('click', '.locationAjax', function() {
	
	$(this).data('id','');
	$(this).val('');
	
});