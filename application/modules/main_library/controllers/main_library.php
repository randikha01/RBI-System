<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main_library extends MX_Controller  {
	
	var $table = "menu";
	var $table_alias = "Admin Menu";
	var $uri_page = 7;
	var $per_page = 25;
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model("main_library/model_main_library", "main_library");
		$this->lang->load('elemen_layout', 'indonesia');
	}
	
	public function setheader()
	{
		return Modules::run('layout/setheaderdetail');
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


	function grid()
	{	
		$contents = $this->grid_content();
	
		$data = array(
				  'admin_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
	}
	
	
	
	function grid_content()
	{	
		#search		
		$sch1_parm = rawurldecode($this->uri->segment(3));
		$sch1_parm = $sch1_parm != 'null' && !empty($sch1_parm) ? $sch1_parm : 'null';
		$ref1 = $this->getDropdownmainlibrary($sch1_parm,1);
		$sch_path = rawurlencode($sch1_parm);
		#end search

		
	
		$data = array(
				  'admin_url' => base_url(),
				  'sch1_parm'=>$sch1_parm,
				  'ref1'=>$ref1,
				  'sch_path'=>$sch_path,
				  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				  'title_link'=>"main_library"
				  );
		return $this->parser->parse("list.html", $data, TRUE);
	}

	function getDropdownmainlibrary($parent_id,$name,$type=NULL)
	{
		$q = $this->main_library->getmainlibrarylist('tbl_menu');
		$list = array();
		foreach ($q->result() as $val) {
			$list[]= array(
						'id' => $val->uri,
						'title'=>ucwords($val->title),
						"selected"=>""
					 );
		}
		$data = array(
				"list"=>$list,
				"name"=>"ref".$name
				);
		return $this->parser->parse("layout/ref_dropdown".$type.".html", $data, TRUE);
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */