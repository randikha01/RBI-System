<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class adminusers_auth extends MX_Controller  {
	
	var $table = "adminusers_auth";
	var $table_alias = "Admin User Auth";
	var $uri_page = 7;
	var $per_page = 25;
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model($this->table."/model_".$this->table, $this->table);
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
		$sch1_val = $sch1_parm != 'null' ? $sch1_parm : '';
		
		$sch2_parm = rawurldecode($this->uri->segment(4));
		$sch2_parm = $sch2_parm != 'null' && !empty($sch2_parm) ? $sch2_parm : 'null';
		$sch2_select_arr[0] = $sch2_parm;
		$sch2_arr = array(
							"Not Publish"=>"Not Publish",
							"Publish"=>"Publish"
						  );
		$ref2 = Modules::run('widget/getStaticDropdown',$sch2_arr,$sch2_select_arr,2);

		
		$sch3_parm = rawurldecode($this->uri->segment(5));
		$sch3_parm = $sch3_parm != 'null' && !empty($sch3_parm) ? $sch3_parm : 'null';
		
		#ref dropdown no multi value for search
		$q_ref3 = Modules::run('widget/getQueryStaticDropdown','adminusers_level','title');
		$ref3_arr = array();
		foreach ($q_ref3->result() as $r_ref3) {
			 $ref3_arr[$r_ref3->id] = $r_ref3->title;
		}
					
		$q_result3 = $q_ref3->result();
		$ref3_select_arr = array();
		$r3 = 0;
		foreach ($q_result3 as $r_ref3) {
			$ref3_select_arr[$r3] = $sch3_parm;
			$r3++;
		}

		$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,$ref3_select_arr,3);
		#end ref dropdown no multi value for search
		
		$sch_path = rawurlencode($sch1_parm)."/".rawurlencode($sch2_parm)."/".rawurlencode($sch3_parm);
		#end search

		
		#paging
		$get_page = $this->uri->segment(6);
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
		$path = base_url().$this->table."/pages/".$sch1_parm."/".$sch2_parm."/".$sch3_parm."/".$per_page;
		$jum_record = $this->adminusers_auth->getTotal($this->table,$sch1_parm,$sch2_parm,$sch3_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->adminusers_auth->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm,$sch3_parm);
		$list = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				
				$username = $r->username;
				$username = highlight_phrase($username, $sch1_parm, '<span style="color:#990000">', '</span>');
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date = date("d/m/Y H:i:s",strtotime($r->create_date));
			
				$list[] = array(
								 "no"=>$no,
								 "id"=>$r->id,
								 "title" =>$username,
								 "level" =>$r->title,
								 "publish"=>$publish,
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
				  'sch1_val'=>$sch1_val,
				  'sch1_parm'=>$sch1_parm,
				  'sch2_parm'=>$sch2_parm,
				  'sch3_parm'=>$sch3_parm,
				  'ref2'=>$ref2,
				  'ref3'=>$ref3,
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
		$sch3 = rawurlencode($this->input->post('ref3'));
		$per_page = rawurlencode($this->input->post('per_page'));
		
		$sch1 = empty($sch1) ? 'null' : $sch1;
		$sch2 = empty($sch2) ? 'null' : $sch2;
		$sch3 = empty($sch3) ? 'null' : $sch3;
		
		echo base_url().$this->table."/pages/".$sch1."/".$sch2."/".$sch3."/".$per_page;
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
		$this->parser->parse('layout/contents_form.html', $data);
		
	}
	
	
	
	function edit_content($id)
	{
		$number = 0;
		$file_image = "";
		
		if(is_numeric($id)){
		
			#set asset
			$ref3_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			$q = $this->adminusers_auth->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					
					$title = $this->session->flashdata("ref_title") ? $this->session->flashdata("ref_title") : $r->username;
					
					#ref dropdown no multi value
					$q_ref2 = Modules::run('widget/getQueryStaticDropdown','adminusers_level','title');
					$ref2_arr = array();
					foreach ($q_ref2->result() as $r_ref2) {
						 $ref2_arr[$r_ref2->id] = $r_ref2->title;
					}
					
					$q_ref2 = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : $q;
					$q_result2 = $this->session->flashdata("ref2") ? $q_ref2 : $q_ref2->result();
					$ref2_select_arr = array();
					$r2 = 0;
					foreach ($q_result2 as $r_ref2) {
						 $ref2_select_arr[$r2] = $this->session->flashdata("ref2") ? $r_ref2 : $r_ref2->adminusers_level_id;
						 $r2++;
					}

					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown no multi value
					
					
					#ref dropdown no multi value
					$ref3_select_arr[0] = $r->publish;
					$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,$ref3_select_arr,3);
					#end ref dropdown no multi value
					

					$list[] = array(
									"id"=>$r->id,
									"title"=>$title,
									"password"=>$r->password,
									"create_date"=>$r->create_date,
									"ref3"=>$ref3
									);
				}
			}else{
				
					#ref dropdown no multi value
					$q_ref2 = Modules::run('widget/getQueryStaticDropdown','adminusers_level','title');
					$ref2_arr = array();
					foreach ($q_ref2->result() as $r_ref2) {
						 $ref2_arr[$r_ref2->id] = $r_ref2->title;
					}
					
					$q_ref2 = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : array();
					$ref2_select_arr = array();
					$r2 = 0;
					foreach ($q_ref2 as $r_ref2) {
						 $ref2_select_arr[$r2] = $r_ref2;
						 $r2++;
					}

					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown no multi value
					
				
					#ref dropdown no multi value
					$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,null,3);
					#end ref dropdown no multi value				


					$list[] = array(
									"id"=>0,
									"title"=>"",
									"password"=>"",
									"create_date"=>"",
									"ref3"=>$ref3
									);
			}

	
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
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table,
					  "ref2"=>$ref2
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{
		$err = "";
		$username = strip_tags($this->input->post("username"));
		$password = $this->input->post("password");
		$password_old = $this->input->post("password_old");
		$publish = $this->input->post("ref3");
		$adminusers_level_id = $this->input->post("ref2");
		$user_id = $this->session->userdata('adminID');
		$id = strip_tags($this->input->post("id"));
		
	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'username', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			redirect($this->table."/edit/".$id);
		}else{
			
			if(!empty($password)){
				$password = md5($password);
				$key = "adminkey";
				$password = md5($key.$password);
			}else{
				$password = $password_old;
			}
			
			if($id > 0)
			{
				$this->adminusers_auth->setUpdate($this->table,$id,$username,$password,$publish,$adminusers_level_id,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				redirect($this->table."/edit/".$id);
			}else{
				$this->adminusers_auth->setInsert($this->table,$id,$username,$password,$publish,$adminusers_level_id,$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				redirect($this->table."/edit/".$last_id);
			}
		}
	}
	
	
	function edit_account()
	{	
		$id = $this->uri->segment(3);
		$contents = $this->edit_account_content($id);
	
		$data = array(
				  'admin_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
	}
	
	
	
	function edit_account_content($id)
	{
		$number = 0;
		$file_image = "";
		
		if(is_numeric($id)){
			
			$q = $this->adminusers_auth->getDetail($this->table,$id);
			$list =  array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){

					$list[] = array(
									"id"=>$r->id,
									"title"=>$r->username,
									"level_title"=>$r->title,
									"password"=>$r->password,
									"publish"=>$r->publish,
									"ref1"=>$r->adminusers_level_id,
									"create_date"=>$r->create_date
									);
				}
			}else{
				

				$list[] = array(
									"id"=>0,
									"title"=>"",
									"level_title"=>"",
									"password"=>"",
									"publish"=>"",
									"ref1"=>"",
									"create_date"=>""
									);
			}

	
			//notification
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
		
			$data = array(
					  'admin_url' => base_url(),
					  'btn_plus'=>$btn_plus,
					  'notif'=>$notif,
					  'list'=>$list,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table
					  );
			return $this->parser->parse("account_edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit_account()
	{
		$err = "";
		$username = strip_tags($this->input->post("username"));
		$password = $this->input->post("password");
		$password_old = $this->input->post("password_old");
		$publish = $this->input->post("publish");
		$adminusers_level_id = $this->input->post("ref1");
		$user_id = $this->session->userdata('adminID');
		$id = strip_tags($this->input->post("id"));
		
	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'username', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			echo base_url().$this->table."/edit_account/".$id;
		}else{
			
			if(!empty($password)){
				$password = md5($password);
				$key = "adminkey";
				$password = md5($key.$password);
			}else{
				$password = $password_old;
			}
			
			if($id > 0)
			{
				$this->adminusers_auth->setUpdate($this->table,$id,$username,$password,$publish,$adminusers_level_id,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				echo base_url().$this->table."/edit_account/".$id;
			}else{
				$this->adminusers_auth->setInsert($this->table,$id,$username,$password,$publish,$adminusers_level_id,$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				echo base_url().$this->table."/edit_account/".$last_id;
			}
		}
	}
	
	
	function delete($id=0)
	{
		$del_status = $this->adminusers_auth->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */