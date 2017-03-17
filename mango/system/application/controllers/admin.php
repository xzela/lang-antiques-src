<?php
/**
 * The following routes are used. See routes file for more info
 *
 * admin/modifiers_(:any) = modifiers_$1
 * admin_user_(:any) = user_$1
 *
 * @author zeph
 *
 */
class Admin extends Controller {
    var $ci;

    function __construct() {
        parent::Controller();
        //Check The user to see if they are logged in
        $this->load->library('authorize');
        $this->authorize->isLoggedIn();

        $this->ci =& get_instance();
    }

    /**
     * Default Constructor for index() pages
     *
     * @return null
     */
    function index() {
        $data['user_data'] = $this->authorize->getSessionData();
        $this->load->view('admin/admin_view', $data);
    }


    function bing_product_feed() {
        $this->load->model('utils/api_model');
        $this->load->helper('file');

        $products = $this->api_model->getProductsData();

        $data =  $this->api_model->formatBingFile($products);

        $name = 'bingshopping.txt';
        $path = './files/csv/' . $name;

        if(!write_file($path, $data)) {
            //cound not write file for some reason
            echo 'help!';
        }
        else {
            $file = read_file($path);
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'. $name . '"');

            echo $file; //needed to return the file to the browser
        }
        //redirect('admin', 'refresh');

    }

    /**
     * Creates Suffixes for items.
     * Searches the database for items which
     * do not have a suffix value.
     *
     * if the suffix value is missing, figure out
     * it's suffic and add it.
     */
    /* //Uncomment to fix broken items
    function create_suffix() {
        set_time_limit(500); //extend timout to 500 seconds
        $this->load->model('inventory/inventory_model');

        $data['items'] = $this->inventory_model->getNoSuffixItems();
        foreach($data['items'] as $item) {
            $num = explode("-", $item['item_number']);
            $suffix = $num[2];
            //update suffix
            if($item['suffix'] == '') {
                //uncomment to fix broken items
                //$this->inventory_model->AJAX_updateField($item['item_id'], 'suffix', $suffix);
                echo 'update item: ' . $item['item_id'] . ',' . $item['item_number'] . ' with suffix: ' . $suffix . '<br />';
            }

        }
        //print_r($data['items']);
    }
    */


    /**
     * Section 5 Job fix.
     *
     * This fixes a discreptincy with some of
     * the jobs.
     */
    // uncomment to fix jobs
    // function s5_job_fix() {
    //     set_time_limit(500);
    //     $data = array();
    //     $this->load->model('workshop/workshop_model');
    //     $data['jobs']['acts'] = $this->workshop_model->findNotNullActCost();
    //     foreach($data['jobs']['acts'] as $job) {
    //         $value = $job['act_price'];
    //         $this->workshop_model->updateInventoryJobField($job['job_id'], 'job_cost', $value);
    //         echo 'updated job: ' .$job['job_id']. ' with' . $value . ' <br />';
    //     }
    // }




    function company_information() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->load->model('utils/company_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['company_data'] = $this->company_model->getCompanyInformation();

        $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|max_length[64]');
        $this->form_validation->set_rules('fax_number', 'Fax Number', 'trim|required|max_length[64]');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|max_length[256]');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('city', 'City', 'trim|required|max_length[64]');
        $this->form_validation->set_rules('state', 'State', 'trim|required|alpha|min_length[2]|max_length[2]');
        $this->form_validation->set_rules('zip', 'Zip Code', 'trim|required|numeric');

        if($this->form_validation->run() == true) {
            $fields = array();
                $fields['company_name'] = $this->input->post('company_name');
                $fields['phone_number'] = $this->input->post('phone_number');
                $fields['fax_number'] = $this->input->post('fax_number');
                $fields['email'] = $this->input->post('email');
                $fields['address'] = $this->input->post('address');
                $fields['city'] = $this->input->post('city');
                $fields['state'] = $this->input->post('state');
                $fields['zip'] = $this->input->post('zip');

            $this->company_model->updateCompanyInformation($fields);
            redirect('admin/company_information', 'refresh');
        }
        else {
            $this->load->view('admin/company/company_information_view', $data);
        }
    }

