<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Admin_Controller {
	public function __construct(){
		parent::__construct();

	}
	public function index()
	{
		// echo $this->router->fetch_class();
		// echo $this->session->userdata( 'user_image' );
		// return;
		$this->data[ "page_title" ] = "Beranda";
		$this->render( "admin/dashboard/content" );
	}
}
