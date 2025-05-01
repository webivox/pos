// JavaScript Document
$(document).on("submit", "#signinForm", function (e) {
    e.preventDefault(); // Prevent the default form submission

    var url = $("#signinForm").data("url");
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
            $('#signinFormBtn').attr('disabled', true).text('Validation...');
            $('#txtUserPos').attr('disabled', true);
            $('#txtPassPos').attr('disabled', true);
        },
        success: function(json) {
            console.log(json);


            if (json['error']) {
                $('#signinFormBtn').attr('disabled', false).text('Sign In');
            	$('#txtUserPos').attr('disabled', false);
            	$('#txtPassPos').attr('disabled', false);
                //$("#modal ul").html(json['error_msg']);
				
				$("#modal_in ul").html(json['error_msg']);
				$("#modal").fadeIn(20);
            }

            if (json['success']) {
				
				window.location.href = json['redirect'];
				
				
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
});


$(document).on("click", "#modal_in_close", function (e) {
	
	$("#modal").fadeOut(20);
	
});

// Close modal when pressing "Enter"
window.addEventListener("keydown", function(event) {
  if (event.key === "Enter") {
	$("#modal").fadeOut(20);
  }
});