<?php
define("TAX_RATE", 0.0875); //San Francisco Tax Rate
define('PRICE_LIMIT', 3000);
class Shopping extends Controller {

    function __construct() {
        parent::Controller();
        $this->session->unset_userdata('parent_id');

    }

    public function index() {
        redirect('shopping/view-cart', 'refresh');
    }

    /**
     * Starts the Checkout Process
     * Loads SSL and checks things for
     *
     */
    public function check_out() {
        $this->load->helper('ssl');
        force_ssl(); //Turn On SSL


        $this->load->library('form_validation');
        $this->load->model('user/user_model');
        $this->load->model('products/inventory_model');
        $this->load->model('shopping/invoice_model');
        $data = array();

        //check to see if anything is in the cart,
        if($this->session->userdata('cart') != null) {
            $numbers = $this->session->userdata('cart');
            $data['cart'] = array();
            foreach($numbers as $item_number) {
                $data['cart'][] = $this->inventory_model->getItemDataByNumber($item_number);
            }
        }
        else { //if not, return to view cart
            redirect('shopping/view-cart', 'refresh');
        }
        //All users are required to sign into Lang before checking out
        //get the customer id
        if($this->session->userdata('customer_id') != null) {
            $data['customer_data'] = $this->user_model->getCustomerData($this->session->userdata('customer_id'));
        }
        else {
            //set checkout to true, will redirect users back to checkout page
            $this->session->set_userdata(array('checkout' => 'true'));
            //redirect user to create acocunt
            redirect('user/signin', 'refresh'); //redirect to signin page
        }
        //validate user input
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|max_length[64]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|max_length[64]');
        $this->form_validation->set_rules('home_phone', 'Phone', 'required|trim|max_length[64]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[256]|valid_email');
        $this->form_validation->set_rules('address', 'Address Line 1', 'required|trim|max_length[256]');
        $this->form_validation->set_rules('address2', 'Address Line 2', 'trim|max_length[256]');
        $this->form_validation->set_rules('city', 'City', 'required|trim|max_length[64]');
        $this->form_validation->set_rules('state', 'State', 'required|trim|max_length[2]');
        $this->form_validation->set_rules('zip', 'Zip', 'required|trim|max_length[9]');
        $this->form_validation->set_rules('country', 'Country', 'required|trim|max_length[64]');

        $this->form_validation->set_rules('ship_contact', 'Shipping Contact', 'required|trim|max_length[64]');
        $this->form_validation->set_rules('ship_phone', 'Shipping Phone', 'required|trim|max_length[64]');
        // $this->form_validation->set_rules('ship_address', 'Shipping Address Line 1', 'required|trim|max_length[256]');
        // $this->form_validation->set_rules('ship_address2', 'Shipping Address Line 2', 'trim|max_length[256]');
        // $this->form_validation->set_rules('ship_city', 'Shipping City', 'required|trim|max_length[64]');
        // $this->form_validation->set_rules('ship_state', 'Shipping State', 'required|trim|max_length[2]');
        // $this->form_validation->set_rules('ship_zip', 'Shipping Zip', 'required|trim|max_length[10]');
        // $this->form_validation->set_rules('ship_country', 'Shpping Country', 'required|trim|max_length[64]');


        //set the billing state of the user.
        $this->session->set_userdata('billing_state', $this->input->post('state'));

        //test to see if the shipping is free (based on view)
        if($this->input->post('shipping_type') == 'free') {
            $this->session->set_userdata('shipping', '0');
        }
        else {
            //else, test cost of shipping
            if($this->input->post('shipping_type') == 75) {
                $this->session->set_userdata('shipping', '75');
            }
            else {
                $this->session->set_userdata('shipping', $this->input->post('shipping_type'));
            }
        }
        //run validation test
        if($this->form_validation->run() == true) {
            $fields = array();
                $fields['first_name'] = $this->input->post('first_name');
                $fields['last_name'] = $this->input->post('last_name');
                $fields['home_phone'] = $this->input->post('home_phone');
                $fields['email'] = $this->input->post('email');
                $fields['address'] = $this->input->post('address');
                $fields['address2'] = $this->input->post('address2');
                $fields['city'] = $this->input->post('city');
                $fields['state'] = $this->input->post('state');
                $fields['zip'] = $this->input->post('zip');
                $fields['country'] = $this->input->post('country');

                //shipping
                $fields['ship_contact'] = $this->input->post('ship_contact');
                $fields['ship_phone'] = $this->input->post('ship_phone');
                $fields['ship_address'] = $this->input->post('address');
                $fields['ship_address2'] = $this->input->post('address2');
                $fields['ship_city'] = $this->input->post('city');
                $fields['ship_state'] = $this->input->post('state');
                $fields['ship_zip'] = $this->input->post('zip');
                $fields['ship_country'] = $this->input->post('country');

                $this->user_model->updateCustomerData($this->session->userdata('customer_id'), $fields);
                // Removed Pice limit logic.
//                if($this->input->post('total_amount') > PRICE_LIMIT) {
                    // $data['customer_data'] = $this->user_model->getCustomerData($this->session->userdata('customer_id'));
                    // $invoice_id = $this->create_invoice($data['customer_data']);
                    // $invoice_data = $this->invoice_model->getInvoiceData($invoice_id);
                    // $this->insert_invoice_items($invoice_data, $data['cart'], $data['customer_data']);
                    // $this->send_reserve_email($invoice_data, $data['customer_data']);
                    // redirect('shopping/reserved', 'refresh');

                // }
               redirect('shopping/payment', 'refresh');
        }
        else {
            $this->load->view('shopping/billing_information_view', $data);
        }
    }

