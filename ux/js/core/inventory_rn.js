// JavaScript Document


function totalCalc() {
    
	var qty = parseFloat($("#qty").val()) || 0;
    var amount = parseFloat($("#amount").val()) || 0;
    var buying_price = parseFloat($("#buying_price").val()) || 0;
    var discount = parseFloat($("#discount").val()) || 0;

    var discountVal = (buying_price * discount) / 100;
    var final_price = buying_price - discountVal;
    var total = final_price * qty;

    $("#final_price").val(final_price.toFixed(2));
    $("#total").val(total.toFixed(2));

   
    var subtotal = 0,
        itemincart = 0,
        totalDiscount = 0;

  
    $(".linerows").each(function () {
        var qty = parseFloat($("input.eqty", this).val()) || 0;
        var amount = parseFloat($("input.eamount", this).val()) || 0;
        var buying_price = parseFloat($("input.ebuying_price", this).val()) || 0;
        var discount = parseFloat($("input.ediscount", this).val()) || 0;

        var discountVal = (buying_price * discount) / 100;
        var final_price = buying_price - discountVal;
        var total = final_price * qty;

        var priceReduced = amount - buying_price;
        totalDiscount += (discountVal + priceReduced) * qty;

        $("input.etotal", this).val(total.toFixed(2));

        itemincart += qty;
        subtotal += total;
    });

    // Update bottom summary fields
    $("#bottom_total_qty").val(itemincart.toFixed(2));
    $("#bottom_total_save").val(totalDiscount.toFixed(2));
    $("#bottom_total").val(subtotal.toFixed(2));
}



$(document).on('change', '#amount', function() {
	
	var amount = $("#amount").val();
	$("#buying_price").val(amount);
	
});

$(document).on('change', '.addlinechange', function() {
		
	var qty = parseFloat($("#qty").val()) || 0;
	var amount = parseFloat($("#amount").val()) || 0;
	var buying_price = parseFloat($("#buying_price").val()) || 0;
	var discount = parseFloat($("#discount").val()) || 0;
	
	var discountVal = buying_price*discount/100;
	var final_price = buying_price-discountVal;
	var total = final_price*qty;
	
	
	$("#final_price").val(final_price);
	$("#total").val(total);

	
});

$(document).on('change', '.editlinechange', function() {
	
	totalCalc();
	
});

$(document).on('click', '#addItem', function() {

	
	var no = parseFloat($("#no").val());
	
	var itemId = $("#item_name_id").val();
	var itemName = $("#item_name").val();
	
	var qty = $("#qty").val();
	var amount = $("#amount").val();
	var buying_price = $("#buying_price").val();
	var discount = $("#discount").val();
	var final_price = $("#final_price").val();
	var total = $("#total").val();
	
	if(no && itemId && itemName && qty && amount && buying_price && discount && final_price && total)
	{
	
		$("#addedItemsList").append('<tr id="row'+no+'" class="linerows"> <td><input type="text" name="added_no[]" disabled="disabled" value="'+no+'" /></td> <td><input type="text" name="added_item_name[]" data-focus="qty" value="'+itemName+'" disabled /><input type="hidden" name="added_item_id[]" value="'+itemId+'"></td> <td><input type="text" name="added_qty[]" class="text-right editlinechange eqty" value="'+qty+'" /></td> <td><input type="text" name="added_amount[]" class="text-right editlinechange eamount" value="'+amount+'" /></td> <td><input type="text" name="added_buying_price[]" class="text-right editlinechange ebuying_price" value="'+buying_price+'" /></td> <td><input type="text" name="added_discount[]" class="text-right editlinechange ediscount" value="'+discount+'" /></td> <td><input type="text" name="added_final_price[]" class="text-right efinal_price" disabled="disabled" value="'+final_price+'" /></td> <td><input type="text" name="added_total[]" disabled class="text-right etotal" value="'+total+'" /></td> <td><a class="btn btn-danger removeItem" data-id="'+no+'"><i class="fa-light fa-trash-xmark"></i></a></td> </tr>');

	
		$("#no").val(no+1);
		$("#itemId").val('');
		$("#item_name").val('');
		$("#qty").val('');
		$("#amount").val('');
		$("#buying_price").val('');
		$("#discount").val('0');
		$("#final_price").val('0');
		$("#total").val('0');
		
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