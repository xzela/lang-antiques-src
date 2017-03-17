
function updateQueueStatus(id, status, type) {
	if (type == 'edit_queue') { 
		new Ajax.Request(base_url+'photographer/AJAX_updateEditStatus/'+ id + '/' + status, {
			method: 'post',
			onSuccess: function () {
					removeRowFromQueue(id);
				} 
			});
	}
	else if (type == 'photo_queue') {
		new Ajax.Request(base_url+'photographer/AJAX_updatePhotoStatus/'+ id + '/' + status, {
			method: 'post',
			onSuccess: function () {
					removeRowFromQueue(id);
				} 
			});	
	}
}


function removeRowFromQueue(id) {
	new Effect.Puff('r'+id);
}