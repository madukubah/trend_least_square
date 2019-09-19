<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/Util.php";
class Prediction_services
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
        'month' => 'Bulan',
        '_x' => 'Periode (X)',
        '_y' => 'Penjualan (Y)',
        '_xx' => 'X^2',
        '_xy' => 'XY',
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
          'field' => 'start_month',
          'label' => 'start_month',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'end_month',
          'label' => 'end_month',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'year',
          'label' => 'year',
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
	public function get_form_data( $context )
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

		$_data[0]["form_data"] = array(
      "product_id" => array(
				'type' => 'select',
				'label' => "Produk",
				'options' => $product_select,
				// 'selected' => $this->product_id,
      ),
      "context" => array(
				'type' => 'hidden',
        'label' => "Produk",
        'value' => $context
      ),
    );
    $_data[1]["form_data"] = array(
      "start_month" => array(
			  'type' => 'select',
				'label' => "Dari Bulan",
				'options' => [1 => "Januari"],
      ),
      "start_year" => array(
			  'type' => 'select',
				'label' => "Tahun",
				'options' => [2016 => "2016"],
      ),
      "end_month" => array(
			  'type' => 'select',
				'label' => "Sampai Bulan",
        'options' => Util::MONTH,
        'selected' => 2,
      ),
      "end_year" => array(
			  'type' => 'select',
				'label' => "Tahun",
        'options' => Util::YEAR,
      ),
    );
		return $_data;
  }

  public function odd_prediction( $data )
	{
    $_n = count( $data );
		$_mid = ( ( $_n - 1 ) / 2 ) + 1;

		$sum_x 		= 0;
		$sum_y 		= 0;
		$sum_xx 	= 0;
		$sum_xy 	= 0;

		for( $i =0 , $_x = ( $_mid - 1 ) * -1; $_x <= ( $_mid - 1 ), $i < $_n ; $_x++, $i++ )
		{
			$sum_x += $_x;
			$sum_y += $data[$i]->_y;
			$sum_xx += ( $_x*$_x );
			$sum_xy += ( $_x*$data[$i]->_y );

			$data[$i]->_x = $_x;
			$data[$i]->_xx = ( $_x*$_x );
			$data[$i]->_xy = ( $_x*$data[$i]->_y );
		}

		$data []= ( object ) 
			array(
				"id" => 0,
				"product_id" => 0,
				"month" => "Total",
				"year" => 0,
				"quantity" => 0,
				"product_name" => 0,
				"next_x" => $_mid,
				"_x" => $sum_x,
				"_y" => $sum_y,
				"_xx" => $sum_xx,
				"_xy" => $sum_xy,

			);
		return $data;
	}

	public function even_prediction( $data )
	{
    $_n = count( $data );
		$_mid = $_n / 2;

		$sum_x 		= 0;
		$sum_y 		= 0;
		$sum_xx 	= 0;
		$sum_xy 	= 0;

		for( $i =0 , $_x = ( $_mid * 2 - 1 ) * -1; $_x <= ( $_mid * 2 - 1 ) , $i < $_n ; $_x+= 2 , $i++ )
		{
			$sum_x += $_x;
			$sum_y += $data[$i]->_y;
			$sum_xx += ( $_x*$_x );
			$sum_xy += ( $_x*$data[$i]->_y );

			$data[$i]->_x = $_x;
			$data[$i]->_xx = ( $_x*$_x );
			$data[$i]->_xy = ( $_x*$data[$i]->_y );
		}

		$data []= ( object ) 
			array(
				"id" => 0,
				"product_id" => 0,
				"month" => "Total",
				"year" => 0,
				"quantity" => 0,
				"product_name" => 0,
				"next_x" => $_mid*2 +1,
				"_x" => $sum_x,
				"_y" => $sum_y,
				"_xx" => $sum_xx,
				"_xy" => $sum_xy,

			);
		return $data;
	}
}
?>
