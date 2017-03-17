<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Edit Item <?php echo $item_data['item_name']; ?></title>

	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.jeditable.css');?>

	<?php echo snappy_style('calendar.css'); //autoloaded ?>
	<?php echo snappy_script('calendar_us.js'); ?>

	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.jeditable.js'); ?>
	<?php echo snappy_script('jquery/jquery.conditional.js'); ?>

	<script type='text/javascript'>
		var base_url = <?php echo "'" . base_url() . "'"; ?>;
		var id = <?php echo $item_data['item_id']; ?>;
		var url = 'inventory/AJAX_updateItemField';


		//these are hard coded. that sucks!
		var gender_data = " {0:'Women',1:'Men',2:'Unisex'} ";
		var gender_json = [{'value':'Women'},{'value':'Men'},{'value':'Unisex'}]; //order: 0,1
		var isDirty = false;
        var showSavedMessage = true;
		var msg = 'The item description hasn\'t saved yet! \nClick "Stay on this Page" to save the changes. \nOnce you see the saved changes message everything should be a ok.';

        $(document).ready(function() {
		    window.onbeforeunload = function(){
		        if(isDirty) {
                    showSavedMessage = true;
		            return msg;
		        }
		    };
        });

        function update_publish_link() {
        	var span = $('')
        }

        </script>

	<?php echo snappy_script('inventory/jquery.inventory.js'); ?>
	<?php
		//Array used for category checkboxes...
		$yes_no_array = array(0 => "", 1 => "checked");
	?>
	<style>
		ul.item_submenu {
			border: 1px solid #cdcdcd;
			background-color: #efefef;
			padding: 2px;
			margin: 3px;
			white-space: normal;
		}

		.item_submenu li {
			display: inline;
			list-style-type: none;
			padding: 1px;
			margin: 1px;

		}

		.item_submenu img {
			border: 0;
			padding: 0;
			margin: 0;
			vertical-align: middle;
		}

		form.inplaceeditor-form{
			width: 250px;
		}
		#sysDiv {
            display: none;
		    background-color: #ffFFa1;
		    border: 1px dashed #ffcca8;
		    padding: 5px;
		    margin-top: 5px;
		    margin-bottom: 5px;
		    position: fixed;
		    top: 0px;
		    left: 15%;
		    font-weight: bold;
        }
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
        <div id='sysDiv'>Your changes to the Item Description have been saved. It is safe to navigate away from this page.</div>
		<h2 class='item'>Inventory View Item: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Inventory Main') ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/modifiers/' . $item_data['item_id'], snappy_image('icons/brick.png') . ' Modifiers') ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/materials/' . $item_data['item_id'], snappy_image('icons/medal_gold_add.png') . ' Materials') ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/images/' . $item_data['item_id'], snappy_image('icons/image_edit.png') . ' Images') ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/item_jobs/' . $item_data['item_id'], snappy_image('icons/basket_add.png') . ' View Jobs') ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/tags/' . $item_data['item_id'], snappy_image('icons/tag_blue.png') . ' Edit Tags')?></li>
		</ul>
		<div id='item_submenu_div'>
			<ul class='item_submenu'>
				<li><?php echo anchor('inventory/add', snappy_image('icons/page_add.png') . ' Create New Item'); ?></li>
				<li>|</li>
				<li><?php echo anchor('inventory/clone_item/' . $item_data['item_id'], snappy_image('icons/arrow_divide.png') . ' Clone Item'); ?></li>
				<?php if($item_data['is_assembled']):?>
					<?php if($item_data['assemble_type'] == 0 || $item_data['assemble_type'] == 2): //child?>
					<?php else: ?>
						<li>|</li>
						<li><?php echo anchor('inventory/assemble/' . $item_data['item_id'], snappy_image('icons/cog.png') . ' Assembly'); ?></li>
					<?php endif;?>
				<?php else:?>
					<li>|</li>
					<li><?php echo anchor('inventory/assemble_create/' . $item_data['item_id'], snappy_image('icons/cog.png') . ' Create Assembly'); ?></li>
				<?php endif;?>
				<li>|</li>
				<li><?php echo anchor('inventory/partnership/' . $item_data['item_id'], snappy_image('icons/report_user.png') . ' Partnerships')?></li>
				<li>|</li>
				<?php if($this->config->item('project') == 'fran'): ?>
	     			<li><?php echo anchor('inventory/push_to_lang/' . $item_data['item_id'], snappy_image('icons/database_link.png') . ' Push to Lang')?></li>
	     			<li>|</li>
	     			<li><?php echo anchor('inventory/create_database_link/' . $item_data['item_id'], snappy_image('icons/table_relationship.png') . ' Link with Lang')?></li>
	     			<li>|</li>
	     		<?php else: ?>
	     			<!-- <li><?php echo anchor('inventory/push_to_fran/' . $item_data['item_id'], snappy_image('icons/database_link.png') . ' Push to Fran')?></li>
	     			<li>|</li> -->
				<?php endif?>
				<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'], 'Change Status'); ?></li>
				<li>|</li>
				<li><?php echo anchor('inventory/comments/' . $item_data['item_id'] . '/staff', snappy_image('icons/comment.png') . 'Staff Comments'); ?></li>
				<li>|</li>
				<li><a href='http://www.langantiques.com/products/item/<?php echo $item_data['item_number']; ?>' target='_blank'> <?php echo snappy_image('icons/world.png')?> View Web Page</a></li>
			</ul>
		</div>
		<h3>
			Item Information:
			<span class="small_text">[<?php echo anchor('inventory/edit_history/' . $item_data['item_id'], 'Show Edit History') ?>]</span>
			<span class="small_text">[<?php echo anchor('inventory/invoice_history/' . $item_data['item_id'], 'Show Invoice History') ?>]</span>
		</h3>
		<table class='item_information'>
			<tr>
				<td colspan='4'><strong>Status &amp; Information:</strong> [<span id='a_show_extra' class='fake_link'>Show Extra Info</span>]</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td style='border: 1px solid #999; background-color: #fff;' colspan='3'>
					<ul class='alert_list'>
						<li>
							<?php echo $item_data['icon_status']; ?>
							<?php if($item_data['verification_date'] != ''): ?>
								<i>Last Verified on <?php echo date('m/d/Y', strtotime($item_data['verification_date'])); ?></i>
							<?php endif;?>
						</li>
						<?php if(sizeof($item_data['push_data']) > 0): ?>
							<li> <?php echo snappy_image('icons/table_relationship.png')?>Pushed to Lang</li>
						<?php endif;?>
						<?php if(isset($item_data['push_data']['inventory_sync_data']['out_sync']) && $item_data['push_data']['inventory_sync_data']['out_sync']): ?>
							<li style='background-color: #eff;' >
								<?php echo snappy_image('icons/exclamation.png')?>
								<span class='warning' style='text-decoration:blink;'>ITEM IS OUT OF SYNC!</span>
								[<?php echo anchor('inventory/sync_with_lang/' . $item_data['item_id'], snappy_image('icons/database_refresh.png') . ' Fix/See Differences')?>]</li>
						<?php endif; ?>
					</ul>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td colspan='3'>
					<div id='catalogue_info_div' style='display: none;'>
						<ul id='item_status_list' style='float:left;'>
							<li><strong>Tasks:</strong></li>
							<li><input class='category_checkbox' type='checkbox' name='state_basic_buy_info' <?php echo $yes_no_array[$item_data['state_basic_buy_info']];?> /> Basic Buy Info</li>
							<li><input class='category_checkbox' type='checkbox' name='state_scan' <?php echo $yes_no_array[$item_data['state_scan']];?>  /> Has Scan Image</li>
							<li><input class='category_checkbox' type='checkbox' name='state_full_catalogue' <?php echo $yes_no_array[$item_data['state_full_catalogue']];?> /> Full Catalogue</li>
							<li><input class='category_checkbox' type='checkbox' name='state_price' <?php echo $yes_no_array[$item_data['state_price']];?> /> Has Retail Price</li>
							<li><input class='category_checkbox' type='checkbox' name='state_internet_description' <?php echo $yes_no_array[$item_data['state_internet_description']];?> /> Internet Description</li>
							<li><input class='category_checkbox' type='checkbox' name='state_web_ready' <?php echo $yes_no_array[$item_data['state_web_ready']];?> /> Ready to Make Live</li>
							<li><strong>Google API</strong></li>
							<li><input class='category_checkbox' type='checkbox' name='api_new_condition' <?php echo $yes_no_array[$item_data['api_new_condition']];?> /> New Condition</li>
						</ul>
						<ul id='item_status_list' style='float: left;'>
							<li><strong>Locations:</strong></li>
							<li><input class='category_checkbox' type='checkbox' name='location_lang' <?php echo $yes_no_array[$item_data['location_lang']];?> /> Lang Antiques</li>
							<li><input class='category_checkbox' type='checkbox' name='location_fran' <?php echo $yes_no_array[$item_data['location_fran']];?> /> Frances Klein</li>
							<li><input class='category_checkbox' type='checkbox' name='location_vault' <?php echo $yes_no_array[$item_data['location_vault']];?> /> Vault</li>
							<li><input class='category_checkbox' type='checkbox' name='location_1stdibs' <?php echo $yes_no_array[$item_data['location_1stdibs']];?> /> 1st Dibs</li>
							<li><strong>Verification:</strong></li>
							<li>
								<form name='verification_date_form'>
									Verified On:
									<?php if($item_data['verification_date'] != ''): ?>
										<input id='verification_date_input' name="verification_date_input" type="text" value="<?php echo date('m/d/Y', strtotime($item_data['verification_date'])); ?>" />
									<?php else: ?>
										<input id='verification_date_input' name="verification_date_input" type="text" value="" />
									<?php endif;?>
									<script type="text/javascript">
									A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
									new tcal ({
										// form name
										'formname': 'verification_date_form',
										// input name
										'controlname': 'verification_date_input',
										// callback
										'callback': function (str_value) {
											var v_url = 'inventory/AJAX_updateItemField';
											$.post(base_url + v_url, {
												item_id : id,
												id: 'verification_date',
												type: 'date',
												value: str_value
											});
										}
									});
									</script>
								</form>
							</li>
						</ul>
						<div style='clear:both;'></div>
						<ul id='item_status_list'>
							<?php
								$lastmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
							?>
							<li><strong>Web Info:</strong></li>
							<li>Web Status:
								<?php if(!$item_data['web_status']):?>
									<?php if($item_data['item_price'] > 0 && $item_data['item_price'] == $item_data['item_price_check']): ?>
										<span class='warning'>Offline</span> - [<?php echo anchor('inventory/web_options/' . $item_data['item_id'] . '/1', 'Publish to Web'); ?>]
									<?php else: ?>
										<span class='warning'>Offline</span> - <span style='color: #999;'>Can't Publish Online until both Retail Price fields are greater than $0. Try refreshing.</span>
									<?php endif; ?>
								<?php else: ?>
									<span class='success'>Online</span> - [<?php echo anchor('inventory/web_options/' . $item_data['item_id'] . '/0', 'Remove from Web'); ?>]
								<?php endif; ?>
							</li>
							<li>Publish Date:
								<?php echo $item_data['publish_date'];?> -
								<?php if($lastmonth <= $item_data['publish_date']): ?>
									[<?php echo anchor('inventory/whats_new/' . $item_data['item_id'] . '/remove', 'Remove From What\'s New'); ?>]
								<?php else: ?>
									[<?php echo anchor('inventory/whats_new/' . $item_data['item_id'] . '/add', 'Add to What\'s New'); ?>]
								<?php endif; ?>
							</li>
						</ul>
						<ul id='item_status_list'>
							<li><strong>Photo Info:</strong></li>
							<li id='photo_queue_li'>
								Photo Queue:
								<?php if($item_data['photo_queue'] == 0): ?>
									<span id='photo_queue_span'>Not in Queue</span>
									[<a id='photo_queue_a' href='javascript:void(0);'>Add to Queue</a>]
								<?php elseif($item_data['photo_queue'] == 1):?>
									<span id='photo_queue_span' class='warning'>In Queue</span>
									[<a id='photo_queue_a' href='javascript:void(0);'>Remove from Queue</a>]
								<?php else: ?>
									<span id='photo_queue_span' class='success'>Photographed</span>
									[<a id='photo_queue_a' href='javascript:void(0);'>ReQueue</a>]
								<?php endif;?>
							</li>
							<li id='edit_queue_li'>
								Edit Queue:
								<?php if($item_data['edit_queue'] == 0): ?>
									<span id='edit_queue_span'>Not in Queue</span>
									[<a id='edit_queue_a' href='javascript:void(0);'>Add to Queue</a>]
								<?php elseif($item_data['edit_queue'] == 1):?>
									<span id='edit_queue_span' class='warning'>In Queue</span>
									[<a id='edit_queue_a' href='javascript:void(0);'>Remove from Queue</a>]
								<?php else: ?>
									<span id='edit_queue_span' class='success'>Edited</span>
									[<a id='edit_queue_a' href='javascript:void(0);'>ReQueue</a>]
								<?php endif;?>
							</li>
							<li><input class='category_checkbox' type='checkbox' name='high_res_image' <?php echo $yes_no_array[$item_data['high_res_image']];?> /> Needs High Res Image</li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan='4'><strong>Alerts &amp; Messages:</strong> [<span id='alerts_messages' class='fake_link' >Hide</span>]</td>
			</tr>
			<tr>
				<td></td>
				<td colspan='3' style='border: 1px solid #999; background-color: #fff;'>
					<div id='tr_alerts' style='display: visible;' >
						<ul class='alert_list'>
						<?php if($item_data['item_status'] == 0): //item is sold ?>
						<?php else: ?>
							<?php if($item_data['icon_web_status'] != false): ?>
								<li><?php echo  $item_data['icon_web_status']; ?></li>
							<?php endif; ?>
						<?php endif;?>
						<?php if($item_data['material_count'] < 1): //why don't i use greater than zero here? ?>
							<li class='warning'><?php echo snappy_image('icons/exclamation.png'); ?> No Materials are Applied</li>
						<?php endif;?>
						<?php if($item_data['modifier_count'] < 1):?>
							<li class='warning'><?php echo snappy_image('icons/exclamation.png'); ?> No Modifiers are Applied</li>
						<?php endif;?>

						<?php if($item_data['is_assembled'] == '1'):?>
							<?php if($item_data['assemble_type'] == '1'):?>
									<li>
										<?php echo snappy_image('icons/cog.png')?> This item has Assembly Data [<?php echo anchor('inventory/assemble/' . $item_data['item_id'], 'View Data')?>]
									</li>
							<?php else:?>
								<li>
									<?php echo snappy_image('icons/cog.png')?> This item has been assembled into: <?php echo anchor('inventory/edit/' . $item_data['assembly_data']['parent']['parent_item_id'], $item_data['assembly_data']['parent']['item_number']); ?>
								</li>
							<?php endif;?>
						<?php endif;?>

						<?php if(sizeof($partnerships) > 0): ?>
							<li>
								<?php echo snappy_image('icons/report_user.png')?> This item has partnerships [<?php echo anchor('inventory/partnership/' . $item_data['item_id'], 'View Partnerships'); ?>]
							</li>
						<?php endif;?>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan='4' ><strong>Images:</strong> [<span id='div_images' class='fake_link' >Hide</span>] [<?php echo anchor('inventory/image_edit_phrase/' . $item_data['item_id'], 'Edit Image Phrases'); ?>]</td>
			</tr>
			<tr>
				<td></td>
				<td colspan='3' style='border: 1px solid #999; background-color: #fff;'>
					<div id='image_area' class='image_area' style='margin: 0px; '>
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
				</td>
			</tr>
			<tr>
				<td class='title'>Item Number:</td>
				<td>
					<strong><?php echo $item_data['item_number']; ?></strong>
						[<?php echo anchor('inventory/reclass/' . $item_data['item_id'], 'Change Number/Class'); ?>]
				</td>
				<td class='title'>Entry By:</td>
				<td><?php echo mailto($entered_user_data['email'], $entered_user_data['first_name'] . ' ' . $entered_user_data['last_name']); ?> on <?php echo date("M d, Y", strtotime($item_data['entry_date'])); ?></td>
			</tr>
			<tr>
				<td class='title'>Major Class:</td>
				<td><?php echo $item_data['mjr_class_name']; ?></td>
				<td class='title'>Minor Class:</td>
				<td><?php echo $item_data['min_class_name']; ?></td>
			</tr>
			<tr>
				<td class='title'>Title:</td>
				<td colspan='3'>
					<div id='item_name' name='item_name' class='edit_title'><?php echo $item_data['item_name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>Description:</td>
				<td colspan='3'>
					<div id='item_description' class='textarea_edit' name='item_description' style='width: 600px;'><?php echo $item_data['item_description']; ?></div>
					[<?php echo anchor('inventory/format_editor/' . $item_data['item_id'], 'Edit with Formatting')?>]
				</td>
			</tr>
			<tr>
				<td colspan='4'><strong>Purchase Information:</strong></td>
			</tr>
			<tr>
				<td class='title' rowspan='2'>
					<?php if($item_data['min_class_id'] == 3): ?>
						Consigner:
					<?php else:?>
						Seller:
					<?php endif;?>
				</td>
				<?php if($item_data['seller_id'] != ''):?>
					<td rowspan='2' style='vertical-align: top;'>
						<?php echo anchor($item_data['seller_data']['link'], $item_data['seller_data']['name']); ?>
						[<?php echo anchor('inventory/seller/' . $item_data['item_id'] . '/edit', 'Edit Sale Details')?>]
						<br />Phone: <?php echo $item_data['seller_data']['phone']; ?>
						<?php if($item_data['min_class_id'] == 3 && $item_data['item_status'] != 7):?>
							<br /> [<?php echo anchor('inventory/return_consignee/' . $item_data['item_id'], 'Return To Consignee'); ?>]
						<?php endif;?>
					</td>
				<?php else:?>
					<td rowspan='2' style='vertical-align: top;'>No Seller information... [<?php echo anchor('inventory/seller/' . $item_data['item_id'] . '/add', 'Add Seller'); ?>]</td>
				<?php endif;?>
				<td class='title'>Purchase Date:</td>
				<td>
					<form name='inventory_purchase_date_form' >
						<?php if($item_data['purchase_date'] != ''): ?>
							<input id='purchase_date_input' name="purchase_date_input" type="text" value="<?php echo date('m/d/Y', strtotime($item_data['purchase_date'])); ?>" />
						<?php else: ?>
							<input id='purchase_date_input' name="purchase_date_input" type="text" value="" />
						<?php endif; ?>

						<script type="text/javascript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'inventory_purchase_date_form',
							// input name
							'controlname': 'purchase_date_input',
							// callback
							'callback': function (str_value) {
								$.post(base_url + url, {
									item_id : id,
									id: 'purchase_date',
									type: 'date',
									value: str_value
								});
							}
						});
						</script>
					</form>
				</td>
			</tr>
			<tr>
				<td class='title' style='color: green'>Purchase Price:</td>
				<td>
					<div id='purchase_price' name='purchase_price' class='edit_money'  >$<?php echo number_format($item_data['purchase_price'], 2); ?></div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td class='title'>Total Job Cost:</td>
				<td>$<?php echo number_format($item_data['item_job_cost'],2); ?></td>
			</tr>
			<tr>
				<td class='title'>Quantity:</td>
				<td>
					<div id='item_quantity' name='item_quantity' class='edit'><?php echo $item_data['item_quantity']; ?></div>
				</td>
				<td class='title warning'>Retail Price:</td>
				<td>
					<div id='item_price' name='item_price' class='edit_money' >$<?php echo number_format($item_data['item_price'], 2); ?></div>
					<div id='item_price_check' name='item_price_check' class='edit_money' >$<?php echo number_format($item_data['item_price_check'], 2); ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td></td>
				<td class='title'>Style Number:</td>
				<td>
					<div id='style_number' class='edit'><?php echo $item_data['style_number']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td></td>
				<td class='title'>Wholesale Price:</td>
				<td>
					<div id='wholesale_price' name='wholesale_price' class='edit_money'  >$<?php echo number_format($item_data['wholesale_price'], 2); ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>Modifiers: </td>
				<td colspan='3'>
					<div class='fake_input'>
					<?php if(sizeof($modifiers) > 0): ?>
						<?php foreach($modifiers as $mod):?>
							<?php echo $mod['modifier_name'];?>,
						<?php endforeach; ?>
					<?php else: ?>
						<span ><span class='warning'>No Modifiers Applied</span> [<?php echo anchor('inventory/modifiers/' . $item_data['item_id'],'Add Modifiers'); ?>]</span>
					<?php endif;?>
					</div>
				</td>
			</tr>
			<tr>
				<td class='title'>Materials: </td>
				<td colspan='3'>
					<div class='fake_input'>
					<?php if(sizeof($material) > 0): ?>
						<?php foreach($material as $mat):?>
							<?php if(trim($mat['karat']) != ''):?>
								<?php echo $mat['material_name'];?> (<?php echo $mat['karat']?>k),
							<?php else: ?>
								<?php echo $mat['material_name'];?>,
							<?php endif;?>
						<?php endforeach; ?>
					<?php else: ?>
						<span ><span class='warning'>No Materials Applied</span> [<?php echo anchor('inventory/materials/' . $item_data['item_id'], 'Add Materials')?>]</span>
					<?php endif; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td class='title' >Notes:</td>
				<td colspan='3' >
					<div id='item_notes' class='textarea_edit' style='width: 600px;'><?php echo $item_data['item_notes']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>More Info:</td>
				<td colspan='3'>[<a id='sub_item_info_a' style='font-size: 14px; font-weight: bold;' href='javascript:void(0);'>Show More info</a> <?php echo snappy_image('icons/resultset_down.png', '', '', 'id="more_info_id"'); ?>] </td>
			</tr>
		</table>

		<div id='sub_item_info_div' style='display: none;'>
		<table class='item_information'>
			<tr>
				<td class='title'>Gender:</td>
				<td>
					<?php
					$gender_data = array('0' => 'Women',
							'1' => 'Men',
							'2' => 'Unisex');
					?>
					<div id='gender' class='select_edit' ><?php echo $gender_data[$item_data['gender']]; ?></div>
				</td>
				<td class='title'>Ring Size:</td>
				<td>
					<div id='item_size' class='edit'><?php echo $item_data['item_size']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>Length:</td>
				<td>
					<div id='item_length' class='edit'><?php echo $item_data['item_length']; ?></div>
				</td>
				<td class='title' >Width:</td>
				<td>
					<div id='item_width' class='edit'><?php echo $item_data['item_width']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>Height:</td>
				<td>
					<div id='item_height' class='edit'><?php echo $item_data['item_height']; ?></div>
				</td>
				<td class='title'>Depth:</td>
				<td>
					<div id='item_depth' class='edit'><?php echo $item_data['item_depth']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title' >Weight:</td>
				<td>
					<div id='item_weight' class='edit'><?php echo $item_data['item_weight']; ?></div>
				</td>
				<td class='title'>Vendor Invoice:</td>
				<td>
					<div id='vendor_text' class='edit'><?php echo $item_data['vendor_text']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title' nowrap>Photo Notes:</td>
				<td colspan='3'>
					<div id='photo_notes' class='textarea_edit' style='width: 600px;'><?php echo $item_data['photo_notes']; ?></div>
				</td>
			</tr>
		</table>
		</div>
		<?php
			echo $this->load->view('inventory/components/gemstone_list_view');
		?>
		<p>Inventory Section of Project Mango</p>
	</div>

<?php
	$this->load->view('_global/footer');
?>

</body>
</html>