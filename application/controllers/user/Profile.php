<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends User_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library( array( 'form_validation' ) );
		$this->load->helper('form');
		$this->config->load('ion_auth', TRUE);
		$this->load->helper(array('url', 'language'));
		$this->lang->load('auth');

	}
	public function index()
	{
		$this->data[ "page_title" ] = "Profile";
		$this->data[ "users" ] = $this->ion_auth->user()->result();
		$this->render( "user/profile/V_detail",  ( $this->ion_auth->is_admin() ? 'admin_master' : 'user_master' ) );
	}
	// public function upload_photo()
	// {
	// 	if ( ! $this->ion_auth->upload_photo( 'user_image' ) )
	// 	{
	// 			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER,  $this->ion_auth->errors() ) );
	// 			redirect(site_url('user/profile'));
	// 	}
	// 	else
	// 	{
	// 			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
	// 			redirect(site_url('user/profile'));
	// 	}
	// }
	public function upload_photo()
	{
		if( !($_POST) )	redirect(site_url('user/profile'));
        $this->load->library( array( 'form_validation' ) );

		$this->form_validation->set_rules('image',  'gambar', 'trim|required');
		if ( $this->form_validation->run() === TRUE )
		{
				if ( ! $this->ion_auth->upload_photo( $this->input->post( "image" ) ) )
				{
						$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER,  $this->ion_auth->errors() ) );
						redirect(site_url('user/profile'));
				}
				else
				{
						$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
						redirect(site_url('user/profile'));
				}
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_advertisement->errors() ? $this->m_advertisement->errors() : $this->session->flashdata('message')));
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}

		redirect(site_url('user/profile'));
	}
	public function edit() //edut curr profile
	{
		$this->data[ "page_title" ] = "Edit Profile";
		$this->form_validation->set_rules('first_name',  $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label') , 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim|required');

		if( !empty( $this->input->post('password') )  )
		{
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label') , 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'trim|required');
			$this->form_validation->set_rules('old_password', $this->lang->line('create_user_validation_old_password_confirm_label'), 'trim|required');
		}

		if ( $this->form_validation->run() === TRUE )
		{
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone' => $this->input->post('phone'),
			);
			if ( $this->input->post('password') )
			{
				$data['password'] = $this->input->post('password');
				$data['old_password'] = $this->input->post('old_password');
			}

			$user = $this->ion_auth->user()->row();//curr user
			// check to see if we are updating the user
			if ( $this->ion_auth->update( $user->id, $data) )
			{
				// redirect them back to the admin page if admin, or to the base url if non admin
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
				if ( $this->input->post('password') )
				{
					redirect(site_url('auth/logout'));
				}
				redirect(site_url('user/profile'));
			}
			else
			{
				// redirect them back to the admin page if admin, or to the base url if non admin
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->ion_auth->errors() ) );
				redirect(site_url('user/profile'));
			}
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

			$user = $this->ion_auth->user(  )->row();
			$this->data[ "user" ] =  $user;
				$this->data['first_name'] = array(
					'name' => 'first_name',
					'id' => 'first_name',
					'type' => 'text',
					'placeholder' => 'Nama Depan',
					'class' => 'form-control form-control-alternative',
					'value' => $this->form_validation->set_value('first_name', $user->first_name  ),
				);
				$this->data['last_name'] = array(
					'name' => 'last_name',
					'id' => 'last_name',
					'type' => 'text',
					'placeholder' => 'Nama Belakang',
					'class' => 'form-control form-control-alternative',
					'value' => $this->form_validation->set_value('last_name', $user->last_name),
				);

				$this->data['phone'] = array(
					'name' => 'phone',
					'id' => 'phone',
					'type' => 'text',
					'placeholder' => 'Nomor HP',
					'class' => 'form-control',
					'value' => $this->form_validation->set_value('phone', $user->phone),
				);

				$this->data['old_password'] = array(
					'name' => 'old_password',
					'id' => 'old_password',
					'type' => 'password',
					'placeholder' => 'Password lama',
					'class' => 'form-control',
					'value' => $this->form_validation->set_value('old_password'),
				);
				$this->data['password'] = array(
					'name' => 'password',
					'id' => 'password',
					'type' => 'password',
					'placeholder' => 'Password',
					'class' => 'form-control',
					'value' => $this->form_validation->set_value('password'),
				);

				$this->data['password_confirm'] = array(
					'name' => 'password_confirm',
					'id' => 'password_confirm',
					'type' => 'password',
					'placeholder' => 'Konfirmasi Password',
					'class' => 'form-control',
					'value' => $this->form_validation->set_value('password_confirm'),
				);

			$this->render( "user/profile/V_edit", ( $this->ion_auth->is_admin() ? 'admin_master' : 'user_master' ) );
		}
	}
}
