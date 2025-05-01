// JavaScript Document


function totalCalc() {
    

	var  itemincart = 0;
	
	
	$(".linerows").each(function () {
		var qty = parseFloat($("input.eqty", this).val()) || 0;
		var amount = parseFloat($("input.eamount", this).val()) || 0;
		var total = qty*amount;
	   
		$("input.etotal", this).val(total.toFixed(2));
		
		itemincart += qty;
	});
	
	// Update bottom summary fields
	$("#bottom_total_qty").val(itemincart.toFixed(2));
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

$(document).on('click', '#addItem', function() {

	
	var no = parseFloat($("#no").val());
	
	var itemId = $("#item_name_id").val();
	var itemName = $("#item_name").val();
	
	var itemType = $("#type").val();
	
	var qty = $("#qty").val();
	
	var amount = $("#amount").val();
	
	if(no && itemId && itemName && itemType && qty && amount)
	{
	
		$("#addedItemsList").append('<tr id="row'+no+'" class="linerows"> <td><input type="text" name="added_no[]" disabled="disabled" value="'+no+'" /></td> <td><input type="text" name="added_item_name[]" data-focus="qty" value="'+itemName+'" disabled /><input type="hidden" name="added_item_id[]" value="'+itemId+'"></td> <td><input type="text" name="added_type[]" value="'+itemType+'" readonly /></td> <td><input type="text" name="added_qty[]" class="text-right editlinechange eqty" value="'+qty+'" /></td>  <td><input type="text" name="added_amount[]" class="text-right editlinechange eamount" value="'+amount+'" /></td> <td><input type="text" name="added_total[]" disabled class="text-right etotal" value="'+total+'" /></td> <td><a class="btn btn-danger removeItem" data-id="'+no+'"><i class="fa-light fa-trash-xmark"></i></a></td> </tr>');

	
		$("#no").val(no+1);
		$("#itemId").val('');
		$("#item_name").val('');
		$("#type").val('');
		$("#qty").val('');
		$("#amount").val('');
		
		$("#item_name").trigger('focus');
	
	}
	else
	{
		$("#modal ul").html('<li>Error Found: Please re check</li>');
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