<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_object extends MX_Controller  {
	
	var $table = "item_object";
	var $table_alias = "Object";
	var $uri_page = 9;
	var $per_page = 25;
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model("".$this->table."/model_".$this->table, $this->table);
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


	function grid($id_plant_folder=NULL)
	{		
		$contents = $this->grid_content($id_plant_folder);
	
		$data = array(
				  'admin_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
	}
	
	
	
	function grid_content($id_plant_folder=NULL)
	{	
		#search
		$sch1_parm = rawurldecode($this->uri->segment(4));
		$sch1_parm = $sch1_parm != 'null' && !empty($sch1_parm) ? $sch1_parm : 'null';
		$sch1_val = $sch1_parm != 'null' ? $sch1_parm : '';
		
		$sch2_parm = rawurldecode($this->uri->segment(5));
		$sch2_parm = $sch2_parm != 'null' && !empty($sch2_parm) ? $sch2_parm : 'null';
		$sch2_select_arr[0] = $sch2_parm;
		$sch2_arr = array(
							"Not Publish"=>"Not Publish",
							"Publish"=>"Publish"
						  );
		$ref2 = Modules::run('widget/getStaticDropdown',$sch2_arr,$sch2_select_arr,2);

		/*$sch3_parm = rawurldecode($this->uri->segment(5));
		$sch3_parm = $sch3_parm != 'null' && !empty($sch3_parm) ? $sch3_parm : 'null';
		$ref3 = Modules::run('item_object/getFolderDropdown',$sch3_parm,3);
*/
		$sch3_parm = rawurldecode($this->uri->segment(6));
		$sch3_parm = $sch3_parm != 'null' && !empty($sch3_parm) ? $sch3_parm : 'null';
		$ref3 = Modules::run('item_object/getItemDropdown',$sch3_parm,3);

		$sch4_parm = rawurldecode($this->uri->segment(7));
		$sch4_parm = $sch4_parm != 'null' && !empty($sch4_parm) ? $sch4_parm : 'null';
		$ref4 = Modules::run('item_object/getObjectsDropdown',$sch4_parm,4);

		$sch_path = rawurlencode($sch1_parm)."/".rawurlencode($sch2_parm)."/".rawurlencode($sch3_parm)."/".rawurlencode($sch4_parm);

		#end search

		#paging
		$get_page = $this->uri->segment(8);
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
		$path = base_url().$this->table."/pages/".$id_plant_folder."/".$sch1_parm."/".$sch2_parm."/".$sch3_parm."/".$sch4_parm."/".$per_page;
		$jum_record = $this->item_object->getTotal($id_plant_folder,$this->table,$sch1_parm,$sch2_parm,$sch3_parm,$sch4_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->item_object->getList($id_plant_folder,$this->table,$per_page,$lmt,$sch1_parm,$sch2_parm,$sch3_parm,$sch4_parm);
		$list = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$zebra = $no % 2 == 0 ? "zebra" : "";

				$item = ucwords($this->item_object->getRefTitle('tbl_ref_items',$r->id_ref_item));
				$object = ucwords($this->item_object->getRefTitle('tbl_ref_objects',$r->id_ref_objects));
				$object_tag_no = ucwords($r->obj_tag_no);
				$management_id = ucwords($r->management_id);
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date = date("d/m/Y H:i:s",strtotime($r->create_date));
			
				$list[] = array(
								 "no"=>$no,
								 "id"=>$r->id,
								 "title" =>$object_tag_no,
								 "id_ref_item" =>$item,
								 "id_ref_objects" =>$object,
								 "management_id" =>$management_id,
								 "publish"=>$publish,
								 "create_date"=>$create_date
								);
			}
		}	
		#end record

		$id_plant = $this->item_object->getPlant('tbl_plant_folder',$id_plant_folder);
	
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
				  'sch4_parm'=>$sch4_parm,
				  'ref2'=>$ref2,
				  'ref3'=>$ref3,
				  'ref4'=>$ref4,
				  'sch_path'=>$sch_path,
				  'per_page'=>$per_page,
				  'pg'=>$go_pg,
				  'id_plant_folder'=>$id_plant_folder,
				  'id_plant'=>$id_plant,
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
		$sch4 = rawurlencode($this->input->post('ref4'));
		$id_plant_folder = rawurlencode($this->input->post('id_plant_folder'));
		
		$per_page = rawurlencode($this->input->post('per_page'));
		
		$sch1 = empty($sch1) ? 'null' : $sch1;
		$sch2 = empty($sch2) ? 'null' : $sch2;
		$sch3 = empty($sch3) ? 'null' : $sch3;
		$sch4 = empty($sch4) ? 'null' : $sch4;
		
		echo base_url().$this->table."/pages/".$id_plant_folder."/".$sch1."/".$sch2."/".$sch3."/".$sch4."/".$per_page;
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
		$id_plant_folder = $this->uri->segment(5);
		$id_plant = $this->uri->segment(4);
		$id_plant_title = $this->item_object->getPlant('tbl_plant_folder',$id_plant_folder);
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
				
					$id_plant_folder = $r->id_plant_folder;
					$id_ref_item = $r->id_ref_item;
					$id_ref_objects = $r->id_ref_objects;
					$id_eq_cat = $r->id_eq_cat;
					$id_ex_type = $r->id_ex_type;
					$id = $r->id;

					$list[] = array(
									"id"=>$r->id,
									"id_plant_folder"=>$r->id_plant_folder,
									"id_ref_item"=>$r->id_ref_item,
									"id_ref_objects"=>$r->id_ref_objects,
									"obj_tag_no"=>$r->obj_tag_no,
									"title"=>$r->obj_tag_no,
									"management_id"=>$r->management_id,
									"desc_"=>$r->desc_,
									"desc_non"=>$r->desc_,
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
				
				$id_plant_folder = $id_plant_folder;
				$id_eq_cat = $id = $id_ex_type = $id_ref_item = $id_ref_objects ="";
				
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
									"id_plant_folder"=>$id_plant_folder,
									"id_ref_item"=>2,
									"id_ref_objects"=>"",
									"obj_tag_no"=>"",
									"title"=>"",
									"management_id"=>"",
									"desc_"=>"",
									"desc_non"=>"",
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
			$ref8 = $this->getDropdownFolder($id_plant_folder,8);
			#end ref dropdown multi value

			#ref dropdown multi value
			$ref9 = $this->getDropdownEqcat($id_eq_cat,9);
			#end ref dropdown multi value

			#ref dropdown multi value
			$ref10 = $this->getDropdownExtype($id_ex_type,10);
			#end ref dropdown multi value

			#ref dropdown multi value
			//$ref11 = $this->getDropdownItem($id_ref_item,11);
			#end ref dropdown multi value

			#ref dropdown multi value
			$ref12 = $this->getDropdownObjects($id_ref_objects,12);
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
					  'ref10'=>$ref10,
					  //'ref11'=>$ref11,
					  'ref12'=>$ref12,
					  'id_plant_folder'=>$id_plant_folder,
					  'id_plant_title'=>$id_plant_title,
					  'id_plant'=>$id_plant
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{
		$err = "";
		$id_plant_folder = $this->input->post("id_plant_folder");
		/*$id_ref_item = $this->input->post("ref11");*/
		$id_ref_item = $this->input->post("id_ref_item");
		$id_ref_objects = $this->input->post("ref12");
		$id_eq_cat = $this->input->post("ref9");
		$id_ex_type = $this->input->post("ref10");
		$obj_tag_no = ($id_ref_item == 2) ? strip_tags($this->input->post("obj_tag_no")) : strip_tags($this->input->post("title"));
		$management_id = $this->input->post("management_id");
		$desc_ = ($id_ref_item == 2) ? $this->input->post("desc_") : $this->input->post("desc_non");
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
		$id_plant = $this->input->post("id_plant");
		
		$this->load->library('form_validation');
		
		($id_ref_item == 2) ? $this->form_validation->set_rules('obj_tag_no', 'Object Tag No', 'required') : $this->form_validation->set_rules('title', 'Title', 'required');
			
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			redirect($this->table."/edit/".$id."/".$id_plant."/".$id_plant_folder);
		}else{
			if($id > 0)
			{
				$this->item_object->setUpdate($this->table,$id,$id_plant_folder,$id_ref_item,$id_ref_objects,$obj_tag_no,$management_id,$desc_,$drawing_ref,$sheet,$rev,$miss_physical_tag,$miss_virtual_tag,$id_eq_cat,$ex_service,$id_ex_type,$cmms_status,$work_order,$publish,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				/*redirect($this->table."/edit/".$id."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
			}else{
				if($id_ref_item == 2){
					$id_term = $this->item_object->setInsert($this->table,$id,$id_plant_folder,$id_ref_item,$id_ref_objects,$obj_tag_no,$management_id,$desc_,$drawing_ref,$sheet,$rev,$miss_physical_tag,$miss_virtual_tag,$id_eq_cat,$ex_service,$id_ex_type,$cmms_status,$work_order,$publish,$user_id);
					$last_id = $this->db->insert_id();
				}else{
					$id_term = $this->item_object->setInsertFolder('tbl_plant_folder',$id_plant,$id_plant_folder,$obj_tag_no,$desc_,$publish,$user_id);
					$last_id = $this->db->insert_id();
				}
					
				$this->session->set_flashdata("success","Data inserted successful");
				/*redirect($this->table."/edit/".$last_id."/".$id_plant."/".$id_plant_folder);*/
				redirect('plant');
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
			$this->item_object->ajaxsort($this->table,$val,$order);
		}
	}
	
	
	function delete($id=0)
	{
		$del_status = $this->item_object->setDelete($this->table,$id);
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
	
	function getDropdownFolder($parent_id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_plant_folder');
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
						'title'=>ucwords($val->title)." - ".ucwords($val->desc_),
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

	function getDropdownItem($parent_id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_ref_items');
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

	function getDropdownObjects($parent_id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_ref_objects');
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

	function getFolderDropdown($id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_plant_folder',null,null,null);
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

	function getItemDropdown($id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_ref_items',null,null,null);
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

	function getObjectsDropdown($id,$name,$type=NULL)
	{
		$q = $this->item_object->getRefList('tbl_ref_objects',null,null,null);
		$list = array();
		foreach ($q->result() as $val) {

			$selected = $val->id == $id ? $selected = "selected='selected'" : "";
			
			$list[] = array(
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

/* End of file item_object.php */
/* Location: ./application/modules/controller/item_object.php */