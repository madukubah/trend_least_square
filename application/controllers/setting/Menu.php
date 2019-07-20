<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends Admin_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'setting';
    private $current_page = 'setting/menu';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Menu_services');
        $this->services = new Menu_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_menu'));
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','name','link','status','position','description'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_menu->search_count($key, $this->name) : $this->m_menu->get_total();
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
        $fetch['order'] = array("field"=>"position","type"=>"ASC");
        $for_table = $this->m_menu->fetch($fetch);

        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        $this->data["parent_page"] = $this->parent_page;
        $this->data["block_header"] = "Menu Management";
        $this->data["header"] = "TABLE MENU";
        $this->data["sub_header"]  = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "admin/menu/content");
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
      $this->data["block_header"] = "Menu Management";
      $this->data["header"] = "CREATE MENU";
      $this->data["sub_header"]  = 'Klik Tombol Save Setelah Mengisi Form';
      $this->render( "admin/menu/create");
    }

    public function insert($data) {
      $insert = $this->m_menu->add($data);
      return $insert;
    }

    public function edit($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_menu->getWhere($w);
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
      $this->data["block_header"] = "Menu Management";
      $this->data["header"] = "EDIT MENU";
      $this->data["sub_header"]  = 'Klik Tombol Save Setelah Mengisi Form';

      $this->render( "admin/menu/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_menu->update($id, $data);
      return $insert;
    }


    public function detail($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_menu->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }
      $this->data["block_header"] = "Menu Management";
      $this->data["header"] = "DETAIL MENU";
      $this->data["sub_header"]  = 'Klik Tombol Kembali Untuk Kembali Ke Halaman Sebelumnya';
      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->render("admin/menu/detail");
    }

    public function delete($id) {
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $delete = $this->m_menu->delete($w);
      if($delete!=false){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Delete data berhasil'));
        redirect($this->current_page);
      }else{
        redirect($this->current_page);
      }

    }

}
