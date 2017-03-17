<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

    <title><?php echo $this->config->item('project_name'); ?> - Admin Options - Edit Modifier: <?php echo $modifier_data['modifier_name']; ?> </title>

    <?php echo snappy_style('styles.css'); //autoloaded ?>
    <?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
    <script type="text/javascript">
    var json = {"fields" : {}};

    $(document).ready(function () {
        var content = $('.input_field').val();
        $.each($('.input_field'), function(i, val) {
            json.fields[i] = {"name" : this.name, "value" : this.value};
        });

        $('.input_field').bind('keyup change', function(event) {
            var index = $('.input_field').index(this);
            var content = json.fields[index].value;

            //alert(content + '=' + $(this).val());
            var div = $('#change_message');
            if($(this).val() != content) {
                $(this).css('color', 'red');
                $(this).css('border', '1px solid red');
                if(div.is(':hidden')) {
                    div.slideDown('slow');
                }
            }
            else {
                $(this).css('color', 'black');
                $(this).css('border', '1px solid #333333');
                var b = true;
                $.each($('.input_field'), function(i, val) {
                    if(this.value != json.fields[i].value) {
                        b = false;
                    }
                });
                if(b) {
                    div.slideUp('slow');
                }
            }
        });

        $('#alt_keyword').bind('change', function() {
            if($(this).val() == '1') {
                //alert('hello');
                $('#keyword_name').removeAttr('disabled');
            }
            else {
                $('#keyword_name').attr('disabled', true);
            }
        });
        $('#alt_keyword').trigger('change');

    });
    </script>
</head>
<body>
<?php
    $this->load->view('_global/header');
    $this->load->view('_global/menu');
?>
    <div id="content">
        <h2 class='item'>Admin - Edit Modifiers: <?php echo $modifier_data['modifier_name']; ?></h2>
        <ul id='submenu'>
            <li><?php echo anchor('admin/modifier_list', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Modifier List'); ?></li>
        </ul>
        <p>Here, edit this modifier</p>
        <?php echo form_open('admin/modifier_edit/' . $modifier_data['modifier_id']); ?>
        <div id='change_message' style='display: none'>You've made changes to this record. They won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
        <table class='form_table'>
            <tr>
                <td class='title'>Modifier ID: </td>
                <td><?php echo $modifier_data['modifier_id']; ?></td>
            </tr>
            <tr>
                <td class='title'>Modifier Name: </td>
                <td>
                    <input type='text' size='50' name='modifier_name' class='input_field' value='<?php echo set_value('modifier_name', $modifier_data['modifier_name']);?>' />
                </td>
            </tr>
            <tr>
                <td class='title'>Active: </td>
                <td>
                    <select name='active' class='input_field'>
                    <?php if($modifier_data['active']):?>
                        <option value='0' >No</option>
                        <option value='1' selected>Yes</option>
                    <?php else: ?>
                        <option value='0' selected>No</option>
                        <option value='1'>Yes</option>
                    <?php endif;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='title'>Embolden:</td>
                <td>
                    <select name='embolden' class='input_field'>
                        <?php if($modifier_data['embolden'] == 'yes'): ?>
                            <option value='no'>No</option>
                            <option value='yes' selected>Yes</option>
                        <?php else: ?>
                            <option value='no' selected>No</option>
                            <option value='yes'>Yes</option>
                        <?php endif; ?>
                    </select>
                    <span style='color: #a1a1a1;'>emboldens the modifier name on the modifier selector screen</span>
                </td>
            </tr>
            <tr>
                <td colspan='2'><strong>Web Centric Options</strong></td>
            </tr>
            <tr>
                <td class='title'>Show On Web: </td>
                <td>
                    <select name='show_web' class='input_field'>
                    <?php if($modifier_data['show_web']):?>
                        <option value='0' >No</option>
                        <option value='1' selected>Yes</option>
                    <?php else: ?>
                        <option value='0' selected>No</option>
                        <option value='1'>Yes</option>
                    <?php endif;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='title'>Is KeyWord?: </td>
                <td>
                    <select id='alt_keyword' name='alt_keyword' class='input_field'>
                    <?php if($modifier_data['alt_keyword']):?>
                        <option value='0' >No</option>
                        <option value='1' selected>Yes</option>
                    <?php else: ?>
                        <option value='0' selected>No</option>
                        <option value='1'>Yes</option>
                    <?php endif;?>
                    </select>
                    <div>
                        <strong>Name:</strong> <input id='keyword_name' type='text' class='input_field' name='keyword_name' value='<?php echo set_value('keyword_name', $modifier_data['keyword_name']);?>' disabled />
                    </div>
                </td>
            </tr>
            <tr>
                <td class='title'>Top Level Modifier: </td>
                <td>
                    <select name='top_level' class='input_field'>
                    <?php if($modifier_data['top_level']):?>
                        <option value='0' >No</option>
                        <option value='1' selected>Yes</option>
                    <?php else: ?>
                        <option value='0' selected>No</option>
                        <option value='1'>Yes</option>
                    <?php endif;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='title'>Staff Pick Modifier: </td>
                <td>
                    <select name='staff' class='input_field'>
                    <?php if($modifier_data['staff']):?>
                        <option value='0' >No</option>
                        <option value='1' selected>Yes</option>
                    <?php else: ?>
                        <option value='0' selected>No</option>
                        <option value='1'>Yes</option>
                    <?php endif;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='title'>Modifier Title: </td>
                <td>
                    <input name='modifier_title' size='50' class='input_field' type='text' value='<?php echo set_value('modifier_title', $modifier_data['modifier_title']); ?>' />
                    <br />
                    <span style='color: #a1a1a1;'>
                        How you want the title of the web page to appear.
                        <br /> Like: '<?php echo $modifier_data['modifier_name']; ?> Jewelry'?
                    </span>
                </td>
            </tr>
            <tr>
                <td class='title'>Element URL Name: </td>
                <td><input name='element_url_name' size='50' class='input_field' type='text' value='<?php echo set_value('element_url_name', $modifier_data['element_url_name']); ?>' /></td>
            </tr>
            <tr>
                <td class='title'>Meta Description: </td>
                <td>
                    <textarea name='meta_description' class='input_field' rows='4' cols='40'><?php echo set_value('meta_description', $modifier_data['meta_description']); ?></textarea>
                </td>
            </tr>
            <tr>
                <td class='title'>Page Paragraph: </td>
                <td>
                    <textarea name='page_paragraph' class='input_field' rows='4' cols='40'><?php echo set_value('page_paragraph', $modifier_data['page_paragraph']); ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <?php echo validation_errors(); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type='submit' value='Update Modifier' /></td>
            </tr>
        </table>
        <?php echo form_close(); ?>
        <div>
            <h3>Delete Modifier</h3>
            <?php if($modifier_data['item_count'] > 0): ?>
                <div class='nodelete_admin_item'>
                    <p>
                    You cannot delete this modifier. It is currently applied to <?php echo $modifier_data['item_count']; ?> different item(s).
                    <br />
                    Reduce this number to 0 (zero) to delete this modifier.
                    </p>
                </div>
            <?php else: ?>
                <div class='delete_admin_item'>
                    <p class='warning'>This item is no longer applied to any items. It is safe to delete.</p>
                    <?php echo form_open('admin/modifier_delete/' . $modifier_data['modifier_id']); ?>
                        <input name='modifier_id' type='hidden' value='<?php echo $modifier_data['modifier_id']?>' />
                        <input type='submit' value='Delete This Modifier' />
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