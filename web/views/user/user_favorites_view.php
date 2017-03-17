<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>My Favorties - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Account Page. Please Enjoy Your Stay!" />
	<style type='text/css'>
		.share_text {
			font-size: small;
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('user/user-account', 'Customer Account')?> &gt; My Favorites
			</div>
			<h2 id='top_h2'>My Favorties At Lang Antiques</h2>
			<?php if(sizeof($favories) > 0): ?>
				<h3>Here's a List of my Favorite Items at Lang Antiques <span class='share_text'>[<a href="share-with-friends">Share with Friends!</a>]</span></h3>
				<div id='listings'>
				<?php foreach($favories as $item):?>
					<div class='item'>
						<?php echo anchor('products/item/' . $item['item_number'], "<img class='image' src='" . base_url() . "images/thumbnails/150/" . $item['images'][0]['image_id'] . ".jpg' />");?>
						<h3><?php echo anchor('products/item/' . $item['item_number'], $item['item_name']); ?></h3>
						<p>
							<?php $summary = preg_replace("/<[^>]*>/","", $item['item_description']);?>
							<?php if(strlen($item['item_description']) > 300): ?>
								<?php echo substr($summary, 0, 300); ?> ...(<?php echo anchor('products/item/' . $item['item_number'], 'more')?>)
							<?php else: ?>
								<?php echo $summary ?>
							<?php endif;?>
						</p>
						<div>
							<?php if(($item['item_status'] != 1 && $item['item_status'] != 2) || $item['item_quantity'] <= 0): ?>
								<strong>This item has been sold</strong> - <?php echo anchor('products/item/' . $item['item_number'], 'View Item'); ?> &nbsp;&nbsp; #<?php echo $item['item_number']; ?>
							<?php else: ?>
								<strong>$<?php echo number_format($item['item_price'],2); ?></strong> - <?php echo anchor('products/item/' . $item['item_number'], 'View Item'); ?> &nbsp;&nbsp; #<?php echo $item['item_number']; ?>
							<?php endif;?>
						</div>
						<div>
							<?php echo form_open('user/remove-favorite');?>
								<input type='hidden' name='customer_id' value='<?php echo $customer_data['customer_id']?>' />
								<input type='hidden' name='item_id' value='<?php echo $item['item_id']?>' />
								<button class='button_link' type='submit'><?php echo snappy_image('icons/heart_delete.png');?> Remove From Favories</button>
							<?php echo form_close();?>
							<br />
							<?php echo anchor('user/add-note/' . $item['item_number'], snappy_image('icons/comment_add.png') . ' Add A Note'); ?>
						</div>
					</div>
					<div class='notes'>
						<?php if(sizeof($item['notes']) > 0): ?>
							<?php foreach($item['notes'] as $note): ?>
								<div class='note'>
									<span class='timestamp'><?php echo date('M d, Y - g:ia', strtotime($note['timestamp'])); ?></span>
									<div class='remove_note'>
										<?php echo form_open('user/remove-note'); ?>
											<input type='hidden' name='customer_id' value='<?php echo $customer_data['customer_id']; ?>' />
											<input type='hidden' name='item_id' value='<?php echo $item['item_id']; ?>' />
											<input type='hidden' name='comment_id' value='<?php echo $note['comment_id']; ?>' />
											<button class='button_link' type='submit'><?php echo snappy_image('icons/comment_delete.png');?> Remove This Note</button>
										<?php echo form_close(); ?>
									</div>
									<p><?php echo $note['comment']; ?></p>
								</div>
							<?php endforeach;?>
						<?php endif;?>
					</div>
				<?php endforeach;?>
				</div>
			<?php else: ?>
				<h3>No Favorites Saved Yet...</h3>
				<p>...try browsing our inventory to find some.</p>
			<?php endif;?>
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
