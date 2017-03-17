<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Merge Vendor </title>
	<?php echo snappy_style('jquery.autocomplete.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.autocomplete.js'); ?>
	<?php echo snappy_script('vendor/vendor_main.js'); ?>
	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';

	var v_id = <?php echo $vendor['vendor_id']; ?> ;
	var v_array = new Array();
	
	$(document).ready(function() {
		testSubmit(); //test the submit button
		$("#vendor_input").keyup(function() { //on keyup, send request
			$('#vendor_results').html('');
			if($('#vendor_input').val().length > 2 ) { //only send if chars are 3 or greater
				$.post(base_url+'vendor/AJAX_get_vendor_names/', {
					q: $('#vendor_input').val(),
					n: v_array.toString() //do not show these ids
				},
				function(data, message) {
					var people = data.people;
					console.log(v_array);
					for(var i = 0; i < people.length; i++) {
						//console.log(people[i]);
						
						if(people[i].vendor_id != v_id) {
							//formatting stuff
							var html = '<div class="fake_ac" onclick="appendVendor(this, ' + people[i].vendor_id + ')">';
								html += '<span><strong>' + people[i].contact + '</strong>' + '[' + people[i].vendor_id + ']</span>';
								html += '<br />' + people[i].phone;
								html += '<br />' + people[i].address + ' ' + people[i].city;
							html += '</div>';
							$('#vendor_results').prepend(html);
						}
					} 
				},
				"json");
			}
		});
	});

	function appendVendor(div, id) {
		v_array.push(id); //push id on top of array
		var results = document.getElementById('vendor_results');
		results.removeChild(div); //remove the child element from customer results

		var list = document.getElementById('merge_list');
		var list_item = document.createElement('li');
		list_item.setAttribute('id', 'list_' + id); //create id attribute
		
		var content = '<span>' + div.childNodes[0].innerHTML + '[<a href="javascript:removeVendor(\'list_' + id + '\',' + id + ')" >Remove</a>]</span>';
		list_item.innerHTML = content; //set the content
		list.appendChild(list_item); //append child element
		testSubmit(); //test submit button
	}

	function removeVendor(list_id, id) { //removing the child
		//interesting for loop to walk array
		for(var i=0; i < v_array.length; i++){  
			if(v_array[i] == id) { //if the indexed value of the array equals the id, remove it 
				v_array.splice(i, 1); //add
			}
			//pass all others who don't macth
		}
		var list = document.getElementById('merge_list');
		var list_item = document.getElementById(list_id);
		list.removeChild(list_item); //remove the child element
		testSubmit(); //test the submit button
	}

	function testSubmit() { //testes the submit button
		var submit = document.getElementById('merge_submit');
		var hidden = document.getElementById('merge_vendors');
		hidden.value = v_array; //assign array value into hidden array
		if(v_array.length > 0) { //c_array has length? enable button
			submit.disabled = false;
		}
		else { //otherwise diable the button
			submit.disabled = true;
		}		
	}
	</script>	
	<style type='text/css'>
		div.fake_ac {
			border: 1px solid #999;
			padding: 5px;
			margin: 5px;
			cursor: pointer;
		}
		div.fake_ac:hover {
			background-color: #d1d1d1;
		}
		
		ul.merge_list {
			
		}
		
		.merge_container {
			float: right; 
			margin-right: 100px;
			padding: 5px;
			border: 1px dashed #999;
			background-color: #fff;
		}
		
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Merge Vendor</h2>
		<ul id="submenu">
			<li><?php echo anchor('vendor/edit/' . $vendor['vendor_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Vendor'); ?></li>
		</ul>
		<div>
			<div class='merge_container' > 
				<h3>List of Vendors to Merge</h3>
				<ul id='merge_list'>
				</ul>
				<?php echo form_open('vendor/merge/' . $vendor['vendor_id']); ?>
					<input id='vendor_id' name='vendor_id' type='hidden' value='<?php echo $vendor['vendor_id']; ?>' />
					<input id='merge_vendors' name='merge_vendors' type='hidden' value='' />
					<input id='merge_submit' type='submit' name='merge_submit' value='Merge These Guys!' disabled />
				<?php echo form_close(); ?>
			</div>
		
			<table class='form_table' >
				<tr>
					<td class='title'>Name:</td>
					<td colspan="3" ><?php echo $vendor['name']; ?></td>
				</tr>
				<tr>
					<td class="title">Main Phone:</td>
					<td><?php echo $vendor['phone']; ?></td>
					<td class="title">Alt Phone:</td>
					<td><?php echo $vendor['alt_phone']; ?></td>
				</tr>
				<tr>
					<td class="title">Email:</td>
					<td colspan='3'><?php echo $vendor['email']; ?></td>
				</tr>					
				<tr>
					<td class="title">Address:</td>
					<td colspan="3" nowrap><?php echo $vendor['address']; ?></td>
				</tr> 
				<tr>
					<td class="title">City:</td>
					<td><?php echo $vendor['city']; ?></td>
					<td class="title">State: <?php echo $vendor['state']; ?></td>
					<td><strong>Zip:</strong><?php echo $vendor['zip']; ?></td>
				</tr>
			</table>
		</div>
		
		<div style='border: 1px solid #999999; padding: 10px; margin: 3px;'>
			Search Name: <input id='vendor_input' name='vendor_input' type='text' style='width: 250px;' />
		</div>
		<div id='vendor_results'>
			
		</div>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>