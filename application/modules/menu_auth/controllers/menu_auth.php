<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class menu_auth extends MX_Controller  {
	
	var $table = "menu_auth";
	var $table_alias = "Admin Menu Auth";
	var $uri_page = 6;
	var $per_page = 25;
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model($this->table."/model_".$this->table, $this->table);
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


	function grid()
	{
		$this->setheader();		
		$contents = $this->grid_content();
	
		$data = array(
				  'admin_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
		
		$this->setfooter();
	}
	
	
	
	function grid_content()
	{	
		#search
		$sch1_parm = rawurldecode($this->uri->segment(3));
		$sch1_parm = $sch1_parm != 'null' && !empty($sch1_parm) ? $sch1_parm : 'null';
		$sch1_val = $sch1_parm != 'null' ? $sch1_parm : '';
		
		$sch2_parm = rawurldecode($this->uri->segment(4));
		$sch2_parm = $sch2_parm != 'null' && !empty($sch2_parm) ? $sch2_parm : 'null';
		
		#ref dropdown no multi value for search
		$q_ref2 = Modules::run('widget/getQueryStaticDropdown','adminusers_level','title');
		$ref2_arr = array();
		foreach ($q_ref2->result() as $r_ref2) {
			 $ref2_arr[$r_ref2->id] = $r_ref2->title;
		}
					
		$q_result2 = $q_ref2->result();
		$ref2_select_arr = array();
		$r2 = 0;
		foreach ($q_result2 as $r_ref2) {
			$ref2_select_arr[$r2] = $sch2_parm;
			$r2++;
		}

		$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
		#end ref dropdown no multi value for search
		
		$sch_path = rawurlencode($sch1_parm)."/".rawurlencode($sch2_parm);
		#end search

		#paging
		$get_page = $this->uri->segment(5);
		$uri_segment = $this->uri_page;
		$pg = $this->uri->segment($uri_segment);
		$per_page = !empty($get_page) ? $get_page : $this->per_page;
		$no = $go_pg = !$pg ? 0 : $pg;
		if(!$pg)
		{
			$lmt = 0;
			$pg = 1;
		}else{
			$lmt = $pg;
		}
		$path = base_url().$this->table."/pages/".$sch1_parm."/".$sch2_parm."/".$per_page;
		$jum_record = $this->menu_auth->getTotal($this->table,$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->menu_auth->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm);
		$list = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				
				$title = ucwords($r->menu_title);
				$title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				$create_date = date("d/m/Y H:i:s",strtotime($r->create_date));
			
				$list[] = array(
								 "no"=>$no,
								 "id"=>$r->id,
								 "title" =>$title,
								 "level" =>$r->title,
								 "create_date"=>$create_date
								);
			}
		}	
		#end record
	
		$data = array(
				  'admin_url' => base_url(),
				  'paging'=>$paging,
				  'list'=>$list,
				  'jum_record'=>$jum_record,
				  'display_record'=>$display_record,
				  'sch1_parm'=>$sch1_parm,
				  'sch1_val'=>$sch1_val,
				  'sch2_parm'=>$sch2_parm,
				  'ref2'=>$ref2,
				  'sch_path'=>$sch_path,
				  'per_page'=>$per_page,
				  'pg'=>$go_pg,
				  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				  'title_link'=>$this->table
				  );
		return $this->parser->parse("list.html", $data, TRUE);
	}
	
	function search()
	{
		$sch1 = rawurlencode($this->input->post('sch1'));
		$sch2 = rawurlencode($this->input->post('ref2'));
		$per_page = rawurlencode($this->input->post('per_page'));
		
		$sch1 = empty($sch1) ? 'null' : $sch1;
		$sch2 = empty($sch2) ? 'null' : $sch2;
		
		redirect($this->table."/pages/".$sch1."/".$sch2."/".$per_page);
	}
	
	
	function edit()
	{
		$this->setheader();		
		$id = $this->uri->segment(3);
		$contents = $this->edit_content($id);
		$add_edit = $id == 0 ? "Add" : "Edit";
		$data = array(
				  'admin_url'=>base_url(),
				  'contents'=>$contents,
				  'add_edit'=>$add_edit
				  );
		$this->parser->parse('layout/contents.html', $data);
		
		$this->setfooter();
	}
	
	function edit_content($id)
	{
		$number = 0;
		$file_image = "";
		
		if(is_numeric($id)){
			
			$q = $this->menu_auth->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					
					$menu_level_id = $r->menu_level_id;
					$menu_id = $r->menu_id;

					$list[] = array(
									"id"=>$r->id,
									"create_date"=>$r->create_date
									);
				}
			}else{
				
				$menu_level_id = "";
				$menu_id = 1;

				$list[] = array(
									"id"=>0,
									"create_date"=>""
									);
			}
			
			#ref dropdown multi value
			$ref1 = Modules::run('menu/getRefDropdown',$menu_id,1,"_multiple");
			#end ref dropdown multi value
			
			#ref dropdown no multi value
			$ref2 = Modules::run('adminusers_level/getRefDropdown',$menu_level_id,2,null,null,'publish');
			#end ref dropdown no multi value
			
			#notification
			$err = $this->session->flashdata("err") ? $this->session->flashdata("err") : "";
			$success = $this->session->flashdata("success") ? $this->session->flashdata("success") : "";
			$notif = array();
			$btn_plus = "display:none;";
			if(!empty($success)){
				$btn_plus = "";
				$notif[] = array(
										"notif_title"=>$success,
										"notif_class"=>"success fade in"
										);
			}else if(!empty($err)){
				$notif[] = array(
										"notif_title"=>$err,
										"notif_class"=>"alert-message error fade in"
										);
			}
			#end notification
			
		
			$data = array(
					  'admin_url' => base_url(),
					  'list'=>$list,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table,
					  "ref1"=>$ref1,
					  "ref2"=>$ref2,
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{
		$err = "";
		$ref1 = $this->input->post("ref1");
		$ref2 = $this->input->post("ref2");
		$user_id = $this->session->userdata('adminID');
		$id = strip_tags($this->input->post("id"));
		
	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ref1', 'select menu', 'required');
		$this->form_validation->set_rules('ref2', 'select admin level', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			redirect($this->table."/edit/".$id);
		}else{
				foreach($ref1 as $ref1_val){
					if($this->menu_auth->cekInsert($this->table,$ref1_val,$ref2) == 0){
						$this->menu_auth->setInsert($this->table,$id,$ref1_val,$ref2,$user_id);
					}
				}
				
				$this->session->set_flashdata("success","Data inserted successful");
				redirect($this->table."/edit/0");
		}
	}
	
	function delete($id=0)
	{
		$del_status = $this->menu_auth->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	

	function ajaxRequest1($id)
	{

		$name = 1;
		$type = "multiple";
		$refDropdown = $selected = "";
		
		# select menu id #
		$query = $this->menu_auth->getList($this->table,null,null,null,$id);
		foreach($query->result() as $row)
		{
			$this->db->where_not_in('id',$row->menu_id);
		}	
		$query_menu = $this->db->get('menu');
		

		$list=array();
		foreach($query_menu->result() as $row_menu)
		{

			$selected = $row_menu->id == 1 ? "selected='selected'" : "";

			$list[]= array(
									'id' =>$row_menu->id,
									'title'=>ucwords($row_menu->title),
									"selected"=>$selected
								 );
		}
		
		$data = array(
					"list"=>$list,
					"name"=>"ref".$name
					);
		$refDropdown = $this->parser->parse("layout/ref_dropdown_".$type.".html", $data,TRUE);

		$data = array(
					  'base_url' => base_url(),
					  'ref'.$name=>$refDropdown
					  );
		echo $this->parser->parse("ajax.html", $data, TRUE);

	}

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */