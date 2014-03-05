<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ref_conv_leng extends MX_Controller  {
	
	var $table = "ref_conv_leng";
	var $table_alias = "Length Conversion";	
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
		$jum_record = $this->ref_conv_leng->getTotal($this->table,$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->ref_conv_leng->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm);
		$list = array();

		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$title = $r->title;
				$in_ = $r->in_;
				$ft = $r->ft;
				$mm = $r->mm;
				$cm = $r->cm;
				$m = $r->m;
				$title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date  = date("d/m/Y H:i",strtotime($r->create_date));

				$list[] = array(
								"no"=>$no,
								"id"=>$r->id,
								"title"=>$title,
								"in_"=>$in_,
								"ft"=>$ft,
								"mm"=>$mm,
								"cm"=>$cm,
								"m"=>$m,
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
			$q = $this->ref_conv_leng->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					
					$title = $this->session->flashdata("title") ? $this->session->flashdata("title") : $r->title;
					$in_ = $this->session->flashdata("in_") ? $this->session->flashdata("in_") : $r->in_;
					$ft = $this->session->flashdata("ft") ? $this->session->flashdata("ft") : $r->ft;
					$mm = $this->session->flashdata("mm") ? $this->session->flashdata("mm") : $r->mm;
					$cm = $this->session->flashdata("cm") ? $this->session->flashdata("cm") : $r->cm;
					$m = $this->session->flashdata("m") ? $this->session->flashdata("m") : $r->m;
					#ref dropdown no multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : $r->publish;
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown no multi value
				
					$list[] = array(
									"id"=>$r->id,
									"title" =>$title,
									"in_" =>$in_,
									"ft" =>$ft,
									"mm" =>$mm,
									"cm" =>$cm,
									"m" =>$m,
									"ref2"=>$ref2,
									"create_date"=>$r->create_date
									);
				}
			}else{
					$title = $this->session->flashdata("title") ? $this->session->flashdata("title") : NULL;
					$in_ = $this->session->flashdata("in_") ? $this->session->flashdata("in_") : NULL;
					$ft = $this->session->flashdata("ft") ? $this->session->flashdata("ft") : NULL;
					$mm = $this->session->flashdata("mm") ? $this->session->flashdata("mm") : NULL;
					$cm = $this->session->flashdata("cm") ? $this->session->flashdata("cm") : NULL;
					$m = $this->session->flashdata("m") ? $this->session->flashdata("m") : NULL;
					
					#ref dropdown no multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : null;
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);	
					#end ref dropdown no multi value
				
					$list[] = array(
										"id"=>0,
										"title" =>$title,
										"in_" =>$in_,
										"ft" =>$ft,
										"mm" =>$mm,
										"cm" =>$cm,
										"m" =>$m,
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
		$in_ = $this->input->post("in_");
		$ft = $this->input->post("ft");
		$mm = $this->input->post("mm");
		$cm = $this->input->post("cm");
		$m = $this->input->post("m");
		$ref2 = $this->input->post("ref2");
		$id = $this->input->post("id");
		$user_id = $this->session->userdata('adminID');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'title', 'required');
	
		if ($this->form_validation->run($this) == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			$this->session->set_flashdata("title",$title);
			$this->session->set_flashdata("in_",$in_);
			$this->session->set_flashdata("ft",$ft);
			$this->session->set_flashdata("mm",$mm);
			$this->session->set_flashdata("cm",$cm);
			$this->session->set_flashdata("m",$m);
			$this->session->set_flashdata("ref2",$ref2);
			redirect($this->table."/edit/".$id);
		}else{
			if($id > 0)
			{
				$this->ref_conv_leng->setUpdate($this->table,$id,$title,$in_,$ft,$mm,$cm,$m,$ref2,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				redirect($this->table."/edit/".$id);
			}else{
				$id_term = $this->ref_conv_leng->setInsert($this->table,$id,$title,$in_,$ft,$mm,$cm,$m,$ref2,$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				redirect($this->table."/edit/".$last_id);
			}
		}
	}
	

	function delete($id=0)
	{
		$del_status = $this->ref_conv_leng->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */