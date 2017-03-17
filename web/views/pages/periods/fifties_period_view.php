<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>The Fifties Period - Lang Antiques</title>
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('pages/decorative-periods', 'Decorative Periods');?> &gt; The Fifties Period
			</div>
			<h2 id='top_h2'>Jewelry of the 1950's </h2>
			<p>
				Post war jewelry of the 1950's has only recently been classified and viewed with an historical perspective by jewelry scholars. 
				Jewelry of the 1950's depicted a birth of artistic diversity that continues to this day.
			</p> 

			<p>
				After the war, all aspects of popular culture changed. 
				In 1947, Christian Dior introduced a new look in fashion that brought femininity back in style. 
				Long, full-skirts, close-fitting waists and d'collet' necklines also called for a change in jewelry styles. 
				50's culture and society had revolutionary influences, like rock &amp; roll, television and the birth of the "Beat" Generation. 
				Jewelry design split in two directions. 
				The large fine jewelry houses and manufacturers produced traditional and classic styles of jewelry while there co-existed a growing community of avant garde studio artists producing new "modernist" designs. 
				Creativity and individuality were the slogans of the period.
			</p>
				<?php echo anchor('products/item/20-1-1382', snappy_image('inventory/20-1-1382.jpg', '20-1-1382', '', 'style="float: right; border: 0px;"')) ;?>
			<p>
				Economic and industrial growth produced a rapidly growing upper middle class. 
				The nouveau riche demanded luxury and were eager to flaunt prosperity. 
				Jewelry of the 1950's reflected the public's mood with a showy and often overly indulgent use of gemstones.
			</p>
			<p>
				DeBeers started to promote diamonds to every income level. 
				In 1948 they coined the slogan "Diamonds are Forever" and in 1954 introduced the annual Diamonds International Awards encouraging the use of diamonds in both casual and formal jewelry. 
				The Diamond International Awards promoted individual designers from around the world.
			</p> 
			<p>
				The large jewelry houses created lavish jewels "dripping" with diamonds and precious gems. 
				One of the greatest innovators and trend setters of the time was Harry Winston in New York. 
				He designed flexible settings that almost invisibly showcased and secured important diamonds, rubies, sapphires, emeralds and pearls. 
				He believed the gems to be the essential element of the jewel, and they ALONE dictated the design! 
				His breathtaking creations were spectacular and became enormously successful with his wealthy clientele! 
				Cartier, Van Cleef &amp; Arpels, Jean Schlumberger for Tiffany and Sterl produced opulent designs that also found a wide audience with the newly prosperous.
			</p>
			<?php echo anchor('products/item/20-1-1483', snappy_image('inventory/20-1-1483.jpg', '20-1-1483', '', 'style="float: left; border: 0px;"')) ;?>
			 
			<p>
				The motifs of 50's jewelry design may have been similar to pieces produced in the '40's, but they were executed in an entirely different way. 
				Retro Jewelry had a solid, heavy and highly polished look whereas 50's jewelry was open, airy and textural. 
				Florentine finishes were popular and braided and twisted wire accents, were preferred. 
				Yellow gold was the prominent metal except in all diamond jewels.
			</p> 
			<p>
				There was a trend for matching accessories, which fueled the popularity of complete sets or parures of '50's jewelry. 
				Suites of matching bracelets, earrings, rings and brooches were in vogue. 
				Popular motifs were animals, snowflakes, stars, bumble bees, butterflies, leaves and flowers with an increasing emphasis on gemstones and texture as focal points. 
				Short necklaces were singularly the most popular jewelry accessory seen in Opulent 50's Jewelry. 
				Choker length strands of graduated pearls were popular for both day and evening. 
				"Riviera" necklaces, chokers of graduated diamonds, were coveted for formal affairs.
			</p>
			<p>
				As mentioned earlier there was a modernist movement of Mid-Century American Studio Jewelers in the United States that had their inspiration in pre-war Europe. 
				The philosophies of Bauhaus, Dadaism, Surrealism, Isomorphism and Cubism could be seen in jewelry designed by a group of prominent European painters and sculptors. 
				Salvador Dali, Georges Braque, Pablo Picasso and Jean Cocteau were among the artists who inspired American Studio Jewelers to create "Jewelry as Art" or "Wearable Art", as it if often referred to. 
				The modernist's work was characterized by abstract and non-objective form. 
				Space does not permit me to fully describe nor pay tribute to the brilliant and often overlooked work of the Mid-Century American Studio Jewelers in the United States.
			</p>
			<p>
				Modernist jewelry is unique and quite specialized. 
				It does not appeal to everyone. 
				Creations by Mid-Century American Studio Jewelers were often one of a kind and totally hand fabricated. 
				There are a growing number of sophisticated collectors who have recently brought attention to this genre.
			</p>
			<p>
				The opulence, comfort and stability that characterized society in the post-war 50's was greeted with dissention, demonstrations and defiance in the 60's. 
				This brought about an abrupt end to jewelry designs flaunting luxury and prosperity. 
				The rejection of the establishment by the younger generation gave birth to dramatic changes in fashion, art, politics and every other aspect of our culture!
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
