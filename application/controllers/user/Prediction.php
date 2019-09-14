<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prediction extends User_Controller {

	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/prediction/';
	
	public function __construct(){
		parent::__construct();
		$this->load->library('services/Prediction_services');
		$this->services = new Prediction_services;
		$this->load->model(
			array(
				'm_sale',
				'm_inventory',
				'm_product',
			)
		);

	}

	public function sale()
	{
		#################################################################3
		$form_data_1 = $this->services->get_form_data( "sale" )[0];
		$form_data_1 = $this->load->view('templates/form/bsb_form', $form_data_1 , TRUE ) ;

		$form_data_2 = $this->services->get_form_data( "sale" )[1];
		$form_data_2 = $this->load->view('templates/form/bsb_form_6', $form_data_2 , TRUE ) ;
			
		$this->data[ "contents" ] = $form_data_1.$form_data_2;

		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Prediksi Penjualan";
		$this->data["header"] = "Prediksi Penjualan";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		
		$this->render( "user/prediction/content" );
	}

	public function inventory()
	{
		#################################################################3
		$form_data_1 = $this->services->get_form_data( "inventory" )[0];
		$form_data_1 = $this->load->view('templates/form/bsb_form', $form_data_1 , TRUE ) ;

		$form_data_2 = $this->services->get_form_data( "inventory" )[1];
		$form_data_2 = $this->load->view('templates/form/bsb_form_6', $form_data_2 , TRUE ) ;
			
		$this->data[ "contents" ] = $form_data_1.$form_data_2;

		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Prediksi Inventori";
		$this->data["header"] = "Prediksi Inventori";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		
		$this->render( "user/prediction/content" );
	}

	public function trend_projection(  )
	{
			$product_id = $this->input->get( 'product_id' );
			$start_month = $this->input->get( 'start_month' );
			$end_month = $this->input->get( 'end_month' );
			$start_year = $this->input->get( 'start_year' );
			$end_year = $this->input->get( 'end_year' );

			$context = $this->input->get( 'context' );

			$product = $this->m_product->product( $product_id )->row();

			$start_date = $start_year."-".$start_month."-1";
			$end_date 	= $end_year."-".$end_month."-20";
			// echo var_dump( $data );
			#################################################################3
			$table = $this->services->table_config( $this->current_page );

			switch( $context )
			{
				case "sale":
					$header = 'Trend Projection Penjualan '. $product->name;
					$inference = 'Penjualan';
					$table[ "rows" ] = $this->m_sale->get_sales_prediction( $product_id, $start_date, $end_date )->result();
					break;
				case "inventory":
					$inference = 'inventori';
					$header = 'Trend Projection Inventori '. $product->name;
					$table[ "rows" ] = $this->m_inventory->get_inventories_prediction( $product_id, $start_date, $end_date )->result();
					break;
				default :
					$table[ "rows" ] = array();
					break;
			}

			$_n = count( $table[ "rows" ] );
			if( $_n == 0 )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Data Kosong / Inputan Tidak Valid !" ) );
				redirect( site_url($this->current_page.$context )  );

			}
			if( $_n % 2 != 0  )
			{
				// $prediction = $this->odd_prediction( $table[ "rows" ] , $_n );
				$prediction = $this->services->odd_prediction( $table[ "rows" ]);
			}
			else
			{
				$prediction = $this->services->even_prediction( $table[ "rows" ] );
				// $prediction = $this->even_prediction( $table[ "rows" ] , $_n );

			}
			$table[ "rows" ] = $prediction;
			$last_month_prediction = $prediction[ count( $prediction ) -2 ] ;
			if( $last_month_prediction->month == 12 )
			{
				$next_month_prediction = (object) array(
					"month" => 1,
					"year" => $last_month_prediction->year + 1,
				);
			}
			else
			{
				$next_month_prediction = (object) array(
					"month" => $last_month_prediction->month + 1,
					"year" => $last_month_prediction->year,
				);
			}
			$this->data[ "next_month_prediction" ] = $next_month_prediction;
			// echo var_dump( $data_prediction->month );
			// return;
			$this->data[ "data_real" ] = $this->get_data_chart( $prediction )["data_real"];
			$this->data[ "data_x" ] = $this->get_data_chart( $prediction )["data_x"];
			//  return ;
			$result = $prediction[ count( $prediction  ) -1 ];
			$_n = count( $prediction  ) -1  ;
			$x = $result->next_x;
			$a = $result->_y / $_n;
			$b = $result->_xy / $result->_xx ;
			$_y_accent = $a +( $b * $result->next_x ) ;

			$this->data[ "data_prediction" ] = $this->get_prediction_chart( $a, $b, $prediction );
			$this->data[ "data_prediction" ] []=  $_y_accent;

			$this->data[ "result" ] = $prediction[ count( $prediction  ) -1 ];
			$this->data[ "_n" ] = count( $prediction  ) -1  ;

		
			$table = $this->load->view('templates/tables/plain_table_12', $table  , true);
			$this->data[ "contents" ] = $table;
			#################################################################3
			

			$alert = $this->session->flashdata('alert');
			$this->data["inference"] = $inference;
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = $header;
			$this->data["header"] = $header;
			$this->data["sub_header"] = $header;

			$this->render( "user/prediction/trend_projection" );
	}

	protected function get_prediction_chart( $a, $b, $data )
	{
		$data_prediction = array();

		$i = 0;
		foreach(  $data as $item )
		{
			if( $i == count( $data ) - 1 ) break;

			$data_prediction[]=  $a +( $b * $item->_x );
			$i++;
		}
		
		return $data_prediction;
	}
	protected function get_data_chart( $data )
	{
		$data_real = array();
		$data_x = array();

		$i = 0;
		foreach(  $data as $item )
		{
			if( $i == count( $data ) - 1 ) break;

			$data_real[]= (double) $item->_y;
			$data_x[]= "".$item->_x;
			$i++;
		}
		if( count( $data_x ) % 2 == 0 )
		{
			$data_x[]= "".( $data_x[ count( $data_x ) -1 ] + 2 ); //even 
		}
		else
		{
			$data_x[]= "".( $data_x[ count( $data_x ) -1 ] + 1 ); //odd 
		}
		return array(
			"data_real" => $data_real,
			"data_x" 	=> $data_x,
		);
	}

}