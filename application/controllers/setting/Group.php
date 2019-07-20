<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends Admin_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'settings';
    private $current_page = 'setting/group';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Group_services');
        $this->services = new Group_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_group'));
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','name','description'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_group->search_count($key, $this->name) : $this->m_group->get_total();
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
        $for_table = $this->m_group->fetch($fetch);

        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Group Management";
        $this->data["header"] = "TABLE GROUP";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        //$data['group'] = $this->m_group->group($this->session->groupdata('group_id'));
        //$data['subgroup'] = $this->m_group->subgroup($this->session->groupdata('group_id'));

        $this->render( "admin/group/content");
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
      $this->data["block_header"] = "Group Management";
      $this->data["header"] = "Tambah Group";
      $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';
      $this->render( "admin/group/create");
    }

    public function insert($data) {
      $insert = $this->m_group->add($data);
      return $insert;
    }

    public function edit($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_group->getWhere($w);
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
              $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Update data berhasil'));
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
      $this->data["block_header"] = "Group Management";
      $this->data["header"] = "Ubah Group";
      $this->data["sub_header"] = 'Silahkan ubah data yang ingin anda ganti';
      $this->render( "admin/group/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_group->update($id, $data);
      return $insert;
    }


    public function detail($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_group->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Group Management";
      $this->data["header"] = "Detail Group";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("admin/group/detail");
    }

    public function delete($id) {
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $delete = $this->m_group->delete($w);
      if($delete!=false){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Delete data berhasil'));
        redirect($this->current_page);
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Terjadi Kesalahan'));
        redirect($this->current_page);
      }

    }

}
