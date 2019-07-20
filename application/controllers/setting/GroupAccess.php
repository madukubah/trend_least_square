<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupAccess extends Admin_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'setting/group';
    private $current_page = 'setting/groupaccess';


    public function __construct(){
        parent::__construct();
        $this->load->library('services/GroupAccess_services');
        $this->services = new GroupAccess_services;
        $this->name = $this->services->name;
        $this->load->model(array('m_group_access','m_group'));
    }


    public function index(){
        $id = $this->input->get('id');
        if($id==null){
          redirect($this->parent_page);
        }else{
          $parent_data = $this->m_group->getWhere(array('id'=>$id));
          (!$parent_data) ? redirect($this->parent_page) : $parent_data = $parent_data[0];
        }
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','menu_name','c','r','u','d','s'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_group_access->search_count($key, $this->name) : $this->m_group_access->get_total();
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);


        //fetch data from database
        $fetch['select'] = ['id','menu_id','c','r','u','d','s'];
        $fetch['select_join'] = ['table_menus.name as menu_name'];
        $fetch['join'] = [array('table'=>'table_menus','id'=>'menu_id','join'=>'left')];
        $fetch['where'] = array('group_id'=>$id);
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $for_table = $this->m_group_access->fetch($fetch);

        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;

        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["id"] = $id;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Group Management";
        $this->data["header"] = "TABLE ACCESS GROUP ".$parent_data->name;
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "admin/group_access/content");
    }


    public function create($id){
      $form_value = new \stdClass;
      $form_value->group_id = $id;
      if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
        if($this->form_validation->run() === TRUE){
            $input_data = $this->input->post();
            $insert = $this->insert($input_data);
            if($insert){
              $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Input data berhasil'));
              redirect($this->current_page.'?id='.$id);
            }else{
              $form = $this->services->form_data($form_value);
            }
        }else {
          $alert = $this->errorValidation(validation_errors());
          $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
          $form = $this->services->form_data($form_value);
        }
      }else{
        $form = $this->services->form_data($form_value);
      }

      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/create/'.$id);
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page.'?id='.$id;
      $this->data["block_header"] = "Group Management";
      $this->data["header"] = "SET ACCESS";
      $this->data["sub_header"] = 'Klik Tombol Save Setelah Mengisi Form';
      $this->render( "admin/group_access/create");
    }

    public function insert($data) {
      $insert = $this->m_group_access->add($data);
      return $insert;
    }

    public function edit($id=null){
      $parent_id = $this->input->get('id');

      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_group_access->getWhere($w);
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
              redirect($this->current_page.'?id='.$parent_id);
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
      $this->data['parent_page'] = $this->current_page.'?id='.$parent_id;
      $this->data["block_header"] = "Group Management";
      $this->data["header"] = "EDIT ACCESS";
      $this->data["sub_header"] = 'Klik Tombol Save Setelah Mengisi Form';
      $this->render( "admin/group_access/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_group_access->update($id, $data);
      return $insert;
    }


    public function detail($id=null){
      $parent_id = $this->input->get('id');
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_group_access->getWhere($w);
      if($form_value==false){
        redirect($this->current_page.'?id='.$parent_id);
      }else{
        $form_value = $form_value[0];
      }
      $this->data["block_header"] = "Group Management";
      $this->data["header"] = "DETAIL ACCESS";
      $this->data["sub_header"] = 'Klik Tombol Kembali Untuk Menuju Ke Halaman Sebelumnya';
      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page.'?id='.$parent_id;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->render("admin/group_access/detail");
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
