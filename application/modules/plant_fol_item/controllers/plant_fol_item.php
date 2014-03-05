<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plant_fol_item extends MX_Controller  {
	
	var $table = "plant_fol_item";
	var $table_alias = "Plant Folder Object";
	var $uri_page = 8;
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
		$ref3 = Modules::run('plant_fol_item/getFolderDropdown',$sch3_parm,3);
		$sch_path = rawurlencode($sch1_parm)."/".rawurlencode($sch2_parm)."/".rawurlencode($sch3_parm);

		$sch4_parm = rawurldecode($this->uri->segment(6));
		$sch4_parm = $sch4_parm != 'null' && !empty($sch4_parm) ? $sch4_parm : 'null';
		$ref4 = Modules::run('plant_fol_item/getEquipmentDropdown',$sch4_parm,4);
		$sch_path = rawurlencode($sch1_parm)."/".rawurlencode($sch2_parm)."/".rawurlencode($sch3_parm)."/".rawurlencode($sch4_parm);
		#end search

		#paging
		$get_page = $this->uri->segment(7);
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
		$path = base_url().$this->table."/pages/".$sch1_parm."/".$sch2_parm."/".$sch3_parm."/".$sch4_parm."/".$per_page;
		$jum_record = $this->plant_fol_item->getTotal($this->table,$sch1_parm,$sch2_parm,$sch3_parm,$sch4_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->plant_fol_item->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm,$sch3_parm,$sch4_parm);
		$list = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$zebra = $no % 2 == 0 ? "zebra" : "";
				
				$title = ucwords($r->title);
				$folder_title = ucwords($this->plant_fol_item->getRefTitle('tbl_plant_folder',$r->id_plant_folder));
				$equipment_title = ucwords($this->plant_fol_item->getRefTitle('tbl_ref_objects',$r->id_ref_objects));
				$title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date = date("d/m/Y H:i:s",strtotime($r->create_date));
			
				$list[] = array(
								 "no"=>$no,
								 "id"=>$r->id,
								 "title" =>$title,
								 "id_plant_folder" =>$folder_title,
								 "id_ref_objects" =>$equipment_title,
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
				  'sch4_parm'=>$sch4_parm,
				  'ref2'=>$ref2,
				  'ref3'=>$ref3,
				  'ref4'=>$ref4,
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
		$sch4 = rawurlencode($this->input->post('ref4'));

		$per_page = rawurlencode($this->input->post('per_page'));
		
		$sch1 = empty($sch1) ? 'null' : $sch1;
		$sch2 = empty($sch2) ? 'null' : $sch2;
		$sch3 = empty($sch3) ? 'null' : $sch3;
		$sch4 = empty($sch4) ? 'null' : $sch4;
		
		echo base_url().$this->table."/pages/".$sch1."/".$sch2."/".$sch3."/".$sch4."/".$per_page;
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
			
			#record
			$q = $this->plant_fol_item->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
								
					#ref dropdown multi value					
					$ref3_select_arr[0] = $r->publish;
					$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,$ref3_select_arr,3);
					#end ref dropdown multi value
				
					$id_plant_folder = $r->id_plant_folder;
					$id_ref_objects = $r->id_ref_objects;
					$id = $r->id;

					$list[] = array(
									"id"=>$r->id,
									"id_plant_folder"=>$r->id_plant_folder,
									"id_ref_objects"=>$r->id_ref_objects,
									"title"=>$r->title,
									"desc_"=>$r->desc_,
									"create_date"=>$r->create_date,
									"ref3"=>$ref3
									);
				}
			}else{
				
				$id_plant_folder = $id_ref_objects = $id = "";
				
				#ref dropdown multi value
				/*$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,null,2);*/
				#end ref dropdown multi value
				
				#ref dropdown multi value
				$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,null,3);
				#end ref dropdown multi value
				
				$list[] = array(
									"id"=>0,
									"id_plant_folder"=>"",
									"id_ref_objects"=>"",
									"title"=>"",
									"desc_"=>"",
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
			$ref1 = $this->getDropdownFolder($id_plant_folder,1);
			#end ref dropdown multi value

			#ref dropdown multi value
			$ref2 = $this->getDropdownEquipment($id_ref_objects,2);
			#end ref dropdown multi value
			
			$data = array(
					  'admin_url' => base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table,
					  'ref1'=>$ref1,
					  'ref2'=>$ref2
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{
		$err = "";
		$id_plant_folder = $this->input->post("ref1");
		$id_ref_objects = $this->input->post("ref2");
		$title = strip_tags($this->input->post("title"));
		$desc_ = $this->input->post("desc_");
		$is_publish = $this->input->post("ref3");
		$user_id = $this->session->userdata('adminID');
		$id = strip_tags($this->input->post("id"));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('desc_', 'Description', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata("err",validation_errors());
			redirect($this->table."/edit/".$id);
		}else{
			if($id > 0)
			{
				$this->plant_fol_item->setUpdate($this->table,$id,$id_plant_folder,$id_ref_objects,$title,$desc_,$is_publish,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				redirect($this->table."/edit/".$id);
			}else{				
				$id_term = $this->plant_fol_item->setInsert($this->table,$id,$id_plant_folder,$id_ref_objects,$title,$desc_,$is_publish,$user_id);
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
			$this->plant_fol_item->ajaxsort($this->table,$val,$order);
		}
	}
	
	
	function delete($id=0)
	{
		$del_status = $this->plant_fol_item->setDelete($this->table,$id);
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
		$q = $this->plant_fol_item->getRefList('tbl_plant_folder');
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

	function getDropdownEquipment($parent_id,$name,$type=NULL)
	{
		$q = $this->plant_fol_item->getRefList('tbl_ref_objects');
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
		$q = $this->plant_fol_item->getRefList('tbl_plant_folder',null,null,null);
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

	function getEquipmentDropdown($id,$name,$type=NULL)
	{
		$q = $this->plant_fol_item->getRefList('tbl_ref_objects',null,null,null);
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

/* End of file plant_fol_item.php */
/* Location: ./application/modules/controller/plant_fol_item.php */