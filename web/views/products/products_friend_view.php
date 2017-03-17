<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>Request More Informabout About <?php echo $page_data['title']; ?> - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php $this->load->view('components/global.includes.php'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {
		});
	</script>

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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo $page_data['breadcrumb']; ?> &gt; Send to a Friend
			</div>
			<h2 id='top_h2'><?php echo $page_data['h2']; ?> <span class='normal'>#<?php echo $item_data['item_number']?></span></h2>
			<table class='inqury_table'>
				<tr>
					<td><img src='<?php echo base_url(); ?>images/thumbnails/120/<?php echo $item_data['images'][0]['image_id'] ?>.jpg' /></td>
					<td class='text'>
						<?php echo $item_data['item_description']; ?>
						<br />
						<?php if(($item_data['item_status'] != 1 && $item_data['item_status'] != 2) || $item_data['item_quantity'] <= 0): ?>
							<strong>This item has been sold</strong>
						<?php else: ?>
							<strong>Price:</strong> $<?php echo number_format($item_data['item_price'],2); ?>
						<?php endif;?>
					</td>
				</tr>
			</table>
			<div class='clear'>&nbsp;</div>
			<h3>Please Fill Out These Fields To Send This Item to a Friend</h3>
			<p>
				Think this <?php echo anchor('products/item/' . $item_data['item_number'], $item_data['item_name']); ?> might be perfect for someone you know?
				Want to show your loved ones this treasures?
				Fill out the following fields to send them what you've found!
			</p>
			<?php echo form_open('products/email-to-friend/' . $item_data['item_number']); ?>
				<table class='form_table'>
					<tr>
						<td colspan='2'><?php echo validation_errors(); ?></td>
					</tr>
					<tr>
						<td class='title'>Your Name:</td>
						<td><input class='required' type='text' name='your_name' value='<?php echo set_value('your_name'); ?>' /><label class='required'>*</label></td>
					</tr>
					<tr>
						<td class='title'>Your Email:</td>
						<td><input class='required' type='text' name='your_email' value='<?php echo set_value('your_email'); ?>'/><label class='required'>*</label></td>
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
						<td class='title'><?php echo $page_data['captcha']; ?></td>
						<td>
							 <input class='required' type='text' name='math_captcha' size='5' /><label class='required'>*</label> (numbers only)
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input type='submit' value='Send to Friend' /> </td>
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
