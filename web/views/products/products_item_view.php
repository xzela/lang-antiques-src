<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="keywords" content="<?php echo $keyword_modifiers; ?>" />
	<title><?php echo $page_data['title']; ?> - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php $this->load->view('components/global.includes.php'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<?php echo snappy_script('jquery/jquery.parse.url.js');?>
	<script type='text/javascript'>
		var base_url = <?php echo "'" . base_url(). "'" ; ?>;
		var images = <?php echo json_encode($item_data['images']);?>

		$(document).ready(function() {
			$('.other').bind('mouseenter', function(e) {
				var src = $(this).attr('src');
				var url = parseUri(src);
				var file = url.file;
				//alert(file.length);
				var id = file.substring(0,file.length-4);
				for(i in images) {
					if(id == images[i].image_id) {
						$('#main_img').attr('src', base_url + images[i].image_location);
					}
				}

			});

			$('#main_img').bind('click', function(e) {
				history.go(-1);
			});
		});
	</script>
	<style type='text/css'>

		dl.detail_list .staff_name{
			color: #666;
		}
		dl.detail_list dt.staff_name {
			font-weight: normal;
		}
		dl.detail_list dt.staff_name span {
			font-weight: bold;
		}

		dl.detail_list .staff_comment {
			font-style: italic;
		}

		ul li.sold {
			font-weight: bold;
			color: #f00;
			border: 1px dashed #F99;
			padding: 5px;
			margin: 5px;
			background-color: #fee;
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo $page_data['breadcrumb']; ?>
			</div>
			<h2 id='top_h2'><?php echo $page_data['h2']; ?></h2>
			<div id='item_container'>
				<span id='other_images'>
					<?php foreach($item_data['images'] as $image ): ?>
						<img class='other' src='<?php echo base_url() . "images/thumbnails/100/" . $image['image_id']; ?>.jpg' alt='<?php echo $image['image_title']; ?>' />
					<?php endforeach; ?>
					<span id='item_functions'>
						<ul>
							<?php if (($item_data['item_status'] != 1 && $item_data['item_status'] != 2) || $item_data['item_quantity'] == 0): ?>
								<li><strong>This item has been sold.</strong></li>
							<?php else: ?>
								<li><strong>Price:</strong> $<?php echo number_format($item_data['item_price'], 2); ?></li>
							<?php endif;?>

							<li><?php echo anchor('products/inquire/' . $item_data['item_number'], snappy_image('icons/help.png','Inquire') . 'Inquire About This Item'); ?></li>
							<li><?php echo anchor('products/email-to-friend/' . $item_data['item_number'], snappy_image('icons/email.png','Email') . 'Email to a Friend'); ?></li>
							<li><?php echo anchor('products/printer/' . $item_data['item_number'], snappy_image('icons/printer.png','Print') . 'Print This Item', 'target="_blank"'); ?></li>


							<?php if($this->session->userdata('customer_id') != null):?>
								<?php if($item_data['is_favorite']): ?>
									<?php echo form_open('user/remove-favorite');?>
										<?php echo snappy_image('icons/heart_delete.png');?>
										<input type='hidden' name='customer_id' value='<?php echo $this->session->userdata('customer_id')?>' />
										<input type='hidden' name='item_id' value='<?php echo $item_data['item_id']?>' />
										<button class='button_link' type='submit'>Remove From Favorites</button>
									<?php echo form_close();?>
								<?php else: ?>
									<?php echo form_open('user/add-favorite');?>
									<?php echo snappy_image('icons/heart_add.png');?>
										<input type='hidden' name='customer_id' value='<?php echo $this->session->userdata('customer_id')?>' />
										<input type='hidden' name='item_id' value='<?php echo $item_data['item_id']?>' />
										<button class='button_link' type='submit'>Add As Favorites</button>
									<?php echo form_close();?>
								<?php endif;?>
							<?php else: ?>
								<?php echo form_open('user/add-favorite');?>
									<?php echo snappy_image('icons/heart_add.png');?>
									<input type='hidden' name='customer_id' value='<?php echo $this->session->userdata('customer_id')?>' />
									<input type='hidden' name='item_id' value='<?php echo $item_data['item_id']?>' />
									<button class='button_link' type='submit'>Add As Favorites</button>
								<?php echo form_close();?>
							<?php endif;?>
							<?php if (($item_data['item_status'] != 1 && $item_data['item_status'] != 2) || $item_data['item_quantity'] == 0): ?>
								<?php //do nothing?>
								<li class='sold'>This item is sold</li>
							<?php else: ?>
								<li><strong><?php echo anchor('products/add-to-cart/' . $item_data['item_number'], snappy_image('icons/cart_add.png','Add to Cart') . 'Add to Cart'); ?></strong></li>
							<?php endif;?>

							<li><strong>Inventory No. <?php echo $item_data['item_number'];?></strong></li>
							<li>
								<!-- AddThis Button BEGIN -->
								<div class="addthis_toolbox addthis_default_style">
									<a href="http://addthis.com/bookmark.php?v=250&amp;username=langantiques" class="addthis_button_compact">Share</a>
									<span class="addthis_separator">|</span>
									<a class="addthis_button_facebook"></a>
									<a class="addthis_button_twitter"></a>
									<a class="addthis_button_favorites"></a>
									<a class="addthis_button_myspace"></a>
								</div>
								<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=langantiques"></script>
								<!-- AddThis Button END -->
								<div style="padding: 2px; padding-top: 5px;">
									<a href="http://pinterest.com/pin/create/button/?url=<?php echo 'http://www.langantiques.com/products/item/' . $item_data['item_number']; ?>&media=<?php echo 'http://www.langantiques.com' . $item_data['images'][0]['image_location']; ?>&description=<?php echo $item_data['item_name']; ?>" class="pin-it-button" count-layout="none"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
									<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
								</div>

							</li>
						</ul>
					</span>
				</span>
				<span id="main_image">
					<img id='main_img' src='<?php echo $item_data['images'][0]['image_location']; ?>' width='500px' alt='<?php echo $item_data['images'][0]['image_title']; ?>' />
				</span>
				<?php if (($item_data['item_status'] != 1 && $item_data['item_status'] != 2) || $item_data['item_quantity'] == 0): ?>
					<p><strong class='warning'>This item has already been sold and is no longer available.</strong> We maintain the Archive for research purposes only. These items have been sold and the sales price is <strong>confidential</strong>. Please click <?php echo anchor('/','here'); ?> to go to our home page to search our current collection.</p>
				<?php endif;?>
				<p><?php echo $item_data['item_description']; ?></p>
				<?php if($item_data['mjr_class_id'] == 10 || $item_data['mjr_class_id'] == 30 || $item_data['mjr_class_id'] == 110): ?>
					<p>*Unless noted, almost all rings can be sized to fit. We include the first sizing with purchase.</p>
				<?php endif;?>
			</div>
			<div style='clear: right;'>
				<strong>Inventory No. <?php echo $item_data['item_number']?></strong>
			</div>
			<div id='gemstone_details'>
				<?php if(sizeof($gemstone_info) > 1): ?>
					<?php if(isset($gemstone_info['center']) && sizeof($gemstone_info['center']) > 0): ?>
						<?php foreach($gemstone_info['center'] as $center): ?>
							<h3>Center <?php echo $center['stone_name']; ?> Details</h3>
							<dl class='detail_list'>
								<?php if($center['carats'] > 0): ?>
									<dt>Carat Weight:</dt>
										<dd><?php echo number_format($center['carats'],2);?> cts</dd>
								<?php endif;?>
								<?php if($center['quantity'] > 1): ?>
									<dt>Quantity: </dt>
										<dd><?php echo $center['quantity'];?></dd>
								<?php endif;?>
								<?php if($center['x1'] != 0): ?>
									<dt>Measurements:</dt>
									<?php if($center['is_ranged']): ?>
										<dd><?php echo $center['x1'] . ' - ' . $center['x2'];?> mm</dd>
									<?php else: ?>
										<?php if(isset($center['x3'])): ?>
											<dd><?php echo $center['x1'] . ' x ' . $center['x2'] . ' x ' . $center['x3'];?> mm</dd>
										<?php else: ?>
											<dd><?php echo $center['x1'] . ' x ' . $center['x2']; ?></dd>
										<?php endif;?>

									<?php endif;?>
								<?php endif;?>
								<?php if($center['cut_name'] != ''): ?>
									<dt>Cut/Shape:</dt>
										<dd><?php echo $center['cut_name'];?></dd>
								<?php endif;?>
								<?php if(isset($center['clarity']) && $center['clarity'] != ''): ?>
									<dt>Clarity:</dt>
										<dd><?php echo $center['clarity'];?></dd>
								<?php endif; ?>
								<?php if(isset($center['clarity']) && $center['color'] != ''): ?>
									<dt>Color:</dt>
										<dd><?php echo $center['color']; ?></dd>
								<?php endif;?>
								<?php if($center['grade_report'] != ''): ?>
									<dt>Grading Report:</dt>
										<dd><?php echo $center['grade_report']; ?></dd>
								<?php endif;?>
							</dl>
						<?php endforeach;?>
					<?php endif;?>
					<?php if(isset($gemstone_info['additional']) && sizeof($gemstone_info['additional']) > 0):?>
						<?php if(!isset($gemstone_info['center'])): ?>
							<h3>Gemstone Details</h3>
						<?php else: ?>
							<h3>Additional Gemstone Details</h3>
						<?php endif;?>
						<dl class='detail_list'>
							<?php foreach($gemstone_info['additional'] as $additional): ?>
								<?php if($additional['quantity'] > 1 && $additional['plural_name'] != ''): ?>
									<dt><?php echo $additional['plural_name']?></dt>
								<?php else: ?>
									<dt><?php echo $additional['stone_name']?></dt>
								<?php endif;?>
								<dd>
									<?php echo $additional['quantity']; ?> <?php echo $additional['cut_name']; ?>
									<?php if($additional['quantity'] > 1 && $additional['plural_name'] != ''): ?>
										<?php echo $additional['plural_name']?>
									<?php else: ?>
										<?php echo $additional['stone_name']?>
									<?php endif;?>
									<br />
									<?php if($additional['carats'] > 0): ?>
										<?php echo $additional['stone_name']?> Total Carat Weight: <?php echo number_format($additional['carats'], 2); ?> carats
									<?php endif;?>

									<?php if($additional['template_type'] == 3): ?>
									 	<?php if(isset($additional['clarity']) && $additional['clarity'] != ''): ?>
									 		<br /> Diamond Clarity: <?php echo $additional['clarity']; ?>
									 	<?php endif;?>
									 	<?php if(isset($additional['color']) && $additional['color'] != ''): ?>
									 		<br /> Diamond Color: <?php echo $additional['color']; ?>
									 	<?php endif;?>
									 <?php endif; ?>
								</dd>
							<?php endforeach;?>
						</dl>
					<?php endif;?>
					<?php if($gemstone_info['total_diamond_weight'] > 0): ?>
						<dl class='detail_list'>
							<dt>Total Diamond Carat Weight:</dt>
							<dd><?php echo number_format($gemstone_info['total_diamond_weight'], 2); ?> cttw</dd>
						</dl>
					<?php endif;?>
				<?php endif;?>
			</div>
			<?php if(sizeof($details) > 0): ?>
				<div id='product_details'>
					<h3>Product Details:</h3>
					<dl class='detail_list'>
						<?php if(sizeof($details['materials']) > 0): ?>
							<dt>Materials:</dt>
							<dd>
								<?php foreach($details['materials'] as $material): ?>
									<?php if($material['karat'] != null): ?>
										<?php echo $material['karat']; ?> Karat
									<?php endif;?>
									<?php echo $material['material_name']; ?>
									<br />
								<?php endforeach;?>
							</dd>
						<?php endif;?>
						<?php if(sizeof($details['dimensions']) > 0): ?>
							<?php foreach($details['dimensions'] as $key => $value): ?>
								<dt><?php echo $key; ?>:</dt>
								<dd><?php echo $value; ?></dd>
							<?php endforeach;?>
						<?php endif; ?>
						<?php if(sizeof($modifiers) > 1): ?>
							<dt>Found In The Following Categories: </dt>
							<dd>
							<?php foreach($modifiers as $mods):?>
								<span><?php echo anchor('products/type/' . $mods['element_url_name'], $mods['modifier_name'])?></span><br />
							<?php endforeach;?>
							</dd>
						<?php endif;?>
					</dl>
					<?php if(sizeof($details['staff_comments']) > 0) :?>
						<h3>Staff Comments: </h3>
						<dl class='detail_list'>
						<?php foreach($details['staff_comments'] as $comment): ?>
							<dt class='staff_name'><span><?php echo $comment['staff_name']; ?></span> says: </dt>
							<dd class='staff_comment'><?php echo $comment['comment']; ?></dd>
						<?php endforeach;?>
						</dl>
					<?php endif;?>
				</div>
			<?php endif;?>
			<?php if(sizeof($similar_items) > 0): ?>
				<h3 style='clear: both;'>People who liked this <?php echo $item_data['item_name']?>, also liked:</h3>
				<div id='similar_items'>
					<?php foreach($similar_items as $item): ?>
						<?php echo anchor('products/item/' . $item['item_number'], "<img class='image' src='" . base_url() . $this->config->item('index_page') ."/images/thumbnails/125/" . $item['images'][0]['image_id'] . ".jpg' /> ");?>
					<?php endforeach;?>
				</div>
			<?php endif; ?>
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
