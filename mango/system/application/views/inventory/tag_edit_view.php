<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Edit Printer Tag for <?php echo $item_data['item_number']; ?></title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Edit Printer Tag for <?php echo $item_data['item_number'];?> : <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<?php if($this->config->item('project') == 'fran'): ?>
				<li><?php echo anchor('printer/tag_printer/' . $item_data['item_id'], snappy_image('icons/printer.png') . 'Print Tag (Frangdango Only)', 'target="_blank"');?></li>
			<?php endif;?>
		</ul>
		<div>
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
        <h3>Item Information:</h3>
        <table class='form_table'>
            <tr>
                <td class='title'>Item Dscription:</td>
                <td><?php echo $item_data['item_description']; ?></td>
            </tr>
            <tr>
                <td class='title'>Item Price:</td>
                <td>$<?php echo number_format($item_data['item_price'], 2); ?></td>
            </tr>
            <tr>
                <td class='title'>Modifiers:</td>
                <td>
                  <?php if(sizeof($item_modifiers) > 0): ?>
                      <?php foreach($item_modifiers as $mod):?>
                          <?php echo $mod['modifier_name'];?>, 
                      <?php endforeach; ?>
                  <?php endif;?>
                </td>
            </tr>
            <tr>
                <td class='title'>Materials:</td>
                <td>
                    <?php if(sizeof($item_material) > 0): ?>
                        <?php foreach($item_material as $mat):?>
                            <?php if(trim($mat['karat']) != ''):?>
                                <?php echo $mat['material_name'];?> (<?php echo $mat['karat']?>k),
                            <?php else: ?>
                                <?php echo $mat['material_name'];?>,
                            <?php endif;?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
        <?php
            echo $this->load->view('inventory/components/gemstone_readonly_list_view'); 
        ?>

        
        <h3>Tag Fields:</h3>
		<?php echo form_open('inventory/tags/' . $item_data['item_id']);?>
		<table class='form_table'>
			<tr>
				<td class='title'>Item Number:</td>
				<td>
					<?php echo $item_data['item_number']; ?>
					<?php if(isset($tag_data['tag_id'])):?>
						<input type='hidden' name='tag_id' value='<?php echo $tag_data['tag_id']?>' />	
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'>Price:</td>
				<td>$<?php echo number_format($item_data['item_price'], 2); ?></td>
			</tr>
			<tr>
				<td class='title'>Line 1:</td>
				<td>
					<?php if($this->config->item('project') == 'fran'): ?>
						<input type='text' name='line_1' maxlength='16' value='<?php echo $tag_data['line_1']; ?>' />
					<?php else: ?>
						<input type='text' name='line_1' maxlength='16' value='<?php echo $tag_data['line_1']; ?>' />
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'>Line 2:</td>
				<td>
					<?php if($this->config->item('project') == 'fran'): ?>
						<input type='text' name='line_2' maxlength='16' value='<?php echo $tag_data['line_2']; ?>' />
					<?php else: ?>
						<input type='text' name='line_2' maxlength='16' value='<?php echo $tag_data['line_2']; ?>' />
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'>Line 3:</td>
				<td>
					<?php if($this->config->item('project') == 'fran'): ?>
						<input type='text' name='line_3' maxlength='16' value='<?php echo $tag_data['line_3']; ?>' />
					<?php else: ?>
						<input type='text' name='line_3' maxlength='16' value='<?php echo $tag_data['line_3']; ?>' />
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'>Line 4:</td>
				<td>
					<?php if($this->config->item('project') == 'fran'): ?>
						<input type='text' name='line_4' maxlength='16' value='<?php echo $tag_data['line_4']; ?>' />
					<?php else: ?>
						<input type='text' name='line_4' maxlength='16' value='<?php echo $tag_data['line_4']; ?>' />
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'>Line 5:</td>
				<td>
					<?php if($this->config->item('project') == 'fran'): ?>
						<input type='text' name='line_5' maxlength='16' value='<?php echo $tag_data['line_5']; ?>' />
					<?php else: ?>
						<input type='text' name='line_5' maxlength='16' value='<?php echo $tag_data['line_5']; ?>' />
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td>
					<input type='submit' name='save_tag' value='Update Tag' />
					<?php if(isset($tag_data['tag_id'])):?>
						| <?php echo anchor('inventory/tags_remove/' .  $item_data['item_id'] . '/' . $tag_data['tag_id'], 'Remove Tag')?>
					<?php endif;?>
				</td>
			</tr>
		</table>
		<?php echo validation_errors(); ?>
		<?php echo form_close();?>
		
		<?php if($this->config->item('project') == 'fran'): ?>
			<h2 class='warning'>About Printing Tags!</h2>
			<p class='warning'>
				You need to select the tag printer as your default printer.<br />
				Also, do a print preview and make sure the page size is set to 2 inches in width, and 1 inch in height. <br />
				If you do not check this you may end up printing out 7 or 8 empty tags. Which is bad for the environment and your checkbook. <br />
				<strong>VERY IMPORTANT!</strong><br />
				Make sure you disable the header and footer print fields in whatever browser you are using. Follow these instructions to <a href='http://www.mintprintables.com/print-tips/header-footer.php' target='_blank'>learn more</a>. 
			</p>
		<?php endif;?>
		<h3>Tags for This Item</h3>
		<table class='customer_table'>
			<tr>
				<th>Tag ID</th>
				<th>Item Number</th>
				<th>In Queue?</th>
				<th>Line 1</th>
				<th>Line 2</th>
				<th>Line 3</th>
				<th>Line 4</th>
				<th>Line 5</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($tags) > 0):?>
				<?php $yes_no = array(0=>'No', 1=>'Yes');?>
				<?php foreach($tags as $tag):?>
					<tr>
						<td><?php echo $tag['tag_id'];?></td>
						<td><?php echo $tag['item_number'];?></td>
						<td><?php echo $yes_no[$tag['active']];?></td>
						<td><?php echo $tag['line_1'];?></td>
						<td><?php echo $tag['line_2'];?></td>
						<td><?php echo $tag['line_3'];?></td>
						<td><?php echo $tag['line_4'];?></td>
						<td><?php echo $tag['line_5'];?></td>
						<td nowrap>
							<?php if($this->config->item('project') == 'fran'): ?>
								<?php echo anchor('printer/print_tag/' . $item_data['item_id'] . '/' . $tag['tag_id'], snappy_image('icons/printer.png') . ' Print Tag!'); ?><br />
							<?php endif;?>
							<?php echo anchor('inventory/tags_requeue/' .  $item_data['item_id'] . '/' . $tag['tag_id'], snappy_image('icons/arrow_rotate_clockwise.png') . ' Requeue Tag', 'class="green"'); ?><br />							
							<?php echo anchor('inventory/tags_remove/' .  $item_data['item_id'] . '/' . $tag['tag_id'], snappy_image('icons/cross.png') . ' Remove Tag', 'class="red"'); ?>
						</td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
			<tr>
				<td colspan='7'>No Tags Found</td>
			</tr>	
			<?php endif;?>
		</table>		
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>
</body>
</html>