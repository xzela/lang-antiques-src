<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

    <?php echo snappy_style('styles.css'); //autoloaded ?>
    <?php echo snappy_script('jquery/jquery-1.4.1.js'); //autoloaded ?>

    <title><?php echo $this->config->item('project_name'); ?> - Main</title>
    <style type="text/css">
        .list_table .right {
            border-right: 1px solid #999;
        }
        .list_table td {
            border-top: 1px solid #d0d0d0;
            border-right: 1px solid #d0d0d0;
        }

        .customer_table th a {
            font-weight: bold;
        }
        .star {
            cursor: pointer;
        }
    </style>
    <script type='text/javascript'>
        var base_url = <?php echo "'" . base_url() . "'"; ?>;
        var img_url = base_url + '/system/application/assets/images/icons/';

        $(document).ready(function() {
            $('.send_workshop').bind('click', function(evt) {
                var job_id = $(this).attr('id');
                var job_status = $(this).attr('status');
                if(job_status == 'yes') { //job is at workshop, change to no
                    $.ajax({
                        url : base_url + 'inventory/AJAX_updateJobAtWorkshopStatus/',
                        type : 'POST',
                        dataType: 'json',
                        data : {job_id: job_id, at_workshop_status: 'no'},
                        beforeSend: function(x) {
                            if(x && x.overrideMimeType) {
                                x.overrideMimeType("application/json;charset=UTF-8");
                            }
                        },
                        success: function(res) {
                            data = $.parseJSON(res.response);
                            //console.log(res)
                            updateJobAtWorkshopStatus(job_id, job_status, res);
                        }

                    });
                }
                else { //job is not at workshop, update to yes
                    $.ajax({
                        url : base_url + 'inventory/AJAX_updateJobAtWorkshopStatus/',
                        type : 'POST',
                        dataType: 'json',
                        data : {job_id: job_id, at_workshop_status: 'yes'},
                        beforeSend: function(x) {
                            if(x && x.overrideMimeType) {
                                x.overrideMimeType("application/json;charset=UTF-8");
                            }
                        },
                        success: function(res, status) {
                            data = $.parseJSON(res.response);
                            //console.log(res);
                            updateJobAtWorkshopStatus(job_id, job_status, res);
                        }
                    });
                }
            });
        });

        function updateJobAtWorkshopStatus(job_id, job_status, data) {
            var node = $('#' + job_id);
            var span = $('#sent_date_' + job_id);
            var img = node.find('img');
            var img_type;
            if(job_status == 'no') {
                node.attr('status', 'yes');
                img_type = 'star.png';
                // console.log(typeof(data));
                span.text(data.sent_date);
            }
            else {
                span.text("");
                node.attr('status', 'no');
                img_type = 'star_gray.png';
            }
            img.attr('src', img_url + img_type);
        }
    </script>
</head>
<body>
<?php
    $this->load->view('_global/header');
    $this->load->view('_global/menu');
