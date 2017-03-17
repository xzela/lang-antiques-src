<!-- START MENU -->
<style type="text/css">
#menu ul.sub-menu {
	margin: 0px;
	padding: 0px;
	margin-top: 5px;
	margin-left: 16px;

	font-size: 11px;
}
</style>
<div id="menu">
	<ul>
		<li><?php echo snappy_image('icons/house.png'); ?> <?php echo anchor('main', 'Home'); ?></li>
		<li>
			<?php echo snappy_image('icons/page_white_ruby.png'); ?> <?php echo anchor('inventory', 'Inventory'); ?>
			<ul style='padding-bottom: 0px; margin-bottom: 0px;'>
				<li><?php echo snappy_image('icons/find.png'); ?><?php echo anchor('search', 'Adv. Search'); ?></li>
				<?php //@TODO: add inventory lists here; ?>
				<li><?php echo anchor('inventory_list/whats_new', 'Whats New');?></li>
				<li><?php echo anchor('inventory_list/list_available_items', 'List Available');?></li>
				<li><?php echo anchor('inventory_list/list_online_items', 'List Online');?></li>
				<li><?php echo anchor('inventory_list/list_sold_items', 'List Sold');?></li>
			</ul>
		</li>
		<li><div class='menu_div' ></div></li>
		<?php if($user_data['user_type'] == 4 || $user_data['user_type'] == 9): // user_data should be autoloaded?>
			<li><?php echo snappy_image('icons/camera.png'); ?> <?php echo anchor('photographer', 'Photographers'); ?></li>
			<li><div class='menu_div' /></div></li>
		<?php endif;?>
		<li><?php echo snappy_image('icons/user.png'); ?> <?php echo anchor('customer', 'Customers'); ?></li>
		<li><?php echo snappy_image('icons/group.png'); ?> <?php echo anchor('vendor', 'Vendors'); ?></li>
		<li>
			<?php echo snappy_image('icons/basket.png'); ?> <?php echo anchor('workshop', 'Workshops'); ?>
			<ul class="sub-menu">
				<li><?php echo anchor('workshop/pending_jobs', 'Pending Repair Queue'); ?></li>
				<li><?php echo anchor('workshop/inventory_jobs_all', 'Inventory Jobs')?></li>
			</ul>
		</li>
		<li><div class='menu_div' /></div></li>
		<li>
			<?php echo snappy_image('icons/money_dollar.png'); ?> <?php echo anchor('sales', 'Sales/Invoices'); ?>
			<ul class="sub-menu">
				<li><?php echo anchor('sales/search/memos', 'All Memos'); ?></li>
			</ul>
		</li>

		<li><?php echo snappy_image('icons/page_white_ruby.png'); ?> <?php echo anchor('appraisals', 'Appraisals'); ?></li>
		<li><?php echo snappy_image('icons/chart_bar.png'); ?> <?php echo anchor('reports', 'Reports'); ?></li>
		<li><div class='menu_div' /></div></li>
		<li><?php echo snappy_image('icons/vcard_edit.png'); ?> <?php echo anchor('user', 'User Options'); ?></li>
		<?php if($user_data['user_type'] >= 9): // user_data should be autoloaded?>
			<li><?php echo snappy_image('icons/application_form_edit.png'); ?> <?php echo anchor('admin', 'Admin Options'); ?></li>
		<?php endif;?>
		<li><div class='menu_div' /></div></li>
		<li><?php echo snappy_image('icons/door_open.png'); ?> <?php echo anchor('login/logout', 'Logout'); ?></li>
	</ul>
</div>


<!-- END MENU -->

<?php
/* End of file menu.php */
/* Location: ./system/application/views/_global/menu.php */
?>