
<!-- START OF FOOTER_VIEW -->
<div id="footer" >
	<?php /* failed footer image ?>
	<div style='padding-left: 125px;'>
		<center>
			<img class='image_foot' src='/web/assets/images/image_footer.png' />
		</center>
	</div>
	<?php */ ?>

	<p style="border-top: 1px solid #ddd;">Lang Antiques - 323 Sutter Street - San Francisco, CA 94108 - (415) 982-2213</p>
	<p class='bottom'>&copy; 2001-<?php echo date('Y'); ?> Lang Antiques, All Rights Reserved.</p>
	<div class="lower_nav" style='border: 0px;'> <!-- START OF lower_nav -->
		<ul>
			<li> <?php echo anchor('/', 'Home'); ?></li>
			<li> <?php echo anchor('pages/contact-us', 'Contact Us');?></li>
			<li> <?php echo anchor('pages/selling-your-jewelry/', 'Selling Your Jewelry')?></li>
			<li> <?php echo anchor('pages/our-store/', 'Our Store')?></li>
			<li> <?php echo anchor('pages/shipping-policies/', 'Shipping Policies')?></li>
			<li> <?php echo anchor('pages/our-friendly-staff/', 'Our Staff')?></li>
		</ul>
	</div> <!-- END OF lower_nave -->
</div>
<!-- START OF Analytic codes -->
<!-- START Google Analytics -->
<?php //google analytics tracking code ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36852339-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- END Google Analytics -->