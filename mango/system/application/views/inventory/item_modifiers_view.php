<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php echo $this->config->item('project_name'); ?> - Inventory Edit Modifiers - <?php echo $item_data['item_name'];?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

    <?php echo snappy_style('styles.css'); //autoloaded ?>
    <?php echo snappy_style('jquery.autocomplete.css'); //autoloaded ?>

    <?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
    <?php echo snappy_script('jquery/jquery.autocomplete.js'); ?>

    <style type="text/css">
    .orig_modifier {
        /*list-style-type: circle;*/
        text-decoration: underline;
        color: #0033FF;
    }
    .orig_modifier:hover {
        cursor: pointer;
        /*color: #3399FF; */
        color: green;
    }

    .applied_modifiers {
        text-decoration: underline;
        color: #0033FF;
    }

    .applied_list_item {
        list-style-type: none;
        padding: 0px;
        margin: 0px;
    }
    .applied_modifiers:hover {
        cursor: pointer;
        color: red;
    }
    #applied_mods {
        border: 1px dashed #999;
        padding: 0px;
    }
    .h3_list {
        padding: 0px;
        margin: 0px;
    }

    .div_form {
        padding: 0 5px 5px 5px;
        margin: 0px 5px 5px 5px;
        background-color: #dfdfdf;
        border: 1px dashed #c0c0c0;
    }
    .menu_categories {
        float: right;
        background-color: #fff;
    }

    #modifier_list {
        border: 1px dashed #999;
        list-style-type: none;
        padding: 10px;
        margin: 5px;
        width: 230px;
    }
    #modifier_list li {
        font-size: 13px;
    }
    #modifier_list li.header {
        list-style-type: none;
        font-weight: bold;
        font-size: 18px;
        margin: 0px;
        padding: 0px;
    }
    #modifier_list li.child {

        padding-left: 10px;
    }
    span.embolden {
        font-size: 14px;
        font-weight: bold;
    }

    </style>
    <script type="text/javascript">
    var base_url = <?php echo '"' . base_url() . '"'; ?>;
    var item_id = <?php echo $item_data['item_id']; ?>;

    var acOption = {
            minChars: 1, //number of chars in text box, 1 (+1);
            dataType: 'json',
            scrollHeight: 300,
            cacheLength: 0,
            max: 50,
            extraParams: {
                format: 'json'
            },
            parse: function(data) {
                var parsed = [];
                //alert(data);
                if(typeof(data.mod) != 'undefined') { //if undefined, return empty array
                    data = data.mod;
                    for(var i = 0; i < data.length ; i++) {
                        parsed[parsed.length] = {
                            data: data[i],
                            value: data[i].modifier_name,
                            result: data[i].modifier_name
                        };
                    }
                }
                return parsed;
            },
            formatItem: function(item) {
                str = item.modifier_name;
                return str;
            }
        };


    $(document).ready(function() {
        //we use 'live' because it hooks into the dom better than 'click'
        //see http://docs.jquery.com/Events/live for more details
        $('span.applied_modifiers').live("click", function() {
                mod_id = $(this).attr('id');
                //reenable modifier
                var mod = $('span.list_' + mod_id);
                mod.attr('class', 'orig_modifier');
                mod.attr('embolden', 'false');
                if($(this).attr('embolden') == 'true') {
                    mod.attr('embolden', 'true');
                    mod.addClass('embolden');
                }
                mod.addClass('list_' + mod_id);
                var url = base_url + 'modifier/AJAX_removeModifier/' + item_id + '/' + mod_id;
                $.post(url);
                //remove from list
                $(this).remove();

            });
        $('.orig_modifier').live("click", function() {
                var class_list = $(this).attr("class").split(/\s/);

                var mod_id = class_list[class_list.length -1].substring(5);
                console.log(mod_id);
                //var mod_id = $(this).attr('class').substring(19);
                var embold = $(this).attr('embolden');

                $('span.list_' + mod_id).each(function(i) {
                    $(this).attr('class', 'orig_modifier_added');
                    $(this).addClass('list_' + mod_id);
                });
                //insert into database
                var url = base_url + 'modifier/AJAX_applyModifier/' + item_id + '/' + mod_id;
                $.post(url);

                //add to list
                var embold_str = 'embolden="false"';
                var classes = 'applied_modifiers';

                if(embold == 'true') {
                    embold_str = 'embolden="true"';
                    classes += ' embolden'
                }
                var str = '<li class="applied_list_item">';
                    str += '<span id=' + mod_id + ' class="' + classes + '"' + embold_str + ' >';
                    str += $(this).html();
                    str += '</span></li>';

                $('#applied_list').append(str);
            });
        $('#open_form_link').click(function() {
                if($('#add_mod_div').is(":hidden")) {
                    $('#add_mod_div').slideDown('slow');
                }
                else {
                    $('#add_mod_div').slideUp('fast');
                }
            });

        $('#modifier_name').bind('keyup', function() {
                if($(this).val().length > 2) {
                    var url = base_url + 'inventory/CB_check_modifier/' + $(this).val();
                    $.get(url, {},
                        function(value, status) {
                            if(!value) {
                                $('#available_status').html('Modifier Name already taken, Please Pick another one');
                                $('#submit_mod').attr('disabled', 'disabled');
                            }
                            else {
                                $('#available_status').html('');
                                $('#submit_mod').attr('disabled', '');
                            }
                        }
                    );
                }
                else {
                    $('#submit_mod').attr('disabled', 'disabled');
                }
            });
        $("#modifier_search")

            .autocomplete(base_url + 'modifier/AJAX_getModifierNames',acOption)
                .attr('name', 'contact')
                .after('<input type="hidden" name="user_id" id="ac_result">')
                .result(function(e, item) {
                        //document.location = base_url + 'customer/edit/' + item.customer_id;
                        var mod_id = item.modifier_id;
                        $('span.list_' + mod_id).each(function(i) {
                            $(this).attr('class', 'orig_modifier_added');
                            $(this).addClass('list_' + mod_id);
                        });
                        //insert into database
                        var url = base_url + 'modifier/AJAX_applyModifier/' + item_id + '/' + mod_id;
                        $.post(url);

                        //add to list
                        var str = '<li class="applied_list_item">';
                            str += '<span id=' + mod_id + ' class="applied_modifiers">';
                            str += item.modifier_name;
                            str += '</span></li>';
                        $('#applied_list').append(str);
                        $(this).val('');
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
        <h2>Edit Modifiers for item: <?php echo $item_data['item_number'];?> - <?php echo $item_data['item_name'];?></h2>
        <ul id='submenu'>
            <li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back'); ?></li>
            <li>|</li>
            <li><a href='#' id='open_form_link'>Add Modifier</a></li>
            <li>|</li>
            <li><?php echo anchor('inventory/materials/' . $item_data['item_id'],'Go To Materials'); ?></li>
            <li>|</li>
        </ul>
        <div id='add_mod_div' style='display: none'>
            <div class='div_form'>
                <h3>Add Modifier</h3>
                <?php echo form_open('inventory/modifiers/' . $item_data['item_id']); ?>
                    <input id='modifier_name' name='modifier_name' type='text' />
                    <input id='submit_mod' name='submit_mod' type='submit' value='Save Modifier' disabled />
                    <br />
                    <span id='available_status' class='warning'></span>
                <?php echo form_close(); ?>
            </div>
        </div>
            <div class='menu_categories'>
                <ul id='modifier_list'>
                    <li class='header'>Top Level Categories</li>
                    <?php foreach($top_categories as $mod): ?>
                        <?php $t_bold_attr =  $mod['modifier_data']['embolden'] == 'yes' ? 'embolden="true"' : 'embolden="false"' ;  ?>
                        <?php $t_bold_class =  $mod['modifier_data']['embolden'] == 'yes' ? 'embolden' : '' ;  ?>
                        <?php if(array_search($mod['modifier_data']['modifier_id'], $applied_ids)): ?>
                            <li class='child'><span class='orig_modifier_added list_<?php echo $mod['modifier_data']['modifier_id']; ?>' <?php echo $t_bold_attr; ?> ><?php echo $mod['modifier_data']['modifier_name']; ?></span></li>
                        <?php else: ?>
                            <li class='child'><span class='<?php echo $t_bold_class; ?> orig_modifier list_<?php echo $mod['modifier_data']['modifier_id']; ?>' <?php echo $t_bold_attr; ?> ><?php echo $mod['modifier_data']['modifier_name']; ?></span></li>
                        <?php endif;?>
                    <?php endforeach;?>
                    <li class='header'>Sub Level Categories</li>
                    <?php foreach($sub_categories as $mod): ?>
                        <?php $m_bold_attr =  $mod['modifier_data']['embolden'] == 'yes' ? 'embolden="true"' : 'embolden="false"' ;  ?>
                        <?php $m_bold_class =  $mod['modifier_data']['embolden'] == 'yes' ? 'embolden' : '' ;  ?>
                        <?php if(array_search($mod['modifier_data']['modifier_id'], $applied_ids)): ?>
                            <li class='child'><span class='orig_modifier_added list_<?php echo $mod['modifier_data']['modifier_id']; ?>' <?php echo $m_bold_attr; ?>><?php echo $mod['modifier_data']['modifier_name']; ?></span></li>
                        <?php else: ?>
                            <li class='child'><span class='<?php echo $m_bold_class; ?> orig_modifier list_<?php echo $mod['modifier_data']['modifier_id']; ?>' <?php echo $m_bold_attr; ?> ><?php echo $mod['modifier_data']['modifier_name']; ?></span></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
        <div class='image_area'>
            <?php if(sizeof($item_data['image_array']['external_images']) > 0): ?>
                <?php foreach($item_data['image_array']['external_images'] as $image): ?>
                    <?php
                        echo anchor('inventory/show_image/' . $image['image_id'] . '/external', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' />");
                    ?>
                <?php endforeach; ?>
            <?php elseif(sizeof($item_data['image_array']['internal_images']) > 0):?>
                <?php foreach($item_data['image_array']['internal_images'] as $image): ?>
                    <?php
                        echo anchor('inventory/show_image/' .$image['image_id'] . '/internal', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' /> ");
                    ?>
                <?php endforeach; ?>
            <?php else: ?>
                No Image Provided
            <?php endif; ?>
        </div>
        <h3>Applied Modifiers</h3>
        <div id="applied_mods">
            <ul id="applied_list">
            <?php foreach($modifier_data as $mod): ?>
                <li class='applied_list_item'>
                    <span id='<?php echo $mod['modifier_id']; ?>' class="applied_modifiers" > <?php echo $mod['modifier_name'];?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <h3>Search Modifiers:</h3>
        <div style='border: 1px solid #999999; padding: 10px; margin: 3px;'>
            Search Name: <input id='modifier_search' name='modifier_search' type='text' style='width: 250px;' />
        </div>
        <h3>Available Modifiers</h3>
        <?php
            $chunk = (int) (count ($modifiers) / 5);
            $prev_start_char = 'none';
            $new_letter;

            $r = '<table width="68%"><tr valign="top">';
            // Stolen from wikipedia
            // This make need to be cleaned up.
            // TODO Clean up Modifier Section
            // loop through the chunks
            for($startChunk = 0, $endChunk = $chunk, $chunkIndex = 0; $chunkIndex < 5; $chunkIndex++, $startChunk = $endChunk, $endChunk += $chunk + 1) {
                $r .= "<td>\n";
                $atColumnTop = false;

                // output all articles in category
                for($index = $startChunk; $index < $endChunk && $index < count($modifiers); $index++ ) {
                    // check for change of starting letter or begining of chunk
                    if ($index == $startChunk) {
                        $r .= "<h3 class='h3_list'>" . htmlspecialchars( substr($modifiers[$index]['modifier_name'], 0, 1) ) . " </h3>\n<ul class='modifier_list'>";
                        if($atColumnTop) {
                            $atColumnTop = false;
                        }
                        $prev_start_char = substr($modifiers[$index]['modifier_name'], 0,1);
                    }
                    else {
                        if ($prev_start_char != substr($modifiers[$index]['modifier_name'], 0,1)) {
                            $r .= "</ul>\n";
                            $r .= "<h3 class='h3_list'>" . htmlspecialchars( substr($modifiers[$index]['modifier_name'], 0, 1) ) . " </h3>\n<ul class='modifier_list'>";
                        }
                        $prev_start_char = substr($modifiers[$index]['modifier_name'], 0,1);
                    }
                    $bold_attribute = $modifiers[$index]['embolden'] == 'yes' ? 'embolden="true"' : 'embolden="false"';
                    $bold_class = $modifiers[$index]['embolden'] == 'yes' ? 'embolden' : '';
                    if (array_search($modifiers[$index]['modifier_id'], $applied_ids)) {
                        $r .= "<li><span class='{$bold_class} orig_modifier_added list_{$modifiers[$index]['modifier_id']}' {$bold_attribute} >{$modifiers[$index]['modifier_name']}</span></li>";
                    }
                    else {
                        $r .= "<li><span class='{$bold_class} orig_modifier list_{$modifiers[$index]['modifier_id']}' {$bold_attribute}> {$modifiers[$index]['modifier_name']}</span></li>";
                    }
                }
                if( !$atColumnTop ) {
                    $r .= "</ul>\n";
                }
                $r .= "</td>\n";
            }
            $r .= '</tr></table>';
            echo $r;
        ?>
        <p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
    $this->load->view('_global/footer');
?>

</body>
</html>