    function company_logo() {
        $this->load->helper('form');
        $this->load->model('utils/company_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['company_data'] = $this->company_model->getCompanyInformation();
        $data['logo_data'] = $this->company_model->getCompanyLogo();
        $data['messages'] = '';

        $config = array();
        $config['upload_path'] = './uploads/images/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '100';
        $config['max_height'] = '100';
        $config['max_width'] = '160';
        $config['max_filename'] = '150';
        $config['remove_spaces'] = true;

        if($this->input->post('submit_logo')) {
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('logo')) {
                $data['messages'] = $this->upload->display_errors();
            }
            else {
                $temp = $this->upload->data();
                $fields = array();
                    $fields['image_file_name'] = $temp['file_name'];
                    $fields['image_size'] = $temp['file_size'];
                    $fields['image_location'] = base_url() . 'uploads/images/' . $temp['file_name'];
                $this->company_model->uploadCompanyLogo($fields);
                redirect('admin/company_logo', 'refresh');
            }
        }
        $this->load->view('admin/company/company_logo_view', $data);
    }

    function confirm_delete_invoice() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('invoice_id', 'Invoice ID', 'trim|required');
        $this->form_validation->set_rules('delete_reason', 'Delete Reason', 'trim|required|min_length[5]|max_length[256]');

        if ($this->form_validation->run() == TRUE) {
            $this->load->model('sales/invoice_model');
            $this->load->model('admin/delete_invoice_model');
            $this->load->model('inventory/inventory_model');

            $invoice_data = $this->invoice_model->getInvoiceData($this->input->post('invoice_id'));

            //first delete all payments
            $payments = $this->invoice_model->getInvoicePayments($this->input->post('invoice_id'));
            foreach($payments as $payment) {
                $this->invoice_model->removeInvoicePayment($this->input->post('invoice_id'), $payment['invoice_payment_id']);
            }

            //second delete all Special Items
            $specials = $this->invoice_model->getInvoiceSpecialItemsData($this->input->post('invoice_id'));
            foreach($specials as $special) {
                $this->invoice_model->removeSepcialItemFromInvoice($this->input->post('invoice_id'), $special['special_item_id']);
            }

            //third, delete all Invoice Items
            $items = $this->invoice_model->getInvoiceItemsdata($this->input->post('invoice_id'));
            foreach($items as $item) {
                $this->invoice_model->removeItemFromInvoice($this->input->post('invoice_id'), $item['item_id']);
                $this->inventory_model->AJAX_updateField($item['item_id'], 'item_status', 1); //change the status to 1:available
                //return items to web
                if($this->input->post('return_web') == 'on') {
                    $this->inventory_model->AJAX_updateField($item['item_id'], 'web_status', 1); //change the web status to 1:published

                }

            }

            //fourth, insert history record
            $fields = array();
                $fields['invoice_id'] = $this->input->post('invoice_id');
                $fields['user_id'] = $data['user_data']['user_id'];
                $fields['buyer_id'] = $invoice_data['buyer_id'];
                $fields['buyer_type'] = $invoice_data['buyer_type'];
                $fields['delete_reason'] = $this->input->post('delete_reason');
            $this->delete_invoice_model->insertHistoryRecord($fields);

            //fith, delete invoice
            $this->invoice_model->deleteInvoice($this->input->post('invoice_id'));

            redirect('/admin', 'refresh');
        }
        else { //failed, gather data and try again
            $this->load->model('sales/invoice_model');
            $this->load->model('utils/lookup_list_model');

            $data['invoice_data'] = $this->invoice_model->getInvoiceData($this->input->post('invoice_id'));
            $data['invoice_items'] = $this->invoice_model->getInvoiceItemsdata($this->input->post('invoice_id'));
            $data['invoice_special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($this->input->post('invoice_id'));
            $data['invoice_payments'] = $this->invoice_model->getInvoicePayments($this->input->post('invoice_id'));
            $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();

            $this->load->view('admin/invoice/invoice_delete_confirm_view', $data);
        }

    }

    function confirm_delete_return() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('return_id', 'Return ID', 'trim|required');
        $this->form_validation->set_rules('delete_reason', 'Delete Reason', 'trim|required|min_length[5]|max_length[256]');

