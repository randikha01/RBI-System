<?php
class Model_pv_pressure extends CI_Model {
	
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
	
	function setUpdate($table,$id,$id_item_object,$design_press_bar,$design_press_psi,$design_press_kpa,$design_press_kg,$design_press_nm,$operating_press_bar,$operating_press_psi,$operating_press_kpa,$operating_press_kg,$operating_press_nm,$test_press_bar,$test_press_psi,$test_press_kpa,$test_press_kg,$test_press_nm,$design_temp_c,$design_temp_f,$normal_op_c,$normal_op_f,$min_op_c,$min_op_f,$max_op_c,$max_op_f,$ref2,$user_id)
	{

		/*$data = array(
				  "title"=>$title,
				  "desc_"=>$desc_,
				  "publish"=>$ref1,
				  "modify_user_id"=>$user_id
			      );*/

		$data = array(
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
			      "publish"=>$ref2,
				  "modify_user_id"=>$user_id,
			      "modify_date"=>date("Y-m-d :H:i:s",now())
			      );
		$this->db->where('id',$id);
		$this->db->update($table,$data);

	}
	
	function setInsert($table,$id,$id_item_object,$design_press_bar,$design_press_psi,$design_press_kpa,$design_press_kg,$design_press_nm,$operating_press_bar,$operating_press_psi,$operating_press_kpa,$operating_press_kg,$operating_press_nm,$test_press_bar,$test_press_psi,$test_press_kpa,$test_press_kg,$test_press_nm,$design_temp_c,$design_temp_f,$normal_op_c,$normal_op_f,$min_op_c,$min_op_f,$max_op_c,$max_op_f,$ref2,$user_id)
	{
		$data = array(
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

	function cekobjectfeatures($table,$id_item_object=NULL){
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