<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Menus extends Admin_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'admin';
	private $current_page = 'admin/menus/';
	
	public function __construct(){
		parent::__construct();
		$this->load->library('services/Menu_services');
		$this->services = new Menu_services;
		$this->load->model(array(
			'm_menu',
			'm_group',
		));

	}
	public function index()
	{
		// echo $this->router->fetch_class();
		// echo $this->router->fetch_method();
		// return;
		// echo var_dump( $this->m_group->groups()->result() ) ;return;
		$table = $this->services->groups_table_config( $this->current_page );
		$table[ "rows" ] = $this->m_group->groups()->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data[ "contents" ] = $table;
		$this->data[ "header_button" ] = '';
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Menu";
		$this->data["header"] = "Menu";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render( "admin/menus/content" );
	}

	public function group( $group_id )
	{
		// echo var_dump( $this->m_menu->tree( $group_id ) ) ;return;
		$group = $this->m_group->group( $group_id )->row();
		$this->data[ "menus_tree" ] = $this->m_menu->tree( $group_id );
		$this->data[ "menu_list" ] = $this->m_menu->get_menu_list(  );
		$this->data[ "group" ] = $group;
		$this->data[ "contents" ] = '' ;
		##################################################################################################################################
		$add_menu = array(
			"name" => "Tambah Menu",
			"modal_id" => "add_menu_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page."add/"),
			"form_data" => array(
			  "name" => array(
				'type' => 'text',
				'label' => "Nama Menu",
			  ),
			  "link" => array(
				'type' => 'text',
				'label' => "Link",
				'value' => $group->name."/",
			  ),
			  "list_id" => array(
				'type' => 'text',
				'label' => "List ID",
				'value' => "-",
			  ),
			  "icon" => array(
				'type' => 'text',
				'label' => "Icon",
				'value' => 'home',
			  ),
			  "position" => array(
				'type' => 'number',
				'label' => "Urutan Ke",
				'value' => 1,
			  ),
			  "status" => array(
				'type' => 'select',
				'label' => "Status",
				'options' => array(
					1 => 'Aktif',
					0 => 'Non Aktif',
				),
			  ),
			  "description" => array(
				'type' => 'textarea',
				'label' => "Deskripsi",
				'value' => "-",				
			  ),
			  "menu_id" => array(
				'type' => 'hidden',
				'label' => "menu_id",
				'value' => $group->id,
			  ),
			  "group_id" => array(
				'type' => 'hidden',
				'label' => "group_id",
				'value' => $group->id,
			  ),
			),
			'data' => NULL
		);

		$add_menu= $this->load->view('templates/actions/modal_form', $add_menu, true ); 

		$this->data[ "header_button" ] =  $add_menu ;
		// return;
		##################################################################################################################################
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Menu Untuk Group ".$group->name;
		$this->data["header"] = "Menu Untuk Group ".$group->name;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render( "admin/menus/content_menus" );
	}

	public function add(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		$group_id = $this->input->post( 'group_id' );
		// echo var_dump( $data );return;
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data['name'] = $this->input->post( 'name' );
			$data['link'] = $this->input->post( 'link' );
			$data['list_id'] = $this->input->post( 'list_id' );
			$data['icon'] = $this->input->post( 'icon' );
			$data['position'] = $this->input->post( 'position' );
			$data['status'] = $this->input->post( 'status' );
			$data['description'] = $this->input->post( 'description' );
			$data['menu_id'] = $this->input->post( 'menu_id' );

			if( $this->m_menu->create( $data ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_menu->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_menu->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_menu->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->m_menu->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		
		redirect( site_url($this->current_page.'group/'.$group_id)  );
	}

	public function edit(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		$group_id = $this->input->post( 'group_id' );
		// echo var_dump( $data );return;
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data['name'] = $this->input->post( 'name' );
			$data['link'] = $this->input->post( 'link' );
			$data['list_id'] = $this->input->post( 'list_id' );
			$data['icon'] = $this->input->post( 'icon' );
			$data['position'] = $this->input->post( 'position' );
			$data['status'] = $this->input->post( 'status' );
			$data['description'] = $this->input->post( 'description' );

			$data_param['id'] = $this->input->post( 'id' );

			if( $this->m_menu->update( $data, $data_param  ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_menu->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_menu->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_menu->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->m_menu->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		
		redirect( site_url($this->current_page.'group/'.$group_id)  );
	}

	public function delete(  ) {
		if( !($_POST) ) redirect( site_url($this->current_page) );
	  
		$data_param['id'] 	= $this->input->post('id');
		$group_id = $this->input->post( 'group_id' );
		if( $this->m_menu->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_menu->messages() ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_menu->errors() ) );
		}
		redirect( site_url($this->current_page.'group/'.$group_id)  );
	  }
}
