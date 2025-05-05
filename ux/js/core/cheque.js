// JavaScript Document

$(document).on('click', '.changeStatus', function() {
	
	var id = $(this).data('id');
	var accountId = $("#account_id"+id).val();
	var realized_date = $("#realized_date"+id).val();
	
	event.preventDefault(); // Make sure 'event' is defined properly
	
	$.ajax({
		url: 'accounts/transaction_cheque/update/' + id,
		type: 'POST',
		data: {accountId:accountId, realized_date:realized_date}, // You can send an empty object if you don't need to send anything
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
	
			if (json['success']) {
				
				$('#statusTxt'+id).text('Realized');
				$("#account_id"+id).remove();
				$("#actionBtn"+id).remove();
				$("#realized_date"+id).remove();
				
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

$(document).on('click', '.revertStatus', function() {

	var id = $(this).data('id');
	
	event.preventDefault(); // Make sure 'event' is defined properly
	
	$.ajax({
		url: 'accounts/transaction_cheque/revertupdate/' + id,
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
	
			if (json['success']) {
				
				$('#statusTxt'+id).text('Pending');
				$("#actionBtn"+id).remove();
				
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