
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
	updateWorkshopMailingList(id, field_name, value);
}

/**
 * Updates the mailing list checkbox 
 * Had to do that because there is no ajax edit_inplace
 * for checkboxes
 * 
 * @param [int] id = workshop id
 * @param [bool] status = status of the field, yes||no
 * 
 */
function updateWorkshopMailingList(id, field, value) {
	new Ajax.Request(base_url + 'workshop/AJAX_updateMailingListStatus/' + id + '/' + field + '/' + value, {
		method: 'post',
		onSuccess: function () {
			updateWorkshopMessage(1);
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
function updateWorkshopMessage(bool) {
	var message = document.getElementById('mailing_content');
	message.innerHTML = "Mailing List Status was Updated";	
	new Effect.SlideDown('mailing_message', {duration: 0.5 });
	setTimeout("new Effect.SlideUp('mailing_message', {duration: 0.5 })", 1500);
}