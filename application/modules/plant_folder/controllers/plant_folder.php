<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plant_folder extends MX_Controller  {
	
	var $table = "plant_folder";
	var $table_alias = "Folder";
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


	function grid($id_plant=NULL)
	{
		$this->setheader();		
		$contents = $this->grid_content($id_plant);
	
		$data = array(
				  'admin_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
		
		$this->setfooter();
	}

	function grid_content($id_plant=NULL)
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

		/*$sch3_parm = rawurldecode($this->uri->segment(6));
		$sch3_parm = $sch3_parm != 'null' && !empty($sch3_parm) ? $sch3_parm : 'null';
		$ref3 = Modules::run('plant_folder/getPlantDropdown',$sch3_parm,3);*/
		$sch_path = rawurlencode($sch1_parm)."/".rawurlencode($sch2_parm);
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
		$path = base_url().$this->table."/pages/".$id_plant."/".$sch1_parm."/".$sch2_parm."/".$per_page;
		$jum_record = $this->plant_folder->getTotal($id_plant,$this->table,$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->plant_folder->getList($id_plant,$this->table,$per_page,$lmt,$sch1_parm,$sch2_parm);
		$list = $list_obj = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$zebra = $no % 2 == 0 ? "zebra" : "";
				
				$title = ucwords($r->title);
				$plant_title = ucwords($this->plant_folder->getPlantTitle('tbl_plant',$r->id_plant));
				$title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date = date("d/m/Y H:i:s",strtotime($r->create_date));

				$qobj = $this->plant_folder->getListObject('tbl_item_object',$r->id);
				$qf_num = $qobj->num_rows();
				if($qobj->num_rows() > 0){
					foreach ($qobj->result() as $f) {
						$ftitle = ucwords($f->obj_tag_no);
						$icon = ($f->id_ref_item == 1) ? '<i class="icon-folder-close"></i>' : "";
						$list_obj[] = array("fid"=>$f->id,"ftitle" =>$ftitle,"ficon" =>$icon);
					}
				}
			
				$list[] = array(
								 "no"=>$no,
								 "id"=>$r->id,
								 "title" =>$title,
								 "plant" =>$plant_title,
								 "id_plant" =>$id_plant,
								 "publish"=>$publish,
								 "create_date"=>$create_date,
								 "list_obj"=>$list_obj
								);
				$list_obj = array();
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
				  /*'sch3_parm'=>$sch3_parm,*/
				  'ref2'=>$ref2,
				  /*'ref3'=>$ref3,*/
				  'sch_path'=>$sch_path,
				  'per_page'=>$per_page,
				  'pg'=>$go_pg,
				  'id_plant'=>$id_plant,
				  'title_head'=>ucfirst(str_replace('_',' ',ucwords($this->plant_folder->getPlantTitle('tbl_plant',$id_plant)))),
				  'title_link'=>$this->table
				  );
		return $this->parser->parse("list.html", $data, TRUE);
	}
	
	function search()
	{
		$sch1 = rawurlencode($this->input->post('sch1'));
		$sch2 = rawurlencode($this->input->post('ref2'));
		$id_plant = rawurlencode($this->input->post('id_plant'));
		//$sch3 = rawurlencode($this->input->post('ref3'));

		$per_page = rawurlencode($this->input->post('per_page'));
		
		$sch1 = empty($sch1) ? 'null' : $sch1;
		$sch2 = empty($sch2) ? 'null' : $sch2;
		//$sch3 = empty($sch3) ? 'null' : $sch3;
		
		redirect($this->table."/pages/".$id_plant."/".$sch1."/".$sch2."/".$per_page);
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
		$id_plant = $this->uri->segment(4);
		$id_parent = $this->uri->segment(5);
		$number = 0;
		$file_image = "";
		
		if(is_numeric($id)){
		
			#set asset
			$ref3_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			#record
			$q = $this->plant_folder->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
								
					#ref dropdown multi value					
					$ref3_select_arr[0] = $r->publish;
					$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,$ref3_select_arr,3);
					#end ref dropdown multi value
				
					$id_plant = $r->id_plant;
					$id_parent = $r->id_parent;
					$id = $r->id;

					$list[] = array(
									"id"=>$r->id,
									"id_plant"=>$r->id_plant,
									"id_parent"=>$r->id_parent,
									"title"=>$r->title,
									"desc_"=>$r->desc_,
									"create_date"=>$r->create_date,
									"ref3"=>$ref3
									);
				}
			}else{
				
				$id = "";
				
				#ref dropdown multi value
				/*$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,null,2);*/
				#end ref dropdown multi value
				
				#ref dropdown multi value
				$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,null,3);
				#end ref dropdown multi value
				
				$list[] = array(
									"id"=>0,
									"id_plant"=>$id_plant,
									"id_parent"=>$id_parent,
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
			//echo $id_plant;
			#ref dropdown multi value
			$ref1 = $this->getDropdownPlant($id_plant,1);
			#end ref dropdown multi value
			
			$data = array(
					  'admin_url' => base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'plant'=>ucwords($this->plant_folder->getPlantTitle('tbl_plant',$id_plant)),
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table,
					  'ref1'=>$ref1,
					  'id_plant'=>$id_plant,
					  'id_parent'=>$id_parent
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{
		$err = "";
		$id_plant = $this->input->post("id_plant");
		$id_parent = $this->input->post("id_parent");
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
				$this->plant_folder->setUpdate($this->table,$id,$id_plant,$id_parent,$title,$desc_,$is_publish,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				/*redirect($this->table."/edit/".$id_plant."/".$id);*/
				redirect('plant');
			}else{				
				$id_term = $this->plant_folder->setInsert($this->table,$id,$id_plant,$id_parent,$title,$desc_,$is_publish,$user_id);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				/*redirect($this->table."/edit/".$id_plant."/".$last_id);*/
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
	
	function getDropdownPlant($parent_id,$name,$type=NULL)
	{
		$q = $this->plant_folder->getPlantList('tbl_plant');
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
	
	function getPlantDropdown($id,$name,$type=NULL)
	{
		$q = $this->plant_folder->getPlantlist('tbl_plant',null,null,null);
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