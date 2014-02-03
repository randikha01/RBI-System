<?php
class model_item_object extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->groupID = $this->session->userdata('adminGroupsID');
	}
	
	
	function getTotal($table,$search1_parm,$search2_parm,$search3_parm)
	{
		if($search1_parm != 'null' && !empty($search1_parm) )
		{
			$this->db->like($table.'.object_tag_no',$search1_parm);
		}
		if($search2_parm != 'null' && !empty($search2_parm) )
		{
			$this->db->where($table.'.publish',$search2_parm);
		}
		if($search3_parm != 'null' && !empty($search3_parm) )
		{
			$this->db->where($table.'.id_plant_fol_item',$search3_parm);
		}
	
		if($this->groupID <> 1){
			$this->db->where($table.".id !=",6);
		}
		$this->db->select("COUNT(id) AS total");
		$query = $this->db->get($table);
		$r = $query->row();
		return $r->total;
	}
	
	
	function getList($table,$per_page,$lmt,$search1_parm,$search2_parm,$search3_parm)
	{
		if($search1_parm != 'null' && !empty($search1_parm) )
		{
			$this->db->like($table.'.object_tag_no',$search1_parm);
		}
		if($search2_parm != 'null' && !empty($search2_parm) )
		{
			$this->db->where($table.'.publish',$search2_parm);
		}
		if($search3_parm != 'null' && !empty($search3_parm) )
		{
			$this->db->where($table.'.id_plant_fol_item',$search3_parm);
		}
		
	
		if($this->groupID <> 1){
			$this->db->where($table.".id !=",6);
		}
		$this->db->order_by('create_date','desc');
		$query = $this->db->get($table,$per_page,$lmt);
		return $query;
	}
	
	
	function getRefTitle($table,$id)
	{
		$parent_title = "#";
		$this->db->select("title");
		$this->db->where('id',$id);
		$query = $this->db->get($table);
		$num = $query->num_rows();
		if($num > 0 ){
			$r = $query->row();
			$parent_title = $r->title;
		}
		return $parent_title;
	}
	
	function getRefList($table)
	{
		$this->db->order_by($table.'.title','asc');
		$query = $this->db->get($table);
		return $query;
	}

	function getDetail($table,$id)
	{
		$this->db->where($table.'.id',$id);
		$query = $this->db->get($table);
		return $query;
	}
	
	function ajaxsort($table,$id,$order)
	{
			$data = array(
					"ordered"=>$order
					);
			$this->db->where("id",$id);
			$this->db->update($table,$data);
	}
	
	
	function setUpdate($table,$id,$id_plant_fol_item,$obj_tag_no,$management_id,$desc_,$drawing_ref,$sheet,$rev,$miss_physical_tag,$miss_virtual_tag,$id_eq_cat,$ex_service,$id_ex_type,$cmms_status,$work_order,$publish,$user_id)
	{	
		$data = array(
			      'id_plant_fol_item'=>$id_plant_fol_item,
				  'obj_tag_no'=>$obj_tag_no,
				  'management_id'=>$management_id,
				  'desc_'=>$desc_,
				  'drawing_ref'=>$drawing_ref,
				  'sheet'=>$sheet,
				  'rev'=>$rev,
				  'miss_physical_tag'=>$miss_physical_tag,
				  'miss_virtual_tag'=>$miss_virtual_tag,
				  'id_eq_cat'=>$id_eq_cat,
				  'ex_service'=>$ex_service,
				  'id_ex_type'=>$id_ex_type,
				  'cmms_status'=>$cmms_status,
				  'work_order'=>$work_order,
				  'publish'=>$publish,
			      'modify_user_id'=>$user_id
			      );
		$this->db->where('id',$id);
		$this->db->update($table,$data);
	}
	
	/*$this->table,$id,$id_plant_fol_item,$obj_tag_no,$management_id,$desc_,$drawing_ref,$sheet,$rev,$miss_physical_tag,$miss_virtual_tag,$id_eq_cat,$ex_service,$id_ex_type,$cmms_status,$work_order,$publish,$user_id*/
	function setInsert($table,$id,$id_plant_fol_item,$obj_tag_no,$management_id,$desc_,$drawing_ref,$sheet,$rev,$miss_physical_tag,$miss_virtual_tag,$id_eq_cat,$ex_service,$id_ex_type,$cmms_status,$work_order,$publish,$user_id)
	{
		$data = array(
			      'id_plant_fol_item'=>$id_plant_fol_item,
				  'obj_tag_no'=>$obj_tag_no,
				  'management_id'=>$management_id,
				  'desc_'=>$desc_,
				  'drawing_ref'=>$drawing_ref,
				  'sheet'=>$sheet,
				  'rev'=>$rev,
				  'miss_physical_tag'=>$miss_physical_tag,
				  'miss_virtual_tag'=>$miss_virtual_tag,
				  'id_eq_cat'=>$id_eq_cat,
				  'ex_service'=>$ex_service,
				  'id_ex_type'=>$id_ex_type,
				  'cmms_status'=>$cmms_status,
				  'work_order'=>$work_order,
				  'publish'=>$publish,
			      'user_id'=>$user_id,
			      'create_date'=>date("Y-m-d :H:i:s",now())
			      );
		$this->db->insert($table,$data);
	}
	
	
	function setDelete($table,$id)
	{
		$status = 0;
		#select first
		$this->db->where('id',$id);
		$this->db->where('publish','Publish');
		$query = $this->db->get($table);
		if($query->num_rows() == 0){
		
			#check menu in menu auth
			$this->db->where('menu_id',$id);
			$q1 = $this->db->get('menu_auth');
			if($q1->num_rows() == 0){
				$status = 1;
			}else{
				$status = 2;
			}
			
			if($status == 1){
				$this->db->where('id',$id);
				$this->db->delete($table);
			}
		}
		return $status;
	}
	
	function setFileUpload($file_image,$file_image_tmp,$file_image_old)
	{
		$this->load->library('image_moo');

 		$this->image_moo->load($file_image_tmp)->resize_crop(150,150)->save("./uploads/".$file_image);
   		
   			if(!$file_image)
			{
			 	$file_image = $file_image_old;
			}

			return $file_image;
	}
	
	function getMax($table)
	{
		$this->db->select_max('ordered','max_ordered');
		$query = $this->db->get($table);
		return $query;
	}
}
?>