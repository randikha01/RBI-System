<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pv_info extends MX_Controller  {
	
	var $table = "pv_info";
	var $table_alias = "Pressure vessel information";	
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
		$this->setheader();		
		$contents = $this->grid_content();
	
		$data = array(
				  'admin_url'=>base_url(),
				  'contents'=>$contents,
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
		$jum_record = $this->pv_info->getTotal($this->table,$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->pv_info->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm);
		$list = array();

		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$title = $r->title;
				$title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date  = date("d/m/Y H:i",strtotime($r->create_date));

				$list[] = array(
								"no"=>$no,
								"id"=>$r->id,
								"title"=>$title,
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
		$this->parser->parse('layout/contents_form.html', $data);
	}
	
	
	
	function edit_content($id)
	{
		$number = 0;
		$file_image = "";
		$id_item_object = $this->uri->segment(4);
		$id_plant = $this->uri->segment(5);
		$id_plant_folder = $this->uri->segment(6);
		$v_obj = $this->pv_info->getitemobject($id_item_object);
		$title_obj = $v_obj["obj_tag_no"];
		$desc_obj = $v_obj["desc_"];
		
		/*$cekobjatpress = $this->pv_info->cekobjectpressure("tbl_pv_pressure",$id_item_object);
		if($cekobjatpress == 0){
			$url_press = base_url()."pv_pressure/edit/0/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}else{
			$url_press = base_url()."pv_pressure/edit/".$cekobjatpress."/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}

		$cekobjatfeatures = $this->pv_info->cekobjectfeatures("tbl_pv_feature",$id_item_object);
		if($cekobjatfeatures == 0){
			$url_feat = base_url()."pv_feature/edit/0/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}else{
			$url_feat = base_url()."pv_feature/edit/".$cekobjatfeatures."/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}*/

		$cekobjatfeatures = $this->pv_info->cekobject("tbl_pv_feature",$id_item_object);
		if($cekobjatfeatures == 0){
			$url_feat = base_url()."pv_feature/edit/0/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}else{
			$url_feat = base_url()."pv_feature/edit/".$cekobjatfeatures."/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}

		$cekobjatpress = $this->pv_info->cekobject("tbl_pv_pressure",$id_item_object);
		if($cekobjatpress == 0){
			$url_press = base_url()."pv_pressure/edit/0/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}else{
			$url_press = base_url()."pv_pressure/edit/".$cekobjatpress."/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}

		$cekobjatinfo = $this->pv_info->cekobject("tbl_pv_info",$id_item_object);
		if($cekobjatinfo == 0){
			$url_info = base_url()."pv_info/edit/0/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}else{
			$url_info = base_url()."pv_info/edit/".$cekobjatinfo."/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}

		
		if(is_numeric($id)){
		
			#set asset
			$ref2_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			#record
			$q = $this->pv_info->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					$id_item_object = $this->session->flashdata("id_item_object") ? $this->session->flashdata("id_item_object") : $r->id_item_object;
					$title = $this->session->flashdata("title") ? $this->session->flashdata("title") : $r->title;
					$serial_no = $this->session->flashdata("serial_no") ? $this->session->flashdata("serial_no") : $r->serial_no;
					$desc_ = $this->session->flashdata("desc_") ? $this->session->flashdata("desc_") : $r->desc_;
					$id_product = $this->session->flashdata("ref1") ? $this->session->flashdata("ref1") : $r->id_product;
					$id_system = $this->session->flashdata("ref3") ? $this->session->flashdata("ref3") : $r->id_system;
					
					$shell1_nt_mm = $this->session->flashdata("shell1_nt_mm") ? $this->session->flashdata("shell1_nt_mm") : $r->shell1_nt_mm;
					$shell1_nt_in = $this->session->flashdata("shell1_nt_in") ? $this->session->flashdata("shell1_nt_in") : $r->shell1_nt_in;
					$shell1_id_cm = $this->session->flashdata("shell1_id_cm") ? $this->session->flashdata("shell1_id_cm") : $r->shell1_id_cm;
					$shell1_id_in = $this->session->flashdata("shell1_id_in") ? $this->session->flashdata("shell1_id_in") : $r->shell1_id_in;
					
					$shell2_nt_mm = $this->session->flashdata("shell2_nt_mm") ? $this->session->flashdata("shell2_nt_mm") : $r->shell2_nt_mm;
					$shell2_nt_in = $this->session->flashdata("shell2_nt_in") ? $this->session->flashdata("shell2_nt_in") : $r->shell2_nt_in;
					$shell2_id_cm = $this->session->flashdata("shell2_id_cm") ? $this->session->flashdata("shell2_id_cm") : $r->shell2_id_cm;
					$shell2_id_in = $this->session->flashdata("shell2_id_in") ? $this->session->flashdata("shell2_id_in") : $r->shell2_id_in;

					$head1_nt_mm = $this->session->flashdata("head1_nt_mm") ? $this->session->flashdata("head1_nt_mm") : $r->head1_nt_mm;
					$head1_nt_in = $this->session->flashdata("head1_nt_in") ? $this->session->flashdata("head1_nt_in") : $r->head1_nt_in;
					$head1_ir_cm = $this->session->flashdata("head1_ir_cm") ? $this->session->flashdata("head1_ir_cm") : $r->head1_ir_cm;
					$head1_ir_in = $this->session->flashdata("head1_ir_in") ? $this->session->flashdata("head1_ir_in") : $r->head1_ir_in;

					$head2_nt_mm = $this->session->flashdata("head2_nt_mm") ? $this->session->flashdata("head2_nt_mm") : $r->head2_nt_mm;
					$head2_nt_in = $this->session->flashdata("head2_nt_in") ? $this->session->flashdata("head2_nt_in") : $r->head2_nt_in;
					$head2_ir_cm = $this->session->flashdata("head2_ir_cm") ? $this->session->flashdata("head2_ir_cm") : $r->head2_ir_cm;
					$head2_ir_in = $this->session->flashdata("head2_ir_in") ? $this->session->flashdata("head2_ir_in") : $r->head2_ir_in;

					$comm_date = $this->session->flashdata("comm_date") ? $this->session->flashdata("comm_date") : $r->comm_date;
					$design_life = $this->session->flashdata("design_life") ? $this->session->flashdata("design_life") : $r->design_life;
					$retirement_date = $this->session->flashdata("retirement_date") ? $this->session->flashdata("retirement_date") : $r->retirement_date;
					$ext_design_life = $this->session->flashdata("ext_design_life") ? $this->session->flashdata("ext_design_life") : $r->ext_design_life;
					
					#ref dropdown multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : $r->publish;	
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown multi value

					$id = $r->id;

					$list[] = array(
					"id"=>$id,
					"id_item_object"=>$id_item_object,
					"title"=>$title,
					"serial_no"=>$serial_no,
					"desc_"=>$desc_,
					"id_product"=>$id_product,
					"id_system"=>$id_system,
					"shell1_nt_mm"=>$shell1_nt_mm,
					"shell1_nt_in"=>$shell1_nt_in,
					"shell1_id_cm"=>$shell1_id_cm,
					"shell1_id_in"=>$shell1_id_in,
					"shell2_nt_mm"=>$shell2_nt_mm,
					"shell2_nt_in"=>$shell2_nt_in,
					"shell2_id_cm"=>$shell2_id_cm,
					"shell2_id_in"=>$shell2_id_in,
					"head1_nt_mm"=>$head1_nt_mm,
					"head1_nt_in"=>$head1_nt_in,
					"head1_ir_cm"=>$head1_ir_cm,
					"head1_ir_in"=>$head1_ir_in,
					"head2_nt_mm"=>$head2_nt_mm,
					"head2_nt_in"=>$head2_nt_in,
					"head2_ir_cm"=>$head2_ir_cm,
					"head2_ir_in"=>$head2_ir_in,
					"comm_date"=>$comm_date,
					"design_life"=>$design_life,
					"retirement_date"=>$retirement_date,
					"ext_design_life"=>$ext_design_life,
					"create_date"=>$r->create_date,
					"ref2"=>$ref2,
					"id_plant"=>$id_plant,
					"id_plant_folder"=>$id_plant_folder
					);
				}
			}else{

					$id = "";

					//$id_item_object = $this->session->flashdata("id_item_object") ? $this->session->flashdata("id_item_object") : $id_item_object;
					$title = $this->session->flashdata("title") ? $this->session->flashdata("title") : $title_obj;
					$serial_no = $this->session->flashdata("serial_no") ? $this->session->flashdata("serial_no") : null;
					$desc_ = $this->session->flashdata("desc_") ? $this->session->flashdata("desc_") : $desc_obj;
					$id_product = $this->session->flashdata("ref1") ? $this->session->flashdata("ref1") : null;
					$id_system = $this->session->flashdata("ref3") ? $this->session->flashdata("ref3") : null;
					
					$shell1_nt_mm = $this->session->flashdata("shell1_nt_mm") ? $this->session->flashdata("shell1_nt_mm") : null;
					$shell1_nt_in = $this->session->flashdata("shell1_nt_in") ? $this->session->flashdata("shell1_nt_in") : null;
					$shell1_id_cm = $this->session->flashdata("shell1_id_cm") ? $this->session->flashdata("shell1_id_cm") : null;
					$shell1_id_in = $this->session->flashdata("shell1_id_in") ? $this->session->flashdata("shell1_id_in") : null;
					
					$shell2_nt_mm = $this->session->flashdata("shell2_nt_mm") ? $this->session->flashdata("shell2_nt_mm") : null;
					$shell2_nt_in = $this->session->flashdata("shell2_nt_in") ? $this->session->flashdata("shell2_nt_in") : null;
					$shell2_id_cm = $this->session->flashdata("shell2_id_cm") ? $this->session->flashdata("shell2_id_cm") : null;
					$shell2_id_in = $this->session->flashdata("shell2_id_in") ? $this->session->flashdata("shell2_id_in") : null;

					$head1_nt_mm = $this->session->flashdata("head1_nt_mm") ? $this->session->flashdata("head1_nt_mm") : null;
					$head1_nt_in = $this->session->flashdata("head1_nt_in") ? $this->session->flashdata("head1_nt_in") : null;
					$head1_ir_cm = $this->session->flashdata("head1_ir_cm") ? $this->session->flashdata("head1_ir_cm") : null;
					$head1_ir_in = $this->session->flashdata("head1_ir_in") ? $this->session->flashdata("head1_ir_in") : null;

					$head2_nt_mm = $this->session->flashdata("head2_nt_mm") ? $this->session->flashdata("head2_nt_mm") : null;
					$head2_nt_in = $this->session->flashdata("head2_nt_in") ? $this->session->flashdata("head2_nt_in") : null;
					$head2_ir_cm = $this->session->flashdata("head2_ir_cm") ? $this->session->flashdata("head2_ir_cm") : null;
					$head2_ir_in = $this->session->flashdata("head2_ir_in") ? $this->session->flashdata("head2_ir_in") : null;

					$comm_date = $this->session->flashdata("comm_date") ? $this->session->flashdata("comm_date") : null;
					$design_life = $this->session->flashdata("design_life") ? $this->session->flashdata("design_life") : null;
					$retirement_date = $this->session->flashdata("retirement_date") ? $this->session->flashdata("retirement_date") : null;
					$ext_design_life = $this->session->flashdata("ext_design_life") ? $this->session->flashdata("ext_design_life") : null;
					
					#ref dropdown multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : null;	
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown multi value

					$list[] = array(
					"id"=>0,
					"id_item_object"=>$id_item_object,
					"title"=>$title,
					"serial_no"=>$serial_no,
					"desc_"=>$desc_,
					"id_product"=>$id_product,
					"id_system"=>$id_system,
					"shell1_nt_mm"=>$shell1_nt_mm,
					"shell1_nt_in"=>$shell1_nt_in,
					"shell1_id_cm"=>$shell1_id_cm,
					"shell1_id_in"=>$shell1_id_in,
					"shell2_nt_mm"=>$shell2_nt_mm,
					"shell2_nt_in"=>$shell2_nt_in,
					"shell2_id_cm"=>$shell2_id_cm,
					"shell2_id_in"=>$shell2_id_in,
					"head1_nt_mm"=>$head1_nt_mm,
					"head1_nt_in"=>$head1_nt_in,
					"head1_ir_cm"=>$head1_ir_cm,
					"head1_ir_in"=>$head1_ir_in,
					"head2_nt_mm"=>$head2_nt_mm,
					"head2_nt_in"=>$head2_nt_in,
					"head2_ir_cm"=>$head2_ir_cm,
					"head2_ir_in"=>$head2_ir_in,
					"comm_date"=>$comm_date,
					"design_life"=>$design_life,
					"retirement_date"=>$retirement_date,
					"ext_design_life"=>$ext_design_life,
					"create_date"=>"",
					"ref2"=>$ref2,
					"id_plant"=>$id_plant,
					"id_plant_folder"=>$id_plant_folder
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

			#ref dropdown multi value
			$ref1 = $this->getRefDropdownProduct($id_product,1);
			#end ref dropdown multi value

			#ref dropdown multi value
			$ref3 = $this->getRefDropdownSystem($id_system,3);
			#end ref dropdown multi value

			$data = array(
					  'admin_url'=>base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'ref1'=>$ref1,
					  'ref2'=>$ref2,
					  'ref3'=>$ref3,
					  'url_press'=>$url_press,
					  'url_feat'=>$url_feat,
					  'url_info'=>$url_info,
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
		$id_item_object = $this->input->post("id_item_object");
		$desc_ = strip_tags($this->input->post("desc_"));
		$title = strip_tags($this->input->post("title"));
		$serial_no = strip_tags($this->input->post("serial_no"));
		$ref1 = $this->input->post("ref1");
		$ref2 = $this->input->post("ref2");
		$ref3 = $this->input->post("ref3");
		$shell1_nt_mm = $this->input->post("shell1_nt_mm");
		$shell1_nt_in = $this->input->post("shell1_nt_in");
		$shell1_id_cm = $this->input->post("shell1_id_cm");
		$shell1_id_in = $this->input->post("shell1_id_in");
		
		$shell2_nt_mm = $this->input->post("shell2_nt_mm");
		$shell2_nt_in = $this->input->post("shell2_nt_in");
		$shell2_id_cm = $this->input->post("shell2_id_cm");
		$shell2_id_in = $this->input->post("shell2_id_in");

		$head1_nt_mm = $this->input->post("head1_nt_mm");
		$head1_nt_in = $this->input->post("head1_nt_in");
		$head1_ir_cm = $this->input->post("head1_ir_cm");
		$head1_ir_in = $this->input->post("head1_ir_in");

		$head2_nt_mm = $this->input->post("head2_nt_mm");
		$head2_nt_in = $this->input->post("head2_nt_in");
		$head2_ir_cm = $this->input->post("head2_ir_cm");
		$head2_ir_in = $this->input->post("head2_ir_in");

		$comm_date = $this->input->post("comm_date");
		$design_life = $this->input->post("design_life");
		$retirement_date = $this->input->post("retirement_date");
		$ext_design_life = $this->input->post("ext_design_life");
		$id = $this->input->post("id");
		$id_plant = $this->input->post("id_plant");
		$id_plant_folder = $this->input->post("id_plant_folder");
		$user_id = $this->session->userdata('adminID');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('serial_no', 'Serial no', 'required');
		$this->form_validation->set_rules('shell1_nt_mm', 'Shell1 nominal tank (mm)', 'required');
		$this->form_validation->set_rules('shell1_nt_in', 'Shell1 nominal tank (in)', 'required');
		$this->form_validation->set_rules('shell1_id_cm', 'Shell1 Internal diameter (cm)', 'required');
		$this->form_validation->set_rules('shell1_id_in', 'Shell1 Internal diameter (in)', 'required');

		$this->form_validation->set_rules('shell2_nt_mm', 'Shell2 nominal tank (mm)', 'required');
		$this->form_validation->set_rules('shell2_nt_in', 'Shell2 nominal tank (in)', 'required');
		$this->form_validation->set_rules('shell2_id_cm', 'Shell2 Internal diameter (cm)', 'required');
		$this->form_validation->set_rules('shell2_id_in', 'Shell2 Internal diameter (in)', 'required');
	
		$this->form_validation->set_rules('head1_nt_mm', 'Head1 nominal tank (mm)', 'required');
		$this->form_validation->set_rules('head1_nt_in', 'Head1 nominal tank (in)', 'required');
		$this->form_validation->set_rules('head1_ir_cm', 'Head1 internal radius (cm)', 'required');
		$this->form_validation->set_rules('head1_ir_in', 'Head1 internal radius (in)', 'required');

		$this->form_validation->set_rules('head2_nt_mm', 'Head2 nominal tank (mm)', 'required');
		$this->form_validation->set_rules('head2_nt_in', 'Head2 nominal tank (in)', 'required');
		$this->form_validation->set_rules('head2_ir_cm', 'Head2 internal radius (cm)', 'required');
		$this->form_validation->set_rules('head2_ir_in', 'Head2 internal radius (in)', 'required');
		$this->form_validation->set_rules('comm_date', 'Commission date', 'required');
		$this->form_validation->set_rules('design_life', 'Design life', 'required');
		$this->form_validation->set_rules('retirement_date', 'Retirement date', 'required');
	
		if ($this->form_validation->run($this) == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			$this->session->set_flashdata("desc_",$desc_);
			$this->session->set_flashdata("title",$title);
			$this->session->set_flashdata("serial_no",$serial_no);
			$this->session->set_flashdata("shell1_nt_mm",$shell1_nt_mm);
			$this->session->set_flashdata("shell1_nt_in",$shell1_nt_in);
			$this->session->set_flashdata("shell1_id_cm",$shell1_id_cm);
			$this->session->set_flashdata("shell1_id_in",$shell1_id_in);

			$this->session->set_flashdata("shell2_nt_mm",$shell2_nt_mm);
			$this->session->set_flashdata("shell2_nt_in",$shell2_nt_in);
			$this->session->set_flashdata("shell2_id_cm",$shell2_id_cm);
			$this->session->set_flashdata("shell2_id_in",$shell2_id_in);

			$this->session->set_flashdata("head1_nt_mm",$head1_nt_mm);
			$this->session->set_flashdata("head1_nt_in",$head1_nt_in);
			$this->session->set_flashdata("head1_ir_cm",$head1_ir_cm);
			$this->session->set_flashdata("head1_ir_in",$head1_ir_in);

			$this->session->set_flashdata("head2_nt_mm",$head2_nt_mm);
			$this->session->set_flashdata("head2_nt_in",$head2_nt_in);
			$this->session->set_flashdata("head2_ir_cm",$head2_ir_cm);
			$this->session->set_flashdata("head2_ir_in",$head2_ir_in);

			$this->session->set_flashdata("comm_date",$comm_date);
			$this->session->set_flashdata("design_life",$design_life);
			$this->session->set_flashdata("retirement_date",$retirement_date);
			$this->session->set_flashdata("ext_design_life",$ext_design_life);

			$this->session->set_flashdata("ref1",$ref1);
			$this->session->set_flashdata("ref3",$ref3);
			$this->session->set_flashdata("ref2",$ref2);

			redirect($this->table."/edit/".$id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);
		}else{
			if($id > 0)
			{
				$this->pv_info->setUpdate($this->table,$id,$id_item_object,$title,$serial_no,$desc_,$ref1,$ref3,$shell1_nt_mm,$shell1_nt_in,$shell1_id_cm,$shell1_id_in,$shell2_nt_mm,$shell2_nt_in,$shell2_id_cm,$shell2_id_in,$head1_nt_mm,$head1_nt_in,$head1_ir_cm,$head1_ir_in,$head2_nt_mm,$head2_nt_in,$head2_ir_cm,$head2_ir_in,$comm_date,$design_life,$retirement_date,$ext_design_life,$ref2,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				/*redirect($this->table."/edit/".$id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
			}else{
				$id_term = $this->pv_info->setInsert($this->table,$id,$id_item_object,$title,$serial_no,$desc_,$ref1,$ref3,$shell1_nt_mm,$shell1_nt_in,$shell1_id_cm,$shell1_id_in,$shell2_nt_mm,$shell2_nt_in,$shell2_id_cm,$shell2_id_in,$head1_nt_mm,$head1_nt_in,$head1_ir_cm,$head1_ir_in,$head2_nt_mm,$head2_nt_in,$head2_ir_cm,$head2_ir_in,$comm_date,$design_life,$retirement_date,$ext_design_life,$ref2,$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				/*redirect($this->table."/edit/".$last_id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
			}
		}
	}
	

	function delete($id=0)
	{
		$del_status = $this->pv_info->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
	
	function getRefDropdownProduct($id,$name,$type=NULL)
	{
	
		$q = $this->pv_info->getDropdown("tbl_ref_products");
		$list = array();
		foreach ($q->result() as $val) {
			$selected = $val->id == $id ? $selected = "selected='selected'" : "";	
			$list[]= array(
						'id' => $val->id,
						'title'=>ucfirst($val->title),
						"selected"=>$selected
					 );
		}
		$data = array(
				"list"=>$list,
				"name"=>"ref".$name
				);
		return $this->parser->parse("layout/ref_dropdown".$type.".html", $data, TRUE);
	}

	function getRefDropdownSystem($id,$name,$type=NULL)
	{
	
		$q = $this->pv_info->getDropdown("tbl_ref_system");
		$list = array();
		foreach ($q->result() as $val) {
			$selected = $val->id == $id ? $selected = "selected='selected'" : "";	
			$list[]= array(
						'id' => $val->id,
						'title'=>ucfirst($val->title),
						"selected"=>$selected
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