<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>The Edwardian Design Period - Lang Antiques</title>
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('pages/decorative-periods', 'Decorative Periods');?> &gt; Edwardian Design Period
			</div>
			<h2 id='top_h2'>The Edwardian Design Period - 1901 to 1915</h2>
				<?php echo anchor('products/item/50-1-1580', snappy_image('inventory/50-1-1580.jpg', '50-1-1580', '', 'style="float: left; border: 0px;"'));?>
			<p>
				The Edwardian style of jewelry had its beginnings in the <?php echo anchor('products/type/victorian', 'Late-Victorian')?> era. 
				The princess of Wales had already become a trend setter with jewelry fashion. 
				The official beginning of the period was 1901, when Edward VII took the throne, at the age of 56. 
				The Edwardian Period was a time when elegance and fashion became society's predominant values. 
				Fashion, although inspired from the French courts of the eighteenth century, took on an almost ethereal lightness. 
				Layers of delicate lace and feathers were incorporated onto garments rendered in the palest pastels or white on white.
			</p>
				<?php echo anchor('products/item/50-1-1542', snappy_image('inventory/50-1-1542t.jpg', '50-1-1442', '', 'style="float: right; border: 0px;"')); ?> 
			<p>
				Diamonds were an essential ingredient in Edwardian Jewelry which represents some of the finest examples of diamond and platinum jewelry in existence. 
				Edwardian jewelry was made to look as light and delicate as possible, reflecting the femininity of the Edwardian lady. 
				This was the first time jewelry was made to be worn at night, lit by electricity, not candles!
			</p>
			<p>
				Advances made in platinum fabrication led to the creation of fine, delicate and sophisticated jewels, resembling diamond encrusted lace. 
				The strength of platinum was fully exploited. 
				It became possible to create jewels that resembled "petit point" embroidery. 
				Edwardian brooches, rings and pendants were often made in this fashion. 
				Milgraining was a typical decorative technique used throughout the Edwardian period. 
				It is a border of small balls or ridges around a setting or on the outer edges of the jewelry that gives a piece a softer and lighter look, similar to the edge of a coin.
			</p>
				<?php echo anchor('products/item/30-1-1260', snappy_image('inventory/30-1-1260t.jpg', '30-1-1260', '', 'style="float: left; border: 0px;"')); ?>
				<?php echo anchor('products/item/90-1-77', snappy_image('inventory/90-1-77.jpg', '90-1-77', '', 'style="float: right; border: 0px;"')); ?>
			<p>
				A number of examples of Edwardian Jewelry reflect <?php echo anchor('products/type/art-nouveau', 'Art Nouveau')?> lines, while others show an Art Deco influence with subtle geometric patterns. 
				That being said, Edwardian Jewelry was not as narcissistic as <?php echo anchor('products/type/art-nouveau', 'Art Nouveau jewelry')?>, nor as self absorbed as <?php echo anchor('products/type/art-deco', 'Art Deco')?> Jewels. 
				It had an understated elegance and noble opulence that reflected the tastes of the people it adorned.
				Some unique and very sweet jewels emerged at this time. 
				The n&eacute;glig&eacute;r pendant became fashionable. 
				This Edwardian necklace had two drops of unequal length, dangling from either a single stone or central element, all suspended from a very fine chain.
			</p>
			<p>
				The sautoir (or long necklace) was usually made of pearls and ended in a tassel. 
				An excellent example of a sautoir is pictured on the right. 
				This was one of the most fashionable of the Edwardian Jewels and a personal favorite of Queen Alexandra.
			</p> 
			<p>
				Stars, ribbons and bows were favorite motifs for Edwardian Jewelry. 
				Diamonds were the most popular gemstone of the period, but amethysts, peridots, demantoid garnets from the Urals, pale blue sapphires from Montana, unheated aquamarines displaying subtle green undertones and black opals from Australia were also favored. 
				These colored gems were frequently combined with tiny pearls or diamonds. 
				The ladidary arts became much more sophisticated in the early 1900's. 
				Calibre-cut rubies, emeralds, sapphires and amethysts were set with baguette, triangular, trapeze, and marquise shaped diamonds.
			</p>
			<p>
				Although Edward VII died in 1910, the "Edwardian" style continued until the outbreak of the war. 
				World War I put an abrupt end to the light hearted Edwardian spirit. 
				Life changed overnight and jewelry all but disappeared, either hid away in secure vaults or sold. 
				Precious metal became scarce and platinum, which was used in the manufacture of armaments, disappeared almost entirely from the market!
			</p>
			<p><span class='fake_button'><?php echo anchor('pages/decorative-periods', snappy_image('icons/arrow_left.png', 'left') . 'Back to Decorative Periods'); ?></span></p>
			
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
