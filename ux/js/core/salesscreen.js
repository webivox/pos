// JavaScript Document

function mask()
{
    
	
    $(".numField").inputmask("decimal", {
        radixPoint: ".",  // Decimal separator
        groupSeparator: "",  // Thousand separator
        digits: 2,  // Decimal places
        autoGroup: false,
        rightAlign: false
    });
    
	
   
	$(".dateField").inputmask({ "alias": "dd-mm-yyyy"});
	
	
}

$(document).ready(function(e) {
	
	mask();
	
});


function total()
{
	
	var discountType = $("#discount_type").val();
	var discountValue = parseFloat($("#discount_value").val());
	
	var lineNo = 0;
	var lineDiscount = 0;
	var subTotal = 0;
	
	$(".cart_item_row").each(function () {
		
		var masterPrice = parseFloat($('.ciMasterPrice',this).val());
		var amount = parseFloat($('.ciAmount',this).val());
		var discount = parseFloat($('.ciDiscount',this).val());
		
		if(discount>0){ var discountCalc = amount*discount/100; }
		else{ var discountCalc = 0; }
		
		var unit_price = amount-discountCalc;
		
		$(".ciPrice",this).val((unit_price).toFixed(2));
		
		var qty = parseFloat($('.ciQty',this).val());
		
		var total = unit_price*qty;
		
		lineDiscount +=(masterPrice-unit_price)*qty;
		
		
		$(".ciTotal",this).val((total).toFixed(2));
		
		//itemincart += qty;
		lineNo += 1;
		subTotal += total;
		
		$(".cart_item_no input",this).val(lineNo.toString().padStart(3, '0'));
	});
	
	$("#lineTotalDiscount").val((lineDiscount).toFixed(2));
	
	var subTotalTxt = subTotal.toLocaleString('en-US', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	});
	
	if(discountType=='P')
	{
		var discountCalc = subTotal*discountValue/100;
	}
	else if(discountType=='F')
	{
		var discountCalc = discountValue;
	}
	else
	{
		var discountCalc = 0;
	}
	
	var netTotal = subTotal-discountCalc;
	
	var discountCalcTxt = discountCalc.toLocaleString('en-US', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	});
	
	var netTotalTxt = netTotal.toLocaleString('en-US', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	});
	
	$("#subTotal").text(subTotalTxt);
	$("#discounAmount").text(discountCalcTxt);
	$("#netTotal").text(netTotalTxt);
	
	
	var totalPaid = 0;
	
	$(".paidamt").each(function () {
		
		var amount = parseFloat($(this).val());
		
		totalPaid += amount;
		
	});
	
	var blanceAmountCalc = netTotal-totalPaid;
	
	if(blanceAmountCalc>0)
	{
		
	
		var balanceAmtTxt = blanceAmountCalc.toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
	
		$("#dueAmount").val(balanceAmtTxt);
		$("#balanceAmount").val('0.00');
		$("#paymentAmt").val(blanceAmountCalc);
	}
	else if(blanceAmountCalc<0)
	{
	
		var balanceAmtTxt = blanceAmountCalc.toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
	
		$("#dueAmount").val('0.00');
		$("#balanceAmount").val(balanceAmtTxt);
		$("#paymentAmt").val('0.00');
	}
	else
	{
	
	
		$("#dueAmount").val('0.00');
		$("#balanceAmount").val('0.00');
		$("#paymentAmt").val('0.00');
	}
	
	
	////
	
	
	
	var cashAmounts = [10, 20, 50, 100, 500, 1000, 5000, 10000, 15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000]; // Available cash amounts

	var enteredAmount = parseInt(blanceAmountCalc);
	
	if(enteredAmount>0)
	{

		if (isNaN(enteredAmount)) {
			$("#right_payment_quick_cash").html("");
			return;
		}
	
		// Find the nearest amount and the next three higher values
		var nearestIndex = cashAmounts.findIndex(amount => amount >= enteredAmount);
	
		if (nearestIndex === -1) {
			$("#right_payment_quick_cash").html("");
			return;
		}
	
		// Get the next 3 available values
		var nextValues = cashAmounts.slice(nearestIndex, nearestIndex + 3);
		
		// Convert values to <a> links
		var htmlContent = nextValues.map(value => `<a class="quick-amount" data-value="${value}">${value}</a>`).join("");
		
		// Append to the container
		$("#right_payment_quick_cash").html(htmlContent);
	}
	else
	{
		$("#right_payment_quick_cash").html("");
	}
	
}

$(document).ready(function(e) {

	$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
   
	total();	
});


$(document).on('click', 'input', function() {
	
	$(this).focus().select();
	
});

$(document).on('click', '#shift_modal_start', function() {

	var pointId = $("#shiftPoint").val();
	event.preventDefault(); // Make sure 'event' is defined properly
	
	$.ajax({
		url: 'sales/screen/shiftstart/' + pointId,
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			$('#saveFormBtn').attr('disabled', true).text('Saving...');
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
			console.log(json);
	
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
			if (json['success_reopen']) {
				$("#right_form_in").attr('class', json['success_class']);
				
				$("#right_form_in_load").html('').load(json['success_reopen_url']);
				$("#right_form").fadeIn(1);
			}
	
			if (json['success']) {
				if (json['reload']) {
					location.reload();
					return;
				}
	
				$("#popup_form_in_close").trigger('click');
				$("#modal_success p").text(json['success_msg']);
				$("#modal_success").fadeIn(1);
				$("#popup_form_in_form").html('');
				
				$("#shift_modal").fadeOut(10);
			}
		},
		error: function(xhr, status, error) {
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
		}
	});

	
});


$(document).on('click', '#end_shift', function() {

	event.preventDefault(); // Make sure 'event' is defined properly
	
	$.ajax({
		url: 'sales/screen/shiftEnd/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			$('#saveFormBtn').attr('disabled', true).text('Loading...');
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
			console.log(json);
	
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
			if (json['success_reopen']) {
				$("#right_form_in").attr('class', json['success_class']);
				
				$("#right_form_in_load").html('').load(json['success_reopen_url']);
				$("#right_form").fadeIn(1);
			}
	
			if (json['success']) {
				
				location.reload();
					
	
				$("#popup_form_in_close").trigger('click');
				$("#modal_success p").text(json['success_msg']);
				$("#modal_success").fadeIn(1);
				$("#popup_form_in_form").html('');
				
				$("#shift_modal").fadeIn(10);
			}
		},
		error: function(xhr, status, error) {
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
		}
	});

	
});



$(document).on('click', '#suspend', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	$.ajax({
		url: 'sales/screen/suspend/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
			if (json['success_reopen']) {
				$("#right_form_in").attr('class', json['success_class']);
				$("#right_form_in_load").html('').load(json['success_reopen_url']);
				$("#right_form").fadeIn(1);
			}
	
			if (json['success']) {
				if (json['reload']) {
					location.reload();
					return;
				}
	
				$("#popup_form_in_close").trigger('click');
				$("#modal_success p").text(json['success_msg']);
				$("#modal_success").fadeIn(1);
				$("#popup_form_in_form").html('');
				
			}
		},
		error: function(xhr, status, error) {
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
		}
	});

	
});






$(document).on('change', '#pendinginvoicedd', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).val();
	
	$.ajax({
		url: 'sales/screen/recall/'+id+'/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
			if (json['success_reopen']) {
				$("#right_form_in").attr('class', json['success_class']);
				$("#right_form_in_load").html('').load(json['success_reopen_url']);
				$("#right_form").fadeIn(1);
			}
	
			if (json['success']) {
				if (json['reload']) {
					location.reload();
					return;
				}
	
				$("#popup_form_in_close").trigger('click');
				$("#modal_success p").text(json['success_msg']);
				$("#modal_success").fadeIn(1);
				$("#popup_form_in_form").html('');
				
			}
		},
		error: function(xhr, status, error) {
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
		}
	});

	
});




$(document).on('click', '#noreprintinvfound', function() {
	
	
	$("#modal ul").html('<li>Invoice not found</li>');
	$("#modal").fadeIn(1);
	
});



$("#itemSearchInput").autocomplete({
	open: function() { 
			// After menu has been opened, set width to 100px
			$('.ui-menu').css({
				'max-width': '672px',
				'max-height': '440px',
				'overflow-y': 'auto',
				'overflow-x': 'hidden',
				'direction': 'ltr', // Force left-to-right scroll behavior
				'box-sizing': 'border-box' // Prevent size overflow due to padding
			});
		} ,
	source:'inventory/master_items/autoComplete/',
	minLength:1,
	select: function(event, ui) {
		event.preventDefault();
		itemchoosed(ui.item.value);
	},
	focus: function(event, ui) {
		
		event.preventDefault();
	}
});



