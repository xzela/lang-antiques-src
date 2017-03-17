<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>The Decorative Periods - Lang Antiques</title>
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
				<?php echo anchor('/', 'Home'); ?> &gt; Decorative Periods
			</div>
			<h2 id='top_h2'>The Most Common Historial Decorative Periods</h2>
			<h3><?php echo anchor('pages/decorative-periods/georgain-period','Georgian Period - 1714 to 1830');?></h3>
			<p>
				The Georgian Period is the earliest jewelry that we have in our collection. 
				Until modern times gemstones, diamonds and precious metals were very rare and these materials were recycled into later styles of jewelry. 
				For this reason very little early Georgian jewelry survived. 
				Most Georgian jewelry available is from the later dates characterized by highly dimensional repouse. 
				Floral and scroll motifs are typical of the period. 
				Garnets, precious topaz, coral and early fully faceted diamonds set in silver were used.
                <?php echo anchor('pages/decorative-periods/georgain-period','(read more...)');?>
			</p>
			
			<h3><?php echo anchor('pages/decorative-periods/victorian-period', 'Victorian Period - 1835 to 1890'); ?></h3>
			<p>
				The Victorian Period is named for the reigning monarch who was reputedly an incurable romantic. 
				Victorian Jewelry virtually drips with sentimentality (she wore a bracelet worn from her children's teeth!) and symbolism. 
				Of all the periods Victorian incorporates the most varied and eclectic motifs. 
				Influences include Egyptian, Renaissance and Etruscan. 
				It is composed almost exclusively of yellow gold, often with contrasting black and cobalt blue enamel. 
				Diamonds were set in silver topped gold.
                <?php echo anchor('pages/decorative-periods/victorian-period', '(read more...)'); ?>
			</p>

			<h3><?php echo anchor('pages/decorative_periods/art-nouveau-period', 'Art Nouveau Period - 1890 to 1910'); ?></h3>
			<p>
				Natural forms inspired Art Nouveau Jewelry. 
				Female forms, dancers, nymphs, mermaids, water lilies, flowers, dragonflies, and flowing lines are recurrent motifs. 
				Colors were applied with fired enamels and quite often with plique azure, translucent enamel evoking stained glass.
                <?php echo anchor('pages/decorative_periods/art-nouveau-period', '(read more...)'); ?>
			</p>
			
			<h3><?php echo anchor('pages/decorative_periods/edwardian-period', 'Edwardian / Belle Epoch Period - 1890 to 1915'); ?></h3>
			<p>
				Edwardian jewels are delicate, romantic, feminine and lacy. 
				Edwardian and Belle Epoch jewels were usually composed of platinum and diamonds and often with natural pearls. 
				The workmanship is highly detailed, open and airy. 
				Bows and garlands were a popular theme.
                <?php echo anchor('pages/decorative_periods/edwardian-period', '(read more...)'); ?> 
			</p>
			
			<h3><?php echo anchor('pages/decorative_periods/art-deco-period', 'Art Deco Period - 1915 to 1935')?></h3>
			<p>
				Art Deco Jewelry depicts the modern age. 
				Designs are streamlined, geometric, symmetric, and highly stylized. 
				Think Empire State Building and Golden Gate Bridge. 
				This was also the time when the gemiest gemstones were being mined: Kashmir sapphires, Burmese Rubies, Old Mine Muzo Emeralds, and Lightning Ridge Opals.
                <?php echo anchor('pages/decorative_periods/art-deco-period', '(read more...)'); ?>
			</p>
			
			<h3><?php echo anchor('pages/decorative_periods/retro-period', 'Retro Period - 1940 to 1945'); ?></h3>
			<p>
				Retro Jewelry, we are back to gold and notably rose gold, due to the short supply of platinum that was required for the war. 
				I think of retro as the golden age of Hollywood glamour jewelry: Joan Crawford, Marlena Detrich, Greta Garbo. 
				There is nothing subtle or demure about it. 
				Over-sized, dramatic, deco-inspired designs, but very often asymmetrical and whimsical. 
				Rubies were all the rage but also many semi-precious stones were used: Citrine, Aqua, Amethyst and Moonstones in large sizes.
                <?php echo anchor('pages/decorative_periods/retro-period', '(read more...)'); ?>
			</p>
			
			<h3><?php echo anchor('pages/decorative_periods/fifties-period', 'Fabulous Fifties'); ?></h3>
			<p>
				Mid-Century (as in 1950's). 
				The fabulous Fifties! Mamie Eisenhower! 
				We are back to platinum and diamonds but seemingly in direct opposition to prewar Art Deco style. 
				Lots of abstract, free-form, linear and floral designs with overlapping and pav diamonds. 
				More flash that finesse.
                <?php echo anchor('pages/decorative_periods/fifties-period', '(read more...)'); ?>
			</p>
			
			<p>
				Many styles overlap decorative periods and there are no precise beginnings and endings to style trends.
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
