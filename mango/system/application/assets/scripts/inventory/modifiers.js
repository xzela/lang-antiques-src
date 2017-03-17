/**
 * @todo - Clean up this area, find dupe code and fix any errors
 */

/**
 * updates the modifiers, sends ajax calls to server
 * 
 * @param [int] id = item id
 * @param [int] mod_id = modifier id
 * @param [bool] status = 1=apply modifier, 0=remove modifier 
 */
function updateModifier(id, mod_id, status) {
	var list_id = 'list_' +  mod_id;	
	if (status) { 
		new Ajax.Request(base_url+'modifier/AJAX_applyModifier/'+ id + '/' + mod_id, {
			method: 'post',
			onSuccess: function () {					
					updateModifierList(id, list_id, mod_id);
				}
			});
	}
	else {
		new Ajax.Request(base_url+'modifier/AJAX_removeModifier/'+ id + '/' + mod_id, {
			method: 'post',
			onSuccess: function () {
					removeAppliedModifier(id, mod_id);
				} 
			});	
	}
}

/**
 * updates the Available Modifiers list
 * 
 * @param [int] id = item id
 * @param [string] list_id = modifier id in the list of available modifiers
 * @param [string] mod_id = modifier id
 */
function updateModifierList(id, list_id, mod_id) {
	var list = document.getElementById(list_id);
	list.className = 'orig_modifier_added';
	list.setAttribute('onclick', '');
	
	new Ajax.Request(base_url + 'modifier/AJAX_getModifierName/' + mod_id, {
		 method: 'post',
		 onComplete: function (transport) {
		 	updateAppliedModifiers(id, mod_id, transport.responseText);
		 }
		 
	});
}

/**
 * Removes Modifiers from the Applied Modifiers List
 *
 * @param [int] id = item id
 * @param [int] mod_id = modifier id, needs 'applied_' appended to actually work 
 */
function removeAppliedModifier(id, mod_id) {
	var applied = document.getElementById('applied_list');
	var item = document.getElementById('applied_' + mod_id);
	applied.removeChild(item);
	
	var list = document.getElementById('list_' + mod_id);
	list.className = 'orig_modifier';
	list.onclick = function(){
			updateModifier(id,mod_id,1);
		};
}

/**
 * Appends a new modifier to the Applied Modifiers list
 * 
 * @param [int] id = item id
 * @param [int] mod_id = modifier id
 * @param [string] mod_name = modifier name
 */
function updateAppliedModifiers(id, mod_id, mod_name) {
	var applied = document.getElementById('applied_list');
	var new_item = document.createElement('li');
	new_item.setAttribute('id', 'applied_' + mod_id);
	new_item.innerHTML = '<span class="applied_modifiers" onclick="javascript:updateModifier('+id+','+mod_id+',0)">'+mod_name+' </span>';
	applied.appendChild(new_item);
}