//////////

function itemchoosed(id)
{
	
	event.preventDefault(); // Make sure 'event' is defined properly
	
	$.ajax({
		url: 'sales/screen/additem/'+id+'/',
		type: 'POST',
		data: {id:id}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
			if (json['success']) {
				
				$("#itemSearchInput").val('');
				
				$("#center_cart_r_items_added").append(json['html']);
	
				$("#itemQty"+json['invoiceItemId']).focus().select();
				
				$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
				total();
				
			}
		},
		error: function(xhr, status, error) {
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
		}
	});
	
}








$("#customerSearchInput").autocomplete({
	open: function() { 
			// After menu has been opened, set width to 100px
			$('.ui-menu').css({
				'max-width': '672px',
				'max-height': '440px',
				'overflow-y': 'auto',
				'overflow-x': 'hidden',
				'direction': 'ltr', // Force left-to-right scroll behavior
				'box-sizing': 'border-box' // Prevent size overflow due to padding
			});
		} ,
	source:'customers/master_customers/autoComplete/',
	minLength:1,
	select: function(event, ui) {
		event.preventDefault();
		customerchoosed(ui.item.value);
	},
	focus: function(event, ui) {
		
		event.preventDefault();
	}
});



//////////

function customerchoosed(id)
{
	
	event.preventDefault(); // Make sure 'event' is defined properly
	
	$.ajax({
		url: 'sales/screen/addcustomer/'+id+'/',
		type: 'POST',
		data: {id:id}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
			if (json['success']) {
				
				$("#loyaltyPointsTotal").val(json['loyaltyPoints']);
				$("#totalOutstanding").val(json['outstanding']);
				
				$("#customerSearchInput").val(json['customername']);
	
				$("#itemSearchInput").focus();
				total();
				
			}
		},
		error: function(xhr, status, error) {
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
		}
	});
	
}





