<?php
class model_widget extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->groupID = $this->session->userdata('adminGroupsID');
	}
	
	
	function getList($table,$id)
	{
		$this->db->where($table.".id",$id);
		$query = $this->db->get($table);
		return $query;
	}
	
	function getDropdown($table,$sort){
		if(empty($sort) || is_null($sort)){
			$sort = "ref_title";
		}
		
		if($this->groupID <> 1 && $table == "adminusers_level"){
			$this->db->where($table.".id !=",1);
		}
		
		$this->db->order_by($table.".".$sort,"asc");
		$q = $this->db->get($table);
		return $q;
	}

	function setUpdate($table,$id,$publish,$user_id,$type)
	{
		
		$data = array(
			      $type=>$publish,
			      'modify_user_id'=>$user_id
			      );
		$this->db->where($table.'.id',$id);
		$this->db->update($table,$data);
	}
	
}
?>