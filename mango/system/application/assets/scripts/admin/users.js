
/**
 * Sends an Ajax request to the server to 
 * reactive the user. 
 * 
 * @param [int] id = user id
 */
function reactivateUser(id) {
	new Ajax.Request(base_url + 'admin/user_reactivate/' + id, {
		method: 'post',
		onSuccess: function () {
			removeUserFromList(id);
		}
	}); 
}

/**
 * Sends an Ajax request to the server to 
 * deactive the user. 
 * 
 * @param [int] id = user id
 */
function deactivateUser(id) {
	new Ajax.Request(base_url + 'admin/user_deactivate/' + id, {
		method: 'post',
		onSuccess: function () {
			removeUserFromList(id);
		}
	}); 
}

/**
 * This removes the user from the list (GUI only)
 * @param [int] id = user id
 */
function removeUserFromList(id) {
	new Effect.BlindUp('user_'+id);
}

/**
 * This opens or closes the password section (GUI only)
 * @param [int] id = user id
 */
function openPasswordReset(id) {
	var link = document.getElementById('link_' + id);
	var field = document.getElementById('password_field_' + id);
	var text = link.innerHTML;
	
	//Is it bad to compare strings? probably...
	if (text == "Close Password") {
		closeElements(id);
	} 
	else {
		link.innerHTML = "Close Password";
		new Effect.BlindDown('password_' + id, {duration: .25});		
	}
}

/**
 * Sends an Ajax request to reset the password
 * 
 * @param [int] user_id = user id
 * @param [int] admin_id = admin id
 * 
 */
function resetPassword(user_id, admin_id) {
	var password = document.getElementById('password_field_' + user_id);
	var message = document.getElementById('message_' + user_id);
	
	new Ajax.Request(base_url + 'admin/user_reset_password/' + user_id + '/' + admin_id + '/'+ password.value, {
		method: 'post',
		onSuccess: function (transport) {
			var respons = transport.responseText;
			if (respons == 0) {
				message.innerHTML = "<span class='warning'>bad admin password</span>";
			}
			else if (respons == 1) {
				message.innerHTML = "<span class='success'>password updated</span>";
				closeElements(user_id);				
			}
			else {
				message.innerHTML = "Error... or something";
			}
			new Effect.BlindDown('message_' + user_id, {duration: .5});
			setTimeout("new Effect.SlideUp('message_" + user_id + "', {duration: 0.5 })", 1500);
			setTimeout(password.value = '', 1000);
		}
	});	
}

/**
 * This closes the password section (GUI only)
 * 
 * @param [int] id = user id
 */
function closeElements(id) {
	var link = document.getElementById('link_' + id);
	var field = document.getElementById('password_field_' + id);
	
	link.innerHTML = "Reset User Password";
	field.value = "";
	new Effect.BlindUp('password_' + id, {duration: .25});	
}
