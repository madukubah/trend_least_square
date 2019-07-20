<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserProfile extends Admin_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'settings';
    private $current_page = 'setting/userprofile';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/UserProfile_services');
        $this->services = new UserProfile_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_userprofile'));
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','surename','birthplace','sex','phone','address'];
        //pagination parameter
        $pagination['base_url'] = base_url() .$this->current_page.'/userprofile/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_userprofile->search_count($key, $this->name) : $this->m_userprofile->get_total();
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);


        //fetch data from database
        $fetch['select'] = $tabel_cell;
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"ASC");
        $for_table = $this->m_userprofile->fetch($fetch);

        //get flashdata
        $status = $this->session->flashdata('status');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        $this->data["headline"] = "TABLE USER PROFILE";
        //$data['menu'] = $this->m_menu->menu($this->session->userprofiledata('userprofile_id'));
        //$data['submenu'] = $this->m_menu->submenu($this->session->userprofiledata('userprofile_id'));

        $this->render( "admin/user_profile/content");
    }


    public function create(){
      if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
        if($this->form_validation->run() === TRUE){
            $input_data = $this->input->post();
            $insert = $this->insert($input_data);
            if($insert){
              $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Input data berhasil'));
              redirect($this->current_page);
            }else{
              $form = $this->services->form_data();
            }
        }else {
          $alert = $this->errorValidation(validation_errors());
          $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
          $form = $this->services->form_data();
        }
      }else{
        $form = $this->form_data;
      }

      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/create');
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page;
      $this->data["headline"] = "CREATE GROUP";
      $this->render( "admin/user_profile/create");
    }

    public function insert($data) {
      $insert = $this->m_userprofile->add($data);
      return $insert;
    }

    public function edit($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_userprofile->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
        if($this->form_validation->run() === TRUE){
            $input_data = $this->input->post();
            $update = $this->update($id, $input_data);
            if($update){
              $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Update data berhasil'));
              redirect($this->current_page);
            }else{
              $form = $this->services->form_data();
            }
        }else{
          $alert = $this->errorValidation(validation_errors());
          $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
          $form = $this->services->form_data();
        }
      }else{
        $form = $this->services->form_data($form_value);
      }
      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/edit/'.$id);
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page;
      $this->data["headline"] = "EDIT GROUP";
      $this->render( "admin/user_profile/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_userprofile->update($id, $data);
      return $insert;
    }


    public function detail($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_userprofile->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }
      $this->data["headline"] = "DETAIL GROUP";
      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->render("admin/user_profile/detail");
    }

    public function delete($id) {
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $delete = $this->m_userprofile->delete($w);
      if($delete!=false){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Delete data berhasil'));
        redirect($this->current_page);
      }else{
        redirect($this->current_page);
      }

    }
}