?>
<div id="content">
    <h2>Welcome to <?php echo $this->config->item('project_name'); ?></h2>
    <h3>Customer Special Orders</h3>
    <table class='customer_table'>
        <tr>
            <th nowrap>Order ID</th>
            <th nowrap>Customer</th>
            <th nowrap>Company</th>
            <th nowrap>Descrption</th>
            <th nowrap>Invoice ID</th>
            <th nowrap>Date</th>
            <th nowrap>Status</th>
            <th nowrap>Options</th>
        </tr>
        <?php if(sizeof($special_orders) > 0):?>
            <?php foreach($special_orders as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td>
                        <?php echo anchor('customer/edit/' . $order['customer_id'], $order['customer_data']['name']); ?>
                        <br />
                        <?php echo $order['customer_data']['spouse_first'] . ' ' . $order['customer_data']['spouse_last']?>
                    </td>
                    <td><?php echo $order['company_name']; ?></td>
                    <td><?php echo $order['order_description']; ?></td>
                    <td><?php echo $order['invoice_id']; ?></td>
                    <td><?php echo date('m/d/Y', strtotime($order['order_date'])); ?></td>
                    <td><?php echo $order['order_status']; ?></td>
                    <td><?php echo anchor('customer/edit_special_order/' . $order['customer_id'] . '/' . $order['order_id'], 'Edit Order');?></td>
                </tr>
            <?php endforeach;?>
        <?php else: ?>
            <tr>
                <td colspan='8'>No Special Orders Found</td>
            </tr>
        <?php endif;?>
    </table>
    <h3>Customer Jobs that are Still Open</h3>
    <table class="customer_table">
        <tr>
            <th nowrap>Job ID</th>
            <th nowrap>
                Workshop
                <?php echo anchor('main/main_sort/customer/workshop_info.name/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
                <?php echo anchor('main/main_sort/customer/workshop_info.name/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>
            </th>
            <th nowrap>
                Customer Name
                <?php echo anchor('main/main_sort/customer/customer_info.last_name/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
                <?php echo anchor('main/main_sort/customer/customer_info.last_name/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>
            </th>
            <th nowrap>Item</th>
            <th nowrap>Date Open</th>
            <th nowrap>Instructions</th>
            <th nowrap>Notes</th>
            <th nowrap>Status</th>
            <th nowrap>Options</th>
        </tr>
    <?php foreach($customer_jobs as $job): ?>
        <tr>
            <?php if($job['rush_order'] == 1 && $job['status'] == 1): ?>
                <?php $rush_order = ' rush_order';?>
                <td class='<?php echo $rush_order; ?>' ><?php echo $job['job_id']?> [Rush]</td>
            <?php else: ?>
                <?php $rush_order = '';?>
                <td class='<?php echo $rush_order; ?>' ><?php echo $job['job_id']?> </td>
            <?php endif;?>
            <td class='<?php echo $rush_order; ?>' ><?php echo anchor('workshop/edit/' . $job['workshop_id'], $job['name']); ?></td>
            <td class='<?php echo $rush_order; ?>' >
                <?php echo anchor('customer/edit/' . $job['customer_id'], $job['last_name'] . ', ' . $job['first_name']); ?>
                <?php if($job['spouse_last'] != ''): ?>
                    <br />
                    <?php echo $job['spouse_last'] . ', ' . $job['spouse_first']?>
                <?php endif;?>
            </td>
            <td class='<?php echo $rush_order; ?>' ><?php echo $job['item_description']?> </td>
            <td class='<?php echo $rush_order; ?>' ><?php echo date('m/d/Y', strtotime($job['open_date']));?> </td>
            <td class='<?php echo $rush_order; ?>' ><?php echo $job['instructions']?> </td>
            <td class='<?php echo $rush_order; ?>' ><?php echo $job['job_notes']?> </td>
            <td class='<?php echo $rush_order; ?>' ><?php echo $job['status_text']?> </td>
            <td class='<?php echo $rush_order; ?> end' >
                <?php echo anchor('customer/edit_job/' . $job['job_id'], snappy_image('icons/magnifier.png') .  'View'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>

    <h3 id='inventory_job_list'>Inventory Jobs that are Still Open</h3>
    <table class="customer_table">
        <tr>
            <th nowrap>Item Num</th>
            <th nowrap>Image</th>
            <th nowrap>
                Workshop
                <?php echo anchor('main/main_sort/workshop/workshop_info.name/asc#inventory_job_list', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
                <?php echo anchor('main/main_sort/workshop/workshop_info.name/desc#inventory_job_list', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>
            </th>
            <th nowrap>Item</th>
            <th nowrap>Date Open</th>
            <th nowrap>Instructions</th>
            <th nowrap>Notes</th>
            <th nowrap>Status</th>
            <th nowrap>Options</th>
        </tr>
        <?php foreach($inventory_jobs as $job): ?>
            <tr>
            <?php if($job['rush_order'] == 1 && $job['status'] == 1): ?>
                <?php $rush_class = 'rush_order'; ?>
                <td class='<?php echo $rush_class; ?>'>
                    <?php echo anchor('inventory/edit/' . $job['item_id'], $job['item_number']); ?>[Rush Order]
                </td>
            <?php else: ?>
                <?php $rush_class = ''; ?>
                <td class='<?php echo $rush_class; ?>'>
                    <?php echo anchor('inventory/edit/' . $job['item_id'], $job['item_number']); ?>
                    <div class="send_workshop" id="<?php echo $job['job_id']; ?>" status="<?php echo $job['at_workshop']; ?>" value='<?php echo $job['job_id']; ?>' >
                        <?php if($job['at_workshop'] == 'yes'): ?>
                            <?php echo snappy_image('icons/star.png', null, 'star'); ?>
                            <span id='sent_date_<?php echo $job['job_id']; ?>' >
                            <?php if($job['sent_date'] != ''): ?>
                                <?php echo date('m/d/Y', strtotime($job['sent_date'])); ?>
                            <?php endif;?>
                            </span>
                        <?php else: ?>
                           <?php echo snappy_image('icons/star_gray.png', null, 'star'); ?>
                           <span id='sent_date_<?php echo $job['job_id']; ?>' ></span>
                        <?php endif; ?>
                    </div>

                </td>
            <?php endif;?>
                <td class='<?php echo $rush_class; ?>'>
                    <?php if(sizeof($job['image_array']['external_images']) > 0): ?>
                        <?php
                            echo anchor('inventory/edit/' . $job['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $job['image_array']['external_images'][0]['image_location'] . '&image_type=' . $job['image_array']['external_images'][0]['image_class'] . '&image_size=' . $job['image_array']['external_images'][0]['image_size'] . "' />");
                        ?>
                    <?php elseif(sizeof($job['image_array']['internal_images']) > 0):?>
                        <?php
                        echo anchor('inventory/edit/' . $job['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $job['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $job['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $job['image_array']['internal_images'][0]['image_size'] . "' />");
                        ?>
                    <?php else: ?>
                        No Image Provided
                    <?php endif; ?>
                </td>
                <td class='<?php echo $rush_class; ?>'><?php echo anchor('workshop/edit/' . $job['workshop_id'], $job['name']); ?></td>
                <td class='<?php echo $rush_class; ?>'><?php echo anchor('inventory/edit/' . $job['item_id'],  $job['item_number'] .' - ' . $job['item_name']); ?></td>
                <td class='<?php echo $rush_class; ?>'><?php echo date('m/d/Y', strtotime($job['open_date'])); ?></td>
                <td class='<?php echo $rush_class; ?>'><?php echo $job['instructions']; ?></td>
                <td class='<?php echo $rush_class; ?>'><?php echo $job['job_notes']; ?></td>
                <td class='<?php echo $rush_class; ?>' nowrap><?php echo $job['status_text']; ?></td>
                <td class='<?php echo $rush_class; ?> end' >
                    <?php echo anchor('workshop/edit_job/' . $job['job_id'], snappy_image('icons/magnifier.png') .  'View'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php
    $this->load->view('_global/footer');
?>
</body>
</html>