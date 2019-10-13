<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends User_Controller {

	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/inventory/';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Inventory_services');
		$this->services = new Inventory_services;
		$this->load->model(
			array(
				'm_inventory',
				"m_product",
			)
		);
	}

	public function index()
	{
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url( $this->current_page ) .'/index';
        $pagination['total_records'] = $this->m_inventory->record_count() ;
        $pagination['limit_per_page'] = 12;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination

		#################################################################3
		$table = $this->services->table_config( $this->current_page, $pagination['start_record'] +1 );

		if( ($_POST) )
		{
			$product_id = $this->input->post( 'product_id' );
			$product 	= $this->m_product->product( $product_id )->row();
			$year		= $this->input->post( 'year' );
			$table[ "rows" ] = $this->m_inventory->filters( $product_id , $year )->result();
			$sub_header = 'Data Inventori '. $product->name." Tahun ".$year;
		}
		else
		{
			if ($pagination['total_records']>0) $this->data['pagination_links'] = $this->setPagination($pagination);
			$table[ "rows" ] = $this->m_inventory->inventories( $pagination['start_record'], $pagination['limit_per_page'] )->result();
			$sub_header = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		}

		// $table[ "rows" ] = $this->m_inventory->inventories()->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data[ "contents" ] = $table;
		$add_menu = array(
			"name" => "Tambah Inventori",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page."add/"),
			"form_data" => $this->services->get_form_data(  )[ "form_data" ]  ,
			'data' => NULL
		);
		$import = array(
			"name" => "Import Inventori",
			"modal_id" => "import_",
			"button_color" => "success",
			"url" => site_url( $this->current_page."import/"),
			"form_data" => array(
				"document_file" => array(
					'type' => 'file',
                    'label' => "File Data Inventori",
				),
			),
			'data' => NULL
		);
		$filter = array(
			"name" => "Filter",
			"modal_id" => "filter_",
			"button_color" => "warning",
			"url" => site_url( $this->current_page ),
			"form_data" => $this->services->get_form_filter(  )[ "form_data" ]  ,
			'data' => NULL
		);

		$empty = array(
			"name" => 'Kosongkan',
			"type" => "modal_delete",
			"modal_id" => "delete_",
			"url" => site_url( $this->current_page."mpty/"),
			"button_color" => "danger",
			"param" => "id",
			"form_data" => array(
			),
			"title" => "Penjualan",
			"data_name" => "product_name",
		);

		$filter= $this->load->view('templates/actions/modal_form', $filter, true ); 
		$add_menu= $this->load->view('templates/actions/modal_form', $add_menu, true ); 
		$import= $this->load->view('templates/actions/modal_form_multipart', $import, true ); 
		$empty= $this->load->view('templates/actions/modal_form', $empty, true ); 

		$this->data[ "header_button" ] =  $filter." ".$add_menu." ".$import;//." ".$empty;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Inventori";
		$this->data["header"] = "Inventori";
		$this->data["sub_header"] = $sub_header ;
		$this->render( "templates/contents/plain_content" );
	}

	public function add(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));
		// echo var_dump( $data );return;
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			
			$data['product_id'] = $this->input->post( 'product_id' );
			$data['month'] = $this->input->post( 'month' );
			$data['year'] = $this->input->post( 'year' );
			$data['quantity'] = $this->input->post( 'quantity' );
			$data["date"] = $data["year"]."-".$data["month"]."-5";
			// echo var_dump( $data );
			// return ;
			if( $this->m_inventory->create( $data ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_inventory->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_inventory->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_inventory->errors() ? $this->m_inventory->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->m_inventory->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		redirect( site_url($this->current_page)  );
	}

	public function import(  )
	{
		$this->load->library('upload'); // Load librari upload
		$filename = "excel";
		$upload_path = 'uploads/excel/';
		$config['upload_path'] = './'.$upload_path;
		$config['allowed_types'] = "xls|xlsx";
		$config['overwrite']="true";
		$config['max_size']="2048";
		$config['file_name'] = ''.$filename;
		$this->upload->initialize($config);
		// echo var_dump( $_FILES ); return;
		if( $this->upload->do_upload("document_file") )
    	{
			$filename = $this->upload->data()["file_name"];
			// echo var_dump( $this->upload->data() );
			// return;
			// Load plugin PHPExcel nya
			include APPPATH.'third_party/PHPExcel.php';
			
			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load( $upload_path . $filename); // Load file yang telah diupload ke folder excel
			$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			$numrow = 1;
			$entries = array();
			foreach($sheet as $row){
				// Cek $numrow apakah lebih dari 1
				// Artinya karena baris pertama adalah nama-nama kolom
				// Jadi dilewat saja, tidak usah diimport
				if($numrow > 1 &&  !empty( $row['A'] ) ){
					// $data_uji["data_name"] = $row['A'] ;
					$product_code = $row['A'];
					$inventory_entry["quantity"] = $row['C'];
					$inventory_entry["month"] = $row['D'];
					$inventory_entry["year"] = $row['E'];
					$inventory_entry["date"] = $inventory_entry["year"]."-".$inventory_entry["month"]."-5";
					##########################################################
					// echo var_dump(  $this->m_product->product_by_code( $product_code )->row()  ) ;
					$inventory_entry["product_id"]  = $this->m_product->product_by_code( $product_code )->row()->id;
					$entries[]= $inventory_entry;
				}
				
				$numrow++; // Tambah 1 setiap kali looping
			}

			if( $this->m_inventory->create_batch( $entries ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_inventory->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_inventory->errors() ) );
			}
		}
		else
		{
			echo $this->upload->display_errors();
		}

		redirect( site_url( $this->current_page )  );
		return;
	}

	public function edit(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		// echo var_dump( $data );return;
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			
			$data['product_id'] = $this->input->post( 'product_id' );
			$data['month'] = $this->input->post( 'month' );
			$data['year'] = $this->input->post( 'year' );
			$data['quantity'] = $this->input->post( 'quantity' );

			$data_param['id'] = $this->input->post( 'id' );

			if( $this->m_inventory->update( $data, $data_param  ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_inventory->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_inventory->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_inventory->errors() ? $this->m_inventory->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->m_inventory->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		redirect( site_url($this->current_page)  );
	}

	public function mpty(  )
	{
		// if( !($_POST) ) redirect( site_url($this->current_page) );
	  
		if( $this->m_inventory->truncate( ) )
		{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_inventory->messages() ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_inventory->errors() ) );
		}
		// return;
		redirect( site_url($this->current_page)  );
	}

	public function delete(  ) 
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
	  
		$data_param['id'] 	= $this->input->post('id');
		if( $this->m_inventory->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_inventory->messages() ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_inventory->errors() ) );
		}
		redirect( site_url($this->current_page)  );
	}
}