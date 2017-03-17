<?php
/**
 * Mailer_model
 * Formats and Sends out Emails to people
 *
 *
 * @author user
 *
 */
class Mailer_model extends Model {

    var $ci;

    function __construct() {
        parent::Model();
    }

    /**
     * Sends out an email to a specific address
     *
     * @param [array] $data = email data;
     */
    public function sendEmail($data) {
        //supress error messages
        mail(
            $data['to'],
            $data['subject'],
            $data['message'],
            "From: " . $data['from'] . "\nContent-Type: text/html; charset=iso-8859-1"
        );
    } //end sendEmail();

    /**
     * Composes an Inqury message about a specific item
     *
     * @param [array] $data = item information
     *
     * @return [array] = mesage array
     */
    public function composeInquryMessage($data) {
        $content = array();
            $content['subject'] = $data['name'] . " would like information about: #" . $data['item_number'] . " - " . $data['item_name'];
            $content['message'] = "<p>" . $data['name'] . " would like to more information about the following item:<br />"
                . "Their contact information is: <br />"
                . "<b>Email</b>: " . $data['email'] . "<br />";
                if ($data['phone_number'] != "") {
                    $content['message'] .=  "<b>Phone</b>: " . $data['phone_number'] . "<br />";
                }
            $content['message'] .= "</p>"
                . "<p><strong>" . $data['name'] . "'s question:</strong> " . $data['question'] . "</p>"
                . " <a href='http://www.langantiques.com/products/item/" . $data['item_number'] . "' ><img src='http://www.langantiques.com/" . $data['image_location'] . "' /></a>"
                . "<p>Click here to view the <a href='http://www.langantiques.com/products/item/" . $data['item_number'] . "' >" . $data['item_name'] . "</a></p>";
        return $content;
    } //end composeInqueryMessage();


    /**
     * Composes the Send to Frend Message
     *
     * @param [array] $data = item information, and friend data
     *
     * @return [array] = message array;
     */
    public function composeFriendMessage($data) {
        $content = array();
        //Copy of Tabitha would like to share this Vintage Two-Tone Amethyst Enamel Ring with you FROM: tmichanick@gmail.com TO: Ralph.nickolas@gmail.com
            $content['subject'] = $data['your_name'] . " would like to share this #" . $data['item_name'] . " with you";

            $content['message'] = "<p>Dear " . $data['friend_name'] . ",</p>"
                . '<p>Your friend, ' . $data['your_name'] . ' thought you may be intersted in an item we have at our website: ' . anchor('http://www.LangAntiques.com', 'http://www.LangAntiques.com') . '</p>'
                . '<p>' . $data['personal_message'] . '</p>'
                . '<h3>' . $data['item_name'] . '</h3>'
                . '<p>' . $data['item_description'] . '</p>'
                . '<a href="http://www.langantiques.com/products/item/'. $data['item_number']. '"><img src="http://www.langantiques.com/' . $data['image_location'] . '" /></a>'
                . '<p>Click here to view the all the details of this <a href="http://www.langantiques.com/products/item/'. $data['item_number']. '">' . $data['item_name'] . '</a></p>'
                . '<p>This email was sent to you on the behalf of ' . $data['your_name'] . ' from ' . anchor('http://www.LangAntiques.com', 'Lang Antiques') . '</p>';
        return $content; //arary
    } //end composeFriendMessage();

    public function composeGatewayMessage($data) {
        $content = array();
            $content['message'] = "The following Credit Card Purchase has failed for Invoice " . $data['invoice_data']['invoice_id'] . ": ";
            $content['message'] .= "Here are the results: <p>";
            foreach($data['gateway_results'] as $key => $value) {
                $content['message'] .= $key . ":" . $value . " <br />";
            }
            $content['message'] .= "</p>";
            $content['message'] .= "<p>You should contact the customer about this</p>";
        return $content;
    }

