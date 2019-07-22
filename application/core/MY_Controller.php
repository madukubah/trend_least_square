<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = array();

    public function __construct(){
       parent::__construct();
    }

    protected function render($the_view = NULL, $template = NULL){
    		if($template == 'json' || $this->input->is_ajax_request()){
    			header('Content-Type: application/json');
    			echo json_encode($this->data);
    		}
    		elseif(is_null($template)){
    			$this->load->view($the_view, $this->data );
    		}else{
    			$this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
    			$this->load->view('templates/V_' . $template . '', $this->data);
    		}
		 }

		 public function setPagination($pagination){
				$config['base_url'] = $pagination['base_url'];
				$config['total_rows'] = $pagination['total_records'];
				$config['per_page'] = $pagination['limit_per_page'];
				$config["uri_segment"] = $pagination['uri_segment'];

				// custom paging configuration
				$config['num_links'] = $pagination['total_records']/$pagination['limit_per_page'];
				$config['use_page_numbers'] = TRUE;
				$config['reuse_query_string'] = TRUE;

				$config['full_tag_open'] = '<ul class="pagination">';
				$config['full_tag_close'] = '</ul>';
				$config['first_link'] = false;
				$config['last_link'] = false;
				$config['first_tag_open'] = '<li>';
				$config['first_tag_close'] = '</li>';
				$config['prev_link'] = '&laquo';
				$config['prev_tag_open'] = '<li class="prev">';
				$config['prev_tag_close'] = '</li>';
				$config['next_link'] = '&raquo';
				$config['next_tag_open'] = '<li>';
				$config['next_tag_close'] = '</li>';
				$config['last_tag_open'] = '<li>';
				$config['last_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="active"><a href="#">';
				$config['cur_tag_close'] = '</a></li>';
				$config['num_tag_open'] = '<li>';
				$config['num_tag_close'] = '</li>';

				$this->pagination->initialize($config);

				// build paging links
				return $this->pagination->create_links();
		}

    public function errorValidation($error){
      $alert = str_replace('<p>','<li>',$error);
      $alert = str_replace('</p>','</li>',$alert);
      $alert = "<ul>".$alert."</ul>";
      return $alert;
    }

	public function set_menu( $group_id )
	{
		$this->load->model(array(
			'm_menu',
		));
		$this->data[ "_menus" ] = $this->m_menu->tree( $group_id );
    }

}

class User_Controller extends MY_Controller{

    public function __construct(){
	     parent::__construct();
  	   if( !$this->ion_auth->logged_in()){
		     $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER,  $this->lang->line('login_not_login')  ) );
		     redirect(site_url('/auth/login'));
  	   }else{
			$this->set_menu( $this->session->userdata('group_id') );
		}
    }

    protected function render($the_view = NULL, $template = 'admin_master'){
  		parent::render($the_view, $template);
  	}

}

class Admin_Controller extends User_Controller{

    public function __construct(){
      parent::__construct();
    	if( !$this->ion_auth->is_admin() ){
    		$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->lang->line('login_must_admin') ) );
    		redirect(site_url('/auth/login'));
    	}else{
      }
    }

    protected function render($the_view = NULL, $template = 'admin_master'){
  		parent::render($the_view, $template);
  	}
}

class Public_Controller extends MY_Controller{

  function __construct(){
		parent::__construct();
  }

  protected function render($the_view = NULL, $template = 'public_master'){
		parent::render($the_view, $template);
	}

}
