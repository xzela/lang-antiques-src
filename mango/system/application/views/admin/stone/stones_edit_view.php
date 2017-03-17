<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Edit Gemstone: <?php echo $stone_data['stone_name']; ?> </title>
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.jeditable.css');?>
	
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.jeditable.js'); ?>
	<?php echo snappy_script('jquery/jquery.conditional.js'); ?>
	
	<script type="text/javascript">
		var base_url = <?php echo '"' . base_url() . '"'; ?>;
	
	<?php 
		$yes_no = array();
			$yes_no["0"] = 'No';
			$yes_no["1"] = 'Yes';
	?>

	
	var url = 'gemstone/AJAX_stone_edit/';
	var id = <?php echo $stone_data['stone_id']; ?>;
	$(document).ready(function () {
		$('.edit,.textarea_edit,.select_edit').bind('keydown', function(event) {
			
	        if(event.keyCode==9) {
				$(this).find("input").blur();
				$(this).find("textarea").blur();
				$(this).find("select").blur();
				var nextBox='';
				if ($(".edit,.textarea_edit,.select_edit").index(this) == ($(".edit,.textarea_edit,.select_edit").length-1)) { //at last box
					nextBox=$(".edit:first"); //last box, go to first
				} 
				else {
					nextBox = $(".edit,.textarea_edit,.select_edit").get($(".edit,.textarea_edit,.select_edit").index(this)+1);
				}
				$(nextBox).click();			
				return false;
			}
	    })
	    .If(function() { //textarea field
		    	return ($(this).attr('class') == 'textarea_edit') ? true : false;
		    })
			.editable(base_url + url + id, {
				type: 'textarea',
				rows: '4',
				cols: '40',
				cssclass: 'inplace_field',
				onblur: 'submit'
			})
		.ElseIf(function() { //select field
				return ($(this).attr('class') == 'select_edit') ? true : false;
	    	})
	    	.editable(base_url + url + id, {
				data: " {0:'No',1:'Yes'} ",
				type: 'select',
				onblur: 'submit',
				callback: function(value) {
					json = [{'value':'No'},{'value':'Yes'}]; //order: 0,1
					this.innerHTML = json[value].value;
				}
	    	})
	    .Else() //default input text field
	    	.editable(base_url + url + id, {
		    	type: 'text',
	    		cssclass: 'inplace_field',
	    		onblur: 'submit',
	    		onsubmit: function(value, settings) {
	    			if(settings.revert == this[0].value) {
		    			return false;
	    			}
	    			return true;
	    		}
		    });
	});
	</script>	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - Edit Gemstone: <?php echo $stone_data['stone_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/stone_list', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Stone List'); ?></li>
			<li>|</li>
		</ul>
		<p>Here, edit this Gemstone</p>
		<table class='item_information'>
			<tr>
				<td class='title'>Gemstone ID: </td>
				<td><?php echo $stone_data['stone_id']; ?></td>
			</tr>
			<tr>
				<td class='title'>Gemstone Name: </td>
				<td>
					<div id='stone_name' class='edit'><?php echo $stone_data['stone_name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>Plural Name: </td>
				<td>
					<div id='plural_name' class='edit'><?php echo $stone_data['plural_name']; ?></div>
				</td>
			</tr>			
			<tr>
				<td class='title'>Template Type: </td>
				<td><?php echo $templates[$stone_data['template_type']]['name']; ?> - <i>Cannot be changed</i></td>			
			</tr>
			<tr>
				<td class='title'>Stone Seq: </td>
				<td><div id='stone_seq' class='edit'><?php echo $stone_data['stone_seq']; ?></div></td>			
			</tr>			
			<tr>
				<td class='title'>Active: </td>
				<td>
					<div id='active' class='select_edit' ><?php echo $yes_no[$stone_data['active']]; ?></div>
				</td>
			</tr>
		</table>
		<div>
			<h3>Delete Gemstone</h3>
			<?php if($stone_data['stone_count'] > 0): ?>
				<div class='nodelete_admin_item'>
					<p>
					You cannot delete this Gemstone. It is currently applied to <?php echo $stone_data['stone_count']; ?> different item(s). 
					<br />
					Reduce this number to 0 (zero) to delete this gemstone.
					</p>
				</div>
			<?php else: ?>
				<div class='delete_admin_item'>
					<p>This gemstone is no longer applied to any items. It is safe to delete.</p>
					<?php echo form_open('admin/stone_delete'); ?>
						<input name='stone_id' type='hidden' value='<?php echo $stone_data['stone_id']?>' />
						<input type='submit' value='Delete This Gemstone' />
					<?php echo form_close(); ?>
				</div>
			<?php endif;?>
		</div>
		<p id='page_end'>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>