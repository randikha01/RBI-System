<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ref_conv_pres extends MX_Controller  {
	
	var $table = "ref_conv_pres";
	var $table_alias = "Pressure Conversion";	
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
				  'admin_url'=>base_url(),
				  'contents'=>$contents,
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
		$jum_record = $this->ref_conv_pres->getTotal($this->table,$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->ref_conv_pres->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm);
		$list = array();

		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$title = $r->title;
				$bar = $r->bar;
				$psi = $r->psi;
				$kpa = $r->kpa;
				$kgcm = $r->kgcm;
				$pan = $r->pan;
				$torr = $r->torr;
				$pascal = $r->pascal;
				$mbar = $r->mbar;
				$title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date  = date("d/m/Y H:i",strtotime($r->create_date));

				$list[] = array(
								"no"=>$no,
								"id"=>$r->id,
								"title"=>$title,
								"bar"=>$bar,
								"psi"=>$psi,
								"kpa"=>$kpa,
								"kgcm"=>$kgcm,
								"pan"=>$pan,
								"torr"=>$torr,
								"pascal"=>$pascal,
								"mbar"=>$mbar,
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
				  'sch1_parm'=>$sch1_parm,
				  'sch1_val'=>$sch1_val,
				  'sch2_parm'=>$sch2_parm,
				  'ref2' => $ref2,
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
		
		echo base_url().$this->table."/pages/".$sch1."/".$sch2."/".$per_page;
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
			$ref2_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			#record
			$q = $this->ref_conv_pres->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					
					$title = $this->session->flashdata("title") ? $this->session->flashdata("title") : $r->title;
					$bar = $this->session->flashdata("bar") ? $this->session->flashdata("bar") : $r->bar;
					$psi = $this->session->flashdata("psi") ? $this->session->flashdata("psi") : $r->psi;
					$kpa = $this->session->flashdata("kpa") ? $this->session->flashdata("kpa") : $r->kpa;
					$kgcm = $this->session->flashdata("kgcm") ? $this->session->flashdata("kgcm") : $r->kgcm;
					$pan = $this->session->flashdata("pan") ? $this->session->flashdata("pan") : $r->pan;
					$torr = $this->session->flashdata("torr") ? $this->session->flashdata("torr") : $r->torr;
					$pascal = $this->session->flashdata("pascal") ? $this->session->flashdata("pascal") : $r->pascal;
					$mbar = $this->session->flashdata("mbar") ? $this->session->flashdata("mbar") : $r->mbar;
					#ref dropdown no multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : $r->publish;
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown no multi value
				
					$list[] = array(
									"id"=>$r->id,
									"title" =>$title,
									"bar" =>$bar,
									"psi" =>$psi,
									"kpa" =>$kpa,
									"kgcm" =>$kgcm,
									"pan" =>$pan,
									"torr" =>$torr,
									"pascal" =>$pascal,
									"mbar" =>$mbar,
									"ref2"=>$ref2,
									"create_date"=>$r->create_date
									);
				}
			}else{
					$title = $this->session->flashdata("title") ? $this->session->flashdata("title") : NULL;
					$bar = $this->session->flashdata("bar") ? $this->session->flashdata("bar") : NULL;
					$psi = $this->session->flashdata("psi") ? $this->session->flashdata("psi") : NULL;
					$kpa = $this->session->flashdata("kpa") ? $this->session->flashdata("kpa") : NULL;
					$kgcm = $this->session->flashdata("kgcm") ? $this->session->flashdata("kgcm") : NULL;
					$pan = $this->session->flashdata("pan") ? $this->session->flashdata("pan") : NULL;
					$torr = $this->session->flashdata("torr") ? $this->session->flashdata("torr") : NULL;
					$pascal = $this->session->flashdata("pascal") ? $this->session->flashdata("pascal") : NULL;
					$mbar = $this->session->flashdata("mbar") ? $this->session->flashdata("mbar") : NULL;
					
					#ref dropdown no multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : null;
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);	
					#end ref dropdown no multi value
				
					$list[] = array(
										"id"=>0,
										"title" =>$title,
										"bar" =>$bar,
										"psi" =>$psi,
										"kpa" =>$kpa,
										"kgcm" =>$kgcm,
										"pan" =>$pan,
										"torr" =>$torr,
										"pascal" =>$pascal,
										"mbar" =>$mbar,
										"ref2"=>$ref2,
										"create_date"=>""
										);
			}
			#end record

	
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
					  'admin_url'=>base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'ref2'=>$ref2,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{
		$title = strip_tags($this->input->post("title"));
		$bar = $this->input->post("bar");
		$psi = $this->input->post("psi");
		$kpa = $this->input->post("kpa");
		$kgcm = $this->input->post("kgcm");
		$pan = $this->input->post("pan");
		$torr = $this->input->post("torr");
		$pascal = $this->input->post("pascal");
		$pmbar = $this->input->post("mbar");
		$ref2 = $this->input->post("ref2");
		$id = $this->input->post("id");
		$user_id = $this->session->userdata('adminID');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'title', 'required');
	
		if ($this->form_validation->run($this) == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			$this->session->set_flashdata("title",$title);
			$this->session->set_flashdata("bar",$bar);
			$this->session->set_flashdata("psi",$psi);
			$this->session->set_flashdata("kpa",$kpa);
			$this->session->set_flashdata("kgcm",$kgcm);
			$this->session->set_flashdata("pan",$pan);
			$this->session->set_flashdata("torr",$torr);
			$this->session->set_flashdata("pascal",$pascal);
			$this->session->set_flashdata("mbar",$mbar);
			$this->session->set_flashdata("ref2",$ref2);
			redirect($this->table."/edit/".$id);
		}else{
			if($id > 0)
			{
				$this->ref_conv_pres->setUpdate($this->table,$id,$title,$bar,$psi,$kpa,$kgcm,$pan,$torr,$pascal,$mbar,$ref2,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				redirect($this->table."/edit/".$id);
			}else{
				$id_term = $this->ref_conv_pres->setInsert($this->table,$id,$title,$bar,$psi,$kpa,$kgcm,$pan,$torr,$pascal,$mbar,$ref2,$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				redirect($this->table."/edit/".$last_id);
			}
		}
	}
	

	function delete($id=0)
	{
		$del_status = $this->ref_conv_pres->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */