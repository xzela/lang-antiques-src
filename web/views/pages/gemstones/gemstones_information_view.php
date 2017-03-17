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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('pages/introduction-to-gemstones', 'Introduction to Gemstones')?> &gt; <?php echo $gemstone['stone_name']; ?>
			</div>
			
			<h2 id='top_h2'>Gemstones, Fact, Fiction, Care &amp; Feeding for <?php echo $gemstone['stone_name']; ?></h2>
			<p><?php echo $gemstone['description']; ?></p>
			<h4>A Brief History for <?php echo $gemstone['stone_name'];?></h4>
			<p><?php echo $gemstone['history']; ?></p>
			<h4>The Metaphysical aspects for <?php echo $gemstone['stone_name'];?></h4>
			<p><?php echo $gemstone['meta']; ?></p>
			<h4>Gemological Information for <?php echo $gemstone['stone_name'];?></h4>
			<table id="gemstone_info_table">
				<tr>
					<td class="title">Color:</td>
					<td><?php echo $gemstone['color']; ?></td>
					<td class="title">Durability:</td>
					<td><?php echo $gemstone['durability']; ?></td>
				</tr> 
				<tr>
					<td class="title">Crystal Structure:</td>
					<td><?php echo $gemstone['crystal']; ?></td>
					<td class="title">Hardness:</td>
					<td><?php echo $gemstone['stone_hard']; ?></td>
				</tr> 
				<tr>
					<td class="title">Refractive Index:</td>
					<td><?php echo $gemstone['stone_ref']; ?></td>
					<td class="title">Family:</td>
					<td><?php echo $gemstone['stone_family']; ?></td>
				</tr> 
				<tr>
					<td class="title">Similar Stones:</td>
					<td colspan='3'><?php echo $gemstone['similar']; ?></td>
				</tr> 
				<tr>
					<td class="title">Treatment:</td>
					<td colspan='3'><?php echo $gemstone['treat']; ?></td>
				</tr> 
				<tr>
					<td colspan='4'><h4>Care of <?php echo $gemstone['stone_name']; ?></h4></td>
				</tr>
				<tr>
					<td class="title">Ultrasonic Cleaner:</td>
					<td><?php echo $gemstone['ultra']; ?></td>
					<td class="title">Chemicals:</td>
					<td><?php echo $gemstone['chemical']; ?></td>
				</tr>
				<tr>
					<td class="title">Steam Cleaner:</td>
					<td><?php echo $gemstone['steam']; ?></td>
					<td class="title">Sesitivity to Light:</td>
					<td><?php echo $gemstone['light']; ?></td>
				</tr>
				<tr>
					<td class="title">Warm Soapy Water:</td>
					<td><?php echo $gemstone['water']; ?></td>
					<td class="title">Sensitivity to heat:</td>
					<td><?php echo $gemstone['heat']; ?></td>
				</tr>								
			</table>
			<p><span class='fake_button'><?php echo anchor('pages/introduction-to-gemstones', snappy_image('icons/arrow_left.png', 'left') . 'Back to Gemstones'); ?></span></p>
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
