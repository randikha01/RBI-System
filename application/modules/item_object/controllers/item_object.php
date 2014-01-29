<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_object extends MX_Controller  {
	
	var $table = "item_object";
	var $table_alias = "Object";
	var $uri_page = 7;
	var $per_page = 25;
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model("".$this->table."/model_".$this->table, $this->table);
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
		$sch2_select_arr[0] = $sch2_parm;
		$sch2_arr = array(
							"Not Publish"=>"Not Publish",
							"Publish"=>"Publish"
						  );
		$ref2 = Modules::run('widget/getStaticDropdown',$sch2_arr,$sch2_select_arr,2);

		$sch3_parm = rawurldecode($this->uri->segment(5));
		$sch3_parm = $sch3_parm != 'null' && !empty($sch3_parm) ? $sch3_parm : 'null';
		$ref3 = Modules::run('item_object/getItemDropdown',$sch3_parm,3);
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
		$jum_record = $this->item_object->getTotal($this->table,$sch1_parm,$sch2_parm,$sch3_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->item_object->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm,$sch3_parm);
		$list = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$zebra = $no % 2 == 0 ? "zebra" : "";
				$item_title = ucwords($this->item_object->getRefTitle('tbl_plant_fol_item',$r->id_plant_fol_item));
				$object_tag_no = ucwords($r->obj_tag_no);
				$management_id = ucwords($r->management_id);
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date = date("d/m/Y H:i:s",strtotime($r->create_date));
			
				$list[] = array(
								 "no"=>$no,
								 "id"=>$r->id,
								 "title" =>$object_tag_no,
								 "id_plant_fol_item" =>$item_title,
								 "management_id" =>$management_id,
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
		
		redirect($this->table."/pages/".$sch1."/".$sch2."/".$sch3.$per_page);
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
		
			#set asset
			$ref3_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			$ref4_arr = array("no"=>"No","yes"=>"Yes");
			$ref5_arr = array("no"=>"No","yes"=>"Yes");
			$ref6_arr = array("no"=>"No","yes"=>"Yes");
			$ref7_arr = array("no"=>"No","yes"=>"Yes");
			
			#record
			$q = $this->item_object->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
								
					#ref dropdown multi value					
					$ref3_select_arr[0] = $r->publish;
					$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,$ref3_select_arr,3);
					#end ref dropdown multi value

					#ref dropdown multi value					
					$ref4_select_arr[0] = $r->miss_physical_tag;
					$ref4 = Modules::run('widget/getStaticDropdown',$ref4_arr,$ref4_select_arr,4);
					#end ref dropdown multi value

					#ref dropdown multi value					
					$ref5_select_arr[0] = $r->miss_virtual_tag;
					$ref5 = Modules::run('widget/getStaticDropdown',$ref5_arr,$ref5_select_arr,5);
					#end ref dropdown multi value

					#ref dropdown multi value					
					$ref6_select_arr[0] = $r->ex_service;
					$ref6 = Modules::run('widget/getStaticDropdown',$ref6_arr,$ref6_select_arr,6);
					#end ref dropdown multi value

					#ref dropdown multi value					
					$ref7_select_arr[0] = $r->work_order;
					$ref7 = Modules::run('widget/getStaticDropdown',$ref7_arr,$ref7_select_arr,7);
					#end ref dropdown multi value
				
					$id_plant_fol_item = $r->id_plant_fol_item;
					$id_eq_cat = $r->id_eq_cat;
					$id_ex_type = $r->id_ex_type;
					$id = $r->id;

					$list[] = array(
									"id"=>$r->id,
									"id_plant_fol_item"=>$r->id_plant_fol_item,
									"obj_tag_no"=>$r->obj_tag_no,
									"management_id"=>$r->management_id,
									"desc_"=>$r->desc_,
									"drawing_ref"=>$r->drawing_ref,
									"sheet"=>$r->sheet,
									"rev"=>$r->rev,
									"ref4"=>$ref4,
									"ref5"=>$ref5,
									"id_eq_cat"=>$r->id_eq_cat,
									"ref6"=>$ref6,
									"id_ex_type"=>$r->id_ex_type,
									"cmms_status"=>$r->cmms_status,
									"ref7"=>$ref7,
									"create_date"=>$r->create_date,
									"ref3"=>$ref3
									);
				}
			}else{
				
				$id_plant_fol_item = $id_eq_cat = $id = $id_ex_type = "";
				
				#ref dropdown multi value
				$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,null,3);
				#end ref dropdown multi value

				#ref dropdown multi value					
				$ref4 = Modules::run('widget/getStaticDropdown',$ref4_arr,null,4);
				#end ref dropdown multi value

				#ref dropdown multi value					
				$ref5 = Modules::run('widget/getStaticDropdown',$ref5_arr,null,5);
				#end ref dropdown multi value

				#ref dropdown multi value					
				$ref6 = Modules::run('widget/getStaticDropdown',$ref6_arr,null,6);
				#end ref dropdown multi value

				#ref dropdown multi value					
				$ref7 = Modules::run('widget/getStaticDropdown',$ref7_arr,null,7);
				#end ref dropdown multi value
				
				$list[] = array(
									"id"=>0,
									"id_plant_fol_item"=>"",
									"obj_tag_no"=>"",
									"management_id"=>"",
									"desc_"=>"",
									"drawing_ref"=>"",
									"sheet"=>"",
									"rev"=>"",
									"ref4"=>$ref4,
									"ref5"=>$ref5,
									"id_eq_cat"=>"",
									"ref6"=>$ref6,
									"id_ex_type"=>"",
									"cmms_status"=>"",
									"ref7"=>$ref7,
									"create_date"=>"",
									"ref3"=>$ref3
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
			$ref8 = $this->getDropdownItem($id_plant_fol_item,8);
			#end ref dropdown multi value

			#ref dropdown multi value
			$ref9 = $this->getDropdownEqcat($id_eq_cat,9);
			#end ref dropdown multi value

			#ref dropdown multi value
			$ref10 = $this->getDropdownExtype($id_ex_type,10);
			#end ref dropdown multi value
			
			$data = array(
					  'admin_url' => base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table,
					  'ref8'=>$ref8,
					  'ref9'=>$ref9,
					  'ref10'=>$ref10
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{

		$err = "";
		$id_plant_fol_item = $this->input->post("ref8");
		$id_eq_cat = $this->input->post("ref9");
		$id_ex_type = $this->input->post("ref10");
		$obj_tag_no = strip_tags($this->input->post("obj_tag_no"));
		$management_id = $this->input->post("management_id");
		$desc_ = $this->input->post("desc_");
		$drawing_ref = $this->input->post("drawing_ref");
		$sheet = $this->input->post("sheet");
		$rev = $this->input->post("rev");
		$miss_physical_tag = $this->input->post("ref4");
		$miss_virtual_tag = $this->input->post("ref5");
		$ex_service = $this->input->post("ref6");
		$cmms_status = $this->input->post("cmms_status");
		$work_order = $this->input->post("ref7");

		$publish = $this->input->post("ref3");
		$user_id = $this->session->userdata('adminID');
		$id = strip_tags($this->input->post("id"));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('obj_tag_no', 'Object Tag No', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			redirect($this->table."/edit/".$id);
		}else{
			if($id > 0)
			{
				$this->item_object->setUpdate($this->table,$id,$id_plant_fol_item,$obj_tag_no,$management_id,$desc_,$drawing_ref,$sheet,$rev,$miss_physical_tag,$miss_virtual_tag,$id_eq_cat,$ex_service,$id_ex_type,$cmms_status,$work_order,$publish,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				redirect($this->table."/edit/".$id);
			}else{				
				$id_term = $this->item_object->setInsert($this->table,$id,$id_plant_fol_item,$obj_tag_no,$management_id,$desc_,$drawing_ref,$sheet,$rev,$miss_physical_tag,$miss_virtual_tag,$id_eq_cat,$ex_service,$id_ex_type,$cmms_status,$work_order,$publish,$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				redirect($this->table."/edit/".$last_id);
			}
		}
	}
	
	
	function ajaxsort()
	{
		$post = $this->input->post('data');
		$order =  $this->input->post('index_order');
		foreach($post as $val)
		{
			$order++;
			$this->plant_folder->ajaxsort($this->table,$val,$order);
		}
	}
	
	
	function delete($id=0)
	{
		$del_status = $this->plant_folder->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
	function unlink($id,$file_image)
	{
		$this->db->where("id",$id);
		$this->db->update($this->table,array("file_image"=>""));
		unlink("uploads/".$file_image);
		redirect($this->table."/edit/".$id);
	}
	
	function getDropdownItem($parent_id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_plant_fol_item');
		$list = array();
		foreach ($q->result() as $val) {
			$selected = $val->id == $parent_id ? $selected = "selected='selected'" : "tidak";	
			$list[]= array(
						'id' => $val->id,
						'title'=>ucwords($val->title),
						"selected"=>$selected
					 );
		}
		$data = array(
				"list"=>$list,
				"name"=>"ref".$name
				);
		return $this->parser->parse("layout/ref_dropdown".$type.".html", $data, TRUE);
	}

	function getDropdownEqcat($parent_id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_ref_equipment_cat');
		$list = array();
		foreach ($q->result() as $val) {
			$selected = $val->id == $parent_id ? $selected = "selected='selected'" : "tidak";	
			$list[]= array(
						'id' => $val->id,
						'title'=>ucwords($val->title),
						"selected"=>$selected
					 );
		}
		$data = array(
				"list"=>$list,
				"name"=>"ref".$name
				);
		return $this->parser->parse("layout/ref_dropdown".$type.".html", $data, TRUE);
	}

	function getDropdownExtype($parent_id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_ref_ex_type');
		$list = array();
		foreach ($q->result() as $val) {
			$selected = $val->id == $parent_id ? $selected = "selected='selected'" : "tidak";	
			$list[]= array(
						'id' => $val->id,
						'title'=>ucwords($val->title),
						"selected"=>$selected
					 );
		}
		$data = array(
				"list"=>$list,
				"name"=>"ref".$name
				);
		return $this->parser->parse("layout/ref_dropdown".$type.".html", $data, TRUE);
	}

	
	
	function getItemDropdown($id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_plant_fol_item',null,null,null);
		$list = array();
		foreach ($q->result() as $val) {

			$selected = $val->id == $id ? $selected = "selected='selected'" : "";	
			
			$list[]= array(
						'id' => $val->id,
						'title'=>ucwords($val->title),
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

/* End of file plant_folder.php */
/* Location: ./application/modules/controller/plant_folder.php */