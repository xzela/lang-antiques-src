<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>We Couldn't Find The Page You Were Looking For</title>
	<?php echo snappy_script('browser.selector.js');?>
	
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name='description' content="What a Shame. We Couldn't Find The Page You Were Looking For" />
	
	<style type='text/css'>

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
				<?php echo anchor('/', 'Home'); ?> &gt; Page Could Not Be Found 
			</div>
			<h2 id='top_h2'>Hmmm.... We Couldn't find the page you were looking for</h2>
			<h3>The Page You Were Looking For Could Not Be Found.</h3>
			<p>
				If you are having difficulty finding that special item, you may call us during our <a href='/contact-us/'>business hours</a> and we'll be more than happy to help you.
			</p>
			<p>
				You can also try using the links on the left side of our web site or you can search for an item using our search tool.
			</p>			
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
