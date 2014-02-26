<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pv_feature extends MX_Controller  {
	
	var $table = "pv_feature";
	var $table_alias = "Pressure vessel feature";	
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
		$jum_record = $this->pv_feature->getTotal($this->table,$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->pv_feature->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm);
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
		
		$cekobjatshell = $this->pv_feature->cekobjectshell("tbl_pv_pressure",$id_item_object);
		if($cekobjatshell == 0){
			$url_shell = base_url()."pv_shell/edit/0/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}else{
			$url_shell = base_url()."pv_shell/edit/".$cekobjatshell."/".$id_item_object."/".$id_plant."/".$id_plant_folder;
		}

		
		if(is_numeric($id)){
		
			#set asset
			$ref2_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			#record
			$q = $this->pv_feature->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					$id_item_object = $this->session->flashdata("id_item_object") ? $this->session->flashdata("id_item_object") : $r->id_item_object;
					$id_ref_insulation = $this->session->flashdata("ref1") ? $this->session->flashdata("ref1") : $r->id_ref_insulation;
					$ins_thick_mm = $this->session->flashdata("ins_thick_mm") ? $this->session->flashdata("ins_thick_mm") : $r->ins_thick_mm;
					$ins_thick_in = $this->session->flashdata("ins_thick_in") ? $this->session->flashdata("ins_thick_in") : $r->ins_thick_in;
					$id_ref_coating_in = $this->session->flashdata("ref3") ? $this->session->flashdata("ref3") : $r->id_ref_coating_in;
					$id_ref_coating_ex = $this->session->flashdata("ref4") ? $this->session->flashdata("ref4") : $r->id_ref_coating_ex;
					
					$ves_length_m = $this->session->flashdata("ves_length_m") ? $this->session->flashdata("ves_length_m") : $r->ves_length_m;
					$ves_length_f = $this->session->flashdata("ves_length_f") ? $this->session->flashdata("ves_length_f") : $r->ves_length_f;
					$trace_heating = $this->session->flashdata("trace_heating") ? $this->session->flashdata("trace_heating") : $r->trace_heating;
					$cath_protected = $this->session->flashdata("cath_protected") ? $this->session->flashdata("cath_protected") : $r->cath_protected;
					$sur_area_cm = $this->session->flashdata("sur_area_cm") ? $this->session->flashdata("sur_area_cm") : $r->sur_area_cm;
					$sur_area_in = $this->session->flashdata("sur_area_in") ? $this->session->flashdata("sur_area_in") : $r->sur_area_in;
					
					$num_nozzle = $this->session->flashdata("num_nozzle") ? $this->session->flashdata("num_nozzle") : $r->num_nozzle;
					$id_ref_criticality = $this->session->flashdata("ref5") ? $this->session->flashdata("ref5") : $r->id_ref_criticality;
					$draw_ref = $this->session->flashdata("draw_ref") ? $this->session->flashdata("draw_ref") : $r->draw_ref;
					
					#ref dropdown multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : $r->publish;	
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown multi value

					$id = $r->id;

					$this->db->where('tbl_pv_deterioration.id_pv_feature',$id);
					$qdeterioration = $this->db->get('tbl_pv_deterioration');
					$deterioration_arr = $qdeterioration->result_array();
					
					$id_ref_deterioration = "";
					foreach ($deterioration_arr as $detr) {
						$id_ref_deterioration .= $detr['id_ref_deterioration'].",";
					}

					$this->db->where('tbl_pv_corrosion.id_pv_feature',$id);
					$qcorrosion = $this->db->get('tbl_pv_corrosion');
					$corrosion_arr = $qcorrosion->result_array();
					
					$id_ref_corrosion = "";
					foreach ($corrosion_arr as $detr) {
						$id_ref_corrosion .= $detr['id_ref_corrosion'].",";
					}

					$list[] = array(
					"id"=>$id,
					"id_item_object"=>$id_item_object,
					"id_ref_insulation"=>$id_ref_insulation,
					"ins_thick_mm"=>$ins_thick_mm,
					"ins_thick_in"=>$ins_thick_in,
					"id_ref_coating_in"=>$id_ref_coating_in,
					"id_ref_coating_ex"=>$id_ref_coating_ex,
					"ves_length_m"=>$ves_length_m,
					"ves_length_f"=>$ves_length_f,
					"trace_heating"=>$trace_heating,
					"cath_protected"=>$cath_protected,
					"sur_area_cm"=>$sur_area_cm,
					"sur_area_in"=>$sur_area_in,
					"num_nozzle"=>$num_nozzle,
					"id_ref_criticality"=>$id_ref_criticality,
					"draw_ref"=>$draw_ref,
					"create_date"=>$r->create_date,
					"ref2"=>$ref2,
					"id_plant"=>$id_plant,
					"id_plant_folder"=>$id_plant_folder
					);
				}
			}else{

					$id = "";

					//$id_item_object = $this->session->flashdata("id_item_object") ? $this->session->flashdata("id_item_object") : $id_item_object;
					$id_ref_insulation = $this->session->flashdata("ref1") ? $this->session->flashdata("ref1") : null;
					$ins_thick_mm = $this->session->flashdata("ins_thick_mm") ? $this->session->flashdata("ins_thick_mm") : null;
					$ins_thick_in = $this->session->flashdata("ins_thick_in") ? $this->session->flashdata("ins_thick_in") : null;
					$id_ref_coating_in = $this->session->flashdata("ref3") ? $this->session->flashdata("ref3") : null;
					$id_ref_coating_ex = $this->session->flashdata("ref4") ? $this->session->flashdata("ref4") : null;
					
					$ves_length_m = $this->session->flashdata("ves_length_m") ? $this->session->flashdata("ves_length_m") : null;
					$ves_length_f = $this->session->flashdata("ves_length_f") ? $this->session->flashdata("ves_length_f") : null;
					$trace_heating = $this->session->flashdata("trace_heating") ? $this->session->flashdata("trace_heating") : null;
					$cath_protected = $this->session->flashdata("cath_protected") ? $this->session->flashdata("cath_protected") : null;
					$sur_area_cm = $this->session->flashdata("sur_area_cm") ? $this->session->flashdata("sur_area_cm") : null;
					$sur_area_in = $this->session->flashdata("sur_area_in") ? $this->session->flashdata("sur_area_in") : null;
					
					$num_nozzle = $this->session->flashdata("num_nozzle") ? $this->session->flashdata("num_nozzle") : null;
					$id_ref_criticality = $this->session->flashdata("ref5") ? $this->session->flashdata("ref5") : null;
					$draw_ref = $this->session->flashdata("draw_ref") ? $this->session->flashdata("draw_ref") : null;
					
					$id_ref_deterioration = "";
					$id_ref_corrosion = "";

					#ref dropdown multi value
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : null;	
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown multi value

					$list[] = array(
					"id"=>0,
					"id_item_object"=>$id_item_object,
					"id_ref_insulation"=>$id_ref_insulation,
					"ins_thick_mm"=>$ins_thick_mm,
					"ins_thick_in"=>$ins_thick_in,
					"id_ref_coating_in"=>$id_ref_coating_in,
					"id_ref_coating_ex"=>$id_ref_coating_ex,
					"ves_length_m"=>$ves_length_m,
					"ves_length_f"=>$ves_length_f,
					"trace_heating"=>$trace_heating,
					"cath_protected"=>$cath_protected,
					"sur_area_cm"=>$sur_area_cm,
					"sur_area_in"=>$sur_area_in,
					"num_nozzle"=>$num_nozzle,
					"id_ref_criticality"=>$id_ref_criticality,
					"draw_ref"=>$draw_ref,
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

			$ref1 = $this->getRefDropdownInsulation($id_ref_insulation,1);
			$ref3 = $this->getRefDropdownCoating($id_ref_coating_in,3);
			$ref4 = $this->getRefDropdownCoating($id_ref_coating_ex,4);
			$ref5 = $this->getRefDropdownCriticality($id_ref_criticality,5);
			$ref6 = $this->getRefDropdownDeterioration($id_ref_deterioration,6,"_multiple");
			$ref7 = $this->getRefDropdownCorrosion($id_ref_corrosion,7,"_multiple");

		
			$data = array(
					  'admin_url'=>base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'ref1'=>$ref1,
					  'ref2'=>$ref2,
					  'ref3'=>$ref3,
					  'ref4'=>$ref4,
					  'ref5'=>$ref5,
					  'ref6'=>$ref6,
					  'ref7'=>$ref7,
					  'url_shell'=>$url_shell,
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
		$ref1 = $this->input->post("ref1");
		$ins_thick_mm = $this->input->post("ins_thick_mm");
		$ins_thick_in = $this->input->post("ins_thick_in");
		$ref3 = $this->input->post("ref3");
		$ref4 = $this->input->post("ref4");
		$ves_length_m = $this->input->post("ves_length_m");
		$ves_length_f = $this->input->post("ves_length_f");
		$trace_heating = $this->input->post("trace_heating");
		$cath_protected = $this->input->post("cath_protected");
		$sur_area_cm = $this->input->post("sur_area_cm");
		$sur_area_in = $this->input->post("sur_area_in");
		$num_nozzle = $this->input->post("num_nozzle");
		$ref5 = $this->input->post("ref5");
		$draw_ref = $this->input->post("draw_ref");

		$ref6 = $this->input->post("ref6");
		$ref7 = $this->input->post("ref7");

		$ref2 = $this->input->post("ref2");
		
		$id = $this->input->post("id");
		$id_plant = $this->input->post("id_plant");
		$id_plant_folder = $this->input->post("id_plant_folder");
		$user_id = $this->session->userdata('adminID');

		$this->load->library('form_validation');
		/*"id_item_object"=>$id_item_object,
					"id_ref_insulation"=>$id_ref_insulation,
					"ins_thick_mm"=>$ins_thick_mm,
					"ins_thick_in"=>$ins_thick_in,
					"id_ref_coating_in"=>$id_ref_coating_in,
					"id_ref_coating_ex"=>$id_ref_coating_ex,
					"ves_length_m"=>$ves_length_m,
					"ves_length_f"=>$ves_length_f,
					"trace_heating"=>$trace_heating,
					"cath_protected"=>$cath_protected,
					"sur_area_cm"=>$sur_area_cm,
					"sur_area_in"=>$sur_area_in,
					"num_nozzle"=>$num_nozzle,
					"id_ref_criticality"=>$id_ref_criticality,
					"draw_ref"=>$draw_ref,*/
		
		$this->form_validation->set_rules('ref1', 'Insulation Type', 'required');
		$this->form_validation->set_rules('ins_thick_mm', 'Insulation Thickness (mm)', 'required');
		$this->form_validation->set_rules('ins_thick_in', 'Insulation Thickness (in)', 'required');
		$this->form_validation->set_rules('ref3', 'Internal Coating Type', 'required');
		$this->form_validation->set_rules('ref4', 'External Coating Type', 'required');
		$this->form_validation->set_rules('ves_length_m', 'Vessel Length (m)', 'required');
		$this->form_validation->set_rules('ves_length_f', 'Vessel Length (ft)', 'required');
		$this->form_validation->set_rules('sur_area_cm', 'Approx Surface Area (cm2)', 'required');
		$this->form_validation->set_rules('sur_area_in', 'Approx Surface Area (in2)', 'required');
		$this->form_validation->set_rules('num_nozzle', 'Number of Nozzles', 'required');
		$this->form_validation->set_rules('ref5', 'Criticality Rating', 'required');
		$this->form_validation->set_rules('draw_ref', 'Drawing Reference', 'required');
		$this->form_validation->set_rules('ref6', 'Deterioration Mechanisms', 'required');
		$this->form_validation->set_rules('ref7', 'Corrosion Control', 'required');
	
		if ($this->form_validation->run($this) == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			$this->session->set_flashdata("ins_thick_mm",$ins_thick_mm);
			$this->session->set_flashdata("ins_thick_in",$ins_thick_in);
			$this->session->set_flashdata("ves_length_m",$ves_length_m);
			$this->session->set_flashdata("ves_length_f",$ves_length_f);
			$this->session->set_flashdata("sur_area_cm",$sur_area_cm);
			$this->session->set_flashdata("sur_area_in",$sur_area_in);
			$this->session->set_flashdata("num_nozzle",$num_nozzle);
			$this->session->set_flashdata("draw_ref",$draw_ref);
			

			$this->session->set_flashdata("ref1",$ref1);
			$this->session->set_flashdata("ref3",$ref3);
			$this->session->set_flashdata("ref2",$ref2);
			$this->session->set_flashdata("ref4",$ref4);
			$this->session->set_flashdata("ref5",$ref5);
			$this->session->set_flashdata("ref6",$ref6);
			$this->session->set_flashdata("ref7",$ref7);

			redirect($this->table."/edit/".$id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);
		}else{
			if($id > 0)
			{
				
				$this->pv_feature->setUpdate($this->table,$id,$id_item_object,$ref1,$ins_thick_mm,$ins_thick_in,$ref3,$ref4,$ves_length_m,$ves_length_f,$trace_heating,$cath_protected,$sur_area_cm,$sur_area_in,$num_nozzle,$ref5,$draw_ref,$ref2,$user_id);

				/*Update Deterioration*/
				$this->db->where('tbl_pv_deterioration.id_pv_feature', $id);
				$this->db->delete('tbl_pv_deterioration');
				foreach($ref6 as $ref6_val){
					$this->db->where('tbl_pv_deterioration.id_ref_deterioration',$ref6_val);
					$this->db->where('tbl_pv_deterioration.id_pv_feature',$id);
					$qsectors = $this->db->get('tbl_pv_deterioration');
					if($qsectors->num_rows() == 0){
						$datasectors = array(
							      'id_ref_deterioration'=>$ref6_val,
							      'id_pv_feature'=>$id,
							      'user_id'=>$user_id,
							      'create_date'=>date("Y-m-d :H:i:s",now())
							      );
						$this->db->insert('tbl_pv_deterioration',$datasectors);
					}
				}

				/*Update Corrosion*/
				$this->db->where('tbl_pv_corrosion.id_pv_feature', $id);
				$this->db->delete('tbl_pv_corrosion');
				foreach($ref7 as $ref7_val){
					$this->db->where('tbl_pv_corrosion.id_ref_corrosion',$ref7_val);
					$this->db->where('tbl_pv_corrosion.id_pv_feature',$id);
					$qsectors = $this->db->get('tbl_pv_corrosion');
					if($qsectors->num_rows() == 0){
						$datasectors = array(
							      'id_ref_corrosion'=>$ref7_val,
							      'id_pv_feature'=>$id,
							      'user_id'=>$user_id,
							      'create_date'=>date("Y-m-d :H:i:s",now())
							      );
						$this->db->insert('tbl_pv_corrosion',$datasectors);
					}
				}

				$this->session->set_flashdata("success","Data saved successful");
				/*redirect($this->table."/edit/".$id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
			}else{
				$id_term = $this->pv_feature->setInsert($this->table,$id,$id_item_object,$ref1,$ins_thick_mm,$ins_thick_in,$ref3,$ref4,$ves_length_m,$ves_length_f,$trace_heating,$cath_protected,$sur_area_cm,$sur_area_in,$num_nozzle,$ref5,$draw_ref,$ref2,$user_id);
				$last_id = $this->db->insert_id();

				/*Add Deterioration*/
				foreach($ref6 as $ref6_val){
					$this->db->where('tbl_pv_deterioration.id_ref_deterioration',$ref6_val);
					$this->db->where('tbl_pv_deterioration.id_pv_feature',$last_id);
					$qdeterioration = $this->db->get('tbl_pv_deterioration');
					if($qdeterioration->num_rows() == 0){
						$dataservices = array(
							      'id_ref_deterioration'=>$ref6_val,
							      'id_pv_feature'=>$last_id,
							      'user_id'=>$user_id,
							      'create_date'=>date("Y-m-d :H:i:s",now())
							      );
						$this->db->insert('tbl_pv_deterioration',$dataservices);
					}
				}

				/*Add corrosion*/
				foreach($ref7 as $ref7_val){
					$this->db->where('tbl_pv_corrosion.id_ref_corrosion',$ref7_val);
					$this->db->where('tbl_pv_corrosion.id_pv_feature',$last_id);
					$qcorrosion = $this->db->get('tbl_pv_corrosion');
					if($qcorrosion->num_rows() == 0){
						$dataservices = array(
							      'id_ref_corrosion'=>$ref7_val,
							      'id_pv_feature'=>$last_id,
							      'user_id'=>$user_id,
							      'create_date'=>date("Y-m-d :H:i:s",now())
							      );
						$this->db->insert('tbl_pv_corrosion',$dataservices);
					}
				}
				
				$this->session->set_flashdata("success","Data inserted successful");
				/*redirect($this->table."/edit/".$last_id."/".$id_item_object."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
			}
		}
	}
	

	function delete($id=0)
	{
		$del_status = $this->pv_feature->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
	
	function getRefDropdownInsulation($id,$name,$type=NULL)
	{
	
		$q = $this->pv_feature->getDropdown("tbl_ref_insulation");
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

	function getRefDropdownCoating($id,$name,$type=NULL)
	{
	
		$q = $this->pv_feature->getDropdown("tbl_ref_coating");
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

	function getRefDropdownCriticality($id,$name,$type=NULL)
	{
		$q = $this->pv_feature->getDropdown("tbl_ref_criticality");
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

	function getRefDropdownDeterioration($id,$name,$type=NULL)
	{
		$q = $this->pv_feature->getDropdown("tbl_ref_deterioration");
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

	function getRefDropdownCorrosion($id,$name,$type=NULL)
	{
		$q = $this->pv_feature->getDropdown("tbl_ref_corrosion");
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