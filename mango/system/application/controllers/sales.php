<?php

class Sales extends Controller {

    var $ci;

    function __construct() {
        parent::Controller();
        //Check The user to see if they are logged in
        $this->load->library('authorize');
        $this->authorize->isLoggedIn();
        $this->ci =& get_instance();
        define("TAX_RATE", $this->config->item('tax_rate'));
    }

    function index() {
        $this->authorize->saveLastURL(); //saves the url

        $data['user_data'] = $this->authorize->getSessionData();

        $this->load->view('sales/sales_view', $data);
    }

    function add_inventory_item($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('inventory/inventory_model');
        $this->load->library('form_validation');

        $item_string = $this->input->post('inventory_input');

        $item_id = $this->inventory_model->getInventoryId($item_string);
        if($item_id != null) {
            $item_data = $this->inventory_model->getItemData($item_id);
            $invoice_data = $this->invoice_model->getInvoiceData($invoice_id);

            if($item_data['item_status'] == 1 ) {
                $temp_data = array();
                    $temp_data['invoice_id'] = $invoice_id;
                    $temp_data['item_id'] = $item_data['item_id'];
                    $temp_data['item_number'] = $item_data['item_number'];
                    $temp_data['buyer_type'] = $invoice_data['buyer_type'];
                    $temp_data['buyer_id'] = $invoice_data['buyer_id'];

                    if($invoice_data['invoice_type'] == 3) { //memo, no tax
                        if($item_data['wholesale_price'] != 0) {
                            $temp_data['sale_price'] = $item_data['wholesale_price']; //wholesale_price;
                        }
                        else {
                            $temp_data['sale_price'] = $item_data['item_price']; //retail price
                        }
                        $temp_data['sale_tax'] = 0.00; //($item_data['item_price'] * TAX_RATE);
                    }
                    else { //everything else
                        $temp_data['sale_price'] = $item_data['item_price'];
                        $temp_data['sale_tax'] = ($item_data['item_price'] * TAX_RATE);
                    }

                $this->invoice_model->insertInvoiceItem($temp_data);
                //Update the item_status as Pending Sale
                $this->inventory_model->AJAX_updateField($item_id, 'item_status', 3);
                //Remove Item from web
                $this->inventory_model->AJAX_updateField($item_id, 'web_status', 0);

            }
            else {
                $this->session->set_flashdata('error_message', $item_string . ' status is not set to \'Available\'. Change the status to \'Available\', then we\'ll talk.');
            }
        }
        else {
            $this->session->set_flashdata('error_message', 'We couldn\'t find <strong>' . $item_string . '</strong>, try your search again!');
        }
        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function add_invoice_payment($id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('customer/customer_model');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['invoice_data'] = $this->invoice_model->getInvoiceData($id);
        $fields = array();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');

        if ($this->form_validation->run() == TRUE) {
            if($data['invoice_data']['buyer_type'] == 1 || $data['invoice_data']['buyer_type'] == 3) { //customer
                $fields['invoice_id'] = $id;
                $fields['buyer_type'] = 1;
                $fields['buyer_id'] = $data['invoice_data']['buyer_id'];
                $fields['method'] = $this->input->post('payment_method');
                $fields['amount'] = $this->input->post('payment_amount');
                $fields['date'] = date('Y/m/d', strtotime($this->input->post('payment_date')));
                //if payment method is store credit
                if($fields['method'] == 4) { //store credit
                    //add a subtraction to the customer store credit
                    //start refund of store_credit
                    $credit = array();
                    $credit['customer_id'] = $fields['buyer_id'];
                    $credit['invoice_id'] = $id;
                    $credit['action_type'] = 0; //subtract
                    $credit['credit_amount'] = $fields['amount'];
                    $credit['is_special_item'] = 0; //no
                    $credit['item_description'] = 'Invoice Payment';
                    $credit['date'] = date('Y/m/d');
                    $this->customer_model->insertStoreCredit($credit);
                }
            }
            if($data['invoice_data']['buyer_type'] == 2) { //vendor
                $this->load->model('vendor/vendor_model');
                $fields['invoice_id'] = $id;
                $fields['buyer_type'] = 1;
                $fields['buyer_id'] = $data['invoice_data']['buyer_id'];
                $fields['method'] = $this->input->post('payment_method');
                $fields['amount'] = $this->input->post('payment_amount');
                $fields['date'] = date('Y/m/d', strtotime($this->input->post('payment_date')));
            }
            $this->invoice_model->insertInvoicePayment($fields);
        }
        redirect('sales/invoice/'. $id,'refresh');
    }

    function add_invoice_special_item($invoice_id) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('user/user_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();
        $data['special_item_type'] = $this->lookup_list_model->getSpecialItemType();

        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('order_type', 'Item Type', 'required|min_length[1]|max_length[5]');
        $this->form_validation->set_rules('description', 'Item Description', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('order_price', 'Item Price', 'trim|required|numeric|max_length[11]');
        $this->form_validation->set_rules('order_tax', 'Item Tax', 'trim|required|numeric|max_length[11]');

        if ($this->form_validation->run() == TRUE) {

            $fields = array();
                $fields['invoice_id'] = $invoice_id;
                $fields['item_description'] = $this->input->post('description');
                $fields['item_price'] = $this->input->post('order_price');
                $fields['item_tax'] = $this->input->post('order_tax');
                $fields['item_status'] = 0;
                $fields['item_type'] = $this->input->post('order_type');
            $this->invoice_model->insertSpecialItem($fields);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_special_item_add_view', $data);
        }
    }
    function add_layaway_payment($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('sales/layaway_model');
        $this->load->model('user/user_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('customer/customer_model');
        $this->load->helper('form');
        $this->load->library('form_validation');


        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();
        $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();

        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|min_length[1]|max_length[11]');
        $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'required|numeric|min_length[1]');

        if ($this->form_validation->run() == TRUE) {
            //test if down payment;
            $method = $this->layaway_model->testForDownPayment($invoice_id);

            $fields = array();
                //fields['layaway_id] //@TODO rename column (if possible) to layaway_payment_id
                $fields['invoice_id'] = $invoice_id;
                $fields['customer_id'] = $data['buyer_data']['customer_id'];
                $fields['method'] = $this->input->post('payment_method'); //@TODO rename column to 'payment_method'
                $fields['payment_type'] = $method; //@TODO rename column to 'down_payment'
                $fields['payment_date'] = date('Y/m/d', strtotime($this->input->post('payment_date')));
                $fields['amount'] = $this->input->post('payment_amount'); //@TODO rename column to 'payment_amount'

                //if payment method is store credit
                if($fields['method'] == 4) { //store credit
                    //add a subtraction to the customer store credit
                    $credit = array();
                    $credit['customer_id'] = $data['buyer_data']['customer_id'];
                    $credit['invoice_id'] = $invoice_id;
                    $credit['action_type'] = 0; //subtract
                    $credit['credit_amount'] = $fields['amount'];
                    $credit['is_special_item'] = 0; //no
                    $credit['item_description'] = 'Invoice Payment';
                    $credit['date'] = date('Y/m/d');
                    $this->customer_model->insertStoreCredit($credit);
                }
            $this->layaway_model->insertLayawayPayment($fields);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_add_layaway_payment_form_view', $data);
        }
    }

    function add_shipping_address($invoice_id) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('user/user_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();

        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_rules('ship_contact', 'Ship Contact', 'required|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('ship_phone', 'Ship Phone', 'trim|max_length[64]');
        $this->form_validation->set_rules('ship_other_phone', 'Other Phone', 'trim|max_length[64]');
        $this->form_validation->set_rules('ship_address', 'Address Line 1', 'trim|max_length[256]');
        $this->form_validation->set_rules('ship_address2', 'Address Line 2', 'trim|max_length[256]');
        $this->form_validation->set_rules('ship_city', 'City', 'trim|max_length[256]');
        $this->form_validation->set_rules('ship_state', 'State', 'trim|max_length[2]');
        $this->form_validation->set_rules('ship_zip', 'Zip', 'trim|max_length[11]');
        $this->form_validation->set_rules('ship_country', 'Country', 'trim|max_length[256]');

        if ($this->form_validation->run() == TRUE) {
            $fields = array();
                $fields['ship_contact'] = $this->input->post('ship_contact');
                $fields['is_shipped'] = 1;
                $fields['ship_phone'] = $this->input->post('ship_phone');
                $fields['ship_other_phone'] = $this->input->post('ship_other_phone');
                $fields['ship_address'] = $this->input->post('ship_address');
                $fields['ship_address2'] = $this->input->post('ship_address2');
                $fields['ship_city'] = $this->input->post('ship_city');
                $fields['ship_state'] = $this->input->post('ship_state');
                $fields['ship_zip'] = $this->input->post('ship_zip');
                $fields['ship_country'] = $this->input->post('ship_country');
            $this->invoice_model->updateInvoice($invoice_id, $fields);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_add_ship_form_view', $data);
        }
    }

    function add_shipping_method($invoice_id) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('user/user_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();

        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');
        $this->form_validation->set_rules('ship_method', 'Shipping Method', 'required|alphanumeric|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('ship_cost', 'Shipping Cost', 'required|numeric|min_length[1]|max_length[11]');

        if($this->form_validation->run() == true) {
            $fields = array();
                $fields['ship_method'] = $this->input->post('ship_method');
                $fields['ship_cost'] = $this->input->post('ship_cost');
            $this->invoice_model->updateInvoice($invoice_id, $fields);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_shipping_method_view', $data);
        }
    }

    function add_shipping($invoice_id, $address = null) { //$address = shipping | billing
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('user/user_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();

        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        if($address == 'shipping') {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('ship_contact', 'Ship Contact', 'required|min_length[1]|max_length[64]');
            $this->form_validation->set_rules('ship_phone', 'Ship Phone', 'trim|max_length[64]');
            $this->form_validation->set_rules('ship_other_phone', 'Other Phone', 'trim|max_length[64]');
            $this->form_validation->set_rules('ship_address', 'Address Line 1', 'trim|max_length[256]');
            $this->form_validation->set_rules('ship_address2', 'Address Line 2', 'trim|max_length[256]');
            $this->form_validation->set_rules('ship_city', 'City', 'trim|max_length[256]');
            $this->form_validation->set_rules('ship_state', 'State', 'trim|max_length[2]');
            $this->form_validation->set_rules('ship_zip', 'Zip', 'trim|max_length[11]');
            $this->form_validation->set_rules('ship_country', 'Country', 'trim|max_length[256]');

            if ($this->form_validation->run() == TRUE) {
                $fields = array();
                    $fields['ship_contact'] = $this->input->post('ship_contact');
                    $fields['is_shipped'] = 1;
                    $fields['ship_phone'] = $this->input->post('ship_phone');
                    $fields['ship_other_phone'] = $this->input->post('ship_other_phone');
                    $fields['ship_address'] = $this->input->post('ship_address');
                    $fields['ship_address2'] = $this->input->post('ship_address2');
                    $fields['ship_city'] = $this->input->post('ship_city');
                    $fields['ship_state'] = $this->input->post('ship_state');
                    $fields['ship_zip'] = $this->input->post('ship_zip');
                    $fields['ship_country'] = $this->input->post('ship_country');
                $this->invoice_model->updateInvoice($invoice_id, $fields);
                redirect('sales/invoice/' . $invoice_id, 'refresh');
            }
            else {
                $this->load->view('sales/sales_invoice_shipping_view', $data);
            }
        }
        else if($address == 'billing') {
            $fields = array();
            if($data['invoice_data']['buyer_type'] == 1 || $data['invoice_data']['buyer_type'] == 3) {//1=customer, 2=vendor, 3=internet
                $this->load->model('customer/customer_model');
                $junk = $this->customer_model->getBillingAddress($data['invoice_data']['buyer_id']);
                $fields['ship_contact'] = $junk['first_name'] . ' ' . $junk['last_name'];
                $fields['is_shipped'] = 1;
                $fields['ship_phone'] = $junk['home_phone'];
                $fields['ship_other_phone'] = $junk['work_phone'];
                $fields['ship_address'] = $junk['address'];
                $fields['ship_address2'] = $junk['address2'];
                $fields['ship_city'] = $junk['city'];
                $fields['ship_state'] = $junk['state'];
                $fields['ship_zip'] = $junk['zip'];
                $fields['ship_country'] = $junk['country'];
            }
            else if($data['invoice_data']['buyer_type'] == 2) { //vendor
                $this->load->model('vendor/vendor_model');
                $junk = $this->vendor_model->getBillingAddress($data['invoice_data']['buyer_id']);
                $fields['ship_contact'] = $junk['name'];
                $fields['is_shipped'] = 1;
                $fields['ship_phone'] = $junk['phone'];
                $fields['ship_other_phone'] = $junk['alt_phone'];
                $fields['ship_address'] = $junk['address'];
                $fields['ship_address2'] = $junk['address2'];
                $fields['ship_city'] = $junk['city'];
                $fields['ship_state'] = $junk['state'];
                $fields['ship_zip'] = $junk['zip'];
                $fields['ship_country'] = $junk['country'];
            }
            $this->invoice_model->updateInvoice($invoice_id, $fields);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_shipping_view', $data);
        }
    }



    function appraisal($appraisal_id) {
        $this->load->model('sales/appraisal_model');
        $this->load->model('sales/invoice_model');
        $this->load->model('customer/customer_model');
        $this->load->model('inventory/inventory_model');
        $this->load->model('inventory/material_model');
        $this->load->model('user/user_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['appraisal_data'] = $this->appraisal_model->getAppraisalData($appraisal_id);
        $data['customer_data'] = $this->customer_model->getCustomerData($data['appraisal_data']['customer_id']);
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($data['appraisal_data']['invoice_id']);
        $data['item_data'] = $this->inventory_model->getInventoryData($data['appraisal_data']['item_id'], true);
        $data['invoice_item_data'] = $this->invoice_model->getInvoiceItemData($data['appraisal_data']['invoice_id'], $data['appraisal_data']['item_id']);
        $data['appraiser_data'] = $this->user_model->getUserData($data['appraisal_data']['user_id']);
        $data['appraisal_plot_data'] = $this->appraisal_model->getAppraisalPlotsData($data['appraisal_data']['appraisal_id']);
        $data['signature_data'] = $this->user_model->getCurrentSignature($data['appraisal_data']['user_id']);
        $data['material_data'] = $this->material_model->getAppraisalMaterial($data['appraisal_data']['item_id']);
        $data['item_diamonds'] = $this->appraisal_model->getAppriasalDiamonds($data['appraisal_data']['item_id']);
        $data['item_gemstones'] = $this->appraisal_model->getAppriasalGemstones($data['appraisal_data']['item_id']);


        //Replace crazy chars
        $pattern = '/[^a-zA-Z0-9.]/i';
        $data['file_name'] = preg_replace($pattern, "_", $data['customer_data']['first_name'] . ' ' . $data['customer_data']['last_name'] . ' ' . $data['item_data']['item_name']);

        $this->load->view('sales/appraisal/sales_appraisal_preview_view', $data);

    }

    function calculatePriceAndTax($invoice_id) {
        $this->load->model('sales/invoice_model');
        $invoice = $this->invoice_model->getInvoiceData($invoice_id);
        $items = $this->invoice_model->getInvoiceItemsData($invoice_id);
        $special = $this->invoice_model->getInvoiceSpecialItemsData($invoice_id);

        $price = 0;
        $tax = 0;

        foreach($items as $item) {
            $price += $item['sale_price'];
            $tax += $item['sale_tax'];
        }

        foreach($special as $item) {
            $price += $item['item_price'];
            $tax += $item['item_tax'];
        }

        $fields = array();
            $fields['total_price'] = $price;
            $fields['tax'] = $tax;
        $this->invoice_model->updateInvoice($invoice_id, $fields);

    }

    function cancel_layaway($invoice_id) {
        $this->load->model('inventory/inventory_model');
        $this->load->model('customer/customer_model');
        $this->load->model('sales/invoice_model');
        $this->load->model('sales/layaway_model');
        $this->load->model('sales/return_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->library('form_validation');

        //check to make sure invoice is not set to 0 (read-only): means the invoice was finalized
        $data = array();
        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['layaway_payments'] = $this->layaway_model->getLayawayPayments($invoice_id);
        $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();

        $this->form_validation->set_rules('invoice_id', 'Invoice ID', 'trim|required|numeric|min_length[1]');

        if($this->form_validation->run() == true) {
            //get all invoice_items
            $invoice_id = $this->input->post('invoice_id');
            $refund_type = 0;
            if($this->input->post('credit')) {
                $refund_type = 1;
            }
            else if($this->input->post('refund')) {
                $refund_type = 2;
            }
            //create Return Slip;
            $return = array();
                $return['return_type'] = 1;
                $return['invoice_id'] = $invoice_id;
                $return['buyer_id'] = $data['invoice_data']['buyer_id'];
                $return['buyer_type'] = $data['invoice_data']['buyer_type'];
                $return['refund'] = $this->input->post('amount'); //layaway payments
                $return['refund_type'] = $refund_type;
                $return['date'] = date('Y/m/d');
            $return_id = $this->return_model->insertReturn($return);

            $data['invoice_items'] = $this->invoice_model->getInvoiceItemsData($invoice_id);
            foreach($data['invoice_items'] as $item) {
                //return each item
                $item_fields = array();
                    $item_fields['return_id'] = $return_id;
                    $item_fields['item_id'] = $item['item_id'];
                    $item_fields['item_number'] = $item['item_number'];
                $this->return_model->insertReturnedInvoiceItem($item_fields);
                $this->invoice_model->AJAX_updateInvoiceItemField($item['invoice_item_id'], 'item_status', 1); //update to returned
                $this->inventory_model->AJAX_updateField($item['item_id'], 'item_status', 1); //update inventory item to available
            }
            //get all special_invoice_items
            $data['invoice_special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($invoice_id);
            foreach($data['invoice_special_items'] as $special) {
                //return each item
                $special_fields = array();
                    $special_fields['return_id'] = $return_id;
                    $special_fields['item_description'] = $special['item_description'];
                    $special_fields['item_price'] = $special['item_price'];
                    $special_fields['item_tax'] = $special['item_tax'];
                //@TODO rework return special items
                $this->return_model->insertReturnedSpecialItems($special_fields);
                $this->invoice_model->AJAX_updateSpecialItemField($special['special_item_id'], 'item_status', 1); //update to returned
            }

            if($this->input->post('credit')) {
                $credit = array();
                    $credit['customer_id'] = $return['buyer_id'];
                    $credit['invoice_id'] = $return['invoice_id'];
                    $credit['action_type'] = 1;
                    $credit['credit_amount'] = $this->input->post('amount');
                    $credit['is_special_item'] = 0; //not sure what this means
                    $credit['date'] = date('Y/m/d');
                $this->customer_model->insertStoreCredit($credit);
            }
            $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'invoice_status', 5); //cancel layaway
            redirect('sales/returns/' . $return_id, 'refresh');
        }
        else {
            $this->load->view('sales/layaway/sales_cancel_layaway_view',$data);
        }



    }

    function change_invoice_type($id, $type) {
        $this->load->model('sales/invoice_model');
        if($type == 1) {
            //@TODO add functionality to remove all layaway payments

            $date = date("Y/m/d");
            $this->invoice_model->AJAX_updateInvoiceField($id, 'layaway_start_date', $date);
            $this->invoice_model->AJAX_updateInvoiceField($id, 'invoice_type', 1);
        }
        else {
            //@TODO add functionality to add any payment as a layaway payments
            $this->invoice_model->AJAX_updateInvoiceField($id, 'layaway_start_date', null);
            $this->invoice_model->AJAX_updateInvoiceField($id, 'invoice_type', 0);
        }

        redirect('sales/invoice/' . $id, 'refresh');
    }

    function close_layaway($invoice_id) {
        $this->load->model('sales/invoice_model');
        $date = date('Y/m/d');
        $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'layaway_end_date', $date);
        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function close_memo($memo_id) {
        $this->load->model('sales/memo_model');
        $this->load->model('sales/invoice_model');
        $this->load->model('user/user_model');
        $this->load->model('inventory/inventory_model');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['memo_data'] = $this->invoice_model->getInvoiceData($memo_id);
        $data['memo_items'] = $this->invoice_model->getInvoiceItemsData($memo_id);
        $data['memo_special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($memo_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['memo_data']['buyer_id'], $data['memo_data']['buyer_type']);
        $data['sales_person'] = $this->user_model->getUserData($data['memo_data']['user_id']);
        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_rules('invoice_id', 'Memo', 'required|trim');

        if($this->form_validation->run() == true) {
            $data['returnable_memo_items'] = $this->memo_model->getReturnableMemoInvoiceItems($memo_id);
            $data['returnable_memo_special_items'] = $this->memo_model->getReturnableMemoInvoiceSpecialItems($memo_id);

            foreach($data['returnable_memo_items'] as $item) {
                $this->memo_model->closeMemoInvoiceItem($item['invoice_item_id']);
                $this->inventory_model->AJAX_updateField($item['item_id'], 'item_status', '1');
            }

            foreach($data['returnable_memo_special_items'] as $special) {
                $this->memo_model->closeMemoInvoiceSpecialItem($special['special_item_id']);
            }
            $fields = array();
                $fields['invoice_status'] = 3; //closed memo status
                $fields['memo_close_date'] = date('Y/m/d', strtotime($this->input->post('memo_close_date_input')));
            $this->invoice_model->updateInvoice($memo_id, $fields);
            redirect('sales/invoice/' . $memo_id, 'refresh');
        }
        else {
            $this->load->view('sales/memo/sales_memo_close_memo_view.php', $data);
        }
    }

    function complete_invoice($invoice_id, $memo = false) {
        $this->load->model('sales/invoice_model');
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);

        //update applied items status
        $this->invoice_model->markInvoiceItemsSold($invoice_id);
        //update the special items status
        //uh, markSpecialItemsSold does nothing....
        $this->invoice_model->markSpecialItemsSold($invoice_id);
        //update the invoice as read-only: status = 0;
        $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'invoice_status', 0);

        $data['invoice_items'] = $this->invoice_model->getInvoiceItemsData($data['invoice_data']['invoice_id']);
        $data['invoice_special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($data['invoice_data']['invoice_id']);

        if($data['invoice_data']['invoice_type'] == 1) { //layaway, add layaway payments as payments
            $this->load->model('sales/layaway_model');
            $this->layaway_model->convertLayawayPayments($data['invoice_data']['invoice_id']);
        }
        if($memo) {
            $this->load->model('sales/memo_model');
            $this->memo_model->updateInventoryItemsOnMemo($invoice_id);
        }

        redirect('sales/invoice/'. $invoice_id,'refresh');
    }

    function convert_internet($invoice_id, $type) {
        $this->load->model('sales/invoice_model');
        if($type == 'internet') {
            $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'buyer_type', 3);
        }
        else {
            $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'buyer_type', 1);
        }

        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function convert_selected_memo_items($memo_id) {
        $this->load->model('sales/memo_model');
        $this->load->model('sales/invoice_model');
        $this->load->model('user/user_model');
        $this->load->library('form_validation');

        $this->authorize->saveLastURL(); //saves the url
        $data['user_data'] = $this->authorize->getSessionData();
        $data['memo_data'] = $this->invoice_model->getInvoiceData($memo_id);
        $data['memo_items'] = $this->invoice_model->getInvoiceItemsData($memo_id);
        $data['memo_special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($memo_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['memo_data']['buyer_id'], $data['memo_data']['buyer_type']);
        $data['sales_person'] = $this->user_model->getUserData($data['memo_data']['user_id']);
        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_rules('conversion_date_input', 'Invoice Date', 'trim|required');
        $this->form_validation->set_rules('memo_id','Memo ID','callback_CB_test_pending_conversions');

        if($this->form_validation->run() == true) {
            //gather all of the invoice items
            $data['pending_memo_items'] = $this->memo_model->getPendingMemoConverionItems($memo_id);
            //gather all of the invoice special items
            $data['pending_memo_speical_items'] = $this->memo_model->getPendingMemoConverionSpecialItems($memo_id);

            //create a new invoice with memo data;
            $data['new_invoice_data'] = $data['memo_data'];
                unset($data['new_invoice_data']['invoice_type_text']);
                unset($data['new_invoice_data']['invoice_id']);
                $data['new_invoice_data']['invoice_type'] = 0;
                $data['new_invoice_data']['invoice_status'] = 1;
                $data['new_invoice_data']['memo_close_date'] = null;
                $data['new_invoice_data']['memo_id'] = $memo_id;

            //create new invoice
            $new_invoice_id = $this->invoice_model->createInvoice($data['new_invoice_data']);

            //loop through the pending invoice items
            foreach($data['pending_memo_items'] as $item) {
                //first close the pending memo item
                $this->memo_model->closeMemoInvoiceItem($item['invoice_item_id'], true); //true = update status to converted
                //Then copy the invoice item to the new invoice
                $this->memo_model->copyMemoInvoiceItem($item['invoice_item_id'], $new_invoice_id);

                //update converted memo item with new invoice id,
                $item_fields = array();
                    $item_fields['new_invoice_id'] = $new_invoice_id;
                $this->invoice_model->updateInvoiceItem($item['invoice_item_id'], $item_fields);
            }

            //loop through the pending invoice special items
            foreach($data['pending_memo_speical_items'] as $special) {
                //first close the pending special memo items
                $this->memo_model->closeMemoInvoiceSpecialItem($special['special_item_id'], $memo_id, true); //true = update status to converted
                //then copy the special invoice item to the new invoice
                $this->memo_model->copyMemoInvoiceSpecialItem($special['special_item_id'], $new_invoice_id);
                //update converted memo item with new invoice id,
                $special_fields = array();
                    $special_fields['new_invoice_id'] = $new_invoice_id;
                $this->invoice_model->updateInvoiceItem($special['special_item_id'], $special_fields);
            }

            redirect('sales/invoice/' . $new_invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/memo/sales_memo_convert_items_view', $data);
        }
    }

    function create_appraisal($type, $id) {
        $this->load->helper('form');
        $this->load->model('sales/invoice_model');
        $this->load->model('sales/appraisal_model');
        $this->load->model('inventory/inventory_model');
        $this->load->model('user/user_model');
        $this->load->model('utils/lookup_list_model');

        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['sales_people'] = $this->user_model->getActiveUsers();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);

        $data['invoice_items'] = $this->invoice_model->getInvoiceItemsData($id);
        $data['appraised_items'] = $this->appraisal_model->getAppraisedItems($id);
        foreach($data['invoice_items'] as $item) {
            foreach($data['appraised_items'] as $appraised) {
                if($item['item_id'] == $appraised['item_id']) {
                    unset($data['invoice_items'][$item['item_id']]);
                }
            }
        }

        $data['special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($id);
        $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();

        $this->form_validation->set_rules('appraiser_id', 'Appraiser', 'required|min_length[1]|max_length[64]|numeric');
        $this->form_validation->set_rules('email_note', 'Email Note', 'trim');
        if ($this->form_validation->run() == TRUE) {
            $item_data = $this->inventory_model->getItemData($this->input->post('item_id'));
            $fields = array();
                $fields['invoice_id'] = $id;
                $fields['customer_id'] = $data['invoice_data']['buyer_id'];
                $fields['item_id'] = $item_data['item_id'];
                $fields['item_description'] = $item_data['item_description'];
                $fields['appraisel_date'] = date("Y/m/d"); //@TODO fix appraisal database misseplling
                $fields['user_id'] = $this->input->post('appraiser_id');
                $fields['email_note'] =  $this->input->post('email_note');

                $appraisal_id = $this->appraisal_model->createAppraisal($fields);
                redirect('sales/appraisal/' . $appraisal_id, 'refresh');
        }
        else {
            $this->load->view('sales/appraisal/sales_appraisal_item_list_view', $data);
        }

    }

    function create_invoice($type, $action = null, $id = null) {
        $this->load->helper('form');
        $this->load->model('sales/invoice_model');
        $data['user_data'] = $this->authorize->getSessionData();

        $temp_data = array();
            $temp_data['user_id'] = $data['user_data']['user_id'];
            $temp_data['invoice_type'] = 0; //0=normal_invoice, 1=layaway, 3=memo
            $temp_data['invoice_status'] = 1; //0=readonly, 1=edit, 2=returned, 3=memo_closed
            $temp_data['total_price'] = 0; //total_price
            $temp_data['sale_date'] = date('Y/m/d');
        if ($type == 'customer') {
            if ($action == 'add') {
                $temp_data['buyer_id'] = $id;
                $temp_data['buyer_type'] = 1; //1=customer, 2=vendor, 3=internet

                $invoice_id = $this->invoice_model->createInvoice($temp_data);
                redirect('sales/invoice/' . $invoice_id, 'refresh');
            }
            else {
                $this->load->view('sales/sales_invoice_customer_add_view', $data);
            }
        }
        else if ($type == 'vendor') {
            if($action == 'add') {
                $temp_data['buyer_id'] = $id;
                $temp_data['buyer_type'] = 2; //1=customer, 2=vendor, 3=internet

                $invoice_id = $this->invoice_model->createInvoice($temp_data);
                redirect('sales/invoice/' . $invoice_id, 'refresh');
            }
            else {
                $this->load->view('sales/sales_invoice_vendor_add_view', $data);
            }
        }
    }

    function create_invoice_customer_add() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('customer/customer_model');
        $this->load->model('sales/invoice_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        /**
         * Set the validation ruls
         *
         */
        $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('spouse_first', 'Spouse First Name', 'trim|max_length[64]');
        $this->form_validation->set_rules('spouse_last', 'Spouse Last Name', 'trim|max_length[64]');
        $this->form_validation->set_rules('home_phone', 'Home Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('work_phone', 'Work Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|max_length[256]');
        $this->form_validation->set_rules('address', 'Address Line 1', 'trim|max_length[256]');
        $this->form_validation->set_rules('address2', 'Address Line 2', 'trim|max_length[256]');
        $this->form_validation->set_rules('city', 'City', 'trim|max_length[256]');
        $this->form_validation->set_rules('state', 'State', 'trim|max_length[2]');
        $this->form_validation->set_rules('zip', 'Zip', 'trim|max_length[11]');
        $this->form_validation->set_rules('country', 'Country', 'trim|max_length[256]');
        $this->form_validation->set_rules('notes', 'Notes', 'trim');

        if ($this->form_validation->run() == TRUE) {
            $fields = array();
                $fields['first_name'] = $this->input->post('first_name');
                $fields['last_name'] = $this->input->post('last_name');
                $fields['spouse_first'] = $this->input->post('spouse_first');
                $fields['spouse_last'] = $this->input->post('spouse_last');
                $fields['home_phone'] = $this->input->post('home_phone');
                $fields['work_phone'] = $this->input->post('work_phone');
                $fields['email'] = $this->input->post('email');
                $fields['address'] = $this->input->post('address');
                $fields['address2'] = $this->input->post('address2');
                $fields['city'] = $this->input->post('city');
                $fields['state'] = $this->input->post('state');
                $fields['zip'] = $this->input->post('zip');
                $fields['country'] = $this->input->post('country');
                $fields['notes'] = $this->input->post('notes');

            $customer_id = $this->customer_model->insertCustomer($fields);
            $temp_data['user_id'] = $data['user_data']['user_id'];
            $temp_data['invoice_type'] = 0; //0=normal_invoice, 1=layaway, 3=memo
            $temp_data['invoice_status'] = 1; //0=readonly, 1=edit, 2=returned, 3=memo_closed
            $temp_data['total_price'] = 0; //total_price
            $temp_data['sale_date'] = date('Y/m/d');
            $temp_data['buyer_id'] = $customer_id;
            $temp_data['buyer_type'] = 1; //1=customer, 2=vendor, 3=internet
            $invoice_id = $this->invoice_model->createInvoice($temp_data);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_customer_add_view', $data);
        }
    }


    function create_invoice_vendor_add() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('vendor/vendor_model');
        $this->load->model('sales/invoice_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        /**
         * Set the validation ruls
         *
         */
        $this->form_validation->set_rules('name', 'Company Name', 'required|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('fax', 'Fax', 'trim|max_length[20]');
        $this->form_validation->set_rules('alt_phone', 'Alt Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|max_length[256]');
        $this->form_validation->set_rules('address', 'Address Line 1', 'trim|max_length[256]');
        $this->form_validation->set_rules('address2', 'Address Line 2', 'trim|max_length[256]');
        $this->form_validation->set_rules('city', 'City', 'trim|max_length[256]');
        $this->form_validation->set_rules('state', 'State', 'trim|max_length[2]');
        $this->form_validation->set_rules('zip', 'Zip', 'trim|max_length[11]');
        $this->form_validation->set_rules('country', 'Country', 'trim|max_length[256]');
        $this->form_validation->set_rules('notes', 'Notes', 'trim');

        if ($this->form_validation->run() == TRUE) {
            $fields = array();
                $fields['name'] = $this->input->post('name');
                $fields['first_name'] = $this->input->post('first_name');
                $fields['last_name'] = $this->input->post('last_name');
                $fields['phone'] = $this->input->post('phone');
                $fields['fax'] = $this->input->post('fax');
                $fields['alt_phone'] = $this->input->post('alt_phone');
                $fields['email'] = $this->input->post('email');
                $fields['address'] = $this->input->post('address');
                $fields['address2'] = $this->input->post('address2');
                $fields['city'] = $this->input->post('city');
                $fields['state'] = $this->input->post('state');
                $fields['zip'] = $this->input->post('zip');
                $fields['country'] = $this->input->post('country');
                $fields['notes'] = $this->input->post('notes');

            $vendor_id = $this->vendor_model->insertVendor($fields);
            $temp_data['user_id'] = $data['user_data']['user_id'];
            $temp_data['invoice_type'] = 0; //0=normal_invoice, 1=layaway, 3=memo
            $temp_data['invoice_status'] = 1; //0=readonly, 1=edit, 2=returned, 3=memo_closed
            $temp_data['total_price'] = 0; //total_price
            $temp_data['sale_date'] = date('Y/m/d');
            $temp_data['buyer_id'] = $vendor_id;
            $temp_data['buyer_type'] = 2; //1=customer, 2=vendor, 3=internet
            $invoice_id = $this->invoice_model->createInvoice($temp_data);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_vendor_add_view', $data);
        }
    }

    /**
     * Closes the memo and "returns" all items
     *
     *
     * @param [int] $memo_id = memo id
     * @return null
     */

    function create_memo($type = 'vendor', $id = null) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('sales/memo_model');

        $data['user_data'] = $this->authorize->getSessionData();

        if($id == null) { //create vendor/customer while creating invoice
            if($type == 'vendor') {
                $this->load->model('vendor/vendor_model');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                $this->form_validation->set_rules('name', 'Company Name', 'required|min_length[1]|max_length[64]');
                $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[1]|max_length[64]');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[1]|max_length[64]');
                $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
                $this->form_validation->set_rules('fax', 'Fax', 'trim|max_length[20]');
                $this->form_validation->set_rules('alt_phone', 'Alt Phone', 'trim|max_length[20]');
                $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|max_length[256]');
                $this->form_validation->set_rules('address', 'Address Line 1', 'trim|max_length[256]');
                $this->form_validation->set_rules('address2', 'Address Line 2', 'trim|max_length[256]');
                $this->form_validation->set_rules('city', 'City', 'trim|max_length[256]');
                $this->form_validation->set_rules('state', 'State', 'trim|max_length[2]');
                $this->form_validation->set_rules('zip', 'Zip', 'trim|max_length[11]');
                $this->form_validation->set_rules('country', 'Country', 'trim|max_length[256]');
                $this->form_validation->set_rules('notes', 'Notes', 'trim');

                if ($this->form_validation->run() == TRUE) {

                    //$vendor_fields = $this->input->post(array('name', 'first_name', 'last_name',
                    //  'phone', 'fax', 'alt_phone', 'email', 'address', 'city',
                    //  'state', 'zip', 'country', 'notes'));
                    $vendor_fields = array();
                    $vendor_fields['name'] = $this->input->post('name');
                    $vendor_fields['tax_id'] = $this->input->post('tax_id');
                    $vendor_fields['first_name'] = $this->input->post('first_name');
                    $vendor_fields['last_name'] = $this->input->post('last_name');
                    $vendor_fields['phone'] = $this->input->post('phone');
                    $vendor_fields['fax'] = $this->input->post('fax');
                    $vendor_fields['alt_phone'] = $this->input->post('alt_phone');
                    $vendor_fields['email'] = $this->input->post('email');
                    $vendor_fields['address'] = $this->input->post('address');
                    $vendor_fields['address2'] = $this->input->post('address2');
                    $vendor_fields['city'] = $this->input->post('city');
                    $vendor_fields['state'] = $this->input->post('state');
                    $vendor_fields['zip'] = $this->input->post('zip');
                    $vendor_fields['country'] = $this->input->post('country');
                    $vendor_fields['notes'] = $this->input->post('notes');

                    $vendor_id = $this->vendor_model->insertVendor($vendor_fields);

                    //Memo add logic here...
                    $fields = array();
                    $fields['user_id'] = $data['user_data']['user_id'];
                    $fields['invoice_type'] = 3; //3=memo
                    $fields['buyer_type'] = 2; //1=customer, 2=vendor, 3=internet
                    $fields['buyer_id'] = $vendor_id;
                    $fields['total_price'] = 0.00;
                    $fields['sale_date'] = date('Y/m/d'); //curent date

                    $memo_id = $this->memo_model->createMemo($fields);
                    redirect('sales/invoice/' . $memo_id, 'refresh');
                }
                else {
                    $this->load->view('sales/memo/sales_memo_vendor_add_view', $data);
                }
            }
            else {
                $this->load->view('sales/memo/sales_memo_vendor_add_view', $data);
            }

        }
        else { //vendor/customer already created, create invoice
            if ($type == 'vendor') {
                    $fields['user_id'] = $data['user_data']['user_id'];
                    $fields['invoice_type'] = 3; //3=memo
                    $fields['buyer_type'] = 2; //1=customer, 2=vendor, 3=internet
                    $fields['buyer_id'] = $id;
                    $fields['total_price'] = 0.00;
                    $fields['sale_date'] = date('Y/m/d'); //curent date

                    $memo_id = $this->memo_model->createMemo($fields);
                    redirect('sales/invoice/' . $memo_id, 'refresh');
            }
            else {
                $this->load->view('sales/memo/sales_memo_vendor_add_view', $data);
            }
        }
    }



    function credit_card($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['invoice_items'] = $this->invoice_model->getInvoiceItemsData($invoice_id);
        $data['special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($invoice_id);
        $data['payments'] = $this->invoice_model->getInvoicePayments($invoice_id);
        $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();
        //not sure if I should do this
        $data['total_invoice_price'] = $data['invoice_data']['total_price'] + $data['invoice_data']['tax'] + $data['invoice_data']['ship_cost'];
        $data['credit_card_data'] = null;

        if($data['invoice_data']['buyer_type'] != 1) { //internet customer or normal customer
            $this->load->model('customer/customer_model');
            $data['buyer_data'] =  $this->customer_model->getCustomerData($data['invoice_data']['buyer_id']);
            if($data['invoice_data']['buyer_type'] == 3) {
                $data['credit_card_data'] = $this->customer_model->getCustomerCreditCardInfo($invoice_id, $data['invoice_data']['buyer_id']);
            }
        }
        else { //vendor
            $this->load->model('customer/customer_model');
            $data['buyer_data'] =  $this->customer_model->getCustomerData($data['invoice_data']['buyer_id']);

        }
        $this->load->view('sales/sales_credit_card_info_view', $data);
    }




    function edit_invoice_fields($invoice_id) {
        $this->load->model('user/user_model');

        $this->load->model('sales/invoice_model');

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->load->model('sales/invoice_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();
        $data['sales_people_display'] = $this->user_model->getAllUsers();
        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_rules('sale_date', 'Invoice Date', 'required|trim');
        $this->form_validation->set_rules('sales_slip_number', 'Sale Slip Number', 'trim|numeric');
        $this->form_validation->set_rules('user_id', 'Sales Person', 'trim|required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $fields = array();
                $fields['sale_date'] = date('Y/m/d', strtotime($this->input->post('sale_date')));
                $fields['sales_slip_number'] = $this->input->post('sales_slip_number');
                $fields['user_id'] = $this->input->post('user_id');

            $this->invoice_model->updateInvoice($invoice_id, $fields);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_fields_edit_view', $data);
        }
    }

    function edit_invoice_item($invoice_id, $item_id) {
        $this->load->model('user/user_model');
        $this->load->model('sales/invoice_model');
        $this->load->model('inventory/inventory_model');
        $this->load->helper('form');
        $this->load->library('form_validation');


        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();
        $data['sales_people_display'] = $this->user_model->getAllUsers();
        $data['invoice_item_data'] = $this->invoice_model->getInvoiceItemData($invoice_id, $item_id);
        $data['item_data'] =  $this->inventory_model->getItemData($data['invoice_item_data']['item_id']);
        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_rules('sale_price', 'Item Price', 'required|trim');
        $this->form_validation->set_rules('sale_tax', 'Item tax', 'trim');

        if ($this->form_validation->run() == TRUE) {
            $chars = array('$', ',');

            $fields = array();
                $fields['sale_price'] = str_replace($chars, '', $this->input->post('sale_price'));
                $fields['sale_tax'] = str_replace($chars, '', $this->input->post('sale_tax'));
            $this->invoice_model->updateInvoiceItem($data['invoice_item_data']['invoice_item_id'], $fields);
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_invoice_item_edit_view', $data);
        }
    }

    function edit_invoice_special_item($invoice_id, $special_id) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('user/user_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['invoice_data']['special_item_id'] = $special_id;
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['sales_people'] = $this->user_model->getActiveUsers();
        $data['special_item_type'] = $this->lookup_list_model->getSpecialItemType();
        $data['special'] = $this->invoice_model->getInvoiceSpecialItemData($special_id);
        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('item_type', 'Item Type', 'required|min_length[1]|max_length[5]');
        $this->form_validation->set_rules('item_description', 'Item Description', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('item_price', 'Item Price', 'trim|required|numeric|max_length[11]');
        $this->form_validation->set_rules('item_tax', 'Item Tax', 'trim|required|numeric|max_length[11]');

        if ($this->form_validation->run() == TRUE) {
            $chars = array('$', ',');

            $fields = array();
                $fields['item_description'] = $this->input->post('item_description');
                $fields['item_price'] = str_replace($chars, '', $this->input->post('item_price'));
                $fields['item_tax'] = str_replace($chars, '', $this->input->post('item_tax'));
                $fields['item_type'] = $this->input->post('item_type');
            $this->invoice_model->updateSpecialItem($special_id, $fields);

            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
        else {
            $this->load->view('sales/sales_special_item_edit_view', $data);
        }
    }

    public function gateway_results() {
        $this->authorize->saveLastURL();
        $this->load->model('admin/gateway_model');

        $data = array();
        $data['user_data'] = $this->authorize->getSessionData();
        $data['gateway_data'] = $this->gateway_model->get_gateway_results();

        $this->load->view('sales/gateway/gateway_results_view', $data);
    }

    function invoice($id) {
        $this->authorize->saveLastURL(); //saves the url

        $this->load->helper('form');
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('user/user_model');

        //why do i do this?
        //is there a better way?
        $this->calculatePriceAndTax($id);

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['invoice_items'] = $this->invoice_model->getInvoiceItemsData($id);
        $data['special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($id);
        $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();
        $data['invoice_item_status'] = $this->lookup_list_model->getInvoiceItemStatus();
        $data['total_invoice_price'] = $data['invoice_data']['total_price'] + $data['invoice_data']['tax'] + $data['invoice_data']['ship_cost'];



        if($data['invoice_data']['invoice_type'] == 1) {
            $this->load->model('sales/layaway_model');
            $data['layaway_payments'] = $this->layaway_model->getLayawayPayments($id);
            $data['total_layaway_payments'] = 0;
            foreach($data['layaway_payments'] as $layaway_payment) {
                $data['total_layaway_payments'] += $layaway_payment['amount'];
            }
        }
        if($data['invoice_data']['invoice_status'] != 0) {
            //$data['sales_people'] = $this->user_model->getActiveUsers();
            $data['sales_people'] = $this->user_model->getAllUsers();
        }
        else {
            $data['sales_people'] = $this->user_model->getAllUsers();
        }

        if($data['invoice_data']['invoice_status'] == 4) { //converted memo
            $data['invoice_data']['invoice_memo_id'] = $this->invoice_model->getConvertedInvoiceFromMemoId($data['invoice_data']['invoice_id']);
        }

        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');
        if($data['invoice_data']['invoice_status'] == 1) { //open, still editabled
            $this->load->view('sales/sales_invoice_edit_view', $data);
        }
        else { //closed, read_only
            $data['payments'] = $this->invoice_model->getInvoicePayments($id);
            $data['total_invoice_price'] = $data['invoice_data']['total_price'] + $data['invoice_data']['tax'] + $data['invoice_data']['ship_cost'];
            $data['total_payments'] = 0;
            foreach($data['payments'] as $payment) {
                $data['total_payments'] += $payment['amount'];
            }

            if($data['invoice_data']['invoice_status'] == 2) {
                $this->load->model('sales/return_model');
                $data['return_data'] = $this->return_model->getReturnData($data['invoice_data']['invoice_id'], 'invoice_id');
            }
            $this->load->view('sales/sales_invoice_view_view', $data);
        }
    }

    function list_all_items($sort = 'entry_date', $direction = 'DESC') {
        $this->authorize->saveLastURL(); //saves the url

        $this->load->model('inventory/inventory_model');
        $this->load->model('inventory/inventory_reports_model');
        $this->load->library('pagination');

        if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'entry_date'; }
        if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'desc';}
        if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
        $data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/';

        $db_config['per_page'] = '20'; //items per page
        $db_config['cur_page'] = $offset;

        $data['search_name'] = 'List All Items';
        $data['user_data'] = $this->authorize->getSessionData(); //load the user data
        $data['items'] = $this->inventory_reports_model->getAllItems($db_config['per_page'], $offset, $sort, $direction);

        $db_config['base_url'] =  '/prototype/inventory_list/list_all_items/' . $sort . '/' . $direction . '/';
        $db_config['total_rows'] = $data['items']['num_rows'];

        $this->pagination->initialize($db_config);
        $data['pagination'] = $this->pagination->create_links(); //load pagination links

        $this->load->view('inventory/inventory_list_view', $data); //load view
    }


    function make_invoice_editable($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'invoice_status', 1);
        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function mask_credit_card($invoice_id, $buyer_id) {
        $this->load->model('customer/customer_model');
        $this->customer_model->maskCreditCard($invoice_id, $buyer_id);

        redirect('sales/credit_card/' . $invoice_id, 'refresh');

    }

    function memo($id) { //this may not be used...
        $this->load->helper('form');
        $this->load->model('sales/memo_model');
        $this->load->model('sales/invoice_model');


        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->memo_model->getMemoData($id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);



        $this->load->view('sales/sales_invoice_edit_view', $data);
    }

    function reopen_layaway($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'layaway_end_date', null);
        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function remove_all_tax($invoice_id) {
        $this->load->model('sales/invoice_model');

        $items = $this->invoice_model->getInvoiceItemsData($invoice_id);
        foreach($items as $item) {
            $this->remove_item_tax($invoice_id, $item['item_id'], false);
        }

        $specials = $this->invoice_model->getInvoiceSpecialItemsData($invoice_id);
        foreach($specials as $item) {
            $this->remove_special_item_tax($invoice_id, $item['special_item_id'], false);
        }
        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function remove_invoice_item($invoice_id, $item_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('inventory/inventory_model');

        $item = $this->invoice_model->getInvoiceItemData($invoice_id, $item_id);
        $invoice = $this->invoice_model->getInvoiceData($invoice_id);

        //Remove the item from the invoice
        $this->invoice_model->removeItemFromInvoice($invoice_id, $item_id);
        //Update the item status (back to available);
        $this->inventory_model->AJAX_updateField($item_id, 'item_status', 1);

        $fields = array();
            $fields['total_price'] = $invoice['total_price'] - $item['sale_price'];
            $fields['tax'] = $invoice['tax'] - $item['sale_tax'];
        $this->invoice_model->updateInvoice($invoice_id, $fields);

        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function remove_invoice_payment($id, $payment_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('customer/customer_model');

        //test to see if store credit was used
        $payment = $this->invoice_model->testForStoreCredit($payment_id);
        if($payment != false) {
            //start refund of store_credit
            $refund['customer_id'] = $payment['buyer_id'];
            $refund['invoice_id'] = $id;
            $refund['action_type'] = 1; //add
            $refund['credit_amount'] = $payment['amount'];
            $refund['is_special_item'] = 0; //no
            $refund['item_description'] = 'Invoice Payment refrund, payment removed from invoice';
            $refund['date'] = date('Y/m/d');
            $this->customer_model->insertStoreCredit($refund);
        }

        //deletes the layaway payment
        $this->invoice_model->removeInvoicePayment($id, $payment_id);
        redirect('sales/invoice/' . $id, 'refresh');

    }

    function remove_item_tax($invoice_id, $item_id, $reload = true) {
        $this->load->model('sales/invoice_model');
        $invoice = $this->invoice_model->getInvoiceData($invoice_id);
        $item = $this->invoice_model->getInvoiceItemData($invoice_id, $item_id);

        $fields = array();
            $fields['sale_tax'] = 0;
        $this->invoice_model->updateInvoiceItem($item['invoice_item_id'], $fields);


        $invoice_fields = array();
            $invoice_fields['tax'] = $invoice['tax'] - $item['sale_tax'];
        $this->invoice_model->updateInvoice($invoice_id, $invoice_fields);

        if($reload) {
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
    }

    function remove_layaway_payment($id, $payment_id) {
        $this->load->model('sales/layaway_model');
        $this->load->model('customer/customer_model');

        //test to see if store credit was used
        $payment = $this->layaway_model->testForStoreCredit($payment_id);
        if($payment != false) {
            //start refund of store_credit
            $refund['customer_id'] = $payment['customer_id'];
            $refund['invoice_id'] = $id;
            $refund['action_type'] = 1; //add
            $refund['credit_amount'] = $payment['amount'];
            $refund['is_special_item'] = 0; //no
            $refund['item_description'] = 'Layaway refrund, payment removed from invoice';
            $refund['date'] = date('Y/m/d');
            $this->customer_model->insertStoreCredit($refund);
        }

        //deletes the layaway payment
        $this->layaway_model->removeLayawayPayment($id, $payment_id);
        redirect('sales/invoice/' . $id, 'refresh');
    }

    function remove_plot($plot_id) {
        $this->load->helper('file');
        $this->load->model('image/image_model');
        $this->load->model('sales/appraisal_model');

        $plot = $this->appraisal_model->getAppraisalItemPlot($plot_id);

        //print_r($plot);

        if(@unlink($_SERVER['DOCUMENT_ROOT'] . $plot['image_location'])) {
            $this->image_model->removePlotImage($plot_id);
        }
        redirect('sales/appraisal/' . $plot['appraisel_id'], 'refresh'); //@TODO fix appraisel misspelling
    }

    function remove_shipping($invoice_id) {
        $this->load->model('sales/invoice_model');

        //remove the shipping
        $fields = array();
            $fields['ship_method'] = '';
            $fields['ship_cost'] = 0.00;
            $fields['is_shipped'] = 0;
        $this->invoice_model->updateInvoice($invoice_id, $fields);

        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }


    function remove_special_item($invoice_id, $special_item_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('inventory/inventory_model');

        //Remove the item from the invoice
        $this->invoice_model->removeSpecialItemFromInvoice($invoice_id, $special_item_id);

        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }



    function remove_special_item_tax($invoice_id, $special_item_id, $reload = true) {
        $this->load->model('sales/invoice_model');
        $fields = array();
            $fields['item_tax'] = 0;
        $this->invoice_model->updateSpecialItem($special_item_id, $fields);

        if($reload) {
            redirect('sales/invoice/' . $invoice_id, 'refresh');
        }
    }





    function returns($return_id) {
        $this->load->model('utils/lookup_list_model');
        $this->load->model('sales/return_model');
        $this->load->model('sales/invoice_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['return_data'] = $this->return_model->getReturnData($return_id);
        $data['return_items'] = $this->return_model->getReturnedInvoiceItems($return_id, true);
        $data['special_items'] = $this->return_model->getReturnedSpecialItems($return_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['return_data']['buyer_id'], $data['return_data']['buyer_type']);
        $data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();
        $data['payments'] = $this->invoice_model->getInvoicePayments($data['return_data']['invoice_id']);

        //$this->output->enable_profiler(TRUE);
        $this->load->view('sales/return/sales_return_view_view', $data);

    }

    function return_edit($return_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('sales/return_model');
        $this->load->model('customer/customer_model');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();

        $data['return_data'] = $this->return_model->getReturnData($return_id);
        $data['return_items'] = $this->return_model->getReturnedInvoiceItems($return_id, true);
        $data['special_items'] = $this->return_model->getReturnedSpecialItems($return_id, true);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['return_data']['buyer_id'], $data['return_data']['buyer_type']);
        $data['credit_data'] = $this->customer_model->getCustomerStoreCreditByDate($data['return_data']['buyer_id'], $data['return_data']['invoice_id'], $data['return_data']['date']);
        $data['return_amount'] = 0;
        $data['header_message'] = 'Step 3: Verify the return amount (You\'re almost done!)';
        foreach($data['return_items'] as $item) {
            $data['return_amount'] += $item['sale_price'];
            $data['return_amount'] += $item['sale_tax'];
        }

        foreach($data['special_items'] as $item) {
            $data['return_amount'] += $item['item_price'];
            $data['return_amount'] += $item['item_tax'];
        }
        if($data['return_data']['refund'] != 0) { //we've already set a value previously
            $data['header_message'] = 'Update Return Slip';
            $data['return_amount'] = $data['return_data']['refund'];
        }


        $this->form_validation->set_rules('date', 'Return Date', 'required|trim');
        $this->form_validation->set_rules('refund', 'Return Amount', 'required|trim|numeric');
        $this->form_validation->set_rules('note', 'Notes', 'trim'); //@TODO update 'note' to 'return_notes'

        if($this->form_validation->run() == true) {
            $fields = array();
                $fields['date'] = date('Y/m/d', strtotime($this->input->post('date')));
                $fields['refund'] = $this->input->post('refund');
                $fields['note'] = $this->input->post('note'); //@TODO update 'note' to 'return_notes'
                $fields['refund_type'] = $this->input->post('refund_type');

            if($data['return_data']['refund_type'] == 1) { //store credit
                $credit = array();
                    $credit['credit_amount'] = $fields['refund'];
                $credit_id = $this->input->post('store_credit_id');
                //update the store credita amount;
                $this->customer_model->updateCustomerStoreCreditByInvoiceId($credit_id, $credit);
            }
            else { //cash return value
                // nothing to do
            }
            $this->return_model->updateReturn($return_id, $fields);
            redirect('sales/returns/' . $return_id, 'refresh');
        }
        else {
            $this->load->view('sales/return/sales_return_edit_view', $data);
        }
    }

    function return_memo_item($memo_id, $invoice_item_id) {
        $this->load->model('sales/memo_model');
        $this->load->model('sales/invoice_model');
        $this->load->model('inventory/inventory_model');

        $item = $this->invoice_model->getInvoiceItemDataByInvoiceItemId($invoice_item_id);

        $this->memo_model->closeMemoInvoiceItem($invoice_item_id);

        $this->inventory_model->AJAX_updateField($item['item_id'], 'item_status', '1');


        redirect('sales/invoice/' . $memo_id, 'refresh');
    }

    function return_items($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('inventory/inventory_model');
        $this->load->model('user/user_model');

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
        $data['invoice_items'] = $this->invoice_model->getInvoiceItemsData($invoice_id);
        $data['special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($invoice_id);
        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');
        $data['sales_person'] = $this->user_model->getUserData($data['invoice_data']['user_id']);

        $this->form_validation->set_rules('return_date_input', 'Return Date', 'trim|required');
        $this->form_validation->set_rules('refund_type', 'Refund Type', 'trim|required|numeric');
        $this->form_validation->set_rules('invoice_id', 'Invoice ID', 'trim|required|callback_CB_test_pending_returns');

        if($this->form_validation->run() == true) {
            $this->load->model('sales/return_model');
            $amount = 0;
            $tax = 0;

            //create return slip
            $return = array();
                $return['return_type'] = 1;
                $return['invoice_id'] = $invoice_id;
                $return['buyer_id'] = $data['invoice_data']['buyer_id'];
                $return['buyer_type'] = $data['invoice_data']['buyer_type'];
                $return['refund'] = 0; //unknown at this point
                $return['refund_type'] = $this->input->post('refund_type');
                $return['date'] = date('Y/m/d', strtotime($this->input->post('return_date_input')));
            $return_id = $this->return_model->insertReturn($return);

            //then add returned items to it
            $items = $this->return_model->getPendingReturnedInvoiceItems($invoice_id);
            foreach($items as $item) {
                $item_fields = array();
                    $item_fields['return_id'] = $return_id;
                    $item_fields['item_id'] = $item['item_id'];
                    $item_fields['item_number'] = $item['item_number'];
                $amount += $item['sale_price'];
                $tax += $item['sale_tax'];
                //@TODO rework return items
                $this->return_model->insertReturnedInvoiceItem($item_fields);
                $this->invoice_model->AJAX_updateInvoiceItemField($item['invoice_item_id'], 'item_status', 1); //update to returned
                $this->inventory_model->AJAX_updateField($item['item_id'], 'item_status', 1); //update inventory item to available
            }

            //then add returned special items to it
            $specials = $this->return_model->getPendingReturnedSpecialItems($invoice_id);
            foreach($specials as $item) {
                $special_fields = array();
                    $special_fields['return_id'] = $return_id;
                    $special_fields['item_description'] = $item['item_description'];
                    $special_fields['item_price'] = $item['item_price'];
                    $special_fields['item_tax'] = $item['item_tax'];
                $amount += $item['item_price'];
                $tax += $item['item_tax'];
                //@TODO rework return special items
                $this->return_model->insertReturnedSpecialItems($special_fields);
                $this->invoice_model->AJAX_updateSpecialItemField($item['special_item_id'], 'item_status', 1); //update to returned
            }

            //calculate return amount,
            $return_fields = array();
                $return_fields['refund'] = $amount + $tax;
            $this->return_model->updateReturn($return_id, $return_fields);


            //test for store credit
            if($return['refund_type'] == 1) {
                //insert store credit
                $this->load->model('customer/customer_model');
                $credit = array();
                    $credit['customer_id'] = $return['buyer_id'];
                    $credit['invoice_id'] = $return['invoice_id'];
                    $credit['action_type'] = 1;
                    $credit['credit_amount'] = $return_fields['refund'];
                    $credit['is_special_item'] = 0; //not sure what this means
                    $credit['date'] = date('Y/m/d', strtotime($this->input->post('return_date_input')));
                $data['store_credit_id'] = $this->customer_model->insertStoreCredit($credit);
            }

            //then show return view (editable)
            redirect('sales/return_edit/' . $return_id, 'refresh');
        }
        else {
            $this->load->view('sales/return/sales_return_items_view', $data);
        }
    }



    function return_selected_items($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('sales/return_model');

        $invoice = $this->invoice_model->getInvoiceData($invoice_id);
        $invoice_items = $this->return_model->getPendingReturnedInvoiceItems($invoice_id);
        $special_items = $this->return_model->getPendingReturnedSpecialItems($invoice_id);

        if(sizeof($invoice_items) <= 0 ) {
            redirect('sales/return_items/' . $invoice_id . '/true', 'refresh');
        }
        else {

            $return = array();
                $return['return_type'] = 1;
                $return['invoice_id'] = $invoice_id;
                $return['buyer_id'] = $invoice['buyer_id'];
                $return['buyer_type'] = $invoice['buyer_type'];
                $return['refund'] = $this->return_model->getReturnTotalAmount($invoice_id);
                $return['refund_type'] = $this->input->post('refund_type');
                $return['date'] = date('Y-m-d', strtotime($this->input->post('return_date_input')));

            $return_id = $this->return_model->insertReturn($return);

            if($return['refund_type'] == 1) { //store credit
                //insert store credit
                $this->load->model('customer/customer_model');
                $credit = array();
                    $credit['customer_id'] = $return['buyer_id'];
                    $credit['invoice_id'] = $invoice_id;
                    $credit['action_type'] = 1;
                    $credit['credit_amount'] = $return['refund'];
                    $credit['is_special_item'] = 0;
                    $credit['date'] = $return['date'];
                $this->customer_model->insertStoreCredit($credit);
            }
            else {//cash given back
                //do nothing
            }

            foreach($invoice_items as $item) {
                $fields = array();
                    $fields['return_id'] = $return_id;
                    $fields['item_id'] = $item['item_id'];
                    $fields['item_number'] = $item['item_number'];
                $this->invoice_model->AJAX_updateInvoiceItemField($item['invoice_item_id'], 'item_status', 1);
                $this->return_model->insertReturnedInvoiceItem($fields);
            }

            foreach($special_items as $item) {
                $fields = array();
                    $fields['return_id'] = $return_id;
                    $fields['item_description'] = $item['item_description'];
                    $fields['item_price'] = $item['item_price'];
                    $fields['item_tax'] = $item['iten_tax'];
                $this->invoice_model->AJAX_updateSpecialItemField($item['special_item_id'], 'item_status', 1);
                $this->return_model->insertReturnedSpecialItem($fields);
            }
            if($this->invoice_model->testAllItemsReturned($invoice_id)) {
                //if true, mark the ivoice as returned;
                $this->invoice_model->AJAX_updateInvoiceField($invoice_id, 'invoice_status', 2);//mark as returned
            }

            redirect('sales/returns/' . $return_id, 'refresh');
        }
    }

    function return_selected_memo_items($memo_id) {
        $this->load->model('sales/memo_model');
        $this->load->model('sales/invoice_model');
        $this->load->model('user/user_model');
        $this->load->model('inventory/inventory_model');
        $this->load->library('form_validation');

        $this->authorize->saveLastURL(); //saves the url
        $data['user_data'] = $this->authorize->getSessionData();
        $data['memo_data'] = $this->invoice_model->getInvoiceData($memo_id);
        $data['memo_items'] = $this->invoice_model->getInvoiceItemsData($memo_id);
        $data['memo_special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($memo_id);
        $data['buyer_data'] = $this->invoice_model->getBuyerData($data['memo_data']['buyer_id'], $data['memo_data']['buyer_type']);
        $data['sales_person'] = $this->user_model->getUserData($data['memo_data']['user_id']);
        $data['invoice_type_text'] = array(0 => 'Normal Invoice', 1 => 'Layaway Invoice', 3 => 'Memo');

        $this->form_validation->set_rules('memo_id','Memo ID','callback_CB_test_pending_returns');

        if($this->form_validation->run() == true) {

            //gather all of the pending returned memo items
            $data['pending_returned_memo_items'] = $this->memo_model->getPendingMemoReturnedItems($memo_id);
            //gather all of the pending returned memo special items
            $data['pending_returned_memo_speical_items'] = $this->memo_model->getPendingMemoReturnedSpecialItems($memo_id);

            //loop through the pending returned memo items
            foreach($data['pending_returned_memo_items'] as $item) {
                //first close the pending returned memo item
                $this->memo_model->returnMemoInvoiceItem($item['invoice_item_id']);
                $this->inventory_model->AJAX_updateField($item['item_id'], 'item_status', 1);
            }

            //loop through the pending returned memo special items
            foreach($data['pending_returned_memo_speical_items'] as $special) {
                //first close the pending special memo items
                $this->memo_model->returnMemoInvoiceSpecialItem($special['special_item_id'], $memo_id);
            }

            redirect('sales/invoice/' . $memo_id, 'refresh');

        }
        else {
            $this->load->view('sales/memo/sales_memo_return_items_view', $data);
        }
    }

    function search($type = null, $sort = 'sale_date', $direction = 'desc') {
        $this->authorize->saveLastURL(); //saves the url
        $this->load->library('pagination');
        $this->load->model('sales/invoice_reports_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->model('sales/invoice_model');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_types'] = $this->lookup_list_model->getInvoiceTypes();
        $data['invoice_status'] =  $this->lookup_list_model->getInvoiceStatus();
        $data['invoice_item_status'] = $this->lookup_list_model->getInvoiceItemStatus();

        if($type != null) {
            if ($this->uri->total_segments() <= 3) {
                $offset = 0;
            }
            else {
                $offset = $this->uri->segment(6, 0);
            }

            $db_config['per_page'] = '50';
            $db_config['cur_page'] = $offset;

            if($type == 'all') {
                $data['direction_url '] = 'sales/search/all/'; //used for directin sorting
                $data['search_name'] = 'View All Sales and Invoices';
                $data['invoices'] = $this->invoice_reports_model->getAllInvoices($db_config['per_page'], $offset, $sort, $direction);
                $db_config['base_url'] =  $this->config->item('base_url') . 'sales/search/all/' . $sort . '/' . $direction . '/';
            }
            else if($type == 'internet') {
                $data['direction_url '] = 'sales/search/internet/'; //used for directin sorting
                $data['search_name'] = 'View All Internet Sales';
                $data['invoices'] = $this->invoice_reports_model->getAllInternetSales($db_config['per_page'], $offset, $sort, $direction);
                $db_config['base_url'] =  $this->config->item('base_url') . 'sales/search/internet/' . $sort . '/' . $direction . '/';
            }
            else if($type == 'memos') {
                $data['direction_url '] = 'sales/search/memos/'; //used for directin sorting
                $data['search_name'] = 'View All Memos';
                $data['invoices'] = $this->invoice_reports_model->getAllMemos($db_config['per_page'], $offset, $sort, $direction);
                $db_config['base_url'] =  $this->config->item('base_url') . 'sales/search/memos/' . $sort . '/' . $direction . '/';
            }
            else if($type == 'open-memos') {
                $data['direction_url '] = 'sales/search/open-memos/'; //used for directin sorting
                $data['search_name'] = 'View All Memos';
                $data['invoices'] = $this->invoice_reports_model->getOpenMemos($db_config['per_page'], $offset, $sort, $direction);
                $db_config['base_url'] =  $this->config->item('base_url') . 'sales/search/open-memos/' . $sort . '/' . $direction . '/';
            }
            else if($type == "vendor-invoice") {
                $data['direction_url '] = 'sales/search/vendor-invoice/'; //used for directin sorting
                $data['search_name'] = 'View All Vendor Invoices';
                $data['invoices'] = $this->invoice_reports_model->getAllVendorInvoices($db_config['per_page'], $offset, $sort, $direction);
                $db_config['base_url'] =  $this->config->item('base_url') . 'sales/search/vendor-invoice/' . $sort . '/' . $direction . '/';
            }

            // @TODO fix this bad design
            $db_config['total_rows'] = $data['invoices']['num_rows']; //['num_rows'];
            unset($data['invoices']['num_rows']); //remove that lame num_rows;

            foreach($data['invoices'] as $invoice) {
                //get current items
                $data['invoices'][$invoice['invoice_id']]['items'] = $this->invoice_model->getInvoiceItemsData($invoice['invoice_id']);
            }

            $this->pagination->initialize($db_config);
            $data['pagination'] = $this->pagination->create_links(); //load pagination links

            //group invoices by date
            $data['date_groups'] = array();
            foreach($data['invoices'] as $invoice) {
                if(!isset($data['date_groups'][$invoice['sale_date']])) {
                    $data['date_groups'][$invoice['sale_date']][$invoice['invoice_id']] = $invoice;
                }
                else {
                    $data['date_groups'][$invoice['sale_date']][$invoice['invoice_id']] = $invoice;
                }
            }
            //var_dump($data['date_groups']);
            $this->load->view('sales/sales_list_view', $data);

        }
        else {
            $this->load->view('sales', $data);
        }
    }

    function search_id() {
        $this->load->model('sales/invoice_model');
        $this->load->model('utils/lookup_list_model');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['invoice_status'] = $this->lookup_list_model->getInvoiceStatus();
        $data['invoice_types'] = $this->lookup_list_model->getInvoiceTypes();

        $this->form_validation->set_rules('search_text', 'Search', 'trim|required|');

        $data['search_results'] = array();
        if($this->form_validation->run() == true) {
            $data['search_results'] = $this->invoice_model->searchInvoice($this->input->post('search_text'));

        }
        //$this->output->enable_profiler();
        $this->load->view('sales/sales_search_id_view', $data);
    }

    function search_returns($type = null, $sort = 'date', $direction = 'desc') {
        $this->authorize->saveLastURL(); //saves the url
        $this->load->library('pagination');
        $this->load->model('sales/invoice_reports_model');
        $this->load->model('utils/lookup_list_model');

        if ($this->uri->total_segments() <= 3) { $offset = 0; } else { $offset = $this->uri->segment(6, 0);}

        $db_config['per_page'] = '50';
        $db_config['cur_page'] = $offset;

        $data['user_data'] = $this->authorize->getSessionData();
        $data['credit_type'] = $this->lookup_list_model->getReturnCreditType();

        $data['direction_url '] = 'sales/search/returns/'; //used for directin sorting
        $data['search_name'] = 'View All Returns';
        $data['returns'] = $this->invoice_reports_model->getAllReturns($db_config['per_page'], $offset, $sort, $direction);

        $db_config['base_url'] =  '/prototype/sales/search/returns/' . $sort . '/' . $direction . '/';
        $db_config['total_rows'] = $data['returns']['num_rows'];
        unset($data['returns']['num_rows']); //remove that lame num_rows;

        $this->pagination->initialize($db_config);
        $data['pagination'] = $this->pagination->create_links(); //load pagination links

        $this->load->view('sales/sales_list_returns_view', $data);
    }

    function ship_billing_address($invoice_id) {
        $this->load->model('sales/invoice_model');
        $fields = array();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);

        if($data['invoice_data']['buyer_type'] == 1 || $data['invoice_data']['buyer_type'] == 3) { //customers
            $this->load->model('customer/customer_model');
            $data['customer'] = $this->customer_model->getBillingAddress($data['invoice_data']['buyer_id']);
                $fields['ship_contact'] = $data['customer']['first_name'] . ' ' . $data['customer']['last_name'];
                $fields['is_shipped'] = 1;
                $fields['ship_address'] = $data['customer']['address'];
                $fields['ship_city'] = $data['customer']['city'];
                $fields['ship_state'] = $data['customer']['state'];
                $fields['ship_zip'] = $data['customer']['zip'];
                $fields['ship_country'] = $data['customer']['country'];
        }
        if($data['invoice_data']['buyer_type'] == 2) { //vendors
            $this->load->model('vendor/vendor_model');
            $data['vendor'] = $this->vendor_model->getBillingAddress($data['invoice_data']['buyer_id']);
                $fields['ship_contact'] = $data['vendor']['name'];
                $fields['is_shipped'] = 1;
                $fields['ship_address'] = $data['vendor']['address'];
                $fields['ship_city'] = $data['vendor']['city'];
                $fields['ship_state'] = $data['vendor']['state'];
                $fields['ship_zip'] = $data['vendor']['zip'];
                $fields['ship_country'] = $data['vendor']['country'];
        }
        $this->invoice_model->updateInvoice($invoice_id, $fields);

        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function ship_shipping_address($invoice_id) {
        $this->load->model('sales/invoice_model');
        $fields = array();
        $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);

        if($data['invoice_data']['buyer_type'] == 1 || $data['invoice_data']['buyer_type'] == 3) { //customers
            $this->load->model('customer/customer_model');
            $data['customer'] = $this->customer_model->getShippingAddress($data['invoice_data']['buyer_id']);
                $fields['ship_contact'] = $data['customer']['ship_contact'];
                $fields['is_shipped'] = 1;
                $fields['ship_phone'] = $data['customer']['ship_phone'];
                $fields['ship_other_phone'] = $data['customer']['ship_other_phone'];
                $fields['ship_address'] = $data['customer']['ship_address'];
                $fields['ship_city'] = $data['customer']['ship_city'];
                $fields['ship_state'] = $data['customer']['ship_state'];
                $fields['ship_zip'] = $data['customer']['ship_zip'];
                $fields['ship_country'] = $data['customer']['ship_country'];
        }
        if($data['invoice_data']['buyer_type'] == 2) { //vendors
            $this->load->model('vendor/vendor_model');
            $data['vendor'] = $this->vendor_model->getShippingAddress($data['invoice_data']['buyer_id']);
                $fields['ship_contact'] = $data['vendor']['ship_contact'];
                $fields['is_shipped'] = 1;
                $fields['ship_phone'] = $data['vendor']['ship_phone'];
                $fields['ship_other_phone'] = $data['vendor']['ship_other_phone'];
                $fields['ship_address'] = $data['vendor']['ship_address'];
                $fields['ship_city'] = $data['vendor']['ship_city'];
                $fields['ship_state'] = $data['vendor']['ship_state'];
                $fields['ship_zip'] = $data['vendor']['ship_zip'];
                $fields['ship_country'] = $data['vendor']['ship_country'];
        }
        $this->invoice_model->updateInvoice($invoice_id, $fields);

        redirect('sales/invoice/' . $invoice_id, 'refresh');
    }

    function trash_invoice($invoice_id) {
        $this->load->model('sales/invoice_model');
        $this->load->model('sales/layaway_model');
        $this->load->model('admin/delete_invoice_model');
        $user = $this->authorize->getSessionData();
        //check to make sure invoice is not set to 0 (read-only): means the invoice was finalized
        $data = $this->invoice_model->getInvoiceData($invoice_id);
        if($data['invoice_status'] == 1) {

            //add the delete record;
            $fields = array();
                $fields['invoice_id'] = $data['invoice_id'];
                $fields['user_id'] = $user['user_id'];
                $fields['delete_reason'] = 'Incomplete Invoice was trashed. Ask them about it.';
                $fields['buyer_id'] = $data['buyer_id'];
                $fields['buyer_type'] = $data['buyer_type'];

                $this->delete_invoice_model->insertHistoryRecord($fields);
            //remove applied items status
                $this->invoice_model->removeInventoryItems($invoice_id);
            //remove layaway payments
                $this->layaway_model->removeLayawayPayments($invoice_id);
            //remove Invoice Payments
                $this->invoice_model->removeInvoicePayments($invoice_id);
            //update the special items status
                $this->invoice_model->removeAllSpecialItems($invoice_id);
            //delete Invoice
                $this->invoice_model->deleteInvoice($invoice_id);
            //redirect back to sales main
            redirect('sales/', 'refresh');
        }
        else {
            echo 'good god! you\'re trying to delete a finished invoice!';
        }
    }














    function update_appraisal($appraisal_id) {
        $this->load->model('sales/appraisal_model');
        $fields = array();
            $fields['email_note'] = $this->input->post('email_note');
            $fields['user_id'] = $this->input->post('appraiser_id');

        $this->appraisal_model->updateAppraisedItem($appraisal_id, $fields);

        redirect('sales/appraisal/' . $appraisal_id, 'refresh');
    }

    function upload_plot($appraisal_id, $stone_id, $type) {
        $this->load->model('sales/appraisal_model');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['appraisal_data'] = $this->appraisal_model->getAppraisalData($appraisal_id);
        $data['current_plot'] = $this->appraisal_model->testForPlot($appraisal_id, $stone_id, $type);

        $data['appraisal_data']['stone_id'] = $stone_id;
        $data['appraisal_data']['template_type'] = $type;
        $data['upload_errors'] = '';

        $this->form_validation->set_rules('imgfile', 'File', 'trim');

        if($this->form_validation->run()) {
            $path = './uploads/plots/';
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'jpg';
            $config['max_size'] = '1000'; //kilobytes
            $config['max_height'] = '500'; //pixels
            $config['max_width'] = '500'; //pixels
            $config['max_filename'] = '256'; //charatures

            $pattern = '/[^a-zA-Z0-9.]/i';
            $file_name = $_FILES['imgfile']['name'];
            $composite_name = preg_replace($pattern, '_', time() . '_' . $file_name);
            $new_file_name = str_replace(' ', '_', $composite_name);

            $hash_name = sha1($new_file_name);
            $config['file_name'] = $hash_name;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('imgfile') == TRUE) {
                $this->load->model('image/image_model');
                $temp_data = $this->upload->data();
                //print_r($temp_data);
                $image['appraisel_id'] = $appraisal_id; //@TODO fix appriasal database misspelling
                $image['gemstone_type'] = $type;
                $image['gemstone_id'] = $stone_id;
                $image['image_name'] = $hash_name . $temp_data['file_ext'];
                $image['image_size'] = $temp_data['file_size'];
                $image['image_location'] = '/mango/uploads/plots/' . $hash_name . $temp_data['file_ext']; //define the actuall path
                $image['image_type'] = $temp_data['file_type'];
                $image['plot_symbols'] = $this->input->post('plot_symbols');
                $image['plot_comments'] = $this->input->post('plot_comments');
                $this->image_model->uploadPlotImage($image);

                redirect('sales/appraisal/' . $appraisal_id, 'refresh');
            }
            else {
                $data['upload_errors'] = $this->upload->display_errors();
                $this->load->view('sales/appraisal/sales_appraisal_upload_plot_view', $data);
            }
        }
        else {
            $data['upload_errors'] = validation_errors();
            $this->load->view('sales/appraisal/sales_appraisal_upload_plot_view', $data);
        }
    }


    /**
     * Ajax Calls
     *
     */
    function jAJAX_updateInvoiceField($type = null) {
        $this->load->model('sales/invoice_model');

        $id = $this->input->post('invoice_id');
        $column = $this->input->post('id');
        $value = $this->input->post('value');
        if($type == 'date') {
            $value = date('Y/m/d', strtotime($value));
        }
        $this->invoice_model->AJAX_updateInvoiceField($id, $column, $value);
        echo $value;

    }

    function AJAX_updateInvoiceField($id, $field, $type = false) {
        $this->load->model('sales/invoice_model');
        $value = $this->input->post('value');

        if($type == 'money') {
            if($value == '')
            {
                $value = 0.00;
                $return_value = '$' . number_format($value, 2);
            }
            else {
                $strip_chars = array(',', '$');
                $value = str_replace($strip_chars, '', $value);
                $return_value = '$' . number_format($value, 2);
            }
        }
        else if($type == 'date') {
            $value = date('Y/m/d', strtotime($value));
            $return_value = date('m/d/Y', strtotime($value));;
        }
        else {
            $return_value = $value;
        }

        $this->invoice_model->AJAX_updateInvoiceField($id, $field, $value);

        echo $return_value; //This returns the value back to the field
    }

    function AJAX_updateInvoiceItemField() { //jQuery
        $this->load->model('sales/invoice_model');

        $id = $this->input->post('invoice_item_id');
        $column = $this->input->post('id');
        $value = $this->input->post('value');
        $this->invoice_model->AJAX_updateInvoiceItemField($id, $column, $value);
        echo $value;
    }

    function AJAX_updateSpecialItemField() { //jQuery
        $this->load->model('sales/invoice_model');

        $id = $this->input->post('special_item_id');
        $column = $this->input->post('id');
        $value = $this->input->post('value');
        $this->invoice_model->AJAX_updateSpecialItemField($id, $column, $value);
        echo $value;
    }

    function AJAX_updateAppraisalField($id, $field, $type = false) {
        $this->load->model('sales/appraisal_model');
        $value = $this->input->post('value');

        if($type == 'money') {
            $strip_chars = array(',', '$');
            $value = str_replace($strip_chars, '', $value);
            $return_value = '$' . number_format($value, 2);

        }
        else if($type == 'date') {
            $value = date('Y/m/d', strtotime($value));
            $return_value = date('m/d/Y', strtotime($value));;
        }
        else {
            $return_value = $value;
        }

        $this->appraisal_model->AJAX_updateAppraisalField($id, $field, $value);
        echo $return_value; //This returns the value back to the field (find a fix)
    }

    function CB_test_pending_conversions($str) {
        $this->load->model('sales/memo_model');
        $bool = false;
        $this->form_validation->set_message('CB_test_pending_conversions', 'You have not selected any Items for Conversion');
        if($this->memo_model->verifyPendingConversionItems($str)) {
            $bool = true;
        }
        return $bool;
    }
    /**
     * Checks to make sure that at least one item is
     * pending a return
     *
     * @param [int] $str = ivoice_id
     * @return [bool]
     */
    function CB_test_pending_returns($str) {
        $this->load->model('sales/invoice_model');
        $bool = false;
        $this->form_validation->set_message('CB_test_pending_returns', 'You have not selected any items for return.');
        if($this->invoice_model->verifyPendingReturnItems($str) > 0) {
            $bool = true;
        }
        return $bool;
    }

    /**
     * Private Calls
     */
    private function PV_getItemStatus($id) {
        $string = '';
        switch($id) {
            case 0:
                $string = '<span class=\'warning\'>' . snappy_image('icon/money_dollar.png') . 'Sold</span>';
                break;
            case 1:
                $string = '<span class=\'success\'>' . snappy_image('icon/tick.png') . 'Available</span>';
                break;
            case 2:
                $string = '<span class=\'warning\'>Out on Job</span>';
                break;
            case 3:
                $string = '<span class=\'warning\'>Pending Sale</span>';
                break;
            case 4:
                $string = '<span class=\'warning\'>Out on Memo</span>';
                break;
            case 5:
                $string = '<span class=\'warning\'>Burgled</span>';
                break;
            case 6:
                $string = '<span class=\'warning\'>Assembled</span>';
                break;
            case 7:
                $string = '<span class=\'warning\'>Returned to Consignment</span>';
                break;
            case 99:
                $string = '<span class=\'warning\'>Unavailable</span>';
                break;

        }
        return $string;
    }

}
?>
