<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Antique Jewelry And Watch Care - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name='description' content='' />
	
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
				<?php echo anchor('/', 'Home'); ?> &gt; Antique Jewelry And Watch Care 
			</div>
			<h2 id='top_h2'>How to Care for your Antique Jewelry And Watches</h2>
			<h4>General Care:</h4>
			<p>
				We encourage you to have your items checked by us or a local jeweler every 6 months to make sure all gemstones are tight and that the piece is in good condition to wear. 
				It is not uncommon for gemstones to become loose overtime and it is best to fix when they are loose instead of replacing a lost stone.
			</p>
			<h4>Cleaning:</h4>
			<p>
				Your jewelry will always look its best if it is clean, the diamonds and gemstones will sparkle like new.
				Most pieces can be cleaned with warm soapy water and an old soft toothbrush, but please contact us if you have any questions or concerns as some older pieces should never be submerged in water. 
				You are always welcome to stop in and have your rings cleaned at any time.
			</p>
			<h4>Rings:</h4>
			<p>
				Rings require special care as they are especially prone to wear. 
				Please do not wear rings to the gym, while doing housework, gardening, rock climbing or during any activity you feel may cause damage to your ring! 
				If you wear a chain you can string your ring on it while at the gym or doing a rough activity. 
				If you take your rings off at night you will double the lifetime of your ring!
			</p>
			<h4>Antiques:</h4>
			<p>
				The following list of unusual materials found in antique jewelry is by no means complete and all need special care. 
				Lockets, foilbacked stones, closed backed stones, organic material (wood, ivory, hair, tortoise), turquoise, coral, shell (cameos), mosaics, horn, bone, pearls, wings of insects (butterfly), feathers, fabric, iron, steel, jet, vulcanite, and bog oak. 
				Feel free to call if you need advice on cleaning any of these unusual materials.
			</p>
			<h4>Gemstones:</h4>
			<p>
				Special care must be taken with some gemstones. 
				Our website link to gemstone information including their care is: <?php echo anchor('pages/introduction-to-gemstones', 'Introduction To Gemstones'); ?> 
				The care instructions are at the bottom of each gemstone section.
			</p>
			<h2 id='top_h2'>Watch Care and Guarantee:</h2>
			<p>
				You have chosen a vintage XXX watch from the 19XX's. 
				The watch is mechanical so it needs to be fully wound daily. 
				Gently wind the crown in one direction until it comes to a natural stop. 
				To set the time, pull the crown out gently and this frees the hands. 
				Set the time in the clockwise direction. 
				This watch is not waterproof or water resistant. 
				We had the watch re-cleaned and lubricated before shipping and it is keeping time. 
				You may find that this watch does not keep time to the minute as a quartz movement would. 
				If it is gaining or losing time in excess of a few minutes in 24 hours please return the watch for adjustment. 
				We guarantee the watch for 6 months from the date of purchase. 
				Vintage watches do need to be cleaned periodically as they are mechanical and need lubrication. 
				We recommend every year or two. 
				Your watch usually tells you when it needs servicing. 
				Enjoy your watch!
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
