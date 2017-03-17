<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<title><?php echo $this->config->item('project_name'); ?> - Inventory Edit Materials - <?php echo $item_data['item_name'];?></title>
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.impromptu.css'); ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.impromptu.2.7.js'); ?>
	
	<style type="text/css">
	
	.orig_modifier {
		list-style-type: none;
		text-decoration: underline;
		color: #0033FF;
		
	}
	
	.orig_modifier:hover {
		cursor: pointer;
		/*color: #3399FF; */
		color: green;
	}
	
	.applied_modifiers {
		text-decoration: underline;
		color: #0033FF;
	}
	.applied_modifiers li {
		list-style-type: none;
	}
	
	.applied_list_item {
		list-style-type: none;
		padding: 0px;
		margin: 0px;	
	}
	
	.applied_modifiers:hover {
		cursor: pointer;
		color: red;		
	}
	.modifier_list li{
		list-style-type: none;
	}
	#applied_mods {
		border: 1px dashed #999;
		padding: 0px;
		margin: 0px;
	}
	.h3_list {
		padding: 0px;
		margin: 0px;
	}
	
	</style>
	<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var item_id = <?php echo $item_data['item_id'];?>;

	function appendMaterial(text, mod_id, k) {
		//insert into database
		var url = base_url + 'material/AJAX_applyMaterial';
		$.post(url, {
				id: item_id,
				material_id: mod_id,
				karat: k
			}, 
			function(item_material_id) {
				//add to list
				var str = '<li class="applied_list_item">';
					str += '<span id=' + item_material_id + ' class="applied_modifiers">';
					str += text; 
					if(k != '') {
						str += ' (' + k + 'k)';
					}
					str += '</span></li>';
				$('#applied_list').append(str);
			});	
	}
	
	$(document).ready(function() {
		//When an Applied Material is clicked...
		//we use 'live' because it hooks into the dom better than 'click'
		//see http://docs.jquery.com/Events/live for more details
		$('span.applied_modifiers').live("click", function() {
			id = $(this).attr('id'); //item_material_id

			//unappend from item from database
			var url = base_url + 'material/AJAX_removeMaterial';
			$.post(url, {
					item_material_id: id
				});
			
			//remove from list
			$(this).remove();			
		});

		$('span.orig_modifier').live('click', function() {

			var mod_id = $(this).attr('id').substring(5);
			var text = $(this).html();
			//test to see if karats are needed
			var k_url = base_url + 'material/AJAX_material_test_karat/' + mod_id;
			var k = false;
			$.get(k_url, {}, function(value, status){
					if(status == 'success') {
						if(value == 1) { //karats are found
							txt = "Please enter a karat amount. <br /> <input type='text' id='alertName' name='myname' />(k)";  
							$.prompt(txt, {
								buttons: {Save:true},
								submit: function(v,m,f) {
									an = m.children('#alertName');
									if(f.myname == '') {
										an.css("border", "solid 1px red");
										return false;
									}
									appendMaterial(text, mod_id, f.myname);									
									return true;
								}
							});
						}
						else {
							appendMaterial(text, mod_id, '');
						}
					}
					
				});
		});
	});
	</script>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Edit Materials for item: <?php echo $item_data['item_name'];?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/modifiers/' . $item_data['item_id'],'Go To Modifiers'); ?></li>
			<li>|</li>
		</ul>		
		<div class='image_area'>
			<?php if(sizeof($item_data['image_array']['external_images']) > 0): ?>
				<?php foreach($item_data['image_array']['external_images'] as $image): ?>
					<?php
						echo anchor('inventory/show_image/' . $image['image_id'] . '/external', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' />");
					?>
				<?php endforeach; ?>
			<?php elseif(sizeof($item_data['image_array']['internal_images']) > 0):?>
				<?php foreach($item_data['image_array']['internal_images'] as $image): ?>
					<?php
						echo anchor('inventory/show_image/' .$image['image_id'] . '/internal', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' /> ");
					?>
				<?php endforeach; ?>
			<?php else: ?>
				No Image Provided						
			<?php endif; ?>
		</div>
		<h3>Applied Materials</h3>
		<div id="applied_mods">
			<?php
				$applied_ids = array(0 => false); //setting 0 is a hack to make sure the first record is returned
			?>
			<ul id="applied_list">
			<?php foreach($material_data as $mat): ?>
				<?php $applied_ids[] = $mat['material_id']; ?>
				<li class='applied_list_item' id="applied_<?php echo $mat['material_id']; ?>">
					<?php if($mat['karat'] != ''): ?>
						<span id='<?php echo $mat['item_material_id']; ?>' class="applied_modifiers" ><?php echo $mat['material_name'] . ' (' . $mat['karat'] . 'k)';?>
					<?php else: ?>
						<span id='<?php echo $mat['item_material_id']; ?>' class="applied_modifiers" ><?php echo $mat['material_name'];?>
					<?php endif; ?>
					</span>
				</li> 
			<?php endforeach; ?>
			</ul>
		</div>
		<h3>Available Material</h3>
		<?php
			$chunk = (int) (count ($materials) / 4);
			$prev_start_char = 'none';
			$new_letter;
		?>
		<table width="100%">
			<tr>
				<?php for($startChunk = 0, $endChunk = $chunk, $chunkIndex = 0; $chunkIndex < 4; $chunkIndex++, $startChunk = $endChunk, $endChunk += $chunk + 1): ?>
					<?php $atColumnTop = false; ?>
					<td>
					<?php for($index = $startChunk; $index < $endChunk && $index < count($materials); $index++ ): ?>
						<?php if($index == $startChunk):?>
							<h3 class='h3_list'><?php echo htmlspecialchars( substr($materials[$index]['material_name'], 0, 1) ); ?></h3>
							<ul class='modifier_list'>
							<?php if($atColumnTop): ?>
								<?php $atColumnTop = false;?>
							<?php endif;?>
							<?php $prev_start_char = substr($materials[$index]['material_name'], 0,1); ?>
						<?php else: ?>
							<?php if($prev_start_char != substr($materials[$index]['material_name'], 0,1)):?>
								</ul>
								<h3 class='h3_list'><?php echo htmlspecialchars( substr($materials[$index]['material_name'], 0, 1) ); ?></h3>
								<ul class='modifier_list'>
							<?php endif;?>
							<?php $prev_start_char = substr($materials[$index]['material_name'], 0,1); ?>
						<?php endif;?>
							<?php if($materials[$index]['karats']): ?>
								<li> 
									<span class='orig_modifier' id='list_<?php echo $materials[$index]['material_id']; ?>' ><?php echo $materials[$index]['material_name']; ?></span>
								</li>
							<?php else: ?>
								<li> 
									<span class='orig_modifier' id='list_<?php echo $materials[$index]['material_id']; ?>' ><?php echo $materials[$index]['material_name']; ?></span>
								</li>						
							<?php endif;?>
					<?php endfor;?>
					<?php if(!$atColumnTop): ?>
						</ul>
					<?php endif;?>
					</td>
				<?php endfor;?>
			</tr>
		</table>
		
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>