<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class rentalplan_application_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'rentalplan_application';
        $this->set_key('application_id');

        $this->fields = array(
            'application_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE
            ),
            'rentalplan_id' => array(
                'type'=>'INT',
                'unsigned'=>TRUE,
            ),
            'contact_id' => array(
                'type'=>'VARCHAR',
                'constraint'=>'100',
            ),
            'contract_id' => array(
                'type'=>'VARCHAR',
                'constraint'=>'255'
            ),
            'status' => array(
                'type'=>'ENUM',
                'constraint'=>"'new','accepted','rejected'",
                'default'=>'new',
            ),
            'postdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        );

        $this->crud = new grocery_CRUD();
        $this->crud->set_table($this->db->dbprefix($this->table));
        //$this->crud->set_url_segment($this->name);
        //check for new contracts
        $this->new_contracts();
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
        $this->crud
            ->unset_add()
            ->columns(
                    'contract_id',
                    'status',
                    'postdate'
        )

            ->display_as('contract_id','Contract Number')
            ->callback_column('contract_id',array($this,'_callback_contract_id'))
        ;
        //$crud->fields();
        //$crud->required_fields();

        return $this->crud->render();
    }

    /***
     * Makes the contract_id linked to the pdf file.
     */
    public function _callback_contract_id($value,$row)
    {
       return anchor("assets/pdf/secure_$value.pdf",$value);
    }

    private function new_contracts(){
        $this->load->helper('file');

        $files = get_filenames('assets/pdf');

        foreach ($files as $file)
        {
            if (preg_match('/secure_/',$file) > 0)
            {
                $contract_id = str_replace('unsecure_','',$file);
                $contract_id = str_replace('secure_','',$contract_id);
                $contract_id = str_replace('.pdf','',$contract_id);
                //todo add rentalplan_id

                $data = array(
                    'contract_id' => $contract_id,
                    'rentalplan_id' => 1
                );

                $this->db->where('contract_id',$contract_id);
                $query = $this->db->get('rentalplan_application');
                if ($query->num_rows() == 0)
                {
                    $this->db->insert('rentalplan_application',$data);
                }
            }
        }
        //read the directory


    }



}
/**
 * Created by CTech
 * Author: Shawn Rhoney
 * Date: 01/04/2013
 * File: rentalplan_application_model.php
 * Module: rental_applications
 * Location: models
 **/