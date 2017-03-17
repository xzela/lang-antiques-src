/*
 * TODO - Clean up this area, find dupe code and fix any errors
 */

/*
 * updates the Materials, sends ajax calls to server
 * 
 * @param [int] id = item id
 * @param [int] mat_id = material id
 * @param [decimel] karat = karat 
 * @param [bool] status = 1=apply material, 0=remove material 
 */
function updateMaterial(id, mat_id, karat, status, karat_value) {	 
	var list_id = 'list_' +  mat_id;
	var karat_value;
	
	if (status) {
		str = base_url+'material/AJAX_applyMaterial/'+ id + '/' + mat_id + '/';
		if (karat) {
			karat_value = prompt('Please Enter Karat Value', '');
			if (karat_value  != '' && karat_value != null) {
				str = base_url+'material/AJAX_applyMaterial/'+ id + '/' + mat_id + '/' + karat_value;
				new Ajax.Request(str, {
					method: 'post',
					onSuccess: function (transport) {
							updateMaterialList(id, list_id, mat_id, karat_value);
						}
					});
				
			}
			else {
				//
			}
		}
		else {
			new Ajax.Request(str, {
				method: 'post',
				onSuccess: function (transport) {
						updateMaterialList(id, list_id, mat_id, karat_value);
					}
				});

		}
	}
	else {
		new Ajax.Request(base_url+'material/AJAX_removeMaterial/'+ id + '/' + mat_id, {
			method: 'post',
			onSuccess: function () {
					removeAppliedMaterial(id, mat_id, karat);
				} 
			});	
	}
}

/*
 * updates the Available Material list
 * 
 * @param [int] id = item id
 * @param [string] list_id = material id in the list of available materials
 * @param [string] mat_id = material id
 * @param [decimal] karat = karat quality... just passing though...
 */
function updateMaterialList(id, list_id, mat_id, karat) {	 
	var list = document.getElementById(list_id);
	list.setAttribute('class', 'orig_modifier_added');
	list.setAttribute('onclick', '');
	new Ajax.Request(base_url + 'material/AJAX_getMaterialName/' + mat_id, {
		 method: 'post',
		 onComplete: function (transport) {
		 	updateAppliedMaterial(id, mat_id, transport.responseText, karat);
		 }
		 
	});
}

/*
 * Removes Material from the Applied Material List
 *
 * @param [int] id = item id
 * @param [int] mat_id = material id, needs 'applied_' appended to actually work 
 */
function removeAppliedMaterial(id, mat_id, karat) {	 
	var applied = document.getElementById('applied_list');
	var item = document.getElementById('applied_' + mat_id);
	applied.removeChild(item);
	
	var list = document.getElementById('list_' + mat_id);
	list.className = 'orig_modifier';
	if (karat) {
		//list.setAttribute('onclick', 'updateMaterial('+id+','+mat_id+',true,1)');
		list.onclick = function () {
			updateMaterial(id, mat_id, true ,1);
		};
	}
	else {
		//list.setAttribute('onclick', 'updateMaterial('+id+','+mat_id+',false,1)')
		list.onclick = function () {
			updateMaterial(id, mat_id, false, 1);
		}
	}
	
}

/*
 * Appends a new Material to the Applied Material list
 * 
 * @param [int] id = item id
 * @param [int] mat_id = material id
 * @param [string] mat_name = material name
 * @param [decimal] karat = karat weight of the gold
 */
function updateAppliedMaterial(id, mat_id, mat_name, karat) {
	var applied = document.getElementById('applied_list');
	var new_item = document.createElement('li');
	new_item.setAttribute('id', 'applied_' + mat_id);
	if (karat == null) {
		new_item.innerHTML = '<span class="applied_modifiers" onclick="javascript:updateMaterial('+id+','+mat_id+',false,0)">'+mat_name+'</span>';
	}
	else {
		new_item.innerHTML = '<span class="applied_modifiers" onclick="javascript:updateMaterial('+id+','+mat_id+',true,0)">'+mat_name+' ('+karat+'k) </span>';
	}
	applied.appendChild(new_item);
}