<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Formsection_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'formsection';
        $fieldprefix = 'formsection_';
        $this->set_key('formsection_id');

        $this->fields = array(
            $fieldprefix.'id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
                'auto_increment'=>TRUE,
            ),
            $fieldprefix.'name' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
            ),
            $fieldprefix.'description' => array(
                'type'=>'TEXT',
                'null'=>TRUE,
            ),
            $fieldprefix.'order'=>array(
                'type'=>'INT',
                'null'=>TRUE,
            ),
        );

        $this->crud = new Grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
    }

    /**
     * Returns a Grocery_CRUD render object.
     *
     * @return object
     *             output - html
     *             js_files - js src
     *             css_files - css href
     *
     * Full documentation at http://www.grocerycrud.com/documentation
     */
    public function output()
    {
        //field names
        $cols = array_keys($this->fields);

        $relation_table1 = $this->db->dbprefix('formfield_to_formsection');
        $selection_table1 = $this->db->dbprefix('formfield');

        $relation_table2 = $this->db->dbprefix('form_to_formsection');
        $selection_table2 = $this->db->dbprefix('form');

        $this->crud
            ->set_subject('Section')
            ->set_relation_n_n('Fields',$relation_table1,$selection_table1,$cols[0],'formfield_id','formfield_label')
            ->set_relation_n_n('Form',$relation_table2,$selection_table2,$cols[0],'form_id','form_name')
            ->columns(
            $cols[0],
            $cols[1],
            $cols[3]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Name')
            ->display_as($cols[3],'Order')

            ->fields($cols[1],$cols[2],$cols[3],'Fields','Form')
        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }
}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: form_model.php
 * Module: rental_applications
 * Location: models
 **/