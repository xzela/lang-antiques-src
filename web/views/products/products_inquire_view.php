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
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo $page_data['breadcrumb']; ?> &gt; Request More Information 
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
			<h3>Please Fill Out These Fields</h3>
			<p>If you have any questions about this item, please address them here and we will get back to you as soon as possible.</p>
			<?php echo form_open('products/inquire/' . $item_data['item_number']); ?>
				<table class='form_table'>
					<tr>
						<td colspan='2'><?php echo validation_errors(); ?></td>
					</tr>
					<tr>
						<td class='title'>Name:</td>
						<td><input class='required' type='text' name='name' value='<?php echo set_value('name'); ?>' /><label class='required'>*</label></td>
					</tr>
					<tr>
						<td class='title'>Email:</td>
						<td><input class='required' type='text' name='email' value='<?php echo set_value('email'); ?>'/><label class='required'>*</label></td>
					</tr>
					<tr>
						<td class='title'>Phone:</td>
						<td>
							<input type='text' name='phone_number' value='<?php echo set_value('phone_number'); ?>' />
							<span class='light_text'>Not Required</span>
						</td>
					</tr>
					<tr>
						<td class='title'>Question:</td>
						<td style='vertical-align: top;'><textarea class='required' name='question' rows='4' cols='40'><?php echo set_value('question'); ?></textarea><label class='required'>*</label></td>
					</tr>
					<tr>
						<td class='title'><?php echo $page_data['captcha']; ?></td>
						<td>
							 <input class='required' type='text' name='math_captcha' size='5' /><label class='required'>*</label> (numbers only)
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input type='submit' value='Submit Question' /> </td>
					</tr>
				</table>
			<?php //echo form_close(); ?>
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
