<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pv_pressure extends MX_Controller  {
	
	var $table = "pv_pressure";
	var $table_alias = "Pressure vessel Temperature";	
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
		$jum_record = $this->pv_pressure->getTotal($this->table,$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->pv_pressure->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm);
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
		$v_obj = $this->pv_pressure->getitemobject($id_item_object);
		$title_obj = $v_obj["obj_tag_no"];
		$desc_obj = $v_obj["desc_"];
		
		$cekobjatfeatures = $this->pv_pressure->cekobjectfeatures("tbl_pv_info",$id_item_object);
		if($cekobjatfeatures == 0){
			$url_feat = base_url()."pv_feature/edit/0/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}else{
			$url_feat = base_url()."pv_feature/edit/".$cekobjatfeatures."/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}

		
		if(is_numeric($id)){
		
			#set asset
			$ref2_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			#record
			$q = $this->pv_pressure->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					$id_item_object = $this->session->flashdata("id_item_object") ? $this->session->flashdata("id_item_object") : $r->id_item_object;

					$design_press_bar = $this->session->flashdata("design_press_bar") ? $this->session->flashdata("design_press_bar") : $r->design_press_bar;
					$design_press_psi = $this->session->flashdata("design_press_psi") ? $this->session->flashdata("design_press_psi") : $r->design_press_psi;
					$design_press_kpa = $this->session->flashdata("design_press_kpa") ? $this->session->flashdata("design_press_kpa") : $r->design_press_kpa;
					$design_press_kg = $this->session->flashdata("design_press_kg") ? $this->session->flashdata("design_press_kg") : $r->design_press_kg;
					$design_press_nm = $this->session->flashdata("design_press_nm") ? $this->session->flashdata("design_press_nm") : $r->design_press_nm;

					$operating_press_bar = $this->session->flashdata("operating_press_bar") ? $this->session->flashdata("operating_press_bar") : $r->operating_press_bar;
					$operating_press_psi = $this->session->flashdata("operating_press_psi") ? $this->session->flashdata("operating_press_psi") : $r->operating_press_psi;
					$operating_press_kpa = $this->session->flashdata("operating_press_kpa") ? $this->session->flashdata("operating_press_kpa") : $r->operating_press_kpa;
					$operating_press_kg = $this->session->flashdata("operating_press_kg") ? $this->session->flashdata("operating_press_kg") : $r->operating_press_kg;
					$operating_press_nm = $this->session->flashdata("operating_press_nm") ? $this->session->flashdata("operating_press_nm") : $r->operating_press_nm;
					
					$test_press_bar = $this->session->flashdata("test_press_bar") ? $this->session->flashdata("test_press_bar") : $r->test_press_bar;
					$test_press_psi = $this->session->flashdata("test_press_psi") ? $this->session->flashdata("test_press_psi") : $r->test_press_psi;
					$test_press_kpa = $this->session->flashdata("test_press_kpa") ? $this->session->flashdata("test_press_kpa") : $r->test_press_kpa;
					$test_press_kg = $this->session->flashdata("test_press_kg") ? $this->session->flashdata("test_press_kg") : $r->test_press_kg;
					$test_press_nm = $this->session->flashdata("test_press_nm") ? $this->session->flashdata("test_press_nm") : $r->test_press_nm;

					$design_temp_c = $this->session->flashdata("design_temp_c") ? $this->session->flashdata("design_temp_c") : $r->design_temp_c;
					$design_temp_f = $this->session->flashdata("design_temp_f") ? $this->session->flashdata("design_temp_f") : $r->design_temp_f;

					$normal_op_c = $this->session->flashdata("normal_op_c") ? $this->session->flashdata("normal_op_c") : $r->normal_op_c;
					$normal_op_f = $this->session->flashdata("normal_op_f") ? $this->session->flashdata("normal_op_f") : $r->normal_op_f;

					$min_op_c = $this->session->flashdata("min_op_c") ? $this->session->flashdata("min_op_c") : $r->min_op_c;
					$min_op_f = $this->session->flashdata("min_op_f") ? $this->session->flashdata("min_op_f") : $r->min_op_f;

					$max_op_c = $this->session->flashdata("max_op_c") ? $this->session->flashdata("max_op_c") : $r->max_op_c;
					$max_op_f = $this->session->flashdata("max_op_f") ? $this->session->flashdata("max_op_f") : $r->max_op_f;


					
					#ref dropdown multi value
					/*$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : $r->publish;	
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);*/
					#end ref dropdown multi value

					$id = $r->id;

					$list[] = array(
					"id"=>$id,
					"id_item_object"=>$id_item_object,
					"design_press_bar"=>$design_press_bar,
					"design_press_psi"=>$design_press_psi,
					"design_press_kpa"=>$design_press_kpa,
					"design_press_kg"=>$design_press_kg,
					"design_press_nm"=>$design_press_nm,
					"operating_press_bar"=>$operating_press_bar,
					"operating_press_psi"=>$operating_press_psi,
					"operating_press_kpa"=>$operating_press_kpa,
					"operating_press_kg"=>$operating_press_kg,
					"operating_press_nm"=>$operating_press_nm,
					"test_press_bar"=>$test_press_bar,
					"test_press_psi"=>$test_press_psi,
					"test_press_kpa"=>$test_press_kpa,
					"test_press_kg"=>$test_press_kg,
					"test_press_nm"=>$test_press_nm,
					"design_temp_c"=>$design_temp_c,
					"design_temp_f"=>$design_temp_f,
					"normal_op_c"=>$normal_op_c,
					"normal_op_f"=>$normal_op_f,
					"min_op_c"=>$min_op_c,
					"min_op_f"=>$min_op_f,
					"max_op_c"=>$max_op_c,
					"max_op_f"=>$max_op_f,
					"create_date"=>$r->create_date,
					/*"ref2"=>$ref2,*/
					"id_plant"=>$id_plant,
					"id_plant_folder"=>$id_plant_folder
					);
				}
			}else{

					$id = "";

					//$id_item_object = $this->session->flashdata("id_item_object") ? $this->session->flashdata("id_item_object") : $id_item_object;
					$design_press_bar = $this->session->flashdata("design_press_bar") ? $this->session->flashdata("design_press_bar") : null;
					$design_press_psi = $this->session->flashdata("design_press_psi") ? $this->session->flashdata("design_press_psi") : null;
					$design_press_kpa = $this->session->flashdata("design_press_kpa") ? $this->session->flashdata("design_press_kpa") : null;
					$design_press_kg = $this->session->flashdata("design_press_kg") ? $this->session->flashdata("design_press_kg") : null;
					$design_press_nm = $this->session->flashdata("design_press_nm") ? $this->session->flashdata("design_press_nm") : null;

					$operating_press_bar = $this->session->flashdata("operating_press_bar") ? $this->session->flashdata("operating_press_bar") : null;
					$operating_press_psi = $this->session->flashdata("operating_press_psi") ? $this->session->flashdata("operating_press_psi") : null;
					$operating_press_kpa = $this->session->flashdata("operating_press_kpa") ? $this->session->flashdata("operating_press_kpa") : null;
					$operating_press_kg = $this->session->flashdata("operating_press_kg") ? $this->session->flashdata("operating_press_kg") : null;
					$operating_press_nm = $this->session->flashdata("operating_press_nm") ? $this->session->flashdata("operating_press_nm") : null;
					
					$test_press_bar = $this->session->flashdata("test_press_bar") ? $this->session->flashdata("test_press_bar") : null;
					$test_press_psi = $this->session->flashdata("test_press_psi") ? $this->session->flashdata("test_press_psi") : null;
					$test_press_kpa = $this->session->flashdata("test_press_kpa") ? $this->session->flashdata("test_press_kpa") : null;
					$test_press_kg = $this->session->flashdata("test_press_kg") ? $this->session->flashdata("test_press_kg") : null;
					$test_press_nm = $this->session->flashdata("test_press_nm") ? $this->session->flashdata("test_press_nm") : null;

					$design_temp_c = $this->session->flashdata("design_temp_c") ? $this->session->flashdata("design_temp_c") : null;
					$design_temp_f = $this->session->flashdata("design_temp_f") ? $this->session->flashdata("design_temp_f") : null;

					$normal_op_c = $this->session->flashdata("normal_op_c") ? $this->session->flashdata("normal_op_c") : null;
					$normal_op_f = $this->session->flashdata("normal_op_f") ? $this->session->flashdata("normal_op_f") : null;

					$min_op_c = $this->session->flashdata("min_op_c") ? $this->session->flashdata("min_op_c") : null;
					$min_op_f = $this->session->flashdata("min_op_f") ? $this->session->flashdata("min_op_f") : null;

					$max_op_c = $this->session->flashdata("max_op_c") ? $this->session->flashdata("max_op_c") : null;
					$max_op_f = $this->session->flashdata("max_op_f") ? $this->session->flashdata("max_op_f") : null;
					#ref dropdown multi value
					/*$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : null;	
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);*/
					#end ref dropdown multi value

					$list[] = array(
					"id"=>0,
					"id_item_object"=>$id_item_object,
					"design_press_bar"=>$design_press_bar,
					"design_press_psi"=>$design_press_psi,
					"design_press_kpa"=>$design_press_kpa,
					"design_press_kg"=>$design_press_kg,
					"design_press_nm"=>$design_press_nm,
					"operating_press_bar"=>$operating_press_bar,
					"operating_press_psi"=>$operating_press_psi,
					"operating_press_kpa"=>$operating_press_kpa,
					"operating_press_kg"=>$operating_press_kg,
					"operating_press_nm"=>$operating_press_nm,
					"test_press_bar"=>$test_press_bar,
					"test_press_psi"=>$test_press_psi,
					"test_press_kpa"=>$test_press_kpa,
					"test_press_kg"=>$test_press_kg,
					"test_press_nm"=>$test_press_nm,
					"design_temp_c"=>$design_temp_c,
					"design_temp_f"=>$design_temp_f,
					"normal_op_c"=>$normal_op_c,
					"normal_op_f"=>$normal_op_f,
					"min_op_c"=>$min_op_c,
					"min_op_f"=>$min_op_f,
					"max_op_c"=>$max_op_c,
					"max_op_f"=>$max_op_f,
					"create_date"=>"",
					/*"ref2"=>$ref2,*/
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
		
			$data = array(
					  'admin_url'=>base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  /*'ref2'=>$ref2,*/
					  'url_feat'=>$url_feat,
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
		$design_press_bar = $this->input->post("design_press_bar");
		$design_press_psi = $this->input->post("design_press_psi");
		$design_press_kpa = $this->input->post("design_press_kpa");
		$design_press_kg = $this->input->post("design_press_kg");
		$design_press_nm = $this->input->post("design_press_nm");

		$operating_press_bar = $this->input->post("operating_press_bar");
		$operating_press_psi = $this->input->post("operating_press_psi");
		$operating_press_kpa = $this->input->post("operating_press_kpa");
		$operating_press_kg = $this->input->post("operating_press_kg");
		$operating_press_nm = $this->input->post("operating_press_nm");
		
		$test_press_bar = $this->input->post("test_press_bar");
		$test_press_psi = $this->input->post("test_press_psi");
		$test_press_kpa = $this->input->post("test_press_kpa");
		$test_press_kg = $this->input->post("test_press_kg");
		$test_press_nm = $this->input->post("test_press_nm");

		$design_temp_c = $this->input->post("design_temp_c");
		$design_temp_f = $this->input->post("design_temp_f");

		$normal_op_c = $this->input->post("normal_op_c");
		$normal_op_f = $this->input->post("normal_op_f");

		$min_op_c = $this->input->post("min_op_c");
		$min_op_f = $this->input->post("min_op_f");

		$max_op_c = $this->input->post("max_op_c");
		$max_op_f = $this->input->post("max_op_f");
		$id = $this->input->post("id");
		$id_plant = $this->input->post("id_plant");
		$id_plant_folder = $this->input->post("id_plant_folder");
		$user_id = $this->session->userdata('adminID');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('design_press_bar', 'Design Pressure (bar)', 'required');
		$this->form_validation->set_rules('design_press_psi', 'Design Pressure (psi)', 'required');
		$this->form_validation->set_rules('design_press_kpa', 'Design Pressure (kPa)', 'required');
		$this->form_validation->set_rules('design_press_kg', 'Design Pressure (kg/cm2)', 'required');
		$this->form_validation->set_rules('design_press_nm', 'Design Pressure (Pa:N/m2)', 'required');

		$this->form_validation->set_rules('operating_press_bar', 'Operating Pressure (bar)', 'required');
		$this->form_validation->set_rules('operating_press_psi', 'Operating Pressure (psi)', 'required');
		$this->form_validation->set_rules('operating_press_kpa', 'Operating Pressure (kPa)', 'required');
		$this->form_validation->set_rules('operating_press_kg', 'Operating Pressure (kg/cm2)', 'required');
		$this->form_validation->set_rules('operating_press_nm', 'Operating Pressure (Pa:N/m2)', 'required');

		$this->form_validation->set_rules('test_press_bar', 'Test Pressure (bar)', 'required');
		$this->form_validation->set_rules('test_press_psi', 'Test Pressure (psi)', 'required');
		$this->form_validation->set_rules('test_press_kpa', 'Test Pressure (kPa)', 'required');
		$this->form_validation->set_rules('test_press_kg', 'Test Pressure (kg/cm2)', 'required');
		$this->form_validation->set_rules('test_press_nm', 'Test Pressure (Pa:N/m2)', 'required');

		$this->form_validation->set_rules('design_temp_c', 'Design Temperature (C)', 'required');
		$this->form_validation->set_rules('design_temp_f', 'Design Temperature (F)', 'required');

		$this->form_validation->set_rules('normal_op_c', 'Normal Operating Temperature (C)', 'required');
		$this->form_validation->set_rules('normal_op_f', 'Normal Operating Temperature (F)', 'required');

		$this->form_validation->set_rules('min_op_c', 'Minimum Operating Temperature (C)', 'required');
		$this->form_validation->set_rules('min_op_f', 'Minimum Operating Temperature (F)', 'required');

		$this->form_validation->set_rules('max_op_c', 'Miximum Operating Temperature (C)', 'required');
		$this->form_validation->set_rules('max_op_f', 'Miximum Operating Temperature (F)', 'required');
		
	
		if ($this->form_validation->run($this) == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			$this->session->set_flashdata("design_press_bar",$design_press_bar);
			$this->session->set_flashdata("design_press_psi",$design_press_psi);
			$this->session->set_flashdata("design_press_kpa",$design_press_kpa);
			$this->session->set_flashdata("design_press_kg",$design_press_kg);
			$this->session->set_flashdata("design_press_nm",$design_press_nm);

			$this->session->set_flashdata("operating_press_bar",$operating_press_bar);
			$this->session->set_flashdata("operating_press_psi",$operating_press_psi);
			$this->session->set_flashdata("operating_press_kpa",$operating_press_kpa);
			$this->session->set_flashdata("operating_press_kg",$operating_press_kg);
			$this->session->set_flashdata("operating_press_nm",$operating_press_nm);

			$this->session->set_flashdata("test_press_bar",$test_press_bar);
			$this->session->set_flashdata("test_press_psi",$test_press_psi);
			$this->session->set_flashdata("test_press_kpa",$test_press_kpa);
			$this->session->set_flashdata("test_press_kg",$test_press_kg);
			$this->session->set_flashdata("test_press_nm",$test_press_nm);

			$this->session->set_flashdata("design_temp_c",$design_temp_c);
			$this->session->set_flashdata("design_temp_f",$design_temp_f);

			$this->session->set_flashdata("normal_op_c",$normal_op_c);
			$this->session->set_flashdata("normal_op_f",$normal_op_f);

			$this->session->set_flashdata("min_op_c",$min_op_c);
			$this->session->set_flashdata("min_op_f",$min_op_f);

			$this->session->set_flashdata("max_op_c",$max_op_c);
			$this->session->set_flashdata("max_op_f",$max_op_f);

			redirect($this->table."/edit/".$id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);
		}else{
			if($id > 0)
			{
				$this->pv_pressure->setUpdate($this->table,$id,$id_item_object,$design_press_bar,$design_press_psi,$design_press_kpa,$design_press_kg,$design_press_nm,$operating_press_bar,$operating_press_psi,$operating_press_kpa,$operating_press_kg,$operating_press_nm,$test_press_bar,$test_press_psi,$test_press_kpa,$test_press_kg,$test_press_nm,$design_temp_c,$design_temp_f,$normal_op_c,$normal_op_f,$min_op_c,$min_op_f,$max_op_c,$max_op_f,'publish',$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				/*redirect($this->table."/edit/".$id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
			}else{
				$id_term = $this->pv_pressure->setInsert($this->table,$id,$id_item_object,$design_press_bar,$design_press_psi,$design_press_kpa,$design_press_kg,$design_press_nm,$operating_press_bar,$operating_press_psi,$operating_press_kpa,$operating_press_kg,$operating_press_nm,$test_press_bar,$test_press_psi,$test_press_kpa,$test_press_kg,$test_press_nm,$design_temp_c,$design_temp_f,$normal_op_c,$normal_op_f,$min_op_c,$min_op_f,$max_op_c,$max_op_f,'publish',$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				/*redirect($this->table."/edit/".$last_id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
			}
		}
	}
	

	function delete($id=0)
	{
		$del_status = $this->pv_pressure->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
	
	function getRefDropdownProduct($id,$name,$type=NULL)
	{
	
		$q = $this->pv_pressure->getDropdown("tbl_ref_products");
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
	
		$q = $this->pv_pressure->getDropdown("tbl_ref_system");
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