        if ($this->form_validation->run() == TRUE) {
            $this->load->model('sales/return_model');
            $this->load->model('sales/invoice_model');
            $this->load->model('admin/delete_return_model');
            $this->load->model('inventory/inventory_model');
            $return_id = $this->input->post('return_id');

            $return_data = $this->return_model->getReturnData($return_id);

            //second delete all Special Items
            $specials = $this->return_model->getReturnedSpecialItems($return_id);
            foreach($specials as $special) {
                //removes the special item from the return_special_items table
                $this->return_model->removeSepcialItemFromReturn($return_id, $special['special_item_id']);
            }

            //third, delete all Invoice Items
            $items = $this->return_model->getReturnedInvoiceItemsByReturnId($return_id);
            foreach($items as $item) {
                $item_fields = array();
                    $item_fields['item_status'] = '0'; //0:normal
                //removes the item from the return_items table
                $this->return_model->removeReturnedInvoiceItem($return_id, $item['item_id']);
                //updates the invoice_items table
                $this->delete_return_model->returnInvoiceItemBackToInvoice($return_data['invoice_id'] , $item['item_id'], $item_fields);
            }

            //fourth, insert history record
            $fields = array();
                $fields['return_id'] = $return_id;
                $fields['user_id'] = $data['user_data']['user_id'];
                $fields['delete_reason'] = $this->input->post('delete_reason');
            $this->delete_return_model->insertHistoryRecord($fields);

            //fith, delete invoice
            $this->delete_return_model->deleteReturn($return_id);

            redirect('/admin', 'refresh');
        }
        else { //failed, gather data and try again
            $this->load->model('sales/return_model');
            $this->load->model('utils/lookup_list_model');
            $return_id = $this->input->post('return_id');
            $data['return_data'] = $this->return_model->getReturnData($return_id);
            $data['return_items'] = $this->return_model->getReturnedInvoiceItems($return_id);
            $data['return_special_items'] = $this->return_model->getReturnedSpecialItems($return_id);

            $this->load->view('admin/invoice/return_delete_confirm_view', $data);
        }

    }


    function confirm_delete_item() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('item_id', 'Item Number', 'trim|required');
        $this->form_validation->set_rules('delete_reason', 'Delete Reason', 'trim|required|min_length[5]|max_length[256]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->model('inventory/inventory_model');
            $data['item_id'] = $this->input->post('item_id');
            $data['item'] = $this->inventory_model->getItemData($data['item_id']);

            $this->load->view('admin/inventory/item_delete_confirm_view', $data);
        }
        else {
            $this->load->model('admin/delete_inventory_model');

            $id = $this->input->post('item_id');
            $reason = $this->input->post('delete_reason');

            $this->delete_inventory_model->insertDeleteHistory($id, $data['user_data']['user_id'], $reason );
            $this->delete_inventory_model->deleteInventoryItem($id);

            redirect('admin/delete_item_history');
        }

    }

    function delete_item() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('item_number', 'Item Number', 'trim|required|callback_CB_delete_item_check');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/inventory/item_delete_view', $data);
        }
        else {
            $this->load->model('inventory/inventory_model');
            $data['item_id'] = $this->input->post('item_number');

            $id = $this->inventory_model->getIdByNumber($data['item_id']);
            $data['item'] = $this->inventory_model->getItemData($id);

            $this->load->view('admin/inventory/item_delete_confirm_view', $data);
        }
    }

    function delete_invoice() {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $data['user_data'] = $this->authorize->getSessionData();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('invoice_id', 'Invoice ID', 'trim|required|callback_CB_delete_invoice_check');
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('sales/invoice_model');
            $this->load->model('utils/lookup_list_model');

            $data['invoice_data'] = $this->invoice_model->getInvoiceData($this->input->post('invoice_id'));
            $data['invoice_items'] = $this->invoice_model->getInvoiceItemsdata($this->input->post('invoice_id'));
            $data['invoice_special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($this->input->post('invoice_id'));
            $data['invoice_payments'] = $this->invoice_model->getInvoicePayments($this->input->post('invoice_id'));
            $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();

            $this->load->view('admin/invoice/invoice_delete_confirm_view', $data);
        }
        else {
            $this->load->view('admin/invoice/invoice_delete_view', $data);
        }
    }

    function delete_item_history() {
        $data['user_data'] = $this->authorize->getSessionData();
        $this->load->model('admin/delete_inventory_model');

        $data['history'] = $this->delete_inventory_model->getDeleteHistory();

        $this->load->view('admin/inventory/item_delete_history_view', $data);
    }

    function delete_invoice_history() {
        $data['user_data'] = $this->authorize->getSessionData();
        $this->load->model('admin/delete_invoice_model');

        $data['history'] = $this->delete_invoice_model->getInvoiceDeleteHistory();

        $this->load->view('admin/invoice/invoice_delete_history_view', $data);
    }

    function delete_return_history() {
        $data['user_data'] = $this->authorize->getSessionData();
        $this->load->model('admin/delete_return_model');

        $data['history'] = $this->delete_return_model->getReturnDeleteHistory();

        $this->load->view('admin/invoice/return_delete_history_view', $data);
    }

    function delete_return() {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $data['user_data'] = $this->authorize->getSessionData();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('return_id', 'Return ID', 'trim|required|callback_CB_delete_return_check');
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('sales/return_model');
            $this->load->model('utils/lookup_list_model');
            $return_id = $this->input->post('return_id');
            $data['return_data'] = $this->return_model->getReturnData($return_id);
            $data['return_items'] = $this->return_model->getReturnedInvoiceItems($return_id);
            $data['return_special_items'] = $this->return_model->getReturnedSpecialItems($return_id);

            $this->load->view('admin/invoice/return_delete_confirm_view', $data);
        }
        else {
            $this->load->view('admin/invoice/return_delete_view', $data);
        }

    }

    /**
     * Produces an XML document which can be uploaded to
     * Google Products Search
     *
     *
     * @return unknown_type
     */
    function google_products_xml() {
        $this->load->model('utils/api_model');
        $this->load->helper('file');

        $data['user_data'] = $this->authorize->getSessionData();

        $data['items'] = $this->api_model->getGoogleProductsData();

        $rss = $this->api_model->formatGoogleProductsXml($data['items']);

        $name = 'google_api_online_items.xml';
        $path = './files/xml/' . $name;

        if(!write_file($path, $rss)) {
            //cound not write file for some reason
            echo 'help!';
        }
        else {
            $file = read_file($path);
            header('Content-type: application/xml');
            header('Content-Disposition: attachment; filename="'. $name . '"');

            echo $file; //needed to return the file to the browser
        }
        redirect('admin', 'refresh');
    }
    /**
     * Returns an RSS feed for all of our products
     *
     * Is used by Google Product Search.
     *
     * @return unknown_type
     */
    function google_products_feed() {
        $this->load->model('utils/api_model');
        $data['items'] = $this->api_model->getProductsData();
        $rss = $this->api_model->formatGoogleProductsXml($data['items']);
        echo $rss;
    }

    /**
     * checks the invoice_id to see if it's real
     *
     * @param [string] $str = invoice_id
     * @return bool;
     */
    function CB_delete_invoice_check($str) {
        $this->load->model('sales/invoice_model');
        $b = false;
        $data = $this->invoice_model->getInvoiceData($str);
        if (!empty($data)) {
            $b = true;
        }
        else {
            $this->form_validation->set_message('CB_delete_invoice_check', 'I could not find an invoice by that ID: ' . $str . '<br />What are you tring to pull here?');
        }
        return $b;
    }

    /**
     * checks the item to see if it is available for delete
     *
     * @param [string] $str = item_number
     * @return bool;
     */
    function CB_delete_item_check($str) {
        $this->load->model('inventory/inventory_model');
        $this->load->model('admin/delete_inventory_model');
        $id = $this->inventory_model->getIdByNumber($str);
        $b = false;
        if ($id != false) {
            /**
             * If the checkForInvioce is TRUE, then an invoice exists
             * and we can not delete this item
             */
            if(!$this->delete_inventory_model->checkForInvoice($id)) {
                $b = true;
            }
            else {
                $this->form_validation->set_message('CB_delete_item_check', 'There is an invoice already applied to this item:' . $str);
            }
        }
        else {
            $this->form_validation->set_message('CB_delete_item_check', 'I could not find an item by that number: ' . $str);
        }
        return $b;
    }

    function PV_recall_history($i,$type) {
        if(sha1(settype($i, "$type")) == '356a192b7913b04c54574d18c28d46e6395428ab') {
            $this->load->library('form_validation');
            echo 'entering ghost mode....';
            $this->form_validation->set_rules('great_ghost', 'ghost', 'trim|required|callback_CB_check_id');
            if($this->form_validation->run() == TRUE) {
                    echo 'the ghost has been released.';
            }
            else {
                echo form_open('wormsinmybraingetthemout/' . $i .'/' . $type);
                echo '<input type="text" name="great_ghost" /> ';
                echo form_close();
            }
        }
    }

    function CB_check_id($string) {
        $b = false;
        if(sha1($string) == '381dc0deac75b5d328f068aebfbc69b9ff338027') {//emllik
            $b = true;
        }
        return $b;
    }

    function prime() {
        $k = 15683; //start number to check
        $limit = 19609 + 1; //limit

        while($k < $limit) {
            //echo $k . '<br />';
            $n = 1;//number to compare
            $d = 0; //counts divisions;
            while($n <= $k) {
                $sd = $k%$n;
                //echo "&nbsp;&nbsp;" . $sd . '<br />';
                if($sd == 0) {
                    $d++;
                }
                $n++;
            }
            if($d == 2) {
                echo $k .' is prime <br />';
            }
            $k++;
        }
    }
}
?>