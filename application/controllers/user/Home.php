<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/Util.php";

class Home extends User_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/';

	public function __construct()
	{
		parent::__construct();
		$this->load->model(
			array(
				'm_inventory',
				"m_sale",
			)
		);

	}

	public function index()
	{
		$inventories 	= $this->m_inventory->last_n_months( 5 )->result();
		$start_inventory= $inventories[ count( $inventories ) - 1 ] ;
		$start_date 	= $start_inventory->year."-".$start_inventory->month."-1" ;


		$sales		 	= $this->m_sale->n_months( $start_date )->result();
		$inventories = array_reverse( $inventories );

		$arr_x 			= $this->generate_x( $start_inventory );
		$sales 			= $this->extract_y( $sales , 'quantity' );
		$inventories 	= $this->extract_y( $inventories , 'quantity' );

		$this->data["arr_x"] = $arr_x;
		$this->data["sales"] = $sales;
		$this->data["inventories"] = $inventories;

		$this->data["sale_sum"] = $this->m_sale->quantity_sum()->row()->sum;
		$this->data["inventory_sum"] = $this->m_inventory->quantity_sum()->row()->sum;

		// echo var_dump( $sales );
		// return;
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Beranda";
		$this->data["header"] = "Beranda";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		
		$this->render( "user/dashboard/content" );
	}

	protected function extract_y( $datas , $feature )
	{
		$arr_y = array();
		foreach( $datas as $item )
		{
			$arr_y []= $item->$feature;
		}
		return $arr_y;

	}
	protected function generate_x( $start_inventory )
	{
		$year 	= $start_inventory->year;
		$month 	= $start_inventory->month;

		$arr_x = array();
		for( $i=0; $i<5; $i++ )
		{
			$arr_x []= Util::MONTH[ $month ]." ".$year;

			if( $month + 1 > 12 )
			{
				$year++;
				$month = ( $month + 1 ) % 12;
			}
			else
			{
				$month++;
			}
		}

		return $arr_x;
	}

}