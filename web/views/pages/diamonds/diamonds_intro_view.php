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
				<?php echo anchor('/', 'Home'); ?> &gt; Introduction to Diamonds
			</div>
			<h2 id='top_h2'>Introduction to Diamonds</h2>		
			<h3>The 4 C's</h3>
			<p>As with modern cut diamonds we still valuate diamonds using the 4 Cs, Carat weight, Color, Clarity and Cut. </p>
			<h3>Carat</h3>
			<p>Diamonds are weighed in carats, the  first C. One carat is  equivalent to 1/5th of a gram. The carat measurement is metric so we further  divide a carat into hundredths. Each hundredth is called a point. More small  diamonds are found than large diamonds resulting in the higher price per carat  for successively larger diamonds due to this inherent rarity.</p>
			<h3>Clarity</h3>
			<p>The second C is clarity and refers  to the purity of the diamond. Diamonds are formed deep in the earth and are  subject to tremendous heat and pressure which cause internal inclusions and  external blemishes. Most diamonds have inclusions and key identifying  characteristics and very few are flawless. We identify and grade diamonds based  on these characteristics specific to their size, nature and location in/on the  stone. Diamonds are graded by a skilled observer with 10X magnification using a  binocular microscope or 10X loupe with optimum lighting conditions.&nbsp;</p>
			<p>The grading categories range from flawless to imperfect. </p>
			<table width="95%" border="1" bordercolor="#edeacc">
				<tr>
				    <td>Flawless</td>
				    <td>VVS1</td>
				    <td>VVS2</td>
				    <td>VS1</td>
				    <td>VS2</td>
				    <td>SI1</td>
				    <td>SI2</td>
				    <td>I1</td>
				    <td>I2</td>
				    <td>I3</td>
				</tr>
				<tr>
				    <td>Internally Flawless</td>
				    <td colspan="2">Very Very Slightly Included</td>
				    <td colspan="2">Very Slightly Included</td>
				    <td colspan="2">Slightly Included</td>
				    <td colspan="3">Included</td>
				</tr>
			</table>
			<h4>Flawless</h4>

			<p>Flawless diamonds must be free from internal inclusions and  external flaws.&nbsp; Internal graining not  visible face up and without discoloration&nbsp;  or small naturals on the girdle as long as they to not flatten the  girdle are acceptable in the flawless grade.</p>
			<h4>VVS</h4>
			<p>Very, Very Slightly Included Diamonds are just that.&nbsp; These diamonds usually require a microscope  to grade as the inclusions are very, very small and difficult for a skilled  observer to see.</p>

			<h4>VS</h4>
			<p>The Very Slightly Included grade is used for diamonds that have  very small internal and external characteristics that are difficult to locate  with 10X magnification and a skilled observer.</p>
			<h4>SI</h4>
			<p>Slightly Included diamonds have small to medium sized inclusions  that are obvious when examined with 10K magnification but usually not visible  to the naked eye.</p>
			<h4>I1, I2, I3</h4>
			<p>Imperfect diamonds have medium to large flaws that are usually  obvious to the unaided eye.&nbsp; Durability  may be an issue with the lower qualities. </p>

			<h3>Color</h3>

			<?php echo snappy_image('photos/diamonds_color.jpg'); ?>

			<table width="600">
				<!-- I did not write this horrible code you see below -ZL-->
			  <tr>
			    <td width="69" align="center">D E F</td>
			    <td width="72" align="center">G H I J</td>
			    <td width="79" align="center">K L M</td>

			    <td width="122" align="center">N O P Q R</td>
			    <td width="106" align="center">S T U V W</td>
			    <td width="61" align="center">X Y Z</td>
			    <td width="59" align="center"></td>
			  </tr>
			  <tr>
			    <td align="center">Colorless</td>
			    <td align="center">Near Colorless</td>
			    <td align="center">Faint Yellow</td>
			    <td align="center">Very Light <br /> Yellow</td>
			    <td align="center">Light<br /> Yellow</td>
			    <td align="center">Yellow</td>
			    <td align="center">Fancy Yellow</td>
			  </tr>
			</table>

			<p>Diamonds occur in all colors of the  rainbow however most diamonds range from colorless to light yellow or light  brown. We use the GIA grading scale for color. The top color is D which is  colorless. D, E and F are colorless to the human eye, G, H, I and J are near  colorless, K, L, and M are faint yellow and the alphabet continues to Z and  then diamonds have enough color to be called fancy. In order to determine the  specific color range of a diamond we use a controlled lighting environment and  master color grading diamonds for comparison. The chart above shows how little  difference there is from one color grade to the next. Because colorless  diamonds are purer with less chemical impurities to absorb light they are able  to reflected and refracted more white light resulting in a brighter diamond.  The more body color the diamond has the more light is absorbed. </p> 
			<h3>Cut</h3>
			<p>The word cut refers to the proportions and finish of a polished  diamond.</p>
			<p>The European cut and the modern round brilliant cut are more alike than different. Both cuts have 58 facets in the same configuration. The modern round brilliant is technically more precision cut than its European cut predecessor. I like to take a few steps back when discussing diamond cuts. Most diamonds start as an octahedron rough, an eight sided natural crystal resembling two 4 sided pyramids joined at the base.</p>
			<table class="center">
				<tr>
					<td>
						<?php echo snappy_image('photos/dia_crystal_shape.jpg'); ?>
					</td>
					<td>
						<?php echo snappy_image('photos/dia_crystal.jpg'); ?>
					</td>
				</tr>
				<tr>
					<td><div align="center">Octahedron</div></td>
					<td><div align="center">Rough Diamond</div></td>
				</tr>
			</table>
			<p>Until  the late 19th century diamonds were very rare and cut for maximum weight  retention from the rough shape. The old mine cut diamonds were chunky with a square  outline with a small table and large culet (top &amp; bottom facets) reflecting  the crystal shape that they were cut from.</p>
			<div style="text-align: center;">
				<?php echo snappy_image('photos/diamond_cuts.jpg'); ?>
			</div>
			<table width="550" border="0" align="center" cellpadding="0">
				<tr>
					<td width="135"><div align="center">Mine Cut </div></td>
					<td width="135"><div align="center">Old European Cut </div></td>
					<td width="135"><div align="center">Transitional Cut </div></td>
					<td width="135"><div align="center">Ideal Round Brilliant </div></td>
				</tr>
			</table>

			<p>By the  early 20th century cutters realized that the fire and brilliance of a diamond  is greatly increased when the proportions and angles between facets were of  certain dimensions making more beautiful diamonds. The modern cutting style was  facilitated by modern technology and some of the weight retention problems were  rectified with the advent of the diamond saw. The European cut was the earliest  versions of the new modern cut. The top, table, facet was still fashioned  smaller, the crown (top) of the diamond a bit heavier with steeper sides, the  pavilion main facets shorter and the culet larger than the current modern round  brilliant which can be cut to precision for perceived maximum reflection and  refraction of light. I say perceived because different environments and viewing  angles (to name two modifiers) can easily be different. The European cut, due  to its smaller table and greater crown area, breaks the light up and acts like  a prism so the diamond can scintillate with the fire of spectral colors when  rotated. The larger table size on the modern diamonds allows for more light to  be returned to the eye of the viewer directly without interference. This direct  light is known as brilliance.</p>

			<h4>Proportions:</h4>
			<p>The proportions are the angles, their measurements and their relationship to one another. The specific parts of the cut diamond measured are the table percentage  of the diameter, the crown angle, the pavilion angle, The total depth  percentage of the diameter, The crown height percentage of diameter, the  pavilion depth percentage of diameter, lower girdle facet percentage of  pavilion depth, the star facet percentage of the crown and the girdle  thickness.</p>
				<?php echo snappy_image('photos/diamond_proportions.jpg'); ?>
			<h4>Finish:</h4>
			<p>The finish is thequality of polish and the  symmetry of the diamond and all the facets. The optical attributes of a diamond are  Brightness, Fire and Scintillation.</p>
			<h4>Brightness:</h4>
			<p>Brightness, also called  brilliance is the effect of the internal and external reflection of white light. The proportions of the diamond  play the main role in determining the brightness.</p>
			<h4>Fire:</h4>
			<p>Fire refers to the  flashes of color resulting from the white light being dispersed into spectral  colors.</p>
			<h4>Scintillation:</h4>

			<p>Scintillation refers to the  areas of light and dark when viewing the top of the diamond.</p>
			<p>All these are factored with design and  craftsmanship to assign a cut grade of Excellent, Very Good, Good, Fair or Poor using the GIA Cut-grading System for round brilliant  cut diamonds.</p>

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
