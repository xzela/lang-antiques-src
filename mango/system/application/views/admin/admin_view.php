<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options</title>
	<style type="text/css">

	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<p>Here is where most of the administrative options are located. You can modify users, inventory items, and lookup lists.</p>
		<h2 class="admin_h2">System Options</h2>
			<h3 class="admin_h3">Administative System Options:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/company_information', snappy_image('icons/building_edit.png') . ' Change Company Information'); ?></li>
				<li><?php echo anchor('admin/company_logo', snappy_image('icons/picture_edit.png') . ' Change Company Logo'); ?></li>
			</ul>

		<h2 class="admin_h2">User Options</h2>
			<h3 class="admin_h3">Administative User Options:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/user_new', snappy_image('icons/vcard_add.png') . ' Add new User'); ?></li>
				<li><?php echo anchor('admin/user_deactivate', snappy_image('icons/vcard_delete.png') . ' Deactivate User'); ?></li>
				<li><?php echo anchor('admin/user_reactivate', snappy_image('icons/vcard.png') . ' Reactivate user'); ?></li>
				<li><?php echo anchor('admin/user_reset_password', snappy_image('icons/lock_edit.png') . ' Reset Users Password'); ?></li>
				<li><?php echo anchor('admin/user_change_privileges', snappy_image('icons/vcard_edit.png') . ' Change User Privileges'); ?></li>
			</ul>

		<h2 class="admin_h2">APIs</h2>
			<h3 class="admin_h3">Inventory APIs:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/google_products_feed', snappy_image('google_favicon.png') . 'View Google Product Feed (RSS)');?> | <?php echo anchor('admin/google_products_xml', snappy_image('icons/page_white_code.png') . 'Download (XML)'); ?></li>
				<li><?php echo anchor('admin/bing_product_feed', snappy_image('bing_favicon.png') . 'Download Bing Product File (CSV)'); ?></li>
			</ul>

		<h2 class="admin_h2">System Options</h2>
			<h3 class="admin_h3">Inventory Options:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/delete_item', snappy_image('icons/page_delete.png') . ' Delete Inventory Item'); ?></li>
				<li><?php echo anchor('admin/delete_item_history', snappy_image('icons/script_delete.png'). ' View Inventory Delete History'); ?></li>
			</ul>
			<h3 class="admin_h3">Invoice Options:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/delete_invoice', snappy_image('icons/page_delete.png') . ' Delete Invoice'); ?></li>
				<li><?php echo anchor('admin/delete_invoice_history', snappy_image('icons/script_delete.png'). ' View Invoice Delete History'); ?></li>
			</ul>
			<h3 class="admin_h3">Return Options:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/delete_return', snappy_image('icons/package_delete.png') . ' Delete Return'); ?></li>
				<li><?php echo anchor('admin/delete_return_history', snappy_image('icons/script_delete.png'). ' View Return Delete History'); ?></li>
			</ul>
			<h3 class="admin_h3">Customers Vendors Workshops:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/customer_delete', snappy_image('icons/user_delete.png') . 'Delete Customer')?></li>
				<li><?php echo anchor('admin/customer_store_credit_delete_history', snappy_image('icons/coins_delete.png') . 'Customer Store Credit Delete History')?></li>
				<li><?php echo anchor('admin/vendor_delete', snappy_image('icons/group_delete.png') . 'Delete Vendor'); ?></li>
				<li><?php echo anchor('admin/workshop_delete', snappy_image('icons/basket_delete.png') . 'Delete Workshop'); ?></li>
			</ul>
			<h3 class="admin_h3">Lookup List Options:</h3>
			<ul class="admin_ul">
				<li><?php echo anchor('admin/major_class_list', snappy_image('icons/page_white_code.png') . 'Change Major Classes'); ?></li>
				<li><?php echo anchor('admin/minor_class_list', snappy_image('icons/page_white_code_red.png') . 'Change Minor Classes'); ?></li>
				<li><?php echo anchor('admin/material_list', snappy_image('icons/medal_gold_1.png') . 'Change Materials'); ?></li>
				<li><?php echo anchor('admin/modifier_list', snappy_image('icons/brick.png') . 'Change Modifiers')?></li>
				<li><?php echo anchor('admin/stone_list', snappy_image('icons/ruby.png') . 'Change Stone'); ?></li>
				<li><?php echo anchor('admin/cuts_list', snappy_image('icons/ruby_gear.png') . 'Change Gemstone Cuts/Shapes'); ?></li>
				<li><?php echo anchor('admin/diamond_clarity_list', snappy_image('icons/contrast_low.png') . 'Change Diamond Clarity')?></li>
				<li><?php echo anchor('admin/diamond_color_list', snappy_image('icons/color_wheel.png') . 'Change Diamond Color')?></li>
			</ul>
		<h2>Website Options</h2>
			<h3 class='admin_h3'>Left Menu Options:</h3>
			<ul class='admin_ul'>
				<li><?php echo anchor('admin/menu_list', snappy_image('icons/application_side_list.png') . ' Change Menu Elements')?></li>
			</ul>
			<h3 class="admin_h3">Page Content Options:</h3>
			<ul class='admin_ul'>
				<li><?php echo anchor('admin/content_our_store', 'Edit Our Store Page Content'); ?></li>
				<li><?php echo anchor('admin/content_our_staff', 'Edit Our Staff Page Content'); ?></li>
				<li><?php echo anchor('admin/content_selling_jewelry', 'Edit Selling Jewelry Page Content'); ?></li>
			</ul>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>