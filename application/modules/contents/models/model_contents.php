<?php
class model_contents extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getTotal($table,$search1_parm,$search2_parm)
	{
		if($search1_parm != 'null' && !empty($search1_parm) )
		{
			$this->db->like($table.'.title',$search1_parm);
		}
		if($search1_parm != 'null' && !empty($search1_parm) )
		{
			$this->db->like($table.'.content',$search1_parm);
		}
		
		if($search2_parm != 'null' && !empty($search2_parm) )
		{
			$this->db->where($table.'.publish',$search2_parm);
		}
		
		$this->db->select("COUNT(id) AS total");
		$query = $this->db->get($table);
		$r = $query->row();
		return $r->total;
	}
	
	function getList($table,$per_page,$lmt,$search1_parm,$search2_parm)
	{
		if($search1_parm != 'null' && !empty($search1_parm) )
		{
			$this->db->like($table.'.title',$search1_parm);
		}
		if($search1_parm != 'null' && !empty($search1_parm) )
		{
			$this->db->like($table.'.content',$search1_parm);
		}
		
		if($search2_parm != 'null' && !empty($search2_parm) )
		{
			$this->db->where($table.'.publish',$search2_parm);
		}
		
		$this->db->order_by($table.".create_date","desc");
		$query = $this->db->get($table,$per_page,$lmt);
		//die($this->db->last_query());
		return $query;
	}
	
	function getDetail($table,$id)
	{
		$this->db->where($table.'.id',$id);
		$query = $this->db->get($table);
		return $query;
	}
	
	function setUpdate($table,$id,$title,$content,$publish,$user_id,$file_image_tmp,$file_image,$file_image_old)
	{
		
		if(!empty($file_image)){
			if(!empty($file_image_old)){
				$this->deleteFileUpload($file_image_old);
			}
			$file_image = $this->setFileUpload($file_image,$file_image_tmp,$file_image_old);
		}else{
			$file_image = $file_image_old;
		}
		
		$data = array(
			      'title'=>$title,
			      'content'=>$content,
				  "file_image"=>$file_image,
			      'publish'=>$publish,
			      'modify_user_id'=>$user_id,
			      );
		$this->db->where('id',$id);
		$this->db->update($table,$data);
	}
	
	function setInsert($table,$id,$title,$content,$publish,$user_id,$file_image_tmp,$file_image,$file_image_old)
	{
		
		if(!empty($file_image)){
			$file_image = $this->setFileUpload($file_image,$file_image_tmp,$file_image_old);
		}
		
		$data = array(
			      'title'=>$title,
			      'content'=>$content,
				  "file_image"=>$file_image,
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
			$this->db->where('id',$id);
			$this->db->delete($table);
			$status = 1;
		}
		return $status;
	}
	
	function setFileUpload($file_image,$file_image_tmp,$file_image_old)
	{
		$d = date("Ymdhis");
		$file_image = $d.$file_image;
		$this->load->library('image_moo');
		
		list($w, $h) = getimagesize($file_image_tmp);
		if($w > $h){
			$w = 485;
			$h = 320;
		}else{
			$w = 213;
			$h = 320;
		}
						
		
 		$this->image_moo->load($file_image_tmp)->resize_crop($w,$h)->save("./uploads/".$file_image);
		$this->image_moo->load($file_image_tmp)->resize_crop(100,100)->save("./uploads/thumbs/".$file_image);
   		
   		if(!$file_image)
		{
			 	$file_image = $file_image_old;
		}

		return $file_image;
	}
	
	
	function deleteFileUpload($file_image)
	{
		if(file_exists("uploads/".$file_image)){ unlink("uploads/".$file_image); }
		if(file_exists("uploads/thumbs/".$file_image)){ unlink("uploads/thumbs/".$file_image); }
	}
}
?>