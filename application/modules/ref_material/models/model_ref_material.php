<?php
class Model_ref_material extends CI_Model {
	
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
		$this->db->where($table.".publish","Publish");
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
	
	function setUpdate($table,$id,$desc_,$title,$ref1,$user_id)
	{

		$data = array(
				  "desc_"=>$desc_,
			      "title"=>$title,
				  "publish"=>$ref1,
				  "modify_user_id"=>$user_id
			      );
		$this->db->where('id',$id);
		$this->db->update($table,$data);

	}
	
	function setInsert($table,$id,$desc_,$title,$ref1,$user_id)
	{

		$data = array(
				  "desc_"=>$desc_,
			      "title"=>$title,
				  "publish"=>$ref1,
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
	
	
}
?>