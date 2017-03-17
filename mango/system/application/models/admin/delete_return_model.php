<?php
/**
 * Delete inventory Related methods
 *
 * @author zeph
 *
 */
class Delete_return_model extends Model {

    var $ci;

    function __construct() {
        parent::Model();

        $this->load->database();
        $this->ci =& get_instance();
    }

    /**
     * Deletes a Return frome the database
     *
     * @todo MAKE IT A STATUS CHANGE ONLY! DELETES ARE BAD!
     *
     * @param [int] $id
     *
     * @return null
     */
    public function deleteReturn($return_id) {
        $this->db->where('return_id', $return_id);
        $this->db->limit(1);
        $this->db->delete("returns");
    } //end deleteInventoryItem();

    /**
     * Gets all Delete History for each item
     *
     * @return array[] = database records
     */
    public function getReturnDeleteHistory() {
        $this->ci->load->model('user/user_model');
        $this->db->from('return_delete_history');
        $this->db->join('users', 'users.user_id = return_delete_history.user_id');
        $this->db->order_by('delete_date', 'DESC');

        $query = $this->db->get();
        $data = array();
        if($query->num_rows() > 0) {
            foreach($query->result_array() as $row) {
                $row['user_data'] = $this->ci->user_model->getUserData($row['user_id']);

                $data[] = $row;
            }
        }

        return $data; //array
    } //end getDeleteHistory();

    /**
     * Inserts a history record for each item that is deleted
     *
     * @param [int] $id = item id
     * @param [int] $user_id = user id
     * @param [text] $reason = reason for delete
     *
     * @return null
     */
    public function insertHistoryRecord($fields) {
        //insert the history record
        $this->db->insert('return_delete_history', $fields);

        return $this->db->insert_id();
    } //end insterDeleteHistory();

    /**
     * Returns an Invoice item back to an Invoice (if it's already been returned)
     *
     * @param  [int] $invoice_id  = invoice id
     * @param  [int] $item_id = item id
     * @param  [array] $fields = array of columns and values
     *
     * @return null
     */
    public function returnInvoiceItemBackToInvoice($invoice_id, $item_id, $fields) {
        $this->db->where('invoice_id', $invoice_id);
        $this->db->where('item_id', $item_id);
        $this->db->limit(1);
        $this->db->update('invoice_items', $fields);

        return null;
    }

}// end Delete_inventory_model();
?>