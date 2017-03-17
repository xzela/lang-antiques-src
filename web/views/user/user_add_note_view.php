<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Add a Note to Your Favorite Item - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Account Page. Please Enjoy Your Stay!" />
	
	<style type='text/css'>
		img.other {
			border: 1px solid #ddd;
			padding: 0px;
			margin: 0px;
			margin-right: 5px;
			margin-bottom: 5px;
			height: 150px;
			width: 150px;
		}
	</style>
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('user/user-account', 'Customer Account')?> &gt; <?php echo anchor('user/favorites', 'My Favorites'); ?> &gt; Add A Note
			</div>
			<h2 id='top_h2'>Add A Note to This Very Special Item</h2>
			<div class='item'>
				<h3><?php echo $item['item_name']; ?></h3>
				<?php foreach($item['images'] as $image ): ?>
					<img class='other' src='<?php echo $image['image_location']; ?>' />
				<?php endforeach; ?>
				<p><?php echo $item['item_description'] ?></p>
			</div>
			<div>
				<h3>Add Your Own Notes Here:</h3>
				<?php echo form_open('user/add-note/' . $item['item_number']);?>
					<input type='hidden' name='customer_id' value='<?php echo $this->session->userdata('customer_id'); ?>' />
					<input type='hidden' name='item_id' value='<?php echo $item['item_id']; ?>' />
					<textarea name='comment' rows='4' cols='80'><?php echo set_value('comment');?></textarea>
					<div>
						<input type='submit' value='Save Notes' />
					</div>
					
				<?php echo form_close();?>
			</div>
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
