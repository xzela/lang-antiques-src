<?php

?>
<!-- START HEADER -->
<div id="header">
	<div class='search_form' style="float: right; height: 25px;">
		Welcome <?php echo $user_data['user_name']; ?> - <?php echo $user_data['ip_address']; ?>
		<br />
		<form method='post' action='<?php echo base_url(); ?>search/quick_search'>
			<input name='search_string' type="text" /> <input type='submit' value='Search!'/>
		</form>
	</div>
	<h1><?php echo snappy_image('mango-icon.png'); ?><?php echo $this->config->item('project_name'); ?> &copy; <?php echo $this->config->item('version'); ?></h1>
</div>
<!-- END HEADER -->


<?php
/* End of file header.php */
/* Location: ./system/application/views/_global/header.php */
?>