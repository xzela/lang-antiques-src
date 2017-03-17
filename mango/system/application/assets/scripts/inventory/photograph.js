
/**
 * Sends the item to a specific queue (the photograph queue)
 * 
 * @param [int] id = item id
 * 
 * @return null 
 */

function sendToPhotoQueue(id) {
	new Ajax.Request(base_url + 'photographer/AJAX_updatePhotoStatus/' + id + '/1', {
		method: 'post',
		onSuccess: function(transport) {
			updatePhotoText('Photo', 'item_photo_li', id, 'enqueued');
		},
		onFailure: function(transport) {
			alert('failed: ' + transport.statusText);
		}
	});
}

/**
 * 
 * @param id
 * @return
 */
function removeFromPhotoQueue(id) {
	new Ajax.Request(base_url + 'photographer/AJAX_updatePhotoStatus/' + id + '/0', {
		method: 'post',
		onSuccess: function(transport) {
			updatePhotoText('Photo', 'item_photo_li', id, 'removed');
		},
		onFailure: function(transport) {
			alert('failed: ' + transport.statusText);
		}
	});
}

/**
 * Sends the item to a specific queue (the photograph queue)
 * 
 * @param [int] id = item id
 * 
 * @return null 
 */

function sendToEditQueue(id) {
	new Ajax.Request(base_url + 'photographer/AJAX_updateEditStatus/' + id + '/1', {
		method: 'post',
		onSuccess: function(transport) {
			updatePhotoText('Edit', 'item_edit_li', id, 'enqueued');
		},
		onFailure: function(transport) {
			alert('failed: ' + transport.statusText);
		}
	});
}

function removeFromEditQueue(id) {
		new Ajax.Request(base_url + 'photographer/AJAX_updateEditStatus/' + id + '/0', {
			method: 'post',
			onSuccess: function(transport) {
				updatePhotoText('Edit', 'item_edit_li', id, 'removed');
			},
			onFailure: function(transport) {
				alert('failed: ' + transport.statusText);
			}
		});
	}
/**
 * Updates the text for the queue status
 * 
 * @return null;
 */
function updatePhotoText(text, text_id, id, action) {
	var li = document.getElementById(text_id);
	if(action == 'removed') {
		li.innerHTML = text + " Queue: Not Enqueued [<a href='javascript:sendTo" + text + "Queue(" + id + ")'>Enqueue</a>]";
	}
	if (action == 'enqueued') {
		li.innerHTML = text + " Queue: <span class='warning'>Enqueue</span> [<a href='javascript:removeFrom" + text + "Queue(" + id + ")'>Remove</a>]";
	}
}

/**
 * Updates the order of each image.
 * I forgot this actually works...
 * 
 * @param id
 * @param string
 * @return null;
 */
function updateImageSequence(id, string) {
	var s = new String(string);
	var ss = s.replace(/sort_images\[\]\=/g, '');
	var order = ss.replace(/&/g, '-');
	new Ajax.Request(base_url + 'photographer/AJAX_updatePhotoSeq/' + id + '/' + order, {
		method: 'post',
		onSuccess: function (transport) {
			//do nothing
		},
		onFailure: function(transport) {
			alert('failed: ' + transport.statusText);
		}
	});
}