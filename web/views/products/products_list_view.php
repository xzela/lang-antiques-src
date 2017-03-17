<?php
ob_start();
//var_dump($this->my_menu->secondary_menu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $page_data['page_title']; ?> - Lang Antiques</title>
    <?php echo snappy_script('browser.selector.js');?>
    <meta name='description' content="<?php echo $page_data['meta']['meta_description']; ?>" />
    <?php //global style sheets, browser independent; ?>
    <?php $this->load->view('components/global.includes.php'); ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('#select_direction').bind('change', function() {
                $('#form_direction').submit();
            });
        });
    </script>
</head>
<body>
    <?php //var_dump($this->my_menu->main_menu); ?>
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
                <?php echo anchor('/', 'Home'); ?> &gt; <?php echo $page_data['breadcrumb']; ?>
            </div>
            <h2 id='top_h2'>
                <?php echo $page_data['h2']; ?>
            </h2>
            <div class="paging">
                <?php if($page_data['pagination'] != null): ?>
                <?php if(isset($this->uri->segments[2]) && $this->uri->segments[2] == 'whats-new'): ?>

                <?php else: ?>
                    <?php echo form_open($this->uri->uri_string(), 'id="form_direction"');?>
                    Sort By:
                    <?php foreach($sorts as $sort): ?>
                        <?php if($sort['direction'] == $page_data['direction'] && $sort['field'] == $page_data['field']): ?>
                            <strong ><?php echo $sort['name']?></strong>
                        <?php else: ?>
                            <button name="<?php echo $sort['field'] . '-' . $sort['direction']; ?>" value='<?php echo $sort['field']?>,<?php echo $sort['direction']?>' type="submit"><?php echo $sort['name']?></button>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php echo form_close(); ?>
                <?php endif; ?>

                <div style='border: 0px solid blue; text-align: right;'>
                    <div><?php echo anchor($page_data['all_url'], 'Show All ' . $page_data['total_rows'] . ' Items');?></div>
                </div>
                <?php endif;?>
                <div class='pagination' style='border: 0px solid blue;'>
                    <?php echo $page_data['pagination']; ?>
                </div>
            </div>
            <?php if($page_data['page_paragraph'] != '' && $page_data['offset'] == null): ?>
                <div id='meta_description'>
                    <?php echo $page_data['page_paragraph']; ?>
                </div>
            <?php endif;?>
            <div id='listings'>
                <?php if(sizeof($inventory_data) > 0): //see if inventory is greater than zero ?>
                    <?php foreach($inventory_data as $item): //loop through each item ?>
                        <?php if(isset($item['images'][0]) && sizeof($item['images'][0]) > 0): ?>
                            <div class='item'>
                                <?php echo anchor('products/item/' . $item['item_number'], "<img class='image' src='" . base_url() . "images/thumbnails/150/" . $item['images'][0]['image_id'] . ".jpg' />");?>
                                <h3><?php echo anchor('products/item/' . $item['item_number'], $item['item_name']); ?></h3>
                                <p>
                                    <?php $summary = preg_replace('/<[^>]*>/',"", $item['item_description']); //remove any HTML elements from the text ?>
                                    <?php //$summary = $item['item_description']; ?>
                                    <?php if(strlen($item['item_description']) > 300): ?>
                                        <?php echo substr($summary, 0, 300); ?> ...(<?php echo anchor('products/item/' . $item['item_number'], 'more')?>)
                                    <?php else: ?>
                                        <?php echo $summary ?>
                                    <?php endif;?>
                                </p>
                                <div>
                                    <?php if(($item['item_status'] != 1 && $item['item_status'] != 2) || $item['item_quantity'] <= 0): //test to see if the item was sold ?>
                                        <strong class='warning'>This item has been sold</strong> - <?php echo anchor('products/item/' . $item['item_number'], 'View Item'); ?> &nbsp;&nbsp; #<?php echo $item['item_number']; ?>
                                    <?php else: ?>
                                        <strong>$<?php echo number_format($item['item_price'],2); ?></strong> - <?php echo anchor('products/item/' . $item['item_number'], 'View Item'); ?> &nbsp;&nbsp; #<?php echo $item['item_number']; ?>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endforeach;?>
                <?php else: //inventory was zero or less, nothing found ?>
                    <p>
                        Hmmm, We couldn't find anything that matched your search criteria.
                        You can try your search again, or use the links on the left hand side.
                    </p>
                <?php endif;?>
            </div>
            <div class="paging">
                <div class='pagination'>
                    <?php echo $page_data['pagination']; ?>
                </div>
                <?php if($page_data['pagination'] != null): ?>
                    <div style='float: right;'>
                        <div><?php echo anchor($page_data['all_url'], 'Show All ' . $page_data['total_rows'] . ' Items');?></div>
                    </div>
                <?php endif;?>
            </div>
            <div style="clear: both">&nbsp;</div>
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
