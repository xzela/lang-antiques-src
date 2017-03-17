<?php

class Tools extends Controller {

    public function __construct() {
        parent::Controller();
        $this->load->library('authorize');
        $this->authorize->isLoggedIn();

        $data['user_data'] = $this->authorize->getSessionData();
        if(!$this->authorize->isAdmin()) {
            // not an admin
            redirect('/', 'refresh');
        }
    }

    public function index() {

    }

    public function maskUnmaskedCreditCards($i = null, $type = null) { //true, "string"
        if(sha1(@settype($i, "$type")) == '356a192b7913b04c54574d18c28d46e6395428ab') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('mask_them', 'Mask Them', 'required|trim');
            if($this->form_validation->run() == true) {
                $this->load->model('customer/customer_model');
                //never do this
                $this->load->database();
                $this->db->from('internet_customer_creditcard');
                $this->db->where('masked', '0');
                $results = $this->db->get();
                if($results->num_rows() > 0) {
                    foreach($results->result_array() as $row) {
                        $this->customer_model->maskCreditCard($row['invoice_id'], $row['int_customer_id']);
                    }
                }
                echo 'done';
            }
            else {
                echo form_open('tools/maskUnmaskedCreditCards/' . $i .'/' . $type);
                echo '<p> mask the unmasked credit cards</p>';
                echo '<input type="submit" name="mask_them" value="mask credit cards" /> ';
                echo form_close();
            }
        }
        else {
            echo 'you need to specify a value and a type to use these tools.';
        }
    }

    public function addCreditCard($i = null, $type = null) {
        if(sha1(@settype($i, "$type")) == '356a192b7913b04c54574d18c28d46e6395428ab') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('card_number', 'Card Number', 'required|trim');
            if($this->form_validation->run() == true) {
                $fields = array();
                    $fields['int_customer_id'] = 8406;
                    $fields['card_type'] = 3;
                    $fields['card_holder'] = 'zeph';
                    $fields['card_number'] = $this->input->post('card_number');
                    $fields['card_cvv'] = 123;
                    $fields['card_month'] = 1;
                    $fields['card_year'] = 2013;
                    $fields['total_price'] = 1;
                    $fields['invoice_id'] = 14554;
                    $fields['masked'] = 0;
                //$this->load->model('customer/customer_model');
                //never do this
                $this->load->database();
                $this->db->set('encrypt_card_number', "AES_ENCRYPT('{$fields['card_number']}','". AES_KEY . "')", FALSE);
                $this->db->set('encrypt_card_cvv', "AES_ENCRYPT('{$fields['card_cvv']}','". AES_KEY . "')", FALSE);
                $this->db->insert('internet_customer_creditcard', $fields);
                echo 'done';
            }
            else {
                echo form_open('tools/addCreditCard/' . $i .'/' . $type);
                echo '<p> add a fake credit cards</p>';
                echo '<input type="text" name="card_number" value="4111111111111111" /> ';
                echo '<input type="submit" name="add_credit_card" value="add credit cards"  /> ';
                echo form_close();
            }
        }
        else {
            echo 'you need to specify a value and a type to use these tools.';
        }
    }


}

?>