<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<title>Edit Web Phrase: <?php echo $this->config->item('project_name'); ?></title>
	
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.timer.js'); ?>
	
	<script type='text/javascript'>
		var base_url = <?php echo "'" . base_url() . "';"; ?> 
		var item_id = <?php echo $item_data['item_id'] . ";"; ?>
		
	function timeOut() {
			
	}
		
		$(document).ready(function() {
			$('.img_text').bind('blur', function() {
				var id = $(this).attr('id');
				var url = base_url + 'inventory/AJAX_updateImage';
				var value = $(this).val();

				if(value.length > 256) {
					$('#'+id+'_error').html('The Title can\'t be larger than 256 chars');
				}
				else {
					$.post(url, {
							image_id: id,
							image_title: value
						}, 
					function(e) {
						var msg = $('#'+id+'_error');
						msg.slideDown('slow');
						msg.html('Saved...');
						//$('#'+id+'_image_title').val(e);
						$.timer(4000, function() {
							msg.slideUp('slow');
						});
					});
				}
			});
			//selects the current textbox which has fucus;
			$('.img_text').bind('focus', function() {
				var id = $(this).attr('id');
				$('#r_' + id).click();
			});
			$('.keyword').bind('click', function() {
				var word = $(this).html();
				$('.radio').each(function() {
					if($(this).attr('checked')) {
						var id = $(this).attr('id').substring(2);
						var t = $('#' + id);
						t.val(t.val() + word + ' ');
						t.trigger('blur');
					}
						
				});
			});
	
			$('.clear_link').bind('click', function() {
				var id = $(this).attr('id').substring(2);
				var t = $('#'+id); 
				t.val('');
				t.trigger('blur');
			}); 
			
		});
	</script>
	<style type='text/css'>
		#keyword_list {
			float: right;
			background-color: #fff;
			border: 1px dashed #999;
			width: 250px;
		}
		#keyword_list ul {
			list-style: none;
			padding: 5px;
			margin: 2px;
		}
		#keyword_list ul li.keyword {
			text-decoration: none;
			color: #0033FF;
			cursor: pointer;
		}
		#keyword_list ul li.keyword:hover {
			text-decoration: underline;
			color: #3399FF;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Image View</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/upload_external_image/' . $item_data['item_id'], 'Upload Web Images'); ?></li>
		</ul>
		<p>
			Update the Image title here. You can only use 256 Characters. Also, no special characters. Letters, Numbers, and Spaces only.
		</p>		
		
		
		<h3>Item Description:</h3>
		<p><?php echo $item_data['item_description']; ?></p>
		<div id='keyword_list'>
			<h3>Key Words</h3>
			<ul style='float: right;'>
				<li class='keyword'>Diamond</li>
				<li class='keyword'>Diamond and</li>
				<li class='keyword'>Emerald</li>
				<li class='keyword'>Garnet</li>
				<li class='keyword'>Gold</li>
				<li class='keyword'>Pearl</li>
				<li class='keyword'>Platinum</li>
				<li class='keyword'>Sapphire</li>
				<li class='keyword'>Ruby</li>
				
			</ul>			
			<ul style='float: left;'>
				<li class='keyword'>Antique</li>
				<li class='keyword'>Art Deco</li>
				<li class='keyword'>Art Nouveau</li>
				<li class='keyword'>Edwardian</li>
				<li class='keyword'>Estate</li>
				<li class='keyword'>Georgian</li>
				<li class='keyword'>Retro</li>
				<li class='keyword'>Victorian</li>
				<li class='keyword'>Vintage</li>
				<li>&nbsp;</li>
				<li class='keyword'>Brooch</li>
				<li class='keyword'>Bracelet</li>
				<li class='keyword'>Earrings</li>
				<li class='keyword'>Ring</li>
				<li class='keyword'>Engagement Ring</li>
				<li class='keyword'>Jewelry</li>
				<li class='keyword'>Necklace</li>
				<li class='keyword'>Pin</li>
				<li class='keyword'>Watch</li>
				<li class='keyword'>Wedding Band</li>
			</ul>
		</div>		
		<table class='form_table' >
			<tr>
				<th>Image</th>
				<th>Name</th>
				<th></th>
				<th>Option</th>
			</tr>
			<?php if(sizeof($item_data['image_array']['external_images']) > 0): ?>
				<?php foreach($item_data['image_array']['external_images'] as $image): ?>
					<tr>
						<td>
							<?php echo "<img src='" . base_url() . "system/application/views/_global/thumbnail.php?image_location=" . $image['image_location'] . "&image_type=" . $image['image_class'] . "&image_size=" . $image['image_size'] . "' />"; ?>
						</td>
						<td>							
							<textarea id='<?php echo $image['image_id'];?>' class='img_text' cols='30' rows='3'><?php echo $image['image_title']; ?></textarea>
						</td>
						<td>
							<input class='radio' id='r_<?php echo $image['image_id'];?>' type='radio' name='buttons' value='<?php echo $image['image_id']; ?>' />Selected
							<br />
							<div id='<?php echo $image['image_id'];?>_error' class='warning'></div>
						</td>
						<td>
							<input id='<?php echo $image['image_id'];?>' class='img_save' type='button' value='Save' />
							<br />
							<span>[<a class='clear_link' id='a_<?php echo $image['image_id']; ?>' href='#'>Clear Text</a>]</span>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan='4'>No Image Provided</td>
				</tr>
			<?php endif; ?>
		</table>			
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>