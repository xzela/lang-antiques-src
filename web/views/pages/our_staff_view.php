<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Our Staff in Downtown San Francisco - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>

	<meta name='keywords' content='' />
	<meta name="description" content="Expert Estate Jewelry Buying Staff and well qualified, GIA educated Antique and Estate Jewelry salespersons at Lang Antiques." />

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
				<?php echo anchor('/', 'Home'); ?> &gt; Our San Francisco Staff
			</div>
			<h2 id='top_h2'>Our San Francisco Staff</h2>

			<?php //echo $content; ?>

			<h3>Lang Antiques is more than a store full of beautiful jewelry.</h3>
			<p>
				We are a family of people with a passion for antique and estate jewelry, history, decorative arts and fine silver.
				We enjoy our relationships with our customers and matching them with our unique jewelry and objet.
				We have a very special niche as very few jewelers specialize in antiques and period jewelry.
				Our collection has been carefully curated and every piece of jewelry we sell has been carefully examined, the stones identified and graded, historically researched and evaluated, lovingly restored (if need be), photographed, and verbally described.
				Our family includes gemologists, historians, jewelers, photographers, stylists, sales people, artists, appraisers, and curators.
			</p>

			<h3>Suzanne</h3>

			<p>Suzanne is a highly credentialed senior gemologist, jewelry appraiser and the curator for our collection.</p>
			<p>Her jewelry credentials include:</p>

			<ul>
				<li>Graduate Gemologist Diploma received from the Gemological Institute of America in residence at Santa Monica December 1978</li>
				<li>GIA Alumni and Associates Member since 1981</li>
				<li>GIA Alumni Chapter officer since 1992</li>
				<li>GIA Alumni Global committee 1999 - 2005</li>
				<li>GIA Global Committee Chair 2005</li>
				<li>National Association of Jewelry Appraisers, Senior Member</li>
				<li>Accredited Gemologists of America Accredited Senior Gemologist</li>
				<li>Accredited Gemologists of America Certified Laboratory</li>
				<li>The American Society of Jewelry Historians, Member</li>
				<li>American Gem Trade Association, Member</li>
				<li>International Colored Stone Association, Member</li>
			</ul>


			<h3>Alison</h3>
			<p>Alison is in charge of cataloging our vast collection of jewelry in store and online. </p>
			<p>Her jewelry credentials include:</p>
			<ul>
				<li>BA, in Art History, with Honors received from Hamilton College in 1999</li>
				<li>Graduate Gemologist Diploma received from the Gemological Institute of America in residence at New York March 2002</li>
				<li>Double Check Diamond Grader, GIA New York Laboratory 2002-2003</li>
				<li>National Association of Jewelry Appraisers, Member</li>
				<li>The American Society of Jewelry Historians, Member</li>
			</ul>


			<h3>Barbra</h3>
			<p>Barbra Voltaire exudes a lifetime passion for gems and jewelry that borders on obsession.  She has been actively employed in every facet of the jewelry industry for the last 35 years.  </p>
			<p>Her jewelry credentials include:</p>
			<ul>
				<li>Bachelor's Degree in Geology, with an emphasis in the mineralogy of gemstones, Minor: Fine Art</li>
				<li>Graduate Gemologist, Deutsche Gemmologische Gesselschaft (German Institute for Advanced Professional Training in Gemmology)</li>
				<li>Graduate Gemologist, Gemological Institute of America</li>
				<li>Accredited Senior Gemologist, American Gemological Association </li>
				<li>American Society of Jewelry Historians</li>
				<li>National Association of Jewelry Appraisers</li>
				<li>Board Member of the Alumni Association of the Gemological Institute of America</li>
				<li>35+ Years Professional Experience in Jewelry Appraising, Fine Jewelry Buying, Diamond Grading, Gemstone Identification, Jewelry</li>
				<li>Design, Bench Work, Retail Management and Sales</li>
			</ul>

			<h3>Erin</h3>
			<p>Erin Beeghly currently researches and writes for Lang's <a href='http://www.langantiques.com/university/'>Antique Jewelry University</a>. She also helps out around Lang as needed. She has been a member of the staff since 2000.</p>
			<p>Her credentials include:</p>
			<ul>
				<li>Graduate Gemologist Diploma received from the Gemological Institute of America in residence at Carlsbad, March 1998.</li>
				<li>Insurance Appraisal Certificate received from the Gemological Institute of America, March 1998.</li>
				<li>Fifteen years experience in retail jewelry, including stints at Beeghly and Company, Tiffany and Company, Saks Fifth Avenue, and Lang Antiques.</li>
				<li>BA, in History, with High Honors received from University of California at Berkeley in 2004.</li>
				<li>BA, in Philosophy and Politics, First Class Degree, received from Oxford University in 2006.</li>
				<li>PhD in progress at University of California at Berkeley, Philosophy</li>
			</ul>

			<h3>Maria</h3>
			<p>Maria has had a contagious passion for jewelry during her long career in fine jewelry sales.  She loves helping her clients select just the right personal piece or gift from our collection.</p>
			<!--  <h3>Thomas</h3>
			<p>After 25 years as a jewelry designer, Thomas Picarella chose to merge his passion for photography with his love for jewelry. As our staff photographer and graphic designer he creates our web images and print ads.</p>
			-->
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
