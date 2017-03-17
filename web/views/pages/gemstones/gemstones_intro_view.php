<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Introduction to Diamonds - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>

	<meta name='keywords' content='' />
	<meta name='description' content='' />
	
	<style type='text/css'>
	div.gemstone_item {
	}
	div.gemstone_item img {
		border: 0px;
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
				<?php echo anchor('/', 'Home'); ?> &gt; Introduction to Gemstones
			</div>
			<h2 id='top_h2'>Gemstones, Fact, Fiction, Care &amp; Feeding</h2>
			<?php foreach($gemstones as $gemstone): ?>
				<div class='gemstone_item'>
					<h3><?php echo anchor('pages/gemstone-information/' . $gemstone['web_name'], snappy_image($gemstone['image_url']) . ' ' . $gemstone['stone_name']); ?></h3>
					<p>
						<?php $summary = preg_replace('/<[^>]*>/',"", $gemstone['description'])?>
						<?php if(strlen($gemstone['description']) > 300): ?>
							<?php echo substr($summary, 0, 300); ?> ...(<?php echo anchor('pages/gemstone-information/' . $gemstone['web_name'], 'more')?>)
						<?php else: ?>
							<?php echo $summary ?>
						<?php endif;?>
					</p>
				</div>
				
				
			<?php endforeach;?>
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
