<?php
/**
 * Invoice model
 *
 * Used to create invoices for on-line sales
 *
 * This can create invoices, invoice_items, and payments
 *
 * @author user
 *
 */
class Gateway_model extends Model {
    public $ci;

    private $gateway_url = 'https://www.myvirtualmerchant.com/VirtualMerchant/process.do';
    private $gateway_merchant_id = 600485;
    private $gateway_user_id = 'webpage';
    private $gateway_pin = 'VCWNMH';
    private $gateway_format = 'ASCII';

    public function __construct() {
        parent::Model();
        $this->ci =& get_instance();
    }

    /**
     * [call_gateway_processor description]
     * @param  [type] $fields [description]
     * @return [type]
     */
    public function call_gateway_processor($fields, $customer_data) {
        $fields_string = '';

        // $fields['ssl_test_mode'] = 'TRUE';
        // $fields['ssl_amount'] = 5;
        // $fields['ssl_salestax'] = 0;
        // $fields['ssl_card_number'] = '';
        // $fields['ssl_exp_date'] = '';
        // $fields['ssl_cvv2cvc2'] = '';
        $fields['ssl_customer_code'] = 1111;
        $fields['ssl_merchant_id'] = $this->gateway_merchant_id;
        $fields['ssl_user_id'] = $this->gateway_user_id;
        $fields['ssl_pin'] = $this->gateway_pin;
        $fields['ssl_result_format'] = $this->gateway_format;

        //url-ify the data for the POST
        foreach($fields as $key => $value) {
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string, '&');

        $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->gateway_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        $curl_results = curl_exec($ch);

        $db_fields = array();
            $db_fields['invoice_id'] = $fields['ssl_invoice_number'];
            $db_fields['buyer_id'] = $customer_data['customer_id'];
            $db_fields['amount'] = $fields['ssl_amount'];
            $db_fields['response'] = $curl_results;

        $this->insert_response($db_fields);
        return $this->parse_gateway_response($curl_results);
    }

    private function parse_gateway_response($string) {
        $pairs = explode("\n", $string);
        $result_fields = array();
        foreach($pairs as $pair) {
            $key_value = explode('=', $pair);
            $result_fields[$key_value[0]] = $key_value[1];
        }
        return $result_fields;
    }

    private function insert_response($fields) {
        $this->db->insert('gateway_results', $fields);
        return $this->db->insert_id();
    }
}
    ?>