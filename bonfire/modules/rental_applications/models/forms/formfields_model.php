<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class formfields_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'formfield';
        $fieldprefix = 'formfield_';
        $this->set_key($fieldprefix.'id');


        $this->fields = array(
            $fieldprefix.'id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
                'auto_increment'=>TRUE,
            ),
            $fieldprefix.'name' => array(
                'type'=>'VARCHAR',
                'constraint'=>'50',
            ),
            $fieldprefix.'description' => array(
                'type'=>'TEXT',
                'null'=>TRUE,
            ),
            $fieldprefix.'label' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
            ),
            $fieldprefix.'validation_rules'=>array(
                'type'=>'TEXT',
                'null'=>TRUE,
            ),
            $fieldprefix.'order'=>array(
                'type'=>'int',
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


        $this->crud
            ->set_subject('Field')
            ->columns(
            $cols[0],
            $cols[1],
            $cols[3],
            $cols[5]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Name')
            ->display_as($cols[3],'Label')
            ->display_as($cols[5],'Order')


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