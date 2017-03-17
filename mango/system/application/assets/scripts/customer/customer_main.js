
/**
 * Gets the state of the checkbox
 * 
 * @param [int] id = customer id
 * @param [object] field = field.object
 * 
 * @return null;
 */
function getMailingListState(id, field) {
	var value = 0;
	var field_name = field.name;
	if (field.checked) {
		value = 1;
	}	
	updateCustomerMailingList(id, field_name, value);
}

/**
 * Updates the mailing list checkbox 
 * Had to do that because there is no ajax edit_inplace
 * for checkboxes
 * 
 * @param [global[string]] base_url = string for base URL (http://localhost/system/...);
 * 
 * @param [int] id = customer id
 * @param [bool] status = status of the field, yes||no
 * 
 * @return null;
 * 
 */
function updateCustomerMailingList(id, field, value) {
	new Ajax.Request(base_url + 'customer/AJAX_updateMailingListStatus/' + id + '/' + field + '/' + value, {
		method: 'post',
		onSuccess: function () {
			updateCustomerMessage(1);
		}
		});
}

/**
 * This updates the message alerting the user that the mailing
 * list status was updated...
 * 
 * @param bool
 * @return null
 */
function updateCustomerMessage(bool) {
	var message = document.getElementById('mailing_content');
	message.innerHTML = "Mailing List Status was Updated";	
	new Effect.SlideDown('mailing_message', {duration: 0.5 });
	setTimeout("new Effect.SlideUp('mailing_message', {duration: 0.5 })", 1500);
}

/**
 * This is an event which searches the customers based on a name;
 * @param [global[string]] base_url = string for base URL (http://localhost/system/...);
 * 
 * @return null
 */
function customerSearch() { 
	new Ajax.Autocompleter('customer_input', 
			'customer_results', 
			base_url+'customer/AJAX_get_customer_names/',{
				frequency: 1,
				paramName: "value",
				minChars: 2,
				afterUpdateElement: getCustomerSelectionId  
				});
}
/**
 * AJAX form submit(ish).
 *  
 * @param [global[string]] base_url = string for base URL (http://localhost/system/...); 
 * @param [string] text = response string from server;
 * @param [object] li = list item object;
 * 
 * @return null
 */
function getCustomerSelectionId(text, li) {
	document.location = base_url+'inventory/AJAX_apply_seller/' + item_id + '/2/' + li.id;
}

/**
 * This opens and closes the form when a 
 * new customer is being created on the seller
 * or invoice page
 * 
 * @param [object] foo = achor link object
 * @return bool;
 */
function openCustomerForm(foo) {
	if(foo.innerHTML == 'Add New Customer') {
		Effect.BlindDown('customer_form');
		foo.innerHTML = 'Close Customer'; 
		return false;
	}
	else {
		Effect.BlindUp('customer_form');
		foo.innerHTML = 'Add New Customer';
		return false;
		
	}
}

/**
 * This adds the customer as a Seller to a select item
 * 
 * @param [global[string]] base_url = string for base URL (http://localhost/system/...); 
 * @param [global[int]] item_id = item id;
 * @param [string] location = where the form is being submitted from;
 * 
 * @return null;
 */
function addCustomer(location) {
	new Ajax.Updater('customer_form_results', base_url + 'customer/AJAX_addCustomer/' + item_id, {
		method: 'post',
		parameters: $('form_customer').serialize(true),
		onSuccess: function(transport, text) {
			if(transport.responseText) {
				document.location = base_url + 'inventory/seller/' + item_id + '/edit';
				}
			}
		});
} 