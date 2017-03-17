<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Share My Favorties - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Account Page. Please Enjoy Your Stay!" />
	
</head>
<body>
	<div id="container" >
		<span class="rtop">
			<b class="r1"></b>
			<b class="r2"></b>
			<b class="r3"></b>
			<b class="r4"></b>
		</span>
	<?php
		$this->load->view('components/header_view');
		$this->load->view('components/menu_view');
	?>
		<div id="content">
			<div class="breadcrumb">
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('user/user-account', 'Customer Account')?> &gt; <?php echo anchor('user/favorites', 'My Favorites')?> &gt; Share My Favorites
			</div>
			<h2 id='top_h2'>Share My Favorties At Lang Antiques</h2>
			<h3>Please Fill Out These Fields To Send Your Favoites list to a Friend</h3>
			<p>
				Think these items might be perfect for someone you know? 
				Fill out the following fields to send them what you've found!
			</p>
			<?php echo form_open('user/share-with-friends'); ?>
				<table class='form_table'>
					<tr>
						<td colspan='2'><?php echo validation_errors(); ?></td>
					</tr>
					<tr>
						<td class='title'>Your Name:</td>
						<td><?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name'];?></td>
					</tr>
					<tr>
						<td class='title'>Friends Name:</td>
						<td><input class='required' type='text' name='friend_name' value='<?php echo set_value('friend_name'); ?>' /><label class='required'>*</label></td>
					</tr>
					<tr>
						<td class='title'>Friends Email:</td>
						<td><input class='required' type='text' name='friend_email' value='<?php echo set_value('friend_email'); ?>'/><label class='required'>*</label></td>
					</tr>
					<tr>
						<td class='title'>Personal Message:</td>
						<td style='vertical-align: top;'><textarea name='personal_message' rows='4' cols='40'><?php echo set_value('personal_message'); ?></textarea></td>
					</tr>
					<tr>
						<td class='title'><?php echo $captcha; ?></td>
						<td>
							 <input class='required' type='text' name='math_captcha' size='5' /><label class='required'>*</label> (numbers only)
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input type='submit' value='Share Your List' /> </td>
					</tr>
				</table>
			<?php echo form_close(); ?>
			<div style="clear: both">&nbsp;</div>
		</div>
	<?php $this->load->view('components/footer_view.php'); ?>
		<span class="rbottom">
			<b class="r4"></b>
			<b class="r3"></b>
			<b class="r2"></b>
			<b class="r1"></b>
		</span>		
	</div>	
</body>
</html>
<?php
ob_flush();
?>
