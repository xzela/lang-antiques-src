		<!-- START OF MENU_VIEW -->
		<script type="text/javascript">
		$(document).ready(function(){
			var input = $('.search_string_field');
			input.bind('click', function(evnt) {
				var div = $('.sub_search_div');
				if(div.is('*')) {
					div.slideDown();
				}
			});
		});
		</script>
		<style type="text/css">
			.sub_search_div {
				font-size: 10px;
			}
		</style>
		<div id="top_image_menu">&nbsp;</div>
		<div id="leftnav"> <!-- START OF leftnav -->
			<div class='content'> <!-- @TODO fix class name of 'content', change to 'menu_content' -->
				<h4 class="leftnav_h4">Quick Search</h4>
				<div class='m_form'>
					<?php echo form_open('search/quick-search'); ?>
						<input class='search_string_field' type='text' name='string' />
						<input type='submit' value='' />
						<?php if($this->session->userdata('parent_id') != "" && @$this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_url'] != "search/everything"): ?>
							<?php if(@isset($this->my_menu->main_menu[$this->session->userdata('parent_id')]) && @$this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_type'] != 3): ?>
								<div class="sub_search_div" style="display: none;">
									<input type="hidden" name="category_id" value="<?php echo $this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_type_id']; ?>"/>
									<input type="checkbox" name="sub" value="<?php echo $this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_type'];?>" /> Search within <?php echo $this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_name']; ?>
								</div>
							<?php elseif(@$this->my_menu->secondary_menu[$this->session->userdata('parent_id')] && @$this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_type'] != 3): ?>
								<div class="sub_search_div" style="display: none;">
									<input type="hidden" name="category_id" value="<?php echo $this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_type_id']; ?>" />
									<input type="checkbox" name="sub" value="<?php echo $this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_type']; ?>" /> Search within <?php echo $this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_name']; ?>
								</div>
							<?php endif; ?>
							<?php if(@$this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_type'] == 3 || @$this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_type'] == 3): ?>
								<?php
									$special_type = null;
									$special_type_name = null;
								?>
								<?php if(isset($this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_type'])): ?>
									<?php
										$special_type = $this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_type'];
										$special_type_name = str_replace(array('search/','-'), ' ', $this->my_menu->main_menu[$this->session->userdata('parent_id')]['element_url']);
									?>
								<?php elseif(isset($this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_type'])): ?>
									<?php
										$special_type = $this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_type'];
										$special_type_name = str_replace(array('search/','-'), ' ', $this->my_menu->secondary_menu[$this->session->userdata('parent_id')]['element_url']);
									?>
								<?php endif; ?>
								<div class="sub_search_div" style="display:none;">
									<input type="hidden" name="special_type" value="<?php echo $special_type_name; ?>" />
									<input type="checkbox" name="sub" value="<?php echo $special_type; ?>" /> Search within <?php echo $special_type_name; ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php echo form_close(); ?>
				</div>
				<ul class="leftnav_ul leftnav_ul_start">
					<?php
						/*
						 * Loop through all main menu elements
						 *
						 * If this is to be more dynamic, we'll need to redo
						 * the my_menu library so its more extendable
						 *
						 */
					?>
					<?php foreach($this->my_menu->main_menu as $element): ?>
						<li>
							<?php //menu element is the 'whats-new', make strong; ?>
							<?php if($element['element_url'] == 'search/whats-new'): ?>
								<strong class='whats_new'><?php echo anchor($element['element_url'], $element['element_name']); ?></strong>
							<?php else:?>
								<?php if($this->my_menu->url['current']['url'] == $element['element_url'] || $this->session->userdata('parent_id') == $element['element_id']): ?>
									<strong><?php echo anchor($element['element_url'], $element['element_name']); ?></strong>
									<?php if(sizeof($element['children']) > 0): ?>
										<ul class='sub_menu_element'>
											<?php foreach($element['children'] as $child): ?>
												<?php if($child['sub_element_url'] == $this->my_menu->url['current']['url']): ?>
													<li>- <?php echo $child['sub_element_name']; ?></li>
												<?php else: ?>
													<li>- <?php echo anchor($child['sub_element_url'], $child['sub_element_name']); ?></li>
												<?php endif;?>
											<?php endforeach;?>
										</ul>
									<?php endif;?>
								<?php else: ?>
									<?php echo anchor($element['element_url'], $element['element_name']); ?>
								<?php endif;?>
							<?php endif;?>
						</li>
					<?php endforeach;?>
				</ul>
				<h4 class="leftnav_h4" >Jewelry Periods</h4>
				<ul class="leftnav_ul">
					<?php foreach($this->my_menu->secondary_menu as $element): ?>
						<li>
							<?php if($this->my_menu->url['current']['url'] == $element['element_url'] || $this->session->userdata('parent_id') == $element['element_id']): ?>
								<strong><?php echo anchor($element['element_url'], $element['element_name']); ?></strong>
								<?php if(sizeof($element['children']) > 0): ?>
									<ul class='sub_menu_element'>
										<?php foreach($element['children'] as $child): ?>
											<?php if($child['sub_element_url'] == $this->my_menu->url['current']['url']): ?>
												<li>- <?php echo $child['sub_element_name']; ?></li>
											<?php else: ?>
												<li>- <?php echo anchor($child['sub_element_url'], $child['sub_element_name']); ?></li>
											<?php endif;?>
										<?php endforeach;?>
									</ul>
								<?php endif;?>
							<?php else: ?>
								<?php echo anchor($element['element_url'], $element['element_name']); ?>
							<?php endif;?>
						</li>
					<?php endforeach;?>
				</ul>
				<h4 class="leftnav_h4" >Resources</h4>
				<ul class="leftnav_ul leftnav_ul_end" style="margin-bottom: 8px" >
					<li><?php echo anchor('pages/decorative-periods/', 'Decorative Periods'); ?></li>
					<li><?php echo anchor('pages/introduction-to-diamonds/', 'Diamonds'); ?></li>
					<li><?php echo anchor('pages/introduction-to-gemstones/', 'Gemstones'); ?></li>
					<li><?php echo anchor('pages/jewelry-care/', 'Jewelry Care'); ?></li>
					<li><?php echo anchor('pages/greeting-cards/', 'Greeting Card Gallery'); ?></li>
					<li><?php echo anchor('pages/shipping-policies/', 'Policies'); ?></li>
					<li><?php echo anchor('pages/testimonials/', 'Testimonials'); ?></li>
					<li><div class='image'><?php echo anchor('http://www.langantiques.com/university', snappy_image('aju.building.logo.jpg', 'AJU'), 'target="_blank"');?></div></li>
					<li><div class='center'><?php echo anchor('http://www.langantiques.com/university/', 'Antique Jewelry <br /> University', 'target="_blank"')?></div></li>
				</ul>
			</div>
			<div id="bottom_image_menu">&nbsp;</div>
		</div>