<?php
class Model_pv_feature extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getTotal($table,$sch1_parm,$sch2_parm)
	{
		if($sch1_parm != 'null' && !empty($sch1_parm) )
		{
			$this->db->like($table.'.title',$sch1_parm);
		}
	
		if($sch2_parm != 'null' && !empty($sch2_parm) )
		{
			$this->db->where($table.'.publish',$sch2_parm);
		}
		
		$this->db->select("COUNT(id) AS total");
		$query = $this->db->get($table);
		$r = $query->row();
		return $r->total;
	}
	
	function getList($table,$per_page,$lmt,$sch1_parm,$sch2_parm)
	{
		if($sch1_parm != 'null' && !empty($sch1_parm) )
		{
			$this->db->like($table.'.title',$sch1_parm);
		}
	
		if($sch2_parm != 'null' && !empty($sch2_parm) )
		{
			$this->db->where($table.'.publish',$sch2_parm);
		}
		
		$this->db->order_by($table.".title","asc");
		$query = $this->db->get($table,$per_page,$lmt);

		return $query;
	}
	
	function getDropdown($table)
	{
		//$this->db->where($table.".publish","Publish");
		$this->db->order_by($table.".title","asc");
		$query = $this->db->get($table);
		return $query;
	}

	function getDetail($table,$id)
	{
		$this->db->where($table.'.id',$id);
		$query = $this->db->get($table);
		return $query;
	}
	
	function getMulti($table,$table_multi,$id)
	{
		$this->db->join($table_multi,$table_multi.".id=".$table.".".$table_multi."_id");
		$this->db->where($table_multi.".id",$id);
		$query = $this->db->get($table);
		return $query;
	}
	
	function setUpdate($table,$id,$id_item_object,$ref1,$ins_thick_mm,$ins_thick_in,$ref3,$ref4,$ves_length_m,$ves_length_f,$trace_heating,$cath_protected,$sur_area_cm,$sur_area_in,$num_nozzle,$ref5,$draw_ref,$ref2,$user_id)
	{
		$data = array(
			"id_item_object"=>$id_item_object,
			"id_ref_insulation"=>$ref1,
			"ins_thick_mm"=>$ins_thick_mm,
			"ins_thick_in"=>$ins_thick_in,
			"id_ref_coating_in"=>$ref3,
			"id_ref_coating_ex"=>$ref4,
			"ves_length_m"=>$ves_length_m,
			"ves_length_f"=>$ves_length_f,
			"trace_heating"=>$trace_heating,
			"cath_protected"=>$cath_protected,
			"sur_area_cm"=>$sur_area_cm,
			"sur_area_in"=>$sur_area_in,
			"num_nozzle"=>$num_nozzle,
			"id_ref_criticality"=>$ref5,
			"draw_ref"=>$draw_ref,
			"publish"=>$ref2,
			"modify_user_id"=>$user_id,
			"modify_date"=>date("Y-m-d :H:i:s",now())
		);
		$this->db->where('id',$id);
		$this->db->update($table,$data);

	}
	
	function setInsert($table,$id,$id_item_object,$ref1,$ins_thick_mm,$ins_thick_in,$ref3,$ref4,$ves_length_m,$ves_length_f,$trace_heating,$cath_protected,$sur_area_cm,$sur_area_in,$num_nozzle,$ref5,$draw_ref,$ref2,$user_id)
	{
		$data = array(
			"id_item_object"=>$id_item_object,
			"id_ref_insulation"=>$ref1,
			"ins_thick_mm"=>$ins_thick_mm,
			"ins_thick_in"=>$ins_thick_in,
			"id_ref_coating_in"=>$ref3,
			"id_ref_coating_ex"=>$ref4,
			"ves_length_m"=>$ves_length_m,
			"ves_length_f"=>$ves_length_f,
			"trace_heating"=>$trace_heating,
			"cath_protected"=>$cath_protected,
			"sur_area_cm"=>$sur_area_cm,
			"sur_area_in"=>$sur_area_in,
			"num_nozzle"=>$num_nozzle,
			"id_ref_criticality"=>$ref5,
			"draw_ref"=>$draw_ref,
			"publish"=>$ref2,
			"user_id"=>$user_id,
			"create_date"=>date("Y-m-d :H:i:s",now())
		);
		$this->db->insert($table,$data);
		$last_id = $this->db->insert_id();
		$this->session->set_flashdata('last_id',$last_id);

		return $last_id;

	}
	

	function setDelete($table,$id)
	{
		$status = 0;
		#select first
		$this->db->where('id',$id);
		$this->db->where('publish','Publish');
		$query = $this->db->get($table);
		if($query->num_rows() == 0){
			$this->db->where('id',$id);
			$this->db->delete($table);

			$status = 1;
		}
		return $status;
	}

	function getitemobject($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('tbl_item_object');
		if($query->num_rows() > 0){
			return $query->row_array();
			/*$v = array(
				"obj_tag_no"=>$q->obj_tag_no,
				"desc_"=>$q->desc,
				); */
		}else{
			return NULL;
		}
	}

	function cekobjectshell($table,$id_item_object=NULL){
		$this->db->where("id_item_object",$id_item_object);
		$q = $this->db->get($table);
		if($q->num_rows() > 0){
			$v = $q->row();
			return $v->id;
		}else{
			return 0;
		}
	}
	
	
}
?>