	function searchForInventory(id) {
		var input = document.getElementById('inventory_input');
		var div = document.getElementById('inventory_results');
		if(input.value.length > 0) {
			new Ajax.Request(base_url+'sales/AJAX_get_inventory_numbers/' + id,{
				method: 'post',
				parameters: "value=" + input.value,
				onComplete: function (transport) {
					div.innerHTML = transport.responseText;
					new Effect.BlindDown(div);
					//alert(transport);
				}
			});		
		}
		else {
			alert("Please Supply a Search String");
		}
	}

	function clearSearch() {
		var input = document.getElementById('inventory_input');
		var div = document.getElementById('inventory_results');
		new Effect.BlindUp(div);
		//div.innerHTML = '';	
		input.value = '';
	}
	function update_tax(value, field, id, invoice_id) {
		var s  =  value;
		var div = document.getElementById(field);
		var filteredValues = "$,";// Characters stripped out
		var i;
		var returnString = "";
		
		for (i = 0; i < s.length; i++) {  // Search through string and append to unfiltered values to returnString.
			var c = s.charAt(i);
			if (filteredValues.indexOf(c) == -1) {
				returnString += c;
			}
		}
		var num = parseFloat(returnString);
		new_tax = (num * 0.095);
		new Ajax.Request(base_url + 'sales/AJAX_updateInvoiceItemField/' + id + '/sale_tax/money',{
				okText: 'Save', 
				okButton: false, 
				cancelText: 'Cancel', 
				cancelLink: false, 
				submitOnBlur: 'true', 
				ajaxOptions: {
					method: 'post'
				},
				postBody: "value=" + new_tax,
				onComplete: function (transport) {
					var div = document.getElementById('sale_tax_div_' + id);
					div.innerHTML = transport.responseText;
					update_total_price(invoice_id);							
				}
			});
			
	}
	function update_total_price(id) {
		new Ajax.Request(base_url + 'sales/AJAX_recalculatePriceAndTax/' + id, {
				okText: 'Save', 
				okButton: false, 
				cancelText: 'Cancel', 
				cancelLink: false, 
				submitOnBlur: 'true', 
				ajaxOptions: {
					method: 'post'
				},			
				onSuccess: function (transport, json) {
				var json = eval('(' + transport.responseText + ')');
				var price = document.getElementById('total_price_div');
					price.innerHTML = json.price;
				var tax = document.getElementById('total_tax_div');
					tax.innerHTML = json.tax;
				var total = document.getElementById('total_invoice_price_div');
					total.innerHTML = json.total;
			}
		});
		
	}