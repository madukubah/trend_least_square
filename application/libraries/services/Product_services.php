<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_services
{
  function __construct(){

  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  
  public function table_config( $_page, $start_number = 1 )
  {
      $table["header"] = array(
        'code' => 'Kode Barang',
        'name' => 'Nama Barang',
      );
      $table["number"] = $start_number;
      $table[ "action" ] = array(
              array(
                "name" => 'Edit',
                "type" => "modal_form",
                "modal_id" => "edit_",
                "url" => site_url( $_page."edit/"),
                "button_color" => "primary",
                "param" => "id",
                "form_data" => array(
                    "id" => array(
                        'type' => 'hidden',
                        'label' => "id",
                    ),
                    "code" => array(
                        'type' => 'text',
                        'label' => "Kode Barang",
                    ),
                    "name" => array(
                        'type' => 'textarea',
                        'label' => "Nama Barang",
                    ),
                ),
                "title" => "Produk",
                "data_name" => "name",
              ),
              array(
                "name" => 'X',
                "type" => "modal_delete",
                "modal_id" => "delete_",
                "url" => site_url( $_page."delete/"),
                "button_color" => "danger",
                "param" => "id",
                "form_data" => array(
                  "id" => array(
                    'type' => 'hidden',
                    'label' => "id",
                  ),
                ),
                "title" => "Produk",
                "data_name" => "name",
              ),
    );
    return $table;
  }
  public function validation_config( ){
    $config = array(
        array(
          'field' => 'code',
          'label' => 'code',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'name',
          'label' => 'name',
          'rules' =>  'trim|required',
        ),
    );
    
    return $config;
  }
}
?>
