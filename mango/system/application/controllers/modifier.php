<?php
/**
 * Half of this class is used by contollers/Admin.php
 * See the system/application/config/routes.php file for details
 *
 * @author zeph
 *
 */

class Modifier extends Controller {
    var $ci;

    function __constuct() {
        parent::Controller();
        //Check The user to see if they are logged in
        $this->ci =& get_instance();

    }

    function modifier_add() {
        $this->load->library('authorize');
        $this->load->model('inventory/modifier_model');
        $this->load->library('form_validation');

        $data['user_data'] = $this->authorize->getSessionData();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('modifier_name', 'Modifier Name', 'trim|required|min_length[3]|max_length[256]|callback_CB_check_modifier');

        if ($this->form_validation->run() == TRUE) {
            $fields = array();
                $fields['modifier_name'] = $this->input->post('modifier_name');
            $modifier_id = $this->modifier_model->insertModifier($fields);

            redirect('admin/modifier_edit/' . $modifier_id, 'refresh');
        }
        else {
            $this->load->view('admin/modifier/modifiers_add_view', $data);
        }
    }

    function modifier_delete($modifier_id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('modifier_id', 'Modifier ID', 'trim|required|numeric|min_length[1]');

        if($this->form_validation->run() == TRUE) {
            $this->load->model('inventory/modifier_model');
            $this->modifier_model->deleteModifier($this->input->post('modifier_id'));
            redirect('admin/modifier_list', 'refresh');
        }
        else {
            echo '<h1>ERRORRRSKJZNMZN.......';
        }
    }

    function modifier_edit($modifier_id) {
        $this->load->model('inventory/modifier_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('authorize');

        $data['user_data'] = $this->authorize->getSessionData();
        $data['modifier_data'] = $this->modifier_model->getModifierData($modifier_id);
        $data['modifier_data']['item_count'] = $this->modifier_model->getModifierCount($modifier_id);

        $this->form_validation->set_rules('modifier_name', 'Modifier Name', 'trim|required|min_length[3]|max_length[256]');
        $this->form_validation->set_rules('modifier_title', 'Modifier Title', 'trim|min_length[3]|max_length[256]');
        $this->form_validation->set_rules('element_url_name', 'Element URL Name', 'trim|max_length[256]|callback_CB_check_modifier_url_string');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim|max_length[256]');


        if($this->form_validation->run() == true) {
            $fields = array();
                $fields['modifier_name'] = $this->input->post('modifier_name');
                $fields['modifier_title'] = $this->input->post('modifier_title');
                $fields['meta_description'] = $this->input->post('meta_description');
                $fields['page_paragraph'] = $this->input->post('page_paragraph');
                $fields['active'] = $this->input->post('active');
                $fields['embolden'] = $this->input->post('embolden');
                $fields['show_web'] = $this->input->post('show_web');
                $fields['alt_keyword'] = $this->input->post('alt_keyword');
                if($this->input->post('alt_keyword') == 1) {
                    $fields['keyword_name'] = $this->input->post('keyword_name');
                }
                $fields['element_url_name'] = $this->input->post('element_url_name');
                $fields['top_level'] = $this->input->post('top_level');
                $fields['staff'] = $this->input->post('staff');
            $this->modifier_model->updateModifier($modifier_id, $fields);
            redirect('admin/modifier_edit/' . $modifier_id, 'refresh');
        }
        else {
            $this->load->view('admin/modifier/modifiers_edit_view', $data);
        }
    }

    function modifier_list() {
        $this->load->library('authorize');
        $this->load->model('inventory/modifier_model');
        $this->authorize->isLoggedIn();

        $data['yesno'] = array(0 => 'No', 1 => 'Yes');
        $data['user_data'] = $this->authorize->getSessionData();
        $data['modifiers'] = $this->modifier_model->getAllModifiers();

        $this->load->view('admin/modifier/modifiers_list_view', $data);

    }









    /**
     * Used by an Ajax Call to apply a modifier to an item
     *
     * @param [int] $id = item id
     * @param [int] $modifier_id = modifier id
     *
     * @return null
     */
    function AJAX_applyModifier($id, $modifier_id) {
        $this->load->model('inventory/modifier_model');
        $this->modifier_model->applyModifier($id, $modifier_id);
    }

    /**
     * prints out the modifier name
     *
     * @param [int] $modifier_id
     *
     * @return string = echos string name back
     */
    function AJAX_getModifierName($modifier_id) {
        $this->load->model('inventory/modifier_model');
        $name = $this->modifier_model->getModifierName($modifier_id);
        echo $name;
    }

    function AJAX_getModifierNames() {
        $this->load->model('inventory/modifier_model');
        $value = $_REQUEST['q']; //jQuery goofyness, can only use $_REQUESTS['q'] for query strings
        $data = $this->modifier_model->searchModifierNames($value);
        $junk = array();
        foreach($data as $row) {
            $json['modifier_id'] = $row['modifier_id'];
            $json['modifier_name'] = $row['modifier_name'];
            $junk['mod'][] = $json;
        }
        echo json_encode($junk);
    }

    function AJAX_modifier_edit($modifier_id) {
        $this->load->model('inventory/modifier_model');
        $value = $this->input->post('value');
        $column = $this->input->post('id');

        $this->modifier_model->AJAX_updateModifierField($modifier_id, $column, $value);
        echo $value;
    }

    /**
     * Used by an Ajax call to remove a modifier from an item
     *
     * @param [int] $id = item id
     * @param [int] $modifier_id = modifier id
     *
     * @return null
     */
    function AJAX_removeModifier($id, $modifier_id) {
        $this->load->model('inventory/modifier_model');
        $this->modifier_model->removeModifier($id, $modifier_id);
    }

    function CB_check_modifier($string) {
        $this->load->model('inventory/modifier_model');
        $b = false;
        $mod = $this->modifier_model->checkModifierNames($string);
        $this->form_validation->set_message('CB_check_modifier', 'Modifier with that Name Found. <br /> Change the name and try again.');
        if(!$mod) { //$mod = false, no material found
            $b =  true;
        }
        return $b;

    }

    function CB_check_modifier_url_string($string) {
        $b = false;
        $this->form_validation->set_message('CB_check_modifier_url_string', 'A Web name can only contain letters, numbers, and dashes. No spaces please');
        $pattern = '/^[a-zA-Z0-9\-]*$/';
        if(preg_match($pattern, $string)) {
            $b = true;
        }
        return $b;

    }
}
?>