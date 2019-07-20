<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Public_Controller {

	function __construct()
	{
			parent::__construct();
			$this->load->model('m_category');
			$this->load->library('graph');
	}
	// public function index()
	// {
	// 	// echo var_dump( $data = $this->m_graph->graphs()->result() )."<br>";

	// 	$domain1 = array(99, 99, 96);
	// 	$domain2 = array(97, 96, 70);
	// 	$graf = $this->graph->create_graph( $domain1, $domain2 );
	// 	// echo json_encode( $graf )."<br>";
	// 	echo json_encode( $this->graph->tree( 99 ) )."<br>";
	// 	foreach( $graf as $graph )
	// 	{
	// 		echo json_encode( $graph )."<br>";
	// 	}
	// 					// echo var_dump( $data = $this->graph->graphs()->result() )."<br>";
						
	// 					// echo json_encode( $graph=$this->graph->parse_graph( $data ) )."<br>";
	// 					// echo json_encode( $this->graph->get_domain() )."<br>";
	// 					// echo json_encode( $this->graph->get_domain("") )."<br>";

	// 					// foreach( $graph as $graf )
	// 					// {
	// 					// 	echo json_encode( $graf )."<br>";
	// 					// }
	// 					// $this->graph->update( $graph );
	// }
	public function index()
	{
		// echo var_dump( $this->jasain_service->portofolios( 2, 15  ) );
		// echo var_dump( $this->jasain_service->portofolio( 9 )->result() );
		echo var_dump( $this->m_category->search( "keber" )->result() );
		//  $this->jasain_service->upload_photo( "", 'table_service' );
	}
	public function delete_category()//ok
	{
		$data_param['id_category'] = 4; 
		if( $this->m_category->delete( $data_param ) )
		{
			echo $this->m_category->messages();
		}
		else
		{
			echo $this->m_category->errors();
		}
	}
	public function delete_portofolio()//ok
	{
		$data_param['id_service'] = 4; //ok for single data
		if( $this->jasain_service->delete_portofolio( $data_param ) )
		{
			echo $this->jasain_service->messages();
		}
		else
		{
			echo $this->jasain_service->errors();
		}
	}

	public function delete_service()//ok
	{
		$data_param['id_service'] = 4; //ok for single data
		// OR 
		// $data_param['id_portofolio'] = 2; //ok for multiple  data
		if( $this->jasain_service->delete_service( $data_param ) )
		{
			echo $this->jasain_service->messages();
		}
		else
		{
			echo $this->jasain_service->errors();
		}
	}

	public function create_service()//ok
	{
		$id_user = $this->user_auth->user()->row();
		if( $id_user == NULL ) redirect('test', 'refresh'); 

		$this->load->helper('form');
		$this->lang->load('jasain_service_lang');
		$this->load->library( array( 'form_validation' ) ); 

		$this->form_validation->set_rules('service_name',  $this->lang->line('service_name'), 'trim|required');
		$this->form_validation->set_rules('service_description',  $this->lang->line('service_description'), 'trim|required');
		$this->form_validation->set_rules('id_category',  $this->lang->line('category_name'), 'trim|required');
		if ( $this->form_validation->run() === TRUE )
		{
			$data['service_name'] = $this->input->post('service_name');
			$data['service_description'] = $this->input->post('service_description');
			$data['id_category'] = $this->input->post('id_category');


			$data['id_user'] = $id_user->id_user;

			if($this->jasain_service->create_service( $data ))
			{
				echo $this->jasain_service->messages();
			}
			else
			{
				echo $this->jasain_service->errors();
			}
			
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->jasain_service->errors() ? $this->jasain_service->errors() : $this->session->flashdata('message')));

			$this->data['service_name'] = array(
				'name' => 'service_name',
				'id' => 'service_name',
				'type' => 'text',
				'placeholder' => $this->lang->line('service_name'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('service_name', 'service_name'),
			);
			$this->data['service_description'] = array(
				'name' => 'service_description',
				'id' => 'service_description',
				'type' => 'text',
				'placeholder' => $this->lang->line('service_description'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('service_description', 'service_description'),
			);
			$this->data['service_images'] = array(
				'name' => 'service_images',
				'id' => 'service_images',
				'type' => 'file',
				'class' => 'form-control',
			);
			$this->data['id_category'] = array(
				'name' => 'id_category',
				'id' => 'id_category',
				'type' => 'text',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('id_category', 1),
			);
			$this->render( "V_test_page" );
		}
	}
	public function edit_service( $id_service = NULL )//ok
	{
		$id_user = $this->user_auth->user()->row();
		if( $id_service == NULL ) redirect('test', 'refresh'); 

		$this->load->helper('form');
		$this->lang->load('jasain_service_lang');
		$this->load->library( array( 'form_validation' ) ); 

		$this->form_validation->set_rules('service_name',  $this->lang->line('service_name'), 'trim|required');
		$this->form_validation->set_rules('service_description',  $this->lang->line('service_description'), 'trim|required');
		$this->form_validation->set_rules('id_category',  $this->lang->line('category_name'), 'trim|required');
		if ( $this->form_validation->run() === TRUE )
		{
			$data['service_name'] = $this->input->post('service_name');
			$data['service_description'] = $this->input->post('service_description');
			$data['id_category'] = $this->input->post('id_category');
			$data['id_service'] = $this->input->post('id_service');

			// $data['id_user'] = $id_user->id_user;
			$data_param['id_service'] = $data['id_service'];

			// echo var_dump( $data );

			if($this->jasain_service->update_service( $data, $data_param ))
			{
				echo $this->jasain_service->messages();
			}
			else
			{
				echo $this->jasain_service->errors();
			}
			
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->jasain_service->errors() ? $this->jasain_service->errors() : $this->session->flashdata('message')));

			$service = $this->jasain_service->service( $id_service )->row();
			if( $service == NULL )redirect('test', 'refresh'); 

			$this->data['id_service'] = array(
				'name' => 'id_service',
				'id' => 'id_service',
				'type' => 'hidden',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('id_service', $service->id_service),
			);
			$this->data['service_name'] = array(
				'name' => 'service_name',
				'id' => 'service_name',
				'type' => 'text',
				'placeholder' => $this->lang->line('service_name'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('service_name', $service->service_name),
			);
			$this->data['service_description'] = array(
				'name' => 'service_description',
				'id' => 'service_description',
				'type' => 'text',
				'placeholder' => $this->lang->line('service_description'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('service_description', $service->service_description),
			);
			$this->data['service_images'] = array(
				'name' => 'service_images',
				'id' => 'service_images',
				'type' => 'file',
				'class' => 'form-control',
			);
			$this->data['id_category'] = array(
				'name' => 'id_category',
				'id' => 'id_category',
				'type' => 'text',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('id_category', $service->id_category ),
			);
			$this->render( "V_test_page" );
		}
	}

	public function create_portofolio(  )//ok
	{
		$id_user = $this->user_auth->user()->row();
		if( $id_user == NULL ) redirect('test', 'refresh'); 

		$this->load->helper('form');
		$this->lang->load('jasain_service_lang');
		$this->load->library( array( 'form_validation' ) ); 

		$this->form_validation->set_rules('portofolio_name',  $this->lang->line('portofolio_name'), 'trim|required');
		$this->form_validation->set_rules('portofolio_description',  $this->lang->line('portofolio_description'), 'trim|required');
		$this->form_validation->set_rules('id_service',  $this->lang->line('service_name'), 'trim|required');
		if ( $this->form_validation->run() === TRUE )
		{
			$data['portofolio_name'] = $this->input->post('portofolio_name');
			$data['portofolio_description'] = $this->input->post('portofolio_description');
			$data['id_service'] = $this->input->post('id_service');

			if($this->jasain_service->create_portofolio( $data ))
			{
				echo $this->jasain_service->messages();
			}
			else
			{
				echo $this->jasain_service->errors();
			}
			
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->jasain_service->errors() ? $this->jasain_service->errors() : $this->session->flashdata('message')));

			$this->data['portofolio_name'] = array(
				'name' => 'portofolio_name',
				'id' => 'portofolio_name',
				'type' => 'text',
				'placeholder' => $this->lang->line('portofolio_name'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('portofolio_name', 'portofolio_name'),
			);
			$this->data['portofolio_description'] = array(
				'name' => 'portofolio_description',
				'id' => 'portofolio_description',
				'type' => 'text',
				'placeholder' => $this->lang->line('portofolio_description'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('portofolio_description', 'portofolio_description'),
			);
			$this->data['portofolio_images'] = array(
				'name' => 'portofolio_images',
				'id' => 'portofolio_images',
				'type' => 'file',
				'class' => 'form-control',
			);
			$this->data['id_service'] = array(
				'name' => 'id_service',
				'id' => 'id_service',
				'type' => 'text',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('id_service', 2),
			);
			$this->render( "V_test_page" );
		}
	}
	public function edit_portofolio( $id_portofolio = NULL )//ok
	{
		$id_user = $this->user_auth->user()->row();
		if( $id_portofolio == NULL ) redirect('test', 'refresh');  

		$this->load->helper('form');
		$this->lang->load('jasain_service_lang');
		$this->load->library( array( 'form_validation' ) ); 

		$this->form_validation->set_rules('portofolio_name',  $this->lang->line('portofolio_name'), 'trim|required');
		$this->form_validation->set_rules('portofolio_description',  $this->lang->line('portofolio_description'), 'trim|required');
		$this->form_validation->set_rules('id_service',  $this->lang->line('service_name'), 'trim|required');
		if ( $this->form_validation->run() === TRUE )
		{
			$data['portofolio_name'] = $this->input->post('portofolio_name');
			$data['portofolio_description'] = $this->input->post('portofolio_description');
			$data['id_portofolio'] = $this->input->post('id_portofolio');

			$data_param['id_portofolio'] = $data['id_portofolio'];

			if( $this->jasain_service->update_portofolio( $data, $data_param ) )
			{
				echo $this->jasain_service->messages();
			}
			else
			{
				echo $this->jasain_service->errors();
			}
			
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->jasain_service->errors() ? $this->jasain_service->errors() : $this->session->flashdata('message')));

			$portofolio = $this->jasain_service->portofolio( $id_portofolio )->row();
			$this->data['id_portofolio'] = array(
				'name' => 'id_portofolio',
				'id' => 'id_portofolio',
				'type' => 'hidden',
				'placeholder' => $this->lang->line('id_portofolio'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('id_portofolio', $portofolio->id_portofolio),
			);
			$this->data['portofolio_name'] = array(
				'name' => 'portofolio_name',
				'id' => 'portofolio_name',
				'type' => 'text',
				'placeholder' => $this->lang->line('portofolio_name'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('portofolio_name', $portofolio->portofolio_name),
			);
			$this->data['portofolio_description'] = array(
				'name' => 'portofolio_description',
				'id' => 'portofolio_description',
				'type' => 'text',
				'placeholder' => $this->lang->line('portofolio_description'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('portofolio_description', $portofolio->portofolio_description),
			);
			$this->data['portofolio_images'] = array(
				'name' => 'portofolio_images',
				'id' => 'portofolio_images',
				'type' => 'file',
				'class' => 'form-control',
			);
			$this->data['id_service'] = array(
				'name' => 'id_service',
				'id' => 'id_service',
				'type' => 'text',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('id_service', $portofolio->id_service ),
			);
			$this->render( "V_test_page" );
		}
	}

	public function create_category()//ok
	{
		$id_user = $this->user_auth->user()->row();
		if( $id_user == NULL ) redirect('test', 'refresh'); 

		$this->load->helper('form');
		$this->lang->load('jasain_service_lang');
		$this->load->library( array( 'form_validation' ) ); 

		$this->form_validation->set_rules('category_name',  $this->lang->line('category_name'), 'trim|required');
		$this->form_validation->set_rules('category_description',  $this->lang->line('category_description'), 'trim|required');
		if ( $this->form_validation->run() === TRUE )
		{
			$data['category_name'] = $this->input->post('category_name');
			$data['category_description'] = $this->input->post('category_description');
			
			if($this->m_category->create( $data ))
			{
				echo $this->m_category->messages();
			}
			else
			{
				echo $this->m_category->errors();
			}
			
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->jasain_service->errors() ? $this->jasain_service->errors() : $this->session->flashdata('message')));

			$this->data['category_name'] = array(
				'name' => 'category_name',
				'id' => 'category_name',
				'type' => 'text',
				'placeholder' => $this->lang->line('category_name'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('category_name', 'category_name'),
			);
			$this->data['category_description'] = array(
				'name' => 'category_description',
				'id' => 'category_description',
				'type' => 'text',
				'placeholder' => $this->lang->line('category_description'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('category_description', 'category_description' ),
			);
			$this->render( "V_test_page" );
		}
	}

	public function edit_category( $id_category = NULL )//ok
	{
		$id_user = $this->user_auth->user()->row();
		if( $id_category == NULL ) redirect('test', 'refresh'); 

		$this->load->helper('form');
		$this->lang->load('jasain_service_lang');
		$this->load->library( array( 'form_validation' ) ); 

		$this->form_validation->set_rules('category_name',  $this->lang->line('category_name'), 'trim|required');
		$this->form_validation->set_rules('category_description',  $this->lang->line('category_description'), 'trim|required');
		$this->form_validation->set_rules('id_category',  $this->lang->line('category_name'), 'trim|required');
		if ( $this->form_validation->run() === TRUE )
		{
			$data['category_name'] = $this->input->post('category_name');
			$data['category_description'] = $this->input->post('category_description');

			$data_param['id_category'] = $this->input->post('id_category');

			
			if($this->m_category->update( $data, $data_param  ) )
			{
				echo $this->m_category->messages();
			}
			else
			{
				echo $this->m_category->errors();
			}
			
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->jasain_service->errors() ? $this->jasain_service->errors() : $this->session->flashdata('message')));

			$category = $this->m_category->category( $id_category )->row();
			$this->data['id_category'] = array(
				'name' => 'id_category',
				'id' => 'id_category',
				'type' => 'hidden',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('id_category', $category->id_category),
			);
			$this->data['category_name'] = array(
				'name' => 'category_name',
				'id' => 'category_name',
				'type' => 'text',
				'placeholder' => $this->lang->line('category_name'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('category_name', $category->category_name),
			);
			$this->data['category_description'] = array(
				'name' => 'category_description',
				'id' => 'category_description',
				'type' => 'text',
				'placeholder' => $this->lang->line('category_description'),
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('category_description', $category->category_description ),
			);
			$this->render( "V_test_page" );
		}
	}
}