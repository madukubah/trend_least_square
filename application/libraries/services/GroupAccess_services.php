<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GroupAccess_services{
    function __construct(){

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public $name = [
      'id',
      'group_id',
      'menu_id',
      'c',
      'r',
      'u',
      'd',
      's',
    ];

    public $label =  [
      'id' => 'Id Hak Akses',
      'group_id' => 'Grup Id',
      'menu_id' => 'Nama Menu',
      'menu_name' => 'Nama Menu',
      'c' => 'Create',
      'r' => 'Read',
      'u' => 'Update',
      'd' => 'Delete',
      's' => 'Spesial',

    ];

    public $type =  [
      'id' => 'number',
      'group_id' => 'hidden',
      'menu_id' => 'select',
      'c' => 'select',
      'r' => 'select',
      'u' => 'select',
      'd' => 'select',
      's' => 'select',
    ];

    public function validation_config(){
        $arr_con = [];
        foreach ($this->name as $key => $value) {
          if($value!='id'){
            switch ($value) {
              case 'menu_id':
                $arr = array(
                  'field' => $value,
                  'label' => $this->label[$value],
                  'rules' =>  'trim|required|is_unique[table_group_access.menu_id]',
                  'errors' => array(
                              'required'      => 'Field %s tidak boleh kosong.',

                      )
                );
                break;

              default:
                $arr = array(
                  'field' => $value,
                  'label' => $this->label[$value],
                  'rules' =>  'trim|required',
                  'errors' => array(
                              'required' => 'Field %s tidak boleh kosong  .',
                      )
                );
                break;
            }

            array_push($arr_con, $arr);
          }
        }

    		return $arr_con;
  	}

    public function form_data($form_value=null, $param=null){
        $this->load->model('m_menu');

        function add_select($a){
          return array('No','Yes');
        }

        $arr  = ['c' => [],'r' => [],'u' => [],'d' => [],'s' => []];
        $select = array_map('add_select', $arr);

        $menu = $this->m_menu->get();

        foreach ($menu as $k => $v) {
          $select['menu_id'][$v->id] = $v->name;
        }

    		foreach ($this->name as $key => $value) {
          if($form_value!=null){
            if(isset($form_value->{$value})&&$form_value->{$value}!=null){
              $val = $form_value->{$value};
            }else{
              $val = $this->form_validation->set_value($value);
            }
          }else{
            $val = $this->form_validation->set_value($value);
          }
          switch ($this->type[$value]) {
            case 'select':
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'option' => $select[$value],
                'class' => 'form-control show-tick',
                'value' => $val,
              );
              break;

            default:
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'class' => 'form-control',
                'value' => $val,
              );
              break;
          }

        };
        unset($data['id']);
    		return $data;
  	}

    public function tabel_header($arr){
      $label = [];
      foreach ($arr as $key => $value) {
        $label[$value] = $this->label[$value];
      }
      if(isset($label['id'])) unset($label['id']);
      return $label;
    }

}
?>
