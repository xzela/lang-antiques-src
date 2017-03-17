
function getCatalogueState(id, field) {
	var status = 0;
	var stage = field.name;
	if (field.checked) {
		status = 1;
	}	
	updateCatalogueStatus(id, stage, status);
}

/**
 * Updates the catalogue status via Ajax request
 * 
 * @param [int] id = item id
 * @param [string] stage = field name to update (database column)
 * @param [int] status = status of the field
 * 
 */
function updateCatalogueStatus(id, stage, status) {
	new Ajax.Request(base_url + 'inventory/AJAX_updateCatalogueStatus/' + id + '/' + stage + '/' + status, {
		method: 'post',
		onSuccess: function () {
				updateCatalogueMessage(1);
			}
		});
}

/*
 * Updates the message displayed near the bottom of the catelogue
 * list. 
 * 
 * @param [bool] bool - why do i send a bool? 
 */
function updateCatalogueMessage(bool) {
	var message = document.getElementById('catalogue_content');
	message.innerHTML = "Status was Updated";
		
	new Effect.SlideDown('catalogue_message', {duration: 0.5 });
	setTimeout("new Effect.SlideUp('catalogue_message', {duration: 0.5 })", 1500);
}