    public function composeReserveMessage($data) {
        $content = array();
            $content['subject'] = "Reserve Information from Lang Antiques - Invoice ID: " . $data['invoice_data']['invoice_id'];
            $content['message'] = "<p>Dear " . $data['customer_data']['first_name'] . " " . $data['customer_data']['last_name'] . ",</p>"
            . "<p>Thank you for your on-line reserve at Lang Antiques. We are very excited about your purchase. </p>"
            . "<p>Once you have supplied payment information, we will ship your item(s) to the address below:</p>"
            . "<p>" . $data['customer_data']['ship_contact'] . "<br />"
            . $data['customer_data']['ship_address'] . "<br />";
            if(trim($data['customer_data']['ship_address2']) != '') {
                $content['message'] .= $data['customer_data']['ship_address2'] . "<br />";
            }
            $content['message'] .= $data['customer_data']['ship_city'] . ", " . $data['customer_data']['ship_state'] . " " . $data['customer_data']['ship_zip'] . "<br />"
            . "Contact Phone Number: " .$data['customer_data']['ship_phone'] . "</p>";
            if ($this->session->userdata('shipping') == '0') {
                $ship_text = "Free Overnight Shipping";
            }
            else if ($this->session->userdata('shipping') == '75') {
                $ship_text = "Overnight shipping";
            }
            else {
                $ship_text = "Normal 2 day shipping";
            }
            $content['message'] .= "<p>Shipped Via FedEx " . $ship_text . ": $" . number_format($this->session->userdata('shipping'), 2) . "<p>";
            $content['message'] .= "<table style='border: 1px solid #ddd;' cellpadding='2px' cellspacing='2px'>";
        //loop through items
        foreach($data['invoice_items'] as $item) {
            $content['message'] .= "<tr><td nowrap><a href='http://www.langantiques.com/products/item/" . $item['item_number'] . "/'>" . $item['item_number'] . " - " . $item['item_name'] . "</a></td><td>$" . number_format($item['sale_price'], 2) . "</td></tr>";
        }

        $content['message'] .= "<tr><td style='text-align: right;'>CA Sales tax:</td> <td>$" . number_format($data['invoice_data']['tax'], 2) . "</td></tr>"
            . "<tr><td style='text-align: right;'>Shipped Via FedEx:</td><td>$" . number_format($this->session->userdata('shipping'), 2) . " (" . $ship_text . ") </td></tr>"
            . "<tr><td style='text-align: right;'>Total Invoice Price:</td><td> <b>$" . number_format(($data['invoice_data']['total_price'] + $data['invoice_data']['tax'] + $this->session->userdata('shipping')), 2). "</b></td></tr>"
            . "<tr><td style='text-align: right;'>Invoice Date:</td><td> " . date("M d, Y", strtotime($data['invoice_data']['sale_date'])) . "</td></tr>"
            . "</table>";
        //Last Paragraph for th email
        $content['message'] .= "<p> If you have any questions, please feel free to contact us by phone at <b>1-800-924-2213 </b> or via email at <a href='mailto:info@langantiques.com'>info@langantiques.com</a>. "
            . "<br /><b>Shipping and Insurance costs are not refundable.</b></p>";

        //Send email to their address, from Sales
        //mail($to, $subject, $message, "From: sales@langantiques.com\nContent-Type: text/html; charset=iso-8859-1");

        return $content; //array
    }


