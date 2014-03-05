<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends MX_Controller  {

	 
	function __construct()
	{
		parent::__construct();
		$this->load->model('configs/model_configs', 'configs');
		$this->load->model('dashboard/model_dashboard', 'dashboard');
		$this->lang->load('elemen_layout', 'indonesia');
	}
	
	public function setheader()
	{
		return Modules::run('layout/setheader');
	}


	public function setfooter()
	{
		return Modules::run('layout/setfooter');
	}
	 
	public function auth()
	{
		return Modules::run('auth/privateAuth');
	}
	
	public function forbiddenAuth()
	{
		return Modules::run('auth/forbiddenAuth');
	}

	function index()
	{
		$this->auth();
		$this->forbiddenAuth();
		$this->grid();
	}
	
	function grid($err=NULL)
	{
		$this->setheader();
		$contents = $this->grid_content($err);
	
		$data = array(
				  'base_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
		
		$this->setfooter();
	}
	
	function grid_content($err=NULL)
	{
		$q = $this->configs->getDetail("configs",1);
		$r = $q->row();
		$site_name = $r->meta_title;
		
		
		
		$data = array(
				  'base_url' => base_url(),
				  'site_name'=>$site_name,
				  );
		return $this->parser->parse('dashboard.html', $data, TRUE);
	}
	
	
	function home($err=NULL)
	{
		$contents = $this->grid_content($err);
	
		$data = array(
				  'base_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
	}
	
	function home_content($err=NULL)
	{
		$q = $this->configs->getDetail("configs",1);
		$r = $q->row();
		$site_name = $r->meta_title;
		
		
		
		$data = array(
				  'base_url' => base_url(),
				  'site_name'=>$site_name,
				  );
		return $this->parser->parse('dashboard.html', $data, TRUE);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */