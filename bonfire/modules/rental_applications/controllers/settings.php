<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class settings extends Admin_Controller{

    public function __construct(){}

    public function index(){

        //$this->install();
    }

    public function install(){
        //local variables
        $transaction = TRUE; //Transaction Flag
        $installed_model = array(); //Keep track of installations.

        //load the models --//using long notation (Easier to Debug)
        $this->load->model('forms/Form_model');
        $this->load->model('forms/Form_to_formsection_model');
        $this->load->model('forms/Formsection_to_formfield_model');
        $this->load->model('forms/Formfields_model');
        $this->load->model('forms/Formsection_model');

        $this->load->model('Rentalplan_to_form_model');
        $this->load->model('Rentalplan_application_model');
        $this->load->model('Rental_model');
        $this->load->model('Accessory_model');
        $this->load->model('Recommendation_model');
        $this->load->model('Organization_recommendation_model');
        $this->load->model('Category_to_Product_model');
        $this->load->model('organization_to_contact_model');
        $this->load->model('standard_rental_model');
        $this->load->model('bravo_rental_model');

        $this->load->model('Rentalplan_model');
        $this->load->model('Rentalplan_details_model');
        $this->load->model('Product_model');
        $this->load->model('Contact_model');
        $this->load->model('Organization_model');
        $this->load->model('Category_model');
        //---------------------------------------


        //install the models using transaction design pattern.
        $model_array = array(
            //forms
            //$this->Form_to_formsection_model,
            //$this->Formsection_to_formfield_model,

            //$this->Form_model,
            //$this->Formfields_model,
            //$this->Formsection_model,*/
            //--------------------------

            //associative entities
            /*$this->Rentalplan_to_form_model,*/
            //$this->Rentalplan_application_model
            //$this->Rental_model,
            //$this->Accessory_model,*/
            //$this->Recommendation_model,
            //$this->Organization_recommendation_model,
            //$this->Category_to_Product_model,
            //$this->organization_to_contact_model,
            $this->standard_rental_model,
            $this->bravo_rental_model,
            //-------------------

            //core Rental entities
            //$this->Rentalplan_model,
            //$this->Rentalplan_details_model,
            /*$this->Product_model,
            $this->Contact_model,
            $this->Organization_model,
            $this->Category_model,*/
            //------------------
        );

        /**/
        //unsafe force update used for development purposes
        foreach ($model_array as $model){
            $model->set_installed(TRUE);
            $model->destroy();
        }
        /**/


        foreach ($model_array as $model){
            $transaction = $model->create();
            if ($transaction === FALSE){
                echo $model->error.'<br>';
                break;//exit loop
            }
            array_push($installed_model,$model);
        }

        if ($transaction === FALSE){
            foreach ($installed_model as $model){
                $model->destroy();
            }
        }

        //-----------------------------
        //Display the result of the transaction
        if ($transaction){
            echo 'Successful Module Install!';
        } else {
            echo 'Failed to Install the Module.';
        }
    }


    public function db_transfer()
    {
        $org_db = $this->load->database('mshoppe',TRUE);
        $new_db = $this->load->database('default',TRUE);

/**
        $query = $org_db->get('stringsrental_instruments');

        if ($query->num_rows() > 0)
        {
            foreach($query->result() as $result)
            {
                var_dump($result);

                $product_data = array(
                    'product_name'=> $result->instrument

                );

                $new_db->insert('bf_product',$product_data);

                $insert_id = $this->db->insert_id();

                $rental_data = array(
                    'product_id' => $insert_id,
                    'rentalplan_id' => 2,
                    'type' => 'standard'
                );

                $new_db->insert('bf_rental',$rental_data);

                $standard_data = array(
                    'product_id' => $insert_id,
                    'rentalplan_id' => 2,
                    'rental_description' => $result->description,
                    **'bronze_price' => $result->bronzeprice,
                    'silver_price' => $result->silverprice,
                    'gold_price' => $result->goldprice,
                    'platinum_price' => $result->platinumprice,**
                    'rent_only_price' => $result->renttorentprice,
                    'two_month_price' => $result->twomonthprice,
                    'maintenance_price' => $result->maintenanceprice,
                    'replacement_price' => $result->replacementprice,
                    'service_charge' => $result->servicecharge,
                    'installments' => $result->installments,
                    /*'purchase_price_bronze' => $result->purchasepricebronze,
                    'purchase_price_silver' => $result->purchasepricesilver,
                    'purchase_price_gold' => $result->purchasepricegold,
                    'purchase_price_platinum' => $result->purchasepriceplatinum,
                    'service_charge_bronze' => $result->bronzeservicecharge,
                    'service_charge_silver' => $result->silverservicecharge,
                    'service_charge_gold' => $result->goldservicecharge,
                    'service_charge_platinum' => $result->platinumservicecharge,**
                );

                $new_db->insert('bf_standard_rental',$standard_data);
            }
        }**/


/*

        $query = $org_db->get('instrumentrental_accessories');

        if ($query->num_rows() > 0)
        {
            foreach($query->result() as $result){

                $product_data = array(
                    'product_name'=> $result->accessoryname,
                    'product_description'=>$result->accessorydescription,
                    'product_price'=>$result->price,
                    'product_sku' => $result->sku,
                );

            $new_db->insert('bf_product',$product_data);
            }
        }


        $query = $org_db->get('stringsrental_accessories');

        if ($query->num_rows() > 0)
        {
            foreach($query->result() as $result){

                $product_data = array(
                    'product_name'=> $result->accessoryname,
                    'product_description'=>$result->accessorydescription,
                    'product_price'=>$result->price,
                    'product_sku' => $result->sku,
                );

            $new_db->insert('bf_product',$product_data);
            }

        }

        */

    /*
        $query = $org_db->get('school');

        if ($query->num_rows() > 0)
        {
            foreach($query->result() as $result){

                $data = array(
                    'organization_name' => $result->school
                );

                $new_db->insert('bf_organization',$data);
            }
        }
    */



        $query = $new_db->get('bf_standard_rental');
        if ($query->num_rows() > 0)
        {
            foreach($query->result() as $result)
            {
                //var_dump($result);


            }
        }
    }
}
/**
 * Created by CTech.
 * Author: user
 * Date: 1/7/13
 * File: settings.php
 */