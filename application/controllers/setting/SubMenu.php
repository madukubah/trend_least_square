<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubMenu extends Admin_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'setting/menu';
    private $current_page = 'setting/submenu';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/SubMenu_services');
        $this->services = new SubMenu_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_submenu','m_menu'));
    }


    public function index(){
        $id = $this->input->get('id');
        if($id==null){
          redirect($this->parent_page);
        }else{
          $parent_data = $this->m_menu->getWhere(array('id'=>$id));
          (!$parent_data) ? redirect($this->parent_page) : $parent_data = $parent_data[0];
        }
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','name','link','status','position','description'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_submenu->search_count($key, $this->name) : $this->m_submenu->get_total();
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);


        //fetch data from database
        $fetch['select'] = $tabel_cell;
        $fetch['where'] = array('menu_id'=>$id);
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"position","type"=>"ASC");
        $for_table = $this->m_submenu->fetch($fetch);

        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["id"] = $id;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Menu Management";
        $this->data["header"] = "TABLE SUBMENU ".$parent_data->name;
        $this->data["sub_header"]  = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "admin/submenu/content");
    }


    public function create($id){
      $form_value = new \stdClass;
      $form_value->menu_id = $id;
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
      $this->data["block_header"] = "Menu Management";
      $this->data["header"] = "Tambah Submenu";
      $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';
      $this->render( "admin/submenu/create");
    }

    public function insert($data) {
      $insert = $this->m_submenu->add($data);
      return $insert;
    }

    public function edit($id=null){
      $parent_id = $this->input->get('id');
      if($id==null || $parent_id==null){
        redirect($this->parent_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_submenu->getWhere($w);
      if($form_value==false){
        redirect($this->parent_page);
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
      $this->data['form_action'] = site_url($this->current_page.'/edit/'.$id.'?id='.$parent_id);
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page.'?id='.$parent_id;
      $this->data["block_header"] = "Menu Management";
      $this->data["header"] = "Edit Submenu";
      $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';
      $this->render( "admin/submenu/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_submenu->update($id, $data);
      return $insert;
    }


    public function detail($id=null){
      $parent_id = $this->input->get('id');
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_submenu->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }
      $this->data["block_header"] = "Menu Management";
      $this->data["header"] = "Detail Submenu";
      $this->data["sub_header"] = 'Tekan Tombol Kembali Untuk Menuju Form Sebelumnya';
      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page.'?id='.$parent_id;
      $this->data['detail'] = true;
      $this->data['name'] = $this->name;
      $this->render("admin/submenu/detail");
    }

    public function delete($id) {
      $parent_id = $this->input->get('id');
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $delete = $this->m_submenu->delete($w);
      if($delete!=false && $parent_id!=null){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Delete data berhasil'));
        redirect($this->current_page.'?id='.$parent_id);
      }else{
        redirect($this->parent_page);
      }

    }

}
