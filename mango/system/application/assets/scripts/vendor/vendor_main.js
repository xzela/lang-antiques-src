
/**
 * Gets the state of the checkbox
 * 
 * @param [int] id = customer id
 * @param field = field.object
 * 
 * @return nothing
 */
function getMailingListState(id, field) {
	var value = 0;
	var field_name = field.name;
	if (field.checked) {
		value = 1;
	}	
	updateVendorMailingList(id, field_name, value);
}

/**
 * Updates the mailing list checkbox 
 * Had to do that because there is no ajax edit_inplace
 * for checkboxes
 * 
 * @param [int] id = customer id
 * @param [bool] status = status of the field, yes||no
 * 
 */
function updateVendorMailingList(id, field, value) {
	new Ajax.Request(base_url + 'vendor/AJAX_updateMailingListStatus/' + id + '/' + field + '/' + value, {
		method: 'post',
		onSuccess: function () {
			updateVendorMessage(1);
		}
		});
}

/**
 * This updates the message alerting the user that the mailing
 * list status was updated...
 * 
 * @param bool
 * @return
 */
function updateVendorMessage(bool) {
	var message = document.getElementById('mailing_content');
	message.innerHTML = "Mailing List Status was Updated";	
	new Effect.SlideDown('mailing_message', {duration: 0.5 });
	setTimeout("new Effect.SlideUp('mailing_message', {duration: 0.5 })", 1500);
}

function vendorSearch() {
	new Ajax.Autocompleter('vendor_input', 
			'vendor_results', 
			base_url+'vendor/AJAX_get_vendor_names/',{
				frequency: 1,
				paramName: "value",
				minChars: 2,
				afterUpdateElement: getVendorSelectionId  
				});
}

/**
 * 
 * @param [string] text = text
 * @param [object] li = list item object;
 * 
 * @return null;
 */
function getVendorSelectionId(text, li) {
	document.location = base_url+'inventory/AJAX_apply_seller/' + item_id + '/1/' + li.id;
}
 
 /**
  * This opens and closes the form when a 
  * new customer is being created on the seller
  * or invoice page
  * 
  * @param [object] foo = achor link object
  * @return bool;
  */
 function openVendorForm(foo) {
 	if(foo.innerHTML == 'Add New Vendor') {
 		Effect.BlindDown('vendor_form');
 		foo.innerHTML = 'Close Vendor'; 
 		return false;
 	}
 	else {
 		Effect.BlindUp('vendor_form');
 		foo.innerHTML = 'Add New Vendor';
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
function addVendor(location) {
	new Ajax.Updater('vendor_form_results', base_url + 'vendor/AJAX_addVendor/' + item_id, {
		method: 'post',
		parameters: $('form_vendor').serialize(true),
		onSuccess: function(transport) {
			if(transport.responseText) {
				document.location = base_url + 'inventory/seller/' + item_id + '/edit';
				}
			}
		});
}
