


$(document).on('change', '.invoicestatuschange', function() {

	event.preventDefault(); // Make sure 'event' is defined properly
	
	var id = $(this).data('id');

	$.ajax({
		url: 'sales/transaction_invoices/cancellation/'+id,
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
	
				$("#invstatus" + id).parent('td').html('Cancelled');
				$("#popup_form_in_close").trigger('click');
				$("#modal_success p").text(json['success_msg']);
				$("#modal_success").fadeIn(1);
				
			}
		},
		error: function(xhr, status, error) {
			$('#saveFormBtn').attr('disabled', false).text('Save');
			$("#modal_loading").fadeOut(1);
			console.error("AJAX Error:", error);
		}
	});

	
});
