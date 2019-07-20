<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/_user_parts/head'); 
$this->load->view('templates/_user_parts/header');
$this->load->view('templates/_user_parts/sidebar_menu');
?>
<?php echo $the_view_content;?>
<?php $this->load->view('templates/_user_parts/footer');?>
