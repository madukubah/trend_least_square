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
		$form_data_1 = $this->services->get_form_data_predict( "sale" )[0];
		$form_data_1 = $this->load->view('templates/form/bsb_form', $form_data_1 , TRUE ) ;

		$form_data_2 = $this->services->get_form_data_predict( "sale" )[1];
		$form_data_2 = $this->load->view('templates/form/bsb_form_6', $form_data_2 , TRUE ) ;

		$form_data_3 = $this->services->get_form_data_predict( "sale" )[2];
		$form_data_3 = $this->load->view('templates/form/bsb_form_6', $form_data_3 , TRUE ) ;
			
		$this->data[ "contents" ] = $form_data_1.$form_data_2;//.$form_data_3;

		// return;

		$this->data[ "months" ] = Util::MONTH;//.$form_data_3;

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

			$end_month_2 = $this->input->get( 'end_month_2' );
			$end_year_2 = $this->input->get( 'end_year_2' );
			// echo $end_month_2;

			$context = $this->input->get( 'context' );

			$product = $this->m_product->product( $product_id )->row();

			$timestamp_1 = strtotime( $end_year."-".$end_month."-20" );
			$timestamp_2 = strtotime( $end_year_2."-".$end_month_2."-20" );

			$start_date = $start_year."-".$start_month."-1";
			$end_date 	= $end_year."-".$end_month."-20";

			if( $timestamp_1 >= $timestamp_2 )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Data Kosong / Inputan Tidak Valid !" ) );
				redirect( site_url($this->current_page.$context )  );
			}
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
			$this->data[ "data_real" ] = $this->services->get_data_chart( $prediction )["data_real"];
			$this->data[ "data_x" ] = $this->services->get_data_chart( $prediction )["data_x"];
			// TODO : tambahkan array untuk treshold
			$this->data[ "data_x" ] []= Util::MONTH[ $next_month_prediction->month ] . " " . $next_month_prediction->year ;
			//  return ;
			$result = $prediction[ count( $prediction  ) -1 ];
			$_n = count( $prediction  ) -1  ;
			$x = $result->next_x;
			$a = $result->_y / $_n;
			$b = $result->_xy / $result->_xx ;
			$_y_accent = $a +( $b * $result->next_x ) ;

			// var_dump( $this->data[ "next_month_prediction" ] );die;

			$prediction_month = $this->data[ "next_month_prediction" ]->month;
			$prediction_year = $this->data[ "next_month_prediction" ]->year;

			
			// $this->data[ "data_prediction" ] = $this->services->get_prediction_chart( $a, $b, $prediction );
			$this->data[ "data_prediction" ] = $this->data[ "data_real" ];

			// TODO : tambahkan array untuk treshold
			$this->data[ "data_prediction" ] []=  $_y_accent;

			$data_table_predition = array(
				(object) array(
					"pos" => Util::MONTH[ $prediction_month ] . " " . $prediction_year ,
					"_x" => $x,
					"_a" => $a,
					"_b" => $b,
					"_y_accent" => $_y_accent ,
				)
			);

			$even_odd = array(
				2, 1
			);

			$curr_x = $x;
			while( !( $prediction_month == $end_month_2 && $prediction_year == $end_year_2 ) )
			{
				if( $prediction_month == 12 )
				{
					$prediction_month = 1;
					$prediction_year += 1;
				}else
					$prediction_month ++;

				$curr_x += $even_odd[ $_n % 2 ];
				// $this->data[ "data_x" ] []= Util::MONTH[ $prediction_month ] . " " . $prediction_year ;
				$data_table_predition []= (object) array(
					"pos" => Util::MONTH[ $prediction_month ] . " " . $prediction_year ,
					"_x" => $curr_x,
					"_a" => $a,
					"_b" => $b,
					"_y_accent" => $a +( $b * $curr_x ) ,
				);
				// $this->data[ "data_prediction" ] []=  $a +( $b * $curr_x);
			}

			$this->data[ "data_x" ] []= Util::MONTH[ $prediction_month ] . " " . $prediction_year ;
			$this->data[ "data_prediction" ] []= $data_table_predition[ count( $data_table_predition ) ]->_y_accent ;// $a +( $b * $curr_x);

			$table_prediction = $this->services->table_prediction_config("");
			$table_prediction[ "rows" ] = $data_table_predition;
			$table_prediction = $this->load->view('user/prediction/plain_table_12', $table_prediction  , true);
			$this->data[ "table_prediction" ] = $table_prediction;
			// var_dump( $data_table_predition );die;


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
			$this->data["block_header"] = "";
			$this->data["header"] = $header;
			$this->data["sub_header"] = "";

			$this->render( "user/prediction/trend_projection" );
	}

}