$(document).on('change', '#salesrepdd', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).val();
	
	$.ajax({
		url: 'sales/screen/addsalesrep/'+id+'/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
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




///Add Edit
		
$(document).on('click','.open_popup_form',function(){

	var width = parseFloat($(this).data('width'));
	var height = parseFloat($(this).data('height'));
	var url = $(this).data('url');
	
	var windowwidth = window.innerWidth;
	
	if(windowwidth<728){ 
	
	
		var height = window.innerHeight-100;
		var maxformheight = height - 185;
	
	}
	else
	{
	
		var formheight = height-135;
		var maxformheight = window.innerHeight - 155;
	}
	
	$("#popup_form_in").css({
		"width": width,
		"height": height,
		'max-width': '96%',
		'max-height': '92vh'
	});
	
	$("#popup_form_in_form").load(url, function () {
		setTimeout(function () {
			
			mask();
			
			$("#popup_form_in_form_in").css({
				"height": formheight,
				"max-height": maxformheight
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






$(document).on('change', '.ciAmount', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).data('id');
	var val = $(this).val();

	$.ajax({
		url: 'sales/screen/updatePrice/'+id+'/',
		type: 'POST',
		data: {val:val}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#itemAmount"+id).val(json['oldValue']);
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				total();
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});

	
});






$(document).on('change', '.ciDiscount', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).data('id');
	var val = $(this).val();

	$.ajax({
		url: 'sales/screen/updateDiscount/'+id+'/',
		type: 'POST',
		data: {val:val}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#itemDiscount"+id).val(json['oldValue']);
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				total();
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});

	
});






$(document).on('change keydown', '.ciQty', function(e) {
    let doThis = false;

    if (e.type === 'keydown' && e.key === 'Enter') {
        doThis = true;
    } else if (e.type === 'change') {
        doThis = true;
    }

    if (doThis) {
        e.preventDefault(); // Use the correct event object

        var id = $(this).data('id');
        var val = $(this).val();

        $.ajax({
            url: 'sales/screen/updateQty/' + id + '/',
            type: 'POST',
            data: { val: val },
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded',
            cache: false,
            processData: true,
            beforeSend: function () {
                $("#modal_loading").fadeIn(1);
            },
            success: function (json) {
                console.log(json);

                $("#modal_loading").fadeOut(1);

                if (json['error']) {
                    $("#itemQty" + id).val(json['oldValue']);
                    $("#modal ul").html(json['error_msg']);
                    $("#modal").fadeIn(1);
                    return;
                }

                if (json['success']) {
                    total();
                    $("#itemSearchInput").focus().select();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
});







$(document).on('click', '.increase', function() {
	
	var id = $(this).data('id');
	var currentQty = parseFloat($("#itemQty"+id).val());
	var qtyWOne = currentQty+1;
	
	$("#itemQty"+id).val(qtyWOne);
	$("#itemQty"+id).trigger('change');
	
});

$(document).on('click', '.reduce', function() {
	
	var id = $(this).data('id');
	var currentQty = parseFloat($("#itemQty"+id).val());
	var qtyWOne = currentQty-1;
	
	$("#itemQty"+id).val(qtyWOne);
	$("#itemQty"+id).trigger('change');
	
});






$(document).on('click', '.remove', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).data('id');

	$.ajax({
		url: 'sales/screen/removeItem/'+id+'/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#itemQty"+id).val(json['oldValue']);
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				$("#cart_item_row"+id).remove();
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});

	total();
	
});


$(document).on('change', '#discount_value', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var type = $("#discount_type").val();
	var val = $(this).val();

	$.ajax({
		url: 'sales/screen/updateSalesDiscount/',
		type: 'POST',
		data: {type:type, val:val}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#itemQty"+id).val(json['oldValue']);
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				total();
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});

	
});




$(document).on('change', '#comments', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var val = $(this).val();

	$.ajax({
		url: 'sales/screen/comments/',
		type: 'POST',
		data: {val:val}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				total();
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});

	
});

$(document).on('click', '.getItemsQM', function() {
	
	var id = $(this).data('id');
	
	$("#popup_form_in_form").load('sales/screen/loadinventoryitems/'+id+'/');
	
});

$(document).on('click', '.quickmenuitem', function() {
	
	var id = $(this).data('id');
	
	itemchoosed(id);
	
	$("#popup_form_in_close").trigger('click');
	
});

$(document).on('click', '.payment_mode', function() {
	
	$(".payment_mode").removeClass('a_active');
	$(this).addClass('a_active');
	
	var type = $(this).data('type');
	
	$(".right_payment_quick_cls").slideUp(10);
	
	if(type=='CASH')
	{
		$("#right_payment_quick_cash").slideDown(11);
	}
	else if(type=='CARD')
	{
		$("#right_payment_quick_card").slideDown(11);
	}
	else if(type=='RETURN')
	{
		$("#right_payment_quick_return").slideDown(11);
	}
	else if(type=='GIFT CARD')
	{
		$("#right_payment_quick_giftcard").slideDown(11);
	}
	else if(type=='LOYALTY')
	{
		$("#right_payment_quick_loyalty").slideDown(11);
	}
	else if(type=='CREDIT')
	{
		$("#right_payment_quick_credit").slideDown(11);
	}
	else if(type=='CHEQUE')
	{
		$("#right_payment_quick_cheque").slideDown(11);
	}
	
});

$(document).on('click', '#right_payment_quick_return_validate', function() {
	
	var no = $("#right_payment_quick_return_no").val();

	$.ajax({
		url: 'sales/screen/loadreturnBalance/'+no+'/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				
				$("#right_payment_quick_return_balance").val(json['balance_value']);
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});
	
});



$(document).on('click', '#right_payment_quick_giftcard_no_validate', function() {
	
	var no = $("#right_payment_quick_giftcard_no").val();

	$.ajax({
		url: 'sales/screen/loadGiftCardBalance/'+no+'/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				
				$("#right_payment_quick_giftcard_no_balance").val(json['balance_value']);
				$("#itemSearchInput").focus().select();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});
	
});


$(document).on('click', '#addPaymentBtn', function() {
	event.preventDefault(); // Make sure 'event' is defined properly

	var type = $(".payment_mode.a_active").data('type');
	var val = $("#paymentAmt").val();
	
	if(type=='CASH')
	{

		$.ajax({
			url: 'sales/screen/addPayment/',
			type: 'POST',
			data: {type:type, val:val}, // You can send an empty object if you don't need to send anything
			dataType: 'json',
			contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
			cache: false,
			processData: true,
			beforeSend: function() {
				
				$("#modal_loading").fadeIn(1);
			},
			success: function(json) {
			
				console.log(json);
		
				$("#modal_loading").fadeOut(1);
		
				if (json['error']) {
					
					$("#modal ul").html(json['error_msg']);
					$("#modal").fadeIn(1);
					return;
				}
		
		
				if (json['success']) {
					
					$("#center_cart_r_payments_added").append(json['html']);
					$("#itemSearchInput").focus().select();
	
					total();
					$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
					
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
			}
		});
	}
	else if(type=='CARD')
	{
		
		var cardOption = $("#cardPaymentOption").val();
		
		$.ajax({
			url: 'sales/screen/addPayment/',
			type: 'POST',
			data: {type:type, val:val, cardOption:cardOption}, // You can send an empty object if you don't need to send anything
			dataType: 'json',
			contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
			cache: false,
			processData: true,
			beforeSend: function() {
				
				$("#modal_loading").fadeIn(1);
			},
			success: function(json) {
			
				console.log(json);
		
				$("#modal_loading").fadeOut(1);
		
				if (json['error']) {
					
					$("#modal ul").html(json['error_msg']);
					$("#modal").fadeIn(1);
					return;
				}
		
		
				if (json['success']) {
					
					$("#center_cart_r_payments_added").append(json['html']);
					$("#itemSearchInput").focus().select();
					total();
					$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
					
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
			}
		});
	}
	else if(type=='CREDIT')
	{
		
		var dueDate = $("#right_payment_quick_credit_date").val();
		
		$.ajax({
			url: 'sales/screen/addPayment/',
			type: 'POST',
			data: {type:type, val:val, dueDate:dueDate}, // You can send an empty object if you don't need to send anything
			dataType: 'json',
			contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
			cache: false,
			processData: true,
			beforeSend: function() {
				
				$("#modal_loading").fadeIn(1);
			},
			success: function(json) {
			
				console.log(json);
		
				$("#modal_loading").fadeOut(1);
		
				if (json['error']) {
					
					$("#modal ul").html(json['error_msg']);
					$("#modal").fadeIn(1);
					return;
				}
		
		
				if (json['success']) {
					
					$("#center_cart_r_payments_added").append(json['html']);
					$("#itemSearchInput").focus().select();
	
					total();
					$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
					
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
			}
		});
	}
	else if(type=='CHEQUE')
	{
		
		var chequeBank = $("#right_payment_quick_cheque_bank").val();
		var chequeDate = $("#right_payment_quick_cheque_date").val();
		var chequeNo = $("#right_payment_quick_cheque_no").val();
		
		$.ajax({
			url: 'sales/screen/addPayment/',
			type: 'POST',
			data: {type:type, val:val, chequeBank:chequeBank, chequeDate:chequeDate, chequeNo:chequeNo}, // You can send an empty object if you don't need to send anything
			dataType: 'json',
			contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
			cache: false,
			processData: true,
			beforeSend: function() {
				
				$("#modal_loading").fadeIn(1);
			},
			success: function(json) {
			
				console.log(json);
		
				$("#modal_loading").fadeOut(1);
		
				if (json['error']) {
					
					$("#modal ul").html(json['error_msg']);
					$("#modal").fadeIn(1);
					return;
				}
		
		
				if (json['success']) {
					
					$("#center_cart_r_payments_added").append(json['html']);
					$("#itemSearchInput").focus().select();
	
					total();
					$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
					
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
			}
		});
	}
	else if(type=='RETURN')
	{
		
		var returnNo = $("#right_payment_quick_return_no").val();
		
		$.ajax({
			url: 'sales/screen/addPayment/',
			type: 'POST',
			data: {type:type, val:val, returnNo:returnNo}, // You can send an empty object if you don't need to send anything
			dataType: 'json',
			contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
			cache: false,
			processData: true,
			beforeSend: function() {
				
				$("#modal_loading").fadeIn(1);
			},
			success: function(json) {
			
				console.log(json);
		
				$("#modal_loading").fadeOut(1);
		
				if (json['error']) {
					
					$("#modal ul").html(json['error_msg']);
					$("#modal").fadeIn(1);
					return;
				}
		
		
				if (json['success']) {
					
					$("#center_cart_r_payments_added").append(json['html']);
					$("#itemSearchInput").focus().select();
	
					total();
					$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
					
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
			}
		});
	}
	else if(type=='GIFT CARD')
	{
		
		var giftCardNo = $("#right_payment_quick_giftcard_no").val();
		
		$.ajax({
			url: 'sales/screen/addPayment/',
			type: 'POST',
			data: {type:type, val:val, giftCardNo:giftCardNo}, // You can send an empty object if you don't need to send anything
			dataType: 'json',
			contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
			cache: false,
			processData: true,
			beforeSend: function() {
				
				$("#modal_loading").fadeIn(1);
			},
			success: function(json) {
			
				console.log(json);
		
				$("#modal_loading").fadeOut(1);
		
				if (json['error']) {
					
					$("#modal ul").html(json['error_msg']);
					$("#modal").fadeIn(1);
					return;
				}
		
		
				if (json['success']) {
					
					$("#center_cart_r_payments_added").append(json['html']);
					$("#itemSearchInput").focus().select();
	
					total();
					$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
					
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
			}
		});
	}
	else if(type=='LOYALTY')
	{
		
		$.ajax({
			url: 'sales/screen/addPayment/',
			type: 'POST',
			data: {type:type, val:val}, // You can send an empty object if you don't need to send anything
			dataType: 'json',
			contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
			cache: false,
			processData: true,
			beforeSend: function() {
				
				$("#modal_loading").fadeIn(1);
			},
			success: function(json) {
			
				console.log(json);
		
				$("#modal_loading").fadeOut(1);
		
				if (json['error']) {
					
					$("#modal ul").html(json['error_msg']);
					$("#modal").fadeIn(1);
					return;
				}
		
		
				if (json['success']) {
					
					$("#center_cart_r_payments_added").append(json['html']);
					$("#itemSearchInput").focus().select();
	
					total();
					$("#center_cart_r_items_list").scrollTop($("#center_cart_r_items_list")[0].scrollHeight);
					
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
			}
		});
	}
	else
	{
		$("#modal ul").html('<li>Invalid Payment Mode</li>');
		$("#modal").fadeIn(1);
	}
	
});







$(document).on('click', '.pmremove', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).data('id');

	$.ajax({
		url: 'sales/screen/removePayment/'+id+'/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#itemQty"+id).val(json['oldValue']);
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				$("#paymentRow"+id).remove();
				$("#itemSearchInput").focus().select();
				total();
				
			}
		},
		error: function(xhr, status, error) {
			console.error("AJAX Error:", error);
		}
	});

	
});

$(document).ready(function() {
    var moved = false;
    
    $("#getmorePaymentMode").on('click', function() {
        if (!moved) {
            // Move left to show hidden items
            $("#inner_payment_mode").css("transform", "translateX(-360px)"); 
            moved = true;
            $(this).html("&lt;"); // change > to <
        } else {
            // Move back to original
            $("#inner_payment_mode").css("transform", "translateX(0px)");
            moved = false;
            $(this).html("&gt;"); // change < back to >
        }
    });
});

$(document).on('click', '.quick-amount', function() {
	
	var val = $(this).data('value');
	
	$("#paymentAmt").val(val);
	$("#addPaymentBtn").trigger('click');
	
});






$(document).on('click', '#completeInv', function() {
	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).data('id');

	$.ajax({
		url: 'sales/screen/complete/',
		type: 'POST',
		data: {}, // You can send an empty object if you don't need to send anything
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded', // Not multipart, since no files
		cache: false,
		processData: true,
		beforeSend: function() {
			
			$("#modal_loading").fadeIn(1);
		},
		success: function(json) {
		
			console.log(json);
	
			$("#modal_loading").fadeOut(1);
	
			if (json['error']) {
				
				$("#itemQty"+id).val(json['oldValue']);
				
				$("#modal ul").html(json['error_msg']);
				$("#modal").fadeIn(1);
				return;
			}
	
	
			if (json['success']) {
				
				window.location.href = json['redirect'];
				
			}
		},
		error: function(xhr, status, error) {
			alert(error);
			console.error("AJAX Error:", error);
		}
	});

	
});





$(document).ajaxComplete(function(e) {
    
  
	jQuery(".itemAjax").autocomplete({
		open: function() { 
				// After menu has been opened, set width to 100px
				$('.ui-menu').css('max-height','440px');
				$('.ui-menu').css('z-index','9999');
			} ,
		source:'inventory/master_items/autoComplete/',
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



$(document).on('click', '.itemAjax', function() {
	
	$(this).data('id','');
	$(this).val('');
	
});
