// JavaScript Document

function totalCalc() {
    

	var  noOfItems = 0;
	var  itemincart = 0;
	var  totalTotal = 0;
	
	
	$(".linerows").each(function () {
		var amount = parseFloat($("input.eamount", this).val()) || 0;
		var discount = parseFloat($("input.ediscount", this).val()) || 0;
		
		if(discount){ var discount = amount*discount/100; }
		else{ var discount = 0; }
		
		var finalamount = amount-discount;
		
		$("input.efinalamount", this).val(finalamount.toFixed(2));
		
		var qty = parseFloat($("input.eqty", this).val()) || 0;
		var total = qty*finalamount;
	   
		$("input.etotal", this).val(total.toFixed(2));
		
		itemincart += qty;
		totalTotal += total;
		noOfItems += 1;
	});
	
	// Update bottom summary fields
	$("#totalNoOfItems").val(noOfItems.toFixed(2));
	$("#totalQty").val(itemincart.toFixed(2));
	$("#totalTotal").val(totalTotal.toFixed(2));
}



$(document).on('change', '.addlinechange', function() {
		
	var amount = parseFloat($("#amount").val()) || 0;
	var discount = parseFloat($("#discount").val()) || 0;
	
	if(discount){ var discount = amount*discount/100; }
	else{ var discount = 0; }
	
	var finalamount = amount-discount;
	
	$("#finalamount").val(finalamount);
	
	var qty = parseFloat($("#qty").val()) || 0;
	
	var total = finalamount*qty;
	
	
	$("#total").val(total);

	
});



$(document).on('change', '.editlinechange', function() {
	
	totalCalc();
	
});

$(document).on('click', '#addItem', function() {

	
	var no = parseFloat($("#no").val());
	
	var itemId = $("#item_name_id").val();
	var itemName = $("#item_name").val();
	
	var amount = $("#amount").val();
	var discount = $("#discount").val();
	var finalamount = $("#finalamount").val();
	var qty = $("#qty").val();
	var total = $("#total").val();
	
	if(no && itemId && itemName && qty && amount)
	{
	
		$("#addedItemsList").append('<tr id="row'+no+'" class="linerows"> <td><input type="text" name="added_no[]" disabled="disabled" value="'+no+'" /></td> <td><input type="text" name="added_item_name[]" data-focus="qty" value="'+itemName+'" disabled /><input type="hidden" name="added_item_id[]" value="'+itemId+'"></td> <td><input type="text" name="added_amount[]" class="text-right editlinechange eamount" value="'+amount+'" /></td> <td><input type="text" name="added_discount[]" class="text-right editlinechange ediscount" value="'+discount+'" /></td> <td><input type="text" name="added_finalamount[]" class="text-right editlinechange efinalamount" value="'+finalamount+'" disabled /></td>  <td><input type="text" name="added_qty[]" class="text-right editlinechange eqty" value="'+qty+'" /></td><td><input type="text" name="added_total[]" disabled class="text-right etotal" value="'+total+'" /></td> <td><a class="btn btn-danger removeItem" data-id="'+no+'"><i class="fa-light fa-trash-xmark"></i></a></td> </tr>');

	
		$("#no").val(no+1);
		$("#itemId").val('');
		$("#item_name").val('');
		$("#amount").val('');
		$("#discount").val('');
		$("#finalamount").val('');
		$("#qty").val('');
		$("#total").val('');
		
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