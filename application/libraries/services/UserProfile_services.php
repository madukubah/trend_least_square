<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UserProfile_services{


    function __construct(){

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public $name = [
        'id',
        'surename',
        'birthplace',
        'sex',
        'phone',
        'address',

    ];

    public $label =  [
      'id' => 'Id',
      'surename' => 'Nama Asli',
      'birthplace' => 'Tempat Lahir',
      'sex' => 'Jenis Kelamin',
      'phone' => 'Telepon',
      'address' => 'Alamat',
    ];

    public $type =  [
      'id' => 'hidden',
      'surename' => 'text',
      'birthplace' => 'text',
      'sex' => 'select',
      'phone' => 'text',
      'address' => 'textarea',
    ];

    public function validation_config(){
        $arr_con = [];
        foreach ($this->name as $key => $value) {
          if($value!='id'){
            $arr = array(
              'field' => $value,
    					'label' => $this->label[$value],
    					'rules' =>  'trim|required',
              'errors' => array(
                          'required' => 'Field %s tidak boleh kosong  .',
                  )
            );
            array_push($arr_con, $arr);
          }
        }
    		return $arr_con;
  	}

    public function form_data($form_value=null){
        $select['sex'] = array('Pria'=>'Pria','Wanita'=>'Wanita');
        foreach ($this->name as $key => $value) {
          if($form_value!=null){
            $val = $form_value->{$value};
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
