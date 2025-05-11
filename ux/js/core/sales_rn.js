// JavaScript Document


function totalCalc() {
    
	var qty = parseFloat($("#qty").val()) || 0;
    var amount = parseFloat($("#amount").val()) || 0;
    var total = amount * qty;

    $("#total").val(total.toFixed(2));

   
    var subtotal = 0;
    var itemincart = 0;

  
    $(".linerows").each(function () {
        var qty = parseFloat($("input.eqty", this).val()) || 0;
        var amount = parseFloat($("input.eamount", this).val()) || 0;

        var total = amount * qty;
        
        $("input.etotal", this).val(total.toFixed(2));

        itemincart += qty;
        subtotal += total;
    });

    // Update bottom summary fields
    $("#bottom_total_qty").val(itemincart.toFixed(2));
    $("#bottom_total").val(subtotal.toFixed(2));
}



$(document).on('change', '.addlinechange', function() {
		
	var qty = parseFloat($("#qty").val()) || 0;
	var amount = parseFloat($("#amount").val()) || 0;
	
	var total = amount*qty;
	
	
	$("#total").val(total);

	
});

$(document).on('change', '.editlinechange', function() {
	
	totalCalc();
	
});

$(document).on('click', '#addItem', function () {

    var no = parseFloat($("#no").val());
    var itemId = $("#item_name_id").val().trim();
    var itemName = $("#item_name").val().trim();
    var qty = $("#qty").val();
    var cost = $("#cost").val();
    var amount = $("#amount").val();
    var total = $("#total").val();

    // Check for required fields (allowing total=0)
    if (!isNaN(no) && itemId !== '' && itemName !== '' && cost !== '' && qty !== '' && amount !== '') {

        $("#addedItemsList").append(
            '<tr id="row' + no + '" class="linerows">' +
            '<td><input type="text" name="added_no[]" disabled="disabled" value="' + no + '" /></td>' +
            '<td><input type="text" name="added_item_name[]" data-focus="qty" value="' + itemName + '" disabled />' +
            '<input type="hidden" name="added_item_id[]" value="' + itemId + '"></td>' +
            '<td><input type="text" name="added_cost[]" class="text-right" value="' + cost + '" /></td>' +
            '<td><input type="text" name="added_amount[]" class="text-right editlinechange eamount" value="' + amount + '" /></td>' +
            '<td><input type="text" name="added_qty[]" class="text-right editlinechange eqty" value="' + qty + '" /></td>' +
            '<td><input type="text" name="added_total[]" disabled class="text-right etotal" value="' + total + '" /></td>' +
            '<td><a class="btn btn-danger removeItem" data-id="' + no + '"><i class="fa-light fa-trash-xmark"></i></a></td>' +
            '</tr>'
        );

        // Reset fields
        $("#no").val(no + 1);
        $("#item_name_id").val('');
        $("#item_name").val('');
        $("#cost").val('');
        $("#qty").val('');
        $("#amount").val('');
        $("#total").val('0');

        $("#item_name").trigger('focus');

        // Hide modal (in case it was shown before)
        $("#modal").fadeOut();

    } else {
        $("#modal ul").html('<li>Error Found: Please re-check</li>');
        $("#modal").fadeIn(1);
    }

    totalCalc();
});

$(document).on('click', '.removeItem', function() {
	
	if (confirm("Click OK to confirm the removal of this item!")) {
		var id = $(this).data("id");
		$("#row"+id).remove();
	}
	
	totalCalc();
	
});