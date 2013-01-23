<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class form_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'form';
        $this->set_key('form_id');


        $this->fields = array(
             'form_id' => array(
                 'type'=>'INT',
                 'unsigned'=>TRUE,
                 'auto_increment'=>TRUE,
             ),
             'form_name' => array(
                 'type'=>'VARCHAR',
                 'constraint'=>'100',
             ),
            'form_description' => array(
                'type'=>'TEXT',
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
            ->set_subject('Form')
            ->columns(
            $cols[0],
            $cols[1]
        )

            ->display_as($cols[0],'ID')
            ->display_as($cols[1],'Name')


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