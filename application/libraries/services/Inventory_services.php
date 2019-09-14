<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/Util.php";

class Inventory_services
{
  function __construct()
  {

  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function table_config_no_action( $_page, $start_number = 1 )
  {
    $table["header"] = array(
      'product_name' => 'Nama Barang',
      'month' => 'Bulan',
      'year' => 'Tahun',
      'quantity' => 'Stok',
    );
    $table[ "number" ] = $start_number;
    return $table;
  }

  public function table_config( $_page, $start_number = 1 )
  {
      $table["header"] = array(
        'product_name' => 'Nama Barang',
        'month' => 'Bulan',
        'year' => 'Tahun',
        'quantity' => 'Stok',
      );
      $table[ "number" ] = $start_number;
      $table[ "action" ] = array(
              array(
                "name" => 'Edit',
                "type" => "modal_form",
                "modal_id" => "edit_",
                "url" => site_url( $_page."edit/"),
                "button_color" => "primary",
                "param" => "id",
                "form_data" => $this->get_form_data(  )[ "form_data" ] ,
                "title" => "Penjualan",
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
                "title" => "Penjualan",
                "data_name" => "product_name",
              ),
    );
    return $table;
  }
  public function validation_config( ){
    $config = array(
        array(
          'field' => 'product_id',
          'label' => 'product_id',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'month',
          'label' => 'month',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'year',
          'label' => 'year',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'quantity',
          'label' => 'quantity',
          'rules' =>  'trim|required',
        ),
    );
    
    return $config;
  }

  /**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data(  )
	{
    $this->load->model('m_product');
		$products = $this->m_product->products()->result();

		$product_options ="";
		foreach($products as $n => $item)
		{	
			$product_options .= form_radio("product_id", $item->id ,set_checkbox('product_id', $item->id), ' id="basic_checkbox_'.$n.'"');
			$product_options .= '<label for="basic_checkbox_'.$n.'"> '. $item->name .'</label><br>';
		}
		$data['products'] = $product_options;
		$product_select = array();
		foreach( $products as $product )
		{
			if( $product->id == 1 ) continue;
			$product_select[ $product->id ] = $product->name;
		}

		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
      ),
      "product_id" => array(
				'type' => 'select',
				'label' => "Produk",
				'options' => $product_select,
				// 'selected' => $this->product_id,
      ),
      
			"month" => array(
			  'type' => 'select',
				'label' => "Bulan",
				'options' => Util::MONTH,
      ),
      "year" => array(
			  'type' => 'select',
				'label' => "Tahun",
				'options' => Util::YEAR,
      ),
      "quantity" => array(
			  'type' => 'number',
			  'label' => "Stok",
			),
    );
		return $_data;
  }
  
  /**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_filter(  )
	{
    $this->load->model('m_product');
		$products = $this->m_product->products()->result();

		$product_options ="";
		foreach($products as $n => $item)
		{	
			$product_options .= form_radio("product_id", $item->id ,set_checkbox('product_id', $item->id), ' id="basic_checkbox_'.$n.'"');
			$product_options .= '<label for="basic_checkbox_'.$n.'"> '. $item->name .'</label><br>';
		}
		$data['products'] = $product_options;
		$product_select = array();
		foreach( $products as $product )
		{
			if( $product->id == 1 ) continue;
			$product_select[ $product->id ] = $product->name;
		}

		$_data["form_data"] = array(
      "product_id" => array(
				'type' => 'select',
				'label' => "Produk",
				'options' => $product_select,
      ),
      // "month_count" => array(
			//   'type' => 'number',
			//   'label' => "Jumlah Bulan",
			// ),
      "year" => array(
			  'type' => 'select',
				'label' => "Tahun",
				'options' => Util::YEAR,
      ),
    );
		return $_data;
	}
}
?>