    /**
     * Composes an Invoice Email message
     *
     * @param [int] $data =  multi-dem array of customer data, item data
     *
     * @return [array] = message array
     */
    public function composeInvoiceMessage($data) {
        $content = array();
            $content['subject'] = "Purchase Information from Lang Antiques - Invoice ID: " . $data['invoice_data']['invoice_id'];
            $content['message'] = "<p>Dear " . $data['customer_data']['first_name'] . " " . $data['customer_data']['last_name'] . ",</p>"
            . "<p>Thank you for your on-line purchase at Lang Antiques. We are very excited about your purchase. </p>"
            . "<p>Once your payment information has been verified, we will ship your item(s) to the address below:</p>"
            . "<p>" . $data['customer_data']['ship_contact'] . "<br />"
            . $data['customer_data']['ship_address'] . "<br />";
            if(trim($data['customer_data']['ship_address2']) != '') {
                $content['message'] .= $data['customer_data']['ship_address2'] . "<br />";
            }
            $content['message'] .= $data['customer_data']['ship_city'] . ", " . $data['customer_data']['ship_state'] . " " . $data['customer_data']['ship_zip'] . "<br />"
            . "Contact Phone Number: " .$data['customer_data']['ship_phone'] . "</p>";
            if ($this->session->userdata('shipping') == '0') {
                $ship_text = "Free Overnight Shipping";
            }
            else if ($this->session->userdata('shipping') == '75') {
                $ship_text = "Overnight shipping";
            }
            else {
                $ship_text = "Normal 2 day shipping";
            }

            $content['message'] .= "<p>Shipped Via FedEx " . $ship_text . ": $" . number_format($this->session->userdata('shipping'), 2) . "<p>";


            $content['message'] .= "<table style='border: 1px solid #ddd;' cellpadding='2px' cellspacing='2px'>";

        //loop through items
        foreach($data['invoice_items'] as $item) {
            $content['message'] .= "<tr><td nowrap><a href='http://www.langantiques.com/products/item/" . $item['item_number'] . "/'>" . $item['item_number'] . " - " . $item['item_name'] . "</a></td><td>$" . number_format($item['sale_price'], 2) . "</td></tr>";
        }

        $content['message'] .= "<tr><td style='text-align: right;'>CA Sales tax:</td> <td>$" . number_format($data['invoice_data']['tax'], 2) . "</td></tr>"
            . "<tr><td style='text-align: right;'>Shipped Via FedEx:</td><td>$" . number_format($this->session->userdata('shipping'), 2) . " (" . $ship_text . ") </td></tr>"
            . "<tr><td style='text-align: right;'>Total Invoice Price:</td><td> <b>$" . number_format(($data['invoice_data']['total_price'] + $data['invoice_data']['tax'] + $this->session->userdata('shipping')), 2). "</b></td></tr>"
            . "<tr><td style='text-align: right;'>Invoice Date:</td><td> " . date("M d, Y", strtotime($data['invoice_data']['sale_date'])) . "</td></tr>"
            . "</table>";
        //Last Paragraph for th email
        $content['message'] .= "<p> If you have any questions, please feel free to contact us by phone at <b>1-800-924-2213 </b> or via email at <a href='mailto:info@langantiques.com'>info@langantiques.com</a>. "
            . "<br /><b>Shipping and Insurance costs are not refundable.</b></p>";

        //Send email to their address, from Sales
        //mail($to, $subject, $message, "From: sales@langantiques.com\nContent-Type: text/html; charset=iso-8859-1");

        return $content; //array
    } //end composeInvoiceMessage();

    public function composeResetPasswordMessage($session) {
        $content = array();
            $content['subject'] = 'You Have Issued a Request to Reset Your Password';
            $content['message'] = '<p> Hello, </p>';
                $content['message'] .= '<p>You have issued a request to reset your password</p>';
                $content['message'] .= '<p>Please follow the URL below.</p>';
                $content['message'] .= '<a href="http://www.langantiques.com/user/reset-password/' . $session . '">http://www.langantiques.com/user/reset-password/' . $session . '</a>';

        return $content;
    }


    /**
     * Composes the Share Favorites Message
     *
     * @param [array] $data = Favorites information, and friend data
     *
     * @return [array] = message array;
     */
    public function composeShareFavoritesMessage($data) {
        $content = array();
        //Copy of Tabitha would like to share this Vintage Two-Tone Amethyst Enamel Ring with you FROM: tmichanick@gmail.com TO: Ralph.nickolas@gmail.com
            $content['subject'] = $data['your_name'] . ' Would Like to Share Something Wonderful!';

            $content['message'] = "<p>Dear " . $data['friend_name'] . ",</p>"
                . '<p>Your friend, ' . $data['your_name'] . ' thought you may be intersted in their favorite items we have at our website: ' . anchor('http://www.LangAntiques.com', 'http://www.LangAntiques.com') . '</p>'
                . '<p>' . $data['personal_message'] . '</p>';
                $t = '<table style="" border=1>';
                if(sizeof($data['favorites']) > 0) {
                    foreach($data['favorites'] as $item) {
                        $t .= '<tr>';
                            $t .= '<td valign="top"><a href="http://www.langantiques.com/products/item/' . $item['item_number'] . '"><img src="http://www.langantiques.com/images/thumbnails/75/' . $item['images'][0]['image_id'] . '.jpg" /></a></td>';
                            $t .= '<td nowrap valign="top"><a href="http://www.langantiques.com/products/item/' . $item['item_number'] . '">' . $item['item_name'] . '</a></td>';
                            $t .= '<td valign="top">' . $item['item_description'] . '</td>';
                        $t .= '</tr>';
                    }
                }
                $t .= '</table>';
                $content['message'] .= $t;
                $content['message'] .= '<p>This email was sent to you on the behalf of ' . $data['your_name'] . ' from ' . anchor('http://www.LangAntiques.com', 'Lang Antiques') . '</p>';
        return $content; //arary
    } //end composeFriendMessage();


} //end Mailer_model();