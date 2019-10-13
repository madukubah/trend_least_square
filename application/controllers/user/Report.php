<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends User_Controller {

	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/report/';
	
	public function __construct(){
		parent::__construct();
		$this->load->library('services/Prediction_services');
		$this->services = new Prediction_services;
		$this->load->model(array(
				'm_sale',
				'm_product',
				'm_inventory',
			)
		);
	}

	public function index()
	{
		#################################################################3
		$form_data_1 = $this->services->get_form_data( "sale" )[0];
		$form_data_1 = $this->load->view('templates/form/bsb_form', $form_data_1 , TRUE ) ;

		$form_data_2 = $this->services->get_form_data( "sale" )[1];
		$form_data_2 = $this->load->view('templates/form/bsb_form_6', $form_data_2 , TRUE ) ;
			
		$this->data[ "sale" ] = $form_data_1.$form_data_2;

		$form_data_1 = $this->services->get_form_data( "inventory" )[0];
		$form_data_1 = $this->load->view('templates/form/bsb_form', $form_data_1 , TRUE ) ;

		$form_data_2 = $this->services->get_form_data( "inventory" )[1];
		$form_data_2 = $this->load->view('templates/form/bsb_form_6', $form_data_2 , TRUE ) ;
			
		$this->data[ "inventory" ] = $form_data_1.$form_data_2;

		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan";
		$this->data["header"] = "Laporan";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		
		$this->render( "user/report/content" );
	}

	public function trend_projection(  )
	{
			$_type = [ "sale"=> "penjualan", "inventory"=> "Inventori" ];
			$_type__ = [ "sale"=> "sale", "inventory"=> "inventory" ];

		// return;
			$product_id = $this->input->get( 'product_id' );
			$start_month = $this->input->get( 'start_month' );
			$end_month = $this->input->get( 'end_month' );
			$start_year = $this->input->get( 'start_year' );
			$end_year = $this->input->get( 'end_year' );
			

			$context = $this->input->get( 'context' );

			$product = $this->m_product->product( $product_id )->row();

			$start_date = $start_year."-".$start_month."-1";
			$end_date 	= $end_year."-".$end_month."-20";
			
			#################################################################3
			

			switch( $context )
			{
				case "sale":
					$this->load->library('services/Sale_services');
					$service_2 = new Sale_services;
					$header = 'Data Penjualan '. $product->name." Bulan ".Util::MONTH[ $start_month ]." ".$start_year." Sampai ".Util::MONTH[ $end_month ]." ".$end_year;
					$inference = 'Penjualan';
					$rows = $this->m_sale->get_sales_prediction( $product_id, $start_date, $end_date )->result();
					break;
				case "inventory":
					$this->load->library('services/Inventory_services');
					$service_2 = new Inventory_services;
					$inference = 'inventori';
					// $header = 'Trend Projection Inventori '. $product->name;
					$header = 'Data Inventori '. $product->name." Bulan ".Util::MONTH[ $start_month ]." ".$start_year." Sampai ".Util::MONTH[ $end_month ]." ".$end_year;
					$rows = $this->m_inventory->get_inventories_prediction( $product_id, $start_date, $end_date )->result();
					break;
				default :
					$rows = array();
					break;
			}

			$this->data['title'] = $header;
			$table = $service_2->table_config_no_action( $this->current_page );
			// $table = $this->services->table_config( $this->current_page );
			$table[ "rows" ] = $rows;
			$_n = count( $table[ "rows" ] );
			// echo var_dump( count( $table[ "rows" ] ) );
			// return;
			if( $_n == 0 )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Data Kosong / Inputan Tidak Valid !" ) );
				redirect( site_url($this->current_page )  );

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

			
			// echo json_encode( $prediction );
			// return;
			$last_month_prediction = $prediction[ count( $prediction ) -2 ] ;
			if( $last_month_prediction->month == 12 )
			{
				$next_month_prediction = (object) array(
					"month" => 1,
					"year" => $last_month_prediction->year + 1,
					"quantity" => 0,
					"product_name" => $last_month_prediction->product_name,
					"_x" => 0,
					"_y" => 0,
					"_xx" => 0,
					"_xy" => 0,
				);
			}
			else
			{
				$next_month_prediction = (object) array(
					"month" => $last_month_prediction->month + 1,
					"year" => $last_month_prediction->year,
					"quantity" => 0,
					"product_name" => $last_month_prediction->product_name,
					"_x" => 0,
					"_y" => 0,
					"_xx" => 0,
					"_xy" => 0,
				);
			}
			$this->data[ "next_month_prediction" ] = $next_month_prediction;
			// echo var_dump( $data_prediction->month );
			// return;
			$this->data[ "data_real" ] = array();//$this->get_data_chart( $prediction )["data_real"];
			$this->data[ "data_x" ] = array();//$this->get_data_chart( $prediction )["data_x"];
			//  return ;
			$result = $prediction[ count( $prediction  ) -1 ];
			$_n = count( $prediction  ) -1  ;
			$x = $result->next_x;
			$a = $result->_y / $_n;
			$b = $result->_xy / $result->_xx ;
			$_y_accent = $a +( $b * $result->next_x ) ;

			$next_month_prediction->quantity = $_y_accent;
			$next_month_prediction->_y = $_y_accent;

			$total = $prediction[ count( $prediction ) - 1 ];
			unset( $prediction[ count( $prediction ) - 1 ] ) ;

			// $prediction = array_merge( $prediction, [$next_month_prediction] );
			$table[ "rows" ] = $prediction;
			// echo json_encode( $prediction );return;

			$this->data[ "data_prediction" ] = array();//$this->get_prediction_chart( $a, $b, $prediction );
			$this->data[ "data_prediction" ] []=  $_y_accent;

			$this->data[ "result" ] = $prediction[ count( $prediction  ) -1 ];
			$this->data[ "_n" ] = count( $prediction  ) -1  ;
		
			

			$table2 = $service_2->table_config_no_action( $this->current_page );
			// $table2[ "rows" ] = [ $table[ "rows" ][ count( $table[ "rows" ] ) - 1 ] ];
			$table2[ "rows" ] = [$next_month_prediction];

			// unset( $table[ "rows" ][ count( $table[ "rows" ] ) - 1 ] ) ;
			// echo var_dump( $table2 );return;
			$table1 = $this->load->view('templates/tables/plain_table_12', $table  , true);
			$table2 = $this->load->view('templates/tables/plain_table_12', $table2  , true);

			if( $context == "inventory" ) $table2="";
			else $table2 = "<br><h5>Prediksi</h5>".$table2 ;

			$this->data[ "contents" ] = $table1.$table2;
			$this->data[ "contents" ].= '<br><br><a href="http://localhost/TLS/user/report/print_trend_projection?product_id=2&context='.$_type__[ $context ].'&start_month=1&start_year=2016&end_month=2&end_year=2019" class="btn btn-bold btn-primary btn-sm " style="margin-left: 5px;" >Cetak</a>';
			
			// return;
			#################################################################3
			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Data ".$_type[ $context ]." ".$product->name ;
			$this->data["header"] = "Data ".$_type[ $context ]." ".$product->name ;
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
			$this->render( "templates/contents/plain_content" );

			return;
			#################################################################3
			$this->data[ "rows" ] 	= $prediction;
			$this->data[ "header" ] = $service_2->table_config_no_action( $this->current_page )['header'];

			$this->load->library('pdf');
			$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

			$pdf->SetTitle( "TLS " );
			
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			
			$pdf->SetTopMargin(10);
			$pdf->SetLeftMargin(10);
			$pdf->SetRightMargin(10);
			$pdf->SetAutoPageBreak(true);
			$pdf->SetAuthor('TLS');
			$pdf->SetDisplayMode('real', 'default');
			$pdf->AddPage();
			$pdf->SetFont('times', NULL, 9);

			switch( $context )
			{
				case "sale":
					$html =  $this->load->view('templates/report/sale', $this->data, true);	
					break;
				case "inventory":
					$html =  $this->load->view('templates/report/inventory', $this->data, true);	
					break;
				default :
					$html = "";
					break;
			}
			
			// Position at 15 mm from bottom
			// $pdf->Image( site_url( WATERMARK ) , 10, 280, 30, 5 );
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->Output("TLS.pdf",'I');
	}

	public function print_trend_projection(  )
	{
			$_type = [ "sale"=> "penjualan", "inventory"=> "Inventori" ];

		// return;
			$product_id = $this->input->get( 'product_id' );
			$start_month = $this->input->get( 'start_month' );
			$end_month = $this->input->get( 'end_month' );
			$start_year = $this->input->get( 'start_year' );
			$end_year = $this->input->get( 'end_year' );
			

			$context = $this->input->get( 'context' );

			$product = $this->m_product->product( $product_id )->row();

			$start_date = $start_year."-".$start_month."-1";
			$end_date 	= $end_year."-".$end_month."-20";
			
			#################################################################3
			

			switch( $context )
			{
				case "sale":
					$this->load->library('services/Sale_services');
					$service_2 = new Sale_services;
					$header = 'Data Penjualan '. $product->name." Bulan ".Util::MONTH[ $start_month ]." ".$start_year." Sampai ".Util::MONTH[ $end_month ]." ".$end_year;
					$inference = 'Penjualan';
					$rows = $this->m_sale->get_sales_prediction( $product_id, $start_date, $end_date )->result();
					break;
				case "inventory":
					$this->load->library('services/Inventory_services');
					$service_2 = new Inventory_services;
					$inference = 'inventori';
					// $header = 'Trend Projection Inventori '. $product->name;
					$header = 'Data Inventori '. $product->name." Bulan ".Util::MONTH[ $start_month ]." ".$start_year." Sampai ".Util::MONTH[ $end_month ]." ".$end_year;
					$rows = $this->m_inventory->get_inventories_prediction( $product_id, $start_date, $end_date )->result();
					break;
				default :
					$rows = array();
					break;
			}

			$this->data['title'] = $header;
			$table = $service_2->table_config_no_action( $this->current_page );
			// $table = $this->services->table_config( $this->current_page );
			$table[ "rows" ] = $rows;
			$_n = count( $table[ "rows" ] );
			// echo var_dump( count( $table[ "rows" ] ) );
			// return;
			if( $_n == 0 )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Data Kosong / Inputan Tidak Valid !" ) );
				redirect( site_url($this->current_page )  );

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

			
			// echo json_encode( $prediction );
			// return;
			$last_month_prediction = $prediction[ count( $prediction ) -2 ] ;
			if( $last_month_prediction->month == 12 )
			{
				$next_month_prediction = (object) array(
					"month" => 1,
					"year" => $last_month_prediction->year + 1,
					"quantity" => 0,
					"product_name" => $last_month_prediction->product_name,
					"_x" => 0,
					"_y" => 0,
					"_xx" => 0,
					"_xy" => 0,
				);
			}
			else
			{
				$next_month_prediction = (object) array(
					"month" => $last_month_prediction->month + 1,
					"year" => $last_month_prediction->year,
					"quantity" => 0,
					"product_name" => $last_month_prediction->product_name,
					"_x" => 0,
					"_y" => 0,
					"_xx" => 0,
					"_xy" => 0,
				);
			}
			$this->data[ "next_month_prediction" ] = $next_month_prediction;
			// echo var_dump( $data_prediction->month );
			// return;
			$this->data[ "data_real" ] = array();//$this->get_data_chart( $prediction )["data_real"];
			$this->data[ "data_x" ] = array();//$this->get_data_chart( $prediction )["data_x"];
			//  return ;
			$result = $prediction[ count( $prediction  ) -1 ];
			$_n = count( $prediction  ) -1  ;
			$x = $result->next_x;
			$a = $result->_y / $_n;
			$b = $result->_xy / $result->_xx ;
			$_y_accent = $a +( $b * $result->next_x ) ;

			$next_month_prediction->quantity = $_y_accent;
			$next_month_prediction->_y = $_y_accent;

			$total = $prediction[ count( $prediction ) - 1 ];
			unset( $prediction[ count( $prediction ) - 1 ] ) ;

			// $prediction = array_merge( $prediction, [$next_month_prediction] );
			$table[ "rows" ] = $prediction;
			// echo json_encode( $prediction );return;

			$this->data[ "data_prediction" ] = array();//$this->get_prediction_chart( $a, $b, $prediction );
			$this->data[ "data_prediction" ] []=  $_y_accent;

			$this->data[ "result" ] = $prediction[ count( $prediction  ) -1 ];
			$this->data[ "_n" ] = count( $prediction  ) -1  ;
		
			

			$table2 = $service_2->table_config_no_action( $this->current_page );
			// $table2[ "rows" ] = [ $table[ "rows" ][ count( $table[ "rows" ] ) - 1 ] ];
			$table2[ "rows" ] = [$next_month_prediction];

			// unset( $table[ "rows" ][ count( $table[ "rows" ] ) - 1 ] ) ;
			// echo var_dump( $table2 );return;
			$table1 = $this->load->view('templates/tables/plain_table_12', $table  , true);
			$table2 = $this->load->view('templates/tables/plain_table_12', $table2  , true);
			$this->data[ "contents" ] = $table1."<br><h5>Prediksi</h5>".$table2 ;
			$this->data[ "contents" ].= '<br><br><a href="http://localhost/TLS/user/report/print_trend_projection?product_id=2&context='.$_type[ $context ].'&start_month=1&start_year=2016&end_month=2&end_year=2019" class="btn btn-bold btn-primary btn-sm " style="margin-left: 5px;" >Cetak</a>';
			
			$this->data[ "rows" ] 	= $prediction;
			$this->data[ "next_month_prediction" ] = [ $next_month_prediction ];			
			$this->data[ "header" ] = $service_2->table_config_no_action( $this->current_page )['header'];

			$this->load->library('pdf');
			$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

			$pdf->SetTitle( "TLS " );
			
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			
			$pdf->SetTopMargin(10);
			$pdf->SetLeftMargin(10);
			$pdf->SetRightMargin(10);
			$pdf->SetAutoPageBreak(true);
			$pdf->SetAuthor('TLS');
			$pdf->SetDisplayMode('real', 'default');
			$pdf->AddPage();
			$pdf->SetFont('times', NULL, 9);

			switch( $context )
			{
				case "sale":
					$html =  $this->load->view('templates/report/sale', $this->data, true);	
					break;
				case "inventory":
					$html =  $this->load->view('templates/report/inventory', $this->data, true);	
					break;
				default :
					$html = "";
					break;
			}
			
			// Position at 15 mm from bottom
			// $pdf->Image( site_url( WATERMARK ) , 10, 280, 30, 5 );
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->Output("TLS.pdf",'I');
	}

}