    private function create_invoice($customer_data) {
        $this->load->model('shopping/invoice_model');
        //create invoice
        $invoice_fields = array();
            $invoice_fields['user_id'] = 42; //internet sale
            $invoice_fields['invoice_type'] = 0; //normal sale
            $invoice_fields['invoice_status'] = 0; //read only
            $invoice_fields['buyer_type'] = 3; //internet sale
            $invoice_fields['buyer_id'] = $customer_data['customer_id']; //customer_id
            $invoice_fields['is_shipped'] = 1; //yes, is shipped
            $invoice_fields['sale_date'] = date('Y/m/d'); //todays date

            $invoice_fields['total_price'] = $this->session->userdata('total_price');
            $invoice_fields['ship_cost'] = $this->session->userdata('shipping');
            $tax = 0;
            //if billing address is in CA, ad some tax
            if(strtoupper($this->session->userdata('billing_state')) == 'CA') {
                $tax = $this->session->userdata('total_price') * TAX_RATE;
            }
            $invoice_fields['tax'] = $tax;
            $invoice_fields['ship_method'] = 'Fed Ex';

            //Shipping information, pulled from customer data
            $invoice_fields['ship_contact'] = $customer_data['ship_contact'];
            $invoice_fields['ship_phone'] = $customer_data['ship_phone'];
            $invoice_fields['ship_address'] = $customer_data['ship_address'];
            $invoice_fields['ship_address2'] = $customer_data['ship_address2'];
            $invoice_fields['ship_city'] = $customer_data['ship_city'];
            $invoice_fields['ship_state'] = $customer_data['ship_state'];
            $invoice_fields['ship_zip'] = $customer_data['ship_zip'];
            $invoice_fields['ship_country'] = $customer_data['ship_country'];
            $invoice_fields['notes'] = $this->input->post('special_notes');
            //insert invoice
            $invoice_id = $this->invoice_model->insertInvoice($invoice_fields);

        return $invoice_id;
    }

    private function send_gateway_results_email($invoice_data, $gateway_results) {
        $this->load->model('mailer/mailer_model');
        $this->load->model('shopping/invoice_model');
        //send out emails
        $mail = array();
            $mail['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_data['invoice_id']);
            //$mail['invoice_items'] = $this->invoice_model->getInvoiceItems($invoice_data['invoice_id']);
            $mail['gateway_results'] = $gateway_results;
        $content  = $this->mailer_model->composeGatewayMessage($mail);
            $content['subject'] = 'Credit Card Failed to Process!';
            $content['to'] = 'info@langantiques.com'; //send to everyone
            $content['from'] = 'mango@langantiques.com';
            $this->mailer_model->sendEmail($content);

    }

    private function send_purchase_email($invoice_data, $customer_data) {
        $this->load->model('mailer/mailer_model');
        $this->load->model('shopping/invoice_model');
        //send out emails
        $mail = array();
            $mail['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_data['invoice_id']);
            $mail['invoice_items'] = $this->invoice_model->getInvoiceItems($invoice_data['invoice_id']);
            $mail['customer_data'] = $customer_data;
        $content  = $this->mailer_model->composeInvoiceMessage($mail);
            $content['to'] = $customer_data['email'];
            $content['from'] = 'sales@langantiques.com';
            $this->mailer_model->sendEmail($content);

            $content['subject'] = 'Internet Sale! ' . $content['subject'];
            $content['to'] = 'info@langantiques.com'; //send to everyone
            $this->mailer_model->sendEmail($content);
    }

    private function send_reserve_email($invoice_data, $customer_data) {
        $this->load->model('mailer/mailer_model');
        $this->load->model('shopping/invoice_model');
        //send out emails
        $mail = array();
            $mail['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_data['invoice_id']);
            $mail['invoice_items'] = $this->invoice_model->getInvoiceItems($invoice_data['invoice_id']);
            $mail['customer_data'] = $customer_data;
        $content  = $this->mailer_model->composeReserveMessage($mail);
            $content['to'] = $customer_data['email'];
            $content['from'] = 'sales@langantiques.com';
            $this->mailer_model->sendEmail($content);

            $content['subject'] = 'Internet Sale! ' . $content['subject'];
            $content['to'] = 'info@langantiques.com'; //send to everyone
            $this->mailer_model->sendEmail($content);

    }

    private function insert_invoice_items($invoice_data, $items, $customer_data) {
        $this->load->model('shopping/invoice_model');
        $this->load->model('products/inventory_model');
        //add items to invoice
        foreach($items as $item) {
            $item_fields = array();
                $item_fields['invoice_id'] = $invoice_data['invoice_id'];
                $item_fields['item_id'] = $item['item_id'];
                $item_fields['item_number'] = $item['item_number'];
                $item_fields['buyer_type'] = 1;
                $item_fields['buyer_id'] = $customer_data['customer_id']; //customer_id
                $item_fields['sale_price'] = $item['item_price'];
                if($invoice_data['tax'] > 0) {
                    $item_fields['sale_tax'] = $item['item_price'] * TAX_RATE;
                }
                else {
                    $item_fields['sale_tax'] = 0;
                }
                $item_fields['item_status'] = 0; //normal

            //insert items into invoice_items table.
            $this->invoice_model->insertInvoiceItem($item_fields);
            //update each item as Sold
            $this->inventory_model->updateItemAsSold($item['item_id']);
        }
    }
    /**
     * Loads the Payment view
     *
     *
     */
    public function payment() {
        $this->load->helper('ssl');
        force_ssl(); //turn on SSL

        $this->load->library('form_validation');
        $this->load->model('user/user_model');
        $this->load->model('products/inventory_model');
        $this->load->model('shopping/invoice_model');
        $this->load->model('shopping/gateway_model');
        $this->load->model('mailer/mailer_model');
        $data = array();
        //credit card types @TODO move this into the database (HARDCODE is badd)
        $cards = array();
            $cards[''] = '';
            $cards[0] = 'American Express';
            $cards[1] = 'Discover';
            $cards[2] = 'Mastercard';
            $cards[3] = 'Visa';

        $data['card_types'] = $cards;
        //test to make sure a customer_id is set within the session
        if($this->session->userdata('customer_id') != null) {
            //test to make sure the cart is not empty
            if($this->session->userdata('cart') != null) {
                $numbers = $this->session->userdata('cart');
                $data['cart'] = array();
                //loop through each item in cart
                foreach($numbers as $item_number) {
                    //get and push each item data set into the 'cart' array
                    $data['cart'][] = $this->inventory_model->getItemDataByNumber($item_number);
                }
                //get customer data, based on customer id
                $data['customer_data'] = $this->user_model->getCustomerData($this->session->userdata('customer_id'));

                //Setup validation rules.
                $this->form_validation->set_rules('special_notes', 'Special Notes', 'trim|max_length[256]');
                $this->form_validation->set_rules('card_type', 'Card Type', 'trim|required|min_length[1]|numeric');
                $this->form_validation->set_rules('card_holder', 'Name on Card', 'trim|required|min_length[1]|max_length[256]');
                //This uses a callback which validates the customer credit card.
                $this->form_validation->set_rules('card_number', 'Card Number', 'trim|required|min_length[1]|max_length[256]|numeric|callback_CB_validateCreditCard[]');
                $this->form_validation->set_rules('card_cvv', 'CVV Number', 'trim|required|min_length[1]|max_length[5]|numeric');
                $this->form_validation->set_rules('card_month', 'Month Expiration', 'trim|required|min_length[1]|max_length[2]|numeric');
                $this->form_validation->set_rules('card_year', 'Year Expiration', 'trim|required|min_length[4]|max_length[4]|numeric');
                $this->form_validation->set_rules('terms', 'Agreement', 'trim|required');

                //Run Validation
                if($this->form_validation->run() == true) {

                    //create invoice
                    $invoice_id = $this->create_invoice($data['customer_data']);
                    //get invoice data
                    $invoice_data = $this->invoice_model->getInvoiceData($invoice_id);
                    //add items to invoice
                    $this->insert_invoice_items($invoice_data, $data['cart'], $data['customer_data']);

                    $tax = 0;
                    //if billing address is in CA, ad some tax
                    if(strtoupper($this->session->userdata('billing_state')) == 'CA') {
                        $tax = $this->session->userdata('total_price') * TAX_RATE;
                    }

                    $payment_fields = array();
                        $payment_fields['invoice_id'] = $invoice_id;
                        $payment_fields['buyer_type'] = 1;
                        $payment_fields['buyer_id'] = $data['customer_data']['customer_id']; //customer_id
                        $payment_fields['method'] = $this->input->post('card_type');
                        $payment_fields['amount'] = $this->session->userdata('total_price') + $this->session->userdata('shipping') + $tax;
                        $payment_fields['date'] = date('Y/m/d'); //todays date

                    //insert invoice payments
                    $payment_id = $this->invoice_model->insertInvoicePayment($payment_fields);
                    $payment_data = $this->invoice_model->getInvoicePaymentById($payment_id);
                    //gateway data
                    $gateway_data = array();
                        $gateway_data['ssl_amount'] = $payment_data['amount'];
                        $gateway_data['ssl_salestax'] = $tax;
                        $gateway_data['ssl_transaction_type'] = 'ccsale';
                        $gateway_data['ssl_show_form'] = 'false';
                        $gateway_data['ssl_invoice_number'] = $invoice_id;

                        //cc details
                        $gateway_data['ssl_card_number'] = $this->input->post('card_number'); // the credit card number
                        $gateway_data['ssl_exp_date'] = $this->input->post('card_month') . substr($this->input->post('card_year'), 2, 2); //date bust be: MMYY
                        $gateway_data['ssl_cvv2cvc2_indicator'] = '1'; //CVV2 Indicator 0=Bypassed, 1=present, 2=Illegible, and 9=Not Present
                        $gateway_data['ssl_cvv2cvc2'] = $this->input->post('card_cvv');
                        $gateway_data['ssl_first_name'] = $data['customer_data']['first_name'];
                        $gateway_data['ssl_last_name'] = $data['customer_data']['last_name'];

                        $gateway_data['ssl_avs_address'] = $data['customer_data']['address'];
                        $gateway_data['ssl_address2'] = $data['customer_data']['address2'];
                        $gateway_data['ssl_city'] = $data['customer_data']['city'];
                        $gateway_data['ssl_state'] = $data['customer_data']['state'];
                        $gateway_data['ssl_avs_zip'] = $data['customer_data']['zip'];
                        $gateway_data['ssl_country'] = $data['customer_data']['country'];

                    // //insert customer credit card data;
                    $customer_cc = array();
                        $customer_cc['int_customer_id'] = $data['customer_data']['customer_id']; //customer_id
                        $customer_cc['card_type'] =  $this->input->post('card_type');
                        $customer_cc['card_holder'] =  $this->input->post('card_holder');
                        $customer_cc['card_number'] =  $this->input->post('card_number');
                        $customer_cc['card_cvv'] =  $this->input->post('card_cvv');
                        $customer_cc['card_month'] =  $this->input->post('card_month');
                        $customer_cc['card_year'] =  $this->input->post('card_year');
                        $customer_cc['total_price'] =  $payment_fields['amount'];
                        $customer_cc['invoice_id'] =  $invoice_id;
                    // Insert Customer Credit Card Information
                    // $this->invoice_model->insertCustomerCreditCard($customer_cc);

                    $gateway_results = $this->gateway_model->call_gateway_processor($gateway_data, $data['customer_data']);
                    // if the gateway processor fails, send an email
                    if(strtolower($gateway_results['ssl_result_message']) != 'approval') {
                        $this->send_gateway_results_email($invoice_data, $gateway_results);
                    }
                    //send out emails
                    $this->send_purchase_email($invoice_data, $data['customer_data']);

                    //clear the cart
                    $this->session->set_userdata(array('cart'=>false)); //clear the cart
                    redirect('http://www.langantiques.com/shopping/thank-you', 'refresh');

                }
                else { //form_validation failed,
                    $this->load->view('shopping/payment_information_view', $data);
                }
            }
            else { //cart is empty, show cart
                redirect('shopping/view-cart', 'refresh');
            }
        }
        else { //customer id is not present, show cart
            redirect('shopping/view-cart', 'refresh');
        }
    }
    /**
     * Load Thank You Page
     */
    public function thank_you() {
        $data = array();

        $this->load->view('shopping/thank_you_view', $data);
    }
    public function reserved() {
        $data = array();

        $this->load->view('shopping/reserved_view', $data);
    }

    /**
     * Load View Cart Page
     */
    public function view_cart() {
        $data = array();
        $this->load->model('products/inventory_model');

        if($this->session->userdata('cart') != null) {
            $numbers = $this->session->userdata('cart');
            $data['cart'] = array();
            foreach($numbers as $item_number) {
                $data['cart'][] = $this->inventory_model->getItemDataByNumber($item_number);
            }
        }
        else {
            $data['cart'] = array();
        }
        //print_r($this->session->userdata);
        $this->load->view('shopping/cart_view', $data);
    }

    /**
     * Removes an Item from the cart.
     *
     * Requires a form submit, and removes items form the cart session value;
     */
    public function remove() {
        $item_id = $this->input->post('item_id');
        $items = $this->session->userdata('cart');
        //print_r($items);
        unset($items[$item_id]);
        //echo '<br />';
        //print_r($items);
        $this->session->set_userdata('cart', $items);
        //print_r($this->session->userdata);
        //echo $item_id;
        redirect('shopping/view-cart', 'refresh');
    }

    /*==============================================================================

    This routine checks the credit card number. The following checks are made:
    http://www.braemoor.co.uk/software/creditcard.php


    ******There are test credit card numbers here*********
    https://www.paypal.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm
    **************************************************************************


    1. A number has been provided
    2. The number is a right length for the card
    3. The number has an appropriate prefix for the card
    4. The number has a valid modulus 10 number check digit if required

    If the validation fails an error is reported.

    The structure of credit card formats was gleaned from a variety of sources on
    the web, although the best is probably on Wikepedia ("Credit card number"):

      http://en.wikipedia.org/wiki/Credit_card_number

    Input parameters:
                cardnumber           number on the card
                cardname             name of card as defined in the card list below
    Output parameters:
                cardnumber           number on the card
                cardname             name of card as defined in the card list below

    Author:     John Gardner
    Date:       4th January 2005
    Updated:    26th February 2005  additional credit cards added
                1st July 2006       multiple definition of Discovery card removed
                27th Nov. 2006      Additional cards added from Wikipedia
                            8th Dec 2007                Problem with Solo card defefintion corrected

    if (isset($_GET['submitted'])) {
      if (checkCreditCard ($_GET['CardNumber'], $_GET['CardType'], $ccerror, $ccerrortext)) {
        $ccerrortext = 'This card has a valid format';
      }
    }

    ==============================================================================*/
    /**
     * CallBack for validation the credit card,
     *
     * @param [string] $string = credit card number
     */
    public function CB_validateCreditCard($string) {
        $b = false; // assume failure
        //set the message for the callback
        $this->form_validation->set_message('CB_validateCreditCard', 'There was an error with your credit card number.');
        //pull the card type for the POST
        $cardname = $this->input->post('card_type');
        $this->checkCreditCard($string, $cardname, $errnum, $errtext);
        //echo $errnum;
        if($errnum != null) { //if error message not null, means error was encounter
            $this->form_validation->set_message('CB_validateCreditCard', $errtext);
        }
        else {
            $b = true;
        }
        return $b;
    }

    private function checkCreditCard ($cardnumber, $cardname, &$errornumber, &$errortext) {

      // Define the cards we support. You may add additional card types.

      //  Name:      As in the selection box of the form - must be same as user's
      //  Length:    List of possible valid lengths of the card number for the card
      //  prefixes:  List of possible prefixes for the card
      //  checkdigit Boolean to say whether there is a check digit

      // Don't forget - all but the last array definition needs a comma separator!

      $cards = array (  array ('name' => '0', //American Express
                              'length' => '15',
                              'prefixes' => '34,37',
                              'checkdigit' => true
                             ),
                       array ('name' => 'Carte Blanche',
                              'length' => '14',
                              'prefixes' => '300,301,302,303,304,305,36,38',
                              'checkdigit' => true
                             ),
                       array ('name' => 'Diners Club',
                              'length' => '14',
                              'prefixes' => '300,301,302,303,304,305,36,38',
                              'checkdigit' => true
                             ),
                       array ('name' => '1', //Discover
                              'length' => '16',
                              'prefixes' => '6011',
                              'checkdigit' => true
                             ),
                       array ('name' => 'Enroute',
                              'length' => '15',
                              'prefixes' => '2014,2149',
                              'checkdigit' => true
                             ),
                       array ('name' => 'JCB',
                              'length' => '15,16',
                              'prefixes' => '3,1800,2131',
                              'checkdigit' => true
                             ),
                       array ('name' => 'Maestro',
                              'length' => '16',
                              'prefixes' => '5020,6',
                              'checkdigit' => true
                             ),
                       array ('name' => '2', //MasterCard
                              'length' => '16',
                              'prefixes' => '51,52,53,54,55',
                              'checkdigit' => true
                             ),
                       array ('name' => 'Solo',
                              'length' => '16,18,19',
                              'prefixes' => '6334,6767',
                              'checkdigit' => true
                             ),
                       array ('name' => 'Switch',
                              'length' => '16,18,19',
                              'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759',
                              'checkdigit' => true
                             ),
                       array ('name' => '3', //Visa
                              'length' => '13,16',
                              'prefixes' => '4',
                              'checkdigit' => true
                             ),
                       array ('name' => 'Visa Electron',
                              'length' => '16',
                              'prefixes' => '417500,4917,4913',
                              'checkdigit' => true
                             )
                    );

      $ccErrorNo = 0;

      $ccErrors [0] = "Please select a Card Type";
      $ccErrors [1] = "Please provide a card number.";
      $ccErrors [2] = "The credit card number has an invalid format.";
      $ccErrors [3] = "The credit card number is invalid.";
      $ccErrors [4] = "The credit card number is the wrong length.";

      // Establish card type
      $cardType = -1;
      for ($i=0; $i<sizeof($cards); $i++) {

        // See if it is this card (ignoring the case of the string)
        if (strtolower($cardname) == strtolower($cards[$i]['name'])) {
          $cardType = $i;
          break;
        }
      }

      // If card type not found, report an error
      if ($cardType == -1) {
         $errornumber = 0;
         $errortext = $ccErrors [$errornumber];
         return false;
      }

      // Ensure that the user has provided a credit card number
      if (strlen($cardnumber) == 0)  {
         $errornumber = 1;
         $errortext = $ccErrors [$errornumber];
         return false;
      }

      // Remove any spaces from the credit card number
      $cardNo = str_replace (' ', '', $cardnumber);

      // Check that the number is numeric and of the right sort of length.
      if (!preg_match('/^[0-9]{13,19}/',$cardNo))  {
         $errornumber = 2;
         $errortext = $ccErrors [$errornumber];
         return false;
      }

      // Now check the modulus 10 check digit - if required
      if ($cards[$cardType]['checkdigit']) {
        $checksum = 0;                                  // running checksum total
        $mychar = "";                                   // next char to process
        $j = 1;                                         // takes value of 1 or 2

        // Process each digit one by one starting at the right
        for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {

          // Extract the next digit and multiply by 1 or 2 on alternative digits.
          $calc = $cardNo{$i} * $j;

          // If the result is in two digits add 1 to the checksum total
          if ($calc > 9) {
            $checksum = $checksum + 1;
            $calc = $calc - 10;
          }

          // Add the units element to the checksum total
          $checksum = $checksum + $calc;

          // Switch the value of j
          if ($j ==1) {$j = 2;} else {$j = 1;};
        }

        // All done - if checksum is divisible by 10, it is a valid modulus 10.
        // If not, report an error.
        if ($checksum % 10 != 0) {
         $errornumber = 3;
         $errortext = $ccErrors [$errornumber];
         return false;
        }
      }

      // The following are the card-specific checks we undertake.

      // Load an array with the valid prefixes for this card
      $prefix = explode(',',$cards[$cardType]['prefixes']);

      // Now see if any of them match what we have in the card number
      $PrefixValid = false;
      for ($i=0; $i<sizeof($prefix); $i++) {
        $exp = '/^' . $prefix[$i] . '/';
        if (preg_match($exp,$cardNo)) {
          $PrefixValid = true;
          break;
        }
      }

      // If it isn't a valid prefix there's no point at looking at the length
      if (!$PrefixValid) {
         $errornumber = 3;
         $errortext = $ccErrors [$errornumber];
         return false;
      }

      // See if the length is valid for this card
      $LengthValid = false;
      $lengths = explode(',',$cards[$cardType]['length']);
      for ($j=0; $j<sizeof($lengths); $j++) {
        if (strlen($cardNo) == $lengths[$j]) {
          $LengthValid = true;
          break;
        }
      }

      // See if all is OK by seeing if the length was valid.
      if (!$LengthValid) {
         $errornumber = 4;
         $errortext = $ccErrors [$errornumber];
         return false;
      };

      // The credit card is in the required format.
      return true;
    }
    /*============================================================================*/
}
