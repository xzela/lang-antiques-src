<!-- START OF HEADER_VIEW -->
<div id="header">
	<ul class="utilnav"> <!-- START OF utilnav -->
		<?php if($this->session->userdata('customer_id') != null):?>
			<li><?php echo anchor('user/favorites/', snappy_image('icons/heart.png') . ' Favorites'); ?></li>
			<li><?php echo anchor('user/user-account/', snappy_image('icons/house.png') . ' My Account'); ?></li>
			<li><?php echo anchor('user/signout/', snappy_image('icons/door_out.png') . ' Sign Out'); ?></li>
		<?php else: ?>
		<li><?php echo anchor('user/signin/', snappy_image('icons/heart.png') . ' Favorites'); ?></li>
		<?php endif; ?>
		<li><?php echo anchor('shopping/view-cart/', snappy_image('icons/cart.png') . ' My Cart'); ?></li>
	</ul> <!-- END OF utilnav -->
	<?php echo anchor('/', snappy_image('langcs.gif', 'Lang Antiques Logo'), 'class="logo"'); ?>
	<div class="text"> <!-- START OF header information text -->
		<div class='img'>
			<?php echo snappy_image('lang.header.text.jpg', 'Lang Antique and Estate Jewelry'); ?>
		</div>
		<div class='large'>
			<?php echo anchor('pages/selling-your-jewelry/', 'Buyers and Sellers of Fine Antique Jewelry ' . "&amp;" . ' Fine Estate Jewelry since 1969', 'class=""')?>
		</div>
		<div class='normal'><span>Call us toll free</span> (800) 924-2213</div>
		<?php //OrangeSoda tracking number ?>
		<?php //<div class='normal'><span>Call us toll free</span> (877) 637-4981</div> ?>
	</div> <!-- END OF header information text -->
</div>
<div class="lower_nav"> <!-- START OF lower_nav -->
	<ul>
		<li> <?php echo anchor('/', 'Home'); ?></li>
		<li> <?php echo anchor('pages/contact-us', 'Contact Us');?></li>
		<li> <?php echo anchor('pages/selling-your-jewelry/', 'Selling Your Jewelry', 'style="color: #990000;"')?></li>
		<li> <?php echo anchor('pages/our-store/', 'Our Store')?></li>
		<li> <?php echo anchor('pages/shipping-policies/', 'Policies')?></li>
		<li> <?php echo anchor('pages/our-friendly-staff/', 'Our Staff')?></li>
        <li><a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-via="langantiques">Tweet</a><script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script></li>
        <li><?php //this is the facebook iframe stuff, dangerious! ?><iframe src="https://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Flang.antiques&amp;layout=button_count&amp;show_faces=false&amp;width=75&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=25" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:25px;" allowTransparency="true"></iframe></li>
        <li>
        	<!-- Place this tag where you want the +1 button to render -->
			<g:plusone size="small" annotation="none"></g:plusone>
			<!-- Place this render call where appropriate -->
			<script type="text/javascript">
			  (function() {
			    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			    po.src = 'https://apis.google.com/js/plusone.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</li>
	</ul>
</div> <!-- END OF lower_nav -->
<!-- END OF HEADER_VIEW -->