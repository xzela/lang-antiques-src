<?php

/**
*/
class Gateway extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->library('authorize');
        $this->authorize->isLoggedIn();

    }


    public function index() {
        $data = array();
        $data['user_data'] = $this->authorize->getSessionData();

        $this->load->view('admin/gateway/gateway_view', $data);
    }

    public function tester() {
        $this->load->model('admin/gateway_model');

        $fields_string ="";
        $fields = array();
            $fields['ssl_test_mode'] = 'TRUE';
            $fields['ssl_amount'] = 5.00;
            $fields['ssl_salestax'] = 0;
            $fields['ssl_transaction_type'] = 'ccsale';
            $fields['ssl_show_form'] = 'false';

            //cc details
            $fields['ssl_card_number'] = '4012888888881881'; // the credit card number
            $fields['ssl_exp_date'] = '0912'; //format: MMYY
            $fields['ssl_cvv2cvc2_indicator'] = '1'; //CVV2 Indicator 0=Bypassed, 1=present, 2=Illegible, and 9=Not Present
            $fields['ssl_cvv2cvc2'] = '000';

            //customer data
            $fields['ssl_avs_address'] = '123 Testing Street';
            $fields['ssl_avs_zip'] = '94508';

        $results = $this->gateway_model->call_gateway_processor($fields);

        var_dump($results);
    }

    /**
     * [invoice description]
     * @param  [type] $invoice_id [description]
     * @return null - returns a view
     */
    public function invoice($invoice_id = null) {
        if(!is_null($invoice_id)) {
            $data = array();
            $data['user_data'] = $this->authorize->getSessionData();
            $this->load->library('form_validation');
            $this->load->model('sales/invoice_model');
            $data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
            $data['invoice_items_data'] = $this->invoice_model->getInvoiceItemsData($invoice_id);
            //var_dump($data['invoice_items_data']);
            $data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);
            $data['buyer_card_data'] = $this->customer_model->getCustomerCreditCardInfo($data['invoice_data']['invoice_id'], $data['invoice_data']['buyer_id']);
            if(!empty($data['buyer_card_data'])) {
                $data['buyer_card_data']['card_month'] = str_pad($data['buyer_card_data']['card_month'], 2, 0, STR_PAD_LEFT);
                $data['buyer_card_data']['card_year'] = substr($data['buyer_card_data']['card_year'], 2);
                $data['buyer_decyrpted_card_data'] = $this->customer_model->getDecryptedCreditCard($data['invoice_data']['invoice_id'], $data['invoice_data']['buyer_id']);
            }

            //Credit Card Rules
            $this->form_validation->set_rules('address', 'Address Line 1', 'required|trim|min_length[1]|max_length[30]');
            $this->form_validation->set_rules('city', 'City', 'required|trim|min_length[1]');
            $this->form_validation->set_rules('state', 'State', 'required|trim|min_length[1]');
            $this->form_validation->set_rules('zip', 'Zip Code', 'required|trim|min_length[1]|max_length[10]');

            $this->form_validation->set_rules('card_number', 'Card Number', 'required|trim|numeric');
            $this->form_validation->set_rules('card_cvv', 'Card CVV', 'required|trim|numeric|min_length[1]|max_length[5]');
            $this->form_validation->set_rules('card_year', 'Card Year', 'required|trim|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('card_month', 'Card Month', 'required|trim|numeric|min_length[2]|max_length[2]');

            if($this->form_validation->run() == true) {
                $this->load->model('admin/gateway_model');
                $fields = array();
                    $fields['ssl_amount'] = $data['invoice_data']['ship_cost'] + $data['invoice_data']['total_price'];
                    $fields['ssl_salestax'] = $data['invoice_data']['tax'];
                    $fields['ssl_transaction_type'] = 'ccsale';
                    $fields['ssl_show_form'] = 'false';
                    $fields['ssl_invoice_number'] = $data['invoice_data']['invoice_id'];
                    $fields['ssl_customer_code'] = 1111;

                    //cc details
                    $fields['ssl_card_number'] = $this->input->post('card_number'); // the credit card number
                    $fields['ssl_exp_date'] = $this->input->post('card_month') . $this->input->post('card_year');
                    $fields['ssl_cvv2cvc2_indicator'] = '1'; //CVV2 Indicator 0=Bypassed, 1=present, 2=Illegible, and 9=Not Present
                    $fields['ssl_cvv2cvc2'] = $this->input->post('card_cvv');
                    $fields['ssl_first_name'] = $this->input->post('first_name');
                    $fields['ssl_last_name'] = $this->input->post('last_name');

                    $fields['ssl_avs_address'] = $this->input->post('address');
                    $fields['ssl_address2'] = $this->input->post('address2');
                    $fields['ssl_city'] = $this->input->post('city');
                    $fields['ssl_state'] = $this->input->post('state');
                    $fields['ssl_avs_zip'] = $this->input->post('zip');
                    $fields['ssl_country'] = $this->input->post('country');

                $data['gateway_results'] = $this->gateway_model->call_gateway_processor($fields);
                $this->load->view('admin/gateway/gateway_invoice_results_view', $data);
            }
            else {
                $this->load->view('admin/gateway/gateway_invoice_view', $data);
            }

        }
        else {
            redirect('sales', 'refresh');
        }
    }

}

?>