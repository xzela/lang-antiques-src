<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title><?php echo $page_data['title']; ?> - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo $page_data['breadcrumb']; ?> &gt; Message Sent 
			</div>
			<h2 id='top_h2'><?php echo $page_data['h2']; ?> </h2>
			<table class='inqury_table'>
				<tr>
					<td><img src='<?php echo base_url(); ?>images/thumbnails/120/<?php echo $item_data['images'][0]['image_id'] ?>.jpg' /></td>
					<td class='text'>
						<?php echo $item_data['item_description']; ?>
						<br />
						<strong>Price:</strong> $<?php echo number_format($item_data['item_price'], 2); ?>					
					</td>
				</tr>
			</table>
			
			<h3><?php echo $page_data['message_title']?></h3>
			<p><?php echo $page_data['message_content']; ?></p>
			<p><span class='fake_button'><a href='javascript:history.go(-2)'><?php echo snappy_image('icons/arrow_left.png', 'left'); ?> Back to Item</a></span></p>			
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
