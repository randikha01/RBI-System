<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tree extends MX_Controller  {
	
	var $table = "tree";
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model("plant/model_plant", "plant");
		$this->lang->load('elemen_layout', 'indonesia');
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
		$node_id = strip_tags($this->input->post("node_id"));
		
		#plant
		$tree = array();
		$q_plant = $this->plant->getList("plant");
		if($node_id == NULL) {
			if($q_plant->num_rows() > 0){
				foreach($q_plant->result() as $r)
				{
						
						$tree[] = array(
										"id" => "pl_".$r->id, 
										"label" => ucwords($r->title), 
										"type" => "folder",
										"icon" => "units"
									   );	
									 
				}
				echo json_encode($tree);
			}
		}

		#plant sub
		$tree = array();
		if( $node_id != NULL ) {
			if($q_plant->num_rows() > 0){
				foreach($q_plant->result() as $r)
					{
						if($node_id == "pl_".$r->id){
						
							#element
							$no_element = 0;
							$q_element = $this->plant->getElement("plant_element");
							if($q_element->num_rows() > 0){
								foreach($q_element->result() as $r_e)
								{
									$no_element++;
									
									$tree[] = array(
															"id" => "el_".$r_e->id, 
															"label" => ucwords($r_e->title), 
															"type" => "folder",
															"icon" => $r_e->icon
														   );
														   
									if($no_element == 2){
								
										#folder lv0
										$qfolder = $this->plant->getListFolder('tbl_plant_folder',$r->id,0);
										$qf_num = $qfolder->num_rows();
										if($qfolder->num_rows() > 0){
											foreach ($qfolder->result() as $f) {
												$tree[] = array(
																"id" => "f0_".$f->id, 
																"label" => ucwords($f->title), 
																"type" => "folder",
																"icon" => "folder"
															   );
											}
										}
									}
									
								}
							}
							#end element
							echo json_encode($tree);
						}
					}	
			}
			
			
			#lv1
			if($q_plant->num_rows() > 0){
				foreach($q_plant->result() as $r)
				{
						#folder lv0
						$qfolder = $this->plant->getListFolder('tbl_plant_folder',$r->id,0);
						if($qfolder->num_rows() > 0){
							foreach ($qfolder->result() as $f) {
								if($node_id == "f0_".$f->id){
										#folder lv1
										$qfolder1 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f->id);
										if($qfolder1->num_rows() > 0){
											foreach ($qfolder1->result() as $f1) {
												$tree[] = array(
																"id" => "f1_".$f1->id, 
																"label" => ucwords($f1->title), 
																"type" => "folder",
																"icon" => "folder"
																);
											}
											
										}
										#end folder lv1
										echo json_encode($tree);
								}
							}		
						}
						#folder lv0
				}
				
			}
			#lv1
			
			
			#lv2
			if($q_plant->num_rows() > 0){
				foreach($q_plant->result() as $r)
				{
						#folder lv0
						$qfolder = $this->plant->getListFolder('tbl_plant_folder',$r->id,0);
						if($qfolder->num_rows() > 0){
							foreach ($qfolder->result() as $f) {
								
								#folder lv1
								$qfolder1 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f->id);
								if($qfolder1->num_rows() > 0){
									foreach ($qfolder1->result() as $f1) {
										if($node_id == "f1_".$f1->id){
											#folder lv2
											$qfolder2 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f1->id);
											if($qfolder2->num_rows() > 0){
												foreach ($qfolder2->result() as $f2) {
													$tree[] = array(
																	"id" => "f2_".$f2->id, 
																	"label" => ucwords($f2->title), 
																	"type" => "folder",
																	"icon" => "folder"
																	);
												}
											}
											#folder lv2
											echo json_encode($tree);
										}
									}
											
								}
								#end folder lv1								
							}	
							
						}
						#end folder lv0
				}
				
			}
			#lv2
			
			
			#lv3
			if($q_plant->num_rows() > 0){
				foreach($q_plant->result() as $r)
				{
						#folder lv0
						$qfolder = $this->plant->getListFolder('tbl_plant_folder',$r->id,0);
						if($qfolder->num_rows() > 0){
							foreach ($qfolder->result() as $f) {
								
								#folder lv1
								$qfolder1 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f->id);
								if($qfolder1->num_rows() > 0){
									foreach ($qfolder1->result() as $f1) {
											#folder lv2
											$qfolder2 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f1->id);
											if($qfolder2->num_rows() > 0){
												foreach ($qfolder2->result() as $f2) {
													if($node_id == "f2_".$f2->id){
														#folder lv3
														$qfolder3 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f2->id);
														if($qfolder3->num_rows() > 0){
															foreach ($qfolder3->result() as $f3) {
																	$tree[] = array(
																					"id" => "f3_".$f3->id, 
																					"label" => ucwords($f3->title), 
																					"type" => "folder",
																					"icon" => "folder"
																					);
															}
														}
														#folder lv3
														echo json_encode($tree);
													}	
												}
											}
											#folder lv2
									}
											
								}
								#end folder lv1								
							}	
							
						}
						#end folder lv0
				}
				
			}
			#lv3
			
			
			#lv4
			if($q_plant->num_rows() > 0){
				foreach($q_plant->result() as $r)
				{
						#folder lv0
						$qfolder = $this->plant->getListFolder('tbl_plant_folder',$r->id,0);
						if($qfolder->num_rows() > 0){
							foreach ($qfolder->result() as $f) {
								
								#folder lv1
								$qfolder1 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f->id);
								if($qfolder1->num_rows() > 0){
									foreach ($qfolder1->result() as $f1) {
											#folder lv2
											$qfolder2 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f1->id);
											if($qfolder2->num_rows() > 0){
												foreach ($qfolder2->result() as $f2) {
														#folder lv3
														$qfolder3 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f2->id);
														if($qfolder3->num_rows() > 0){
															foreach ($qfolder3->result() as $f3) {
																if($node_id == "f3_".$f3->id){
																	#folder lv4
																	$qfolder4 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f3->id);
																	if($qfolder4->num_rows() > 0){
																		foreach ($qfolder4->result() as $f4) {
																			$tree[] = array(
																							"id" => "f4_".$f4->id, 
																							"label" => ucwords($f4->title), 
																							"type" => "folder",
																							"icon" => "folder"
																							);
																		}
																	}
																	#folder lv4
																	echo json_encode($tree);
																}
															}
														}
														#folder lv3
			
												}
											}#folder lv2
									}
											
								}
								#end folder lv1								
							}	
							
						}
						#end folder lv0
				}
				
			}
			#lv4
			
			
			
			#lv5
			if($q_plant->num_rows() > 0){
				foreach($q_plant->result() as $r)
				{
						#folder lv0
						$qfolder = $this->plant->getListFolder('tbl_plant_folder',$r->id,0);
						if($qfolder->num_rows() > 0){
							foreach ($qfolder->result() as $f) {
								
								#folder lv1
								$qfolder1 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f->id);
								if($qfolder1->num_rows() > 0){
									foreach ($qfolder1->result() as $f1) {
											#folder lv2
											$qfolder2 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f1->id);
											if($qfolder2->num_rows() > 0){
												foreach ($qfolder2->result() as $f2) {
														#folder lv3
														$qfolder3 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f2->id);
														if($qfolder3->num_rows() > 0){
															foreach ($qfolder3->result() as $f3) {
																	#folder lv4
																	$qfolder4 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f3->id);
																	if($qfolder4->num_rows() > 0){
																		foreach ($qfolder4->result() as $f4) {
																			if($node_id == "f4_".$f4->id){
																				#folder lv5
																				$qfolder5 = $this->plant->getListFolder('tbl_plant_folder',$r->id,$f4->id);
																				if($qfolder5->num_rows() > 0){
																					foreach ($qfolder5->result() as $f5) {
																						$tree[] = array(
																										"id" => "f5_".$f5->id, 
																										"label" => ucwords($f5->title), 
																										"type" => "folder",
																										"icon" => "folder"
																										);
																					}
																				}#end folder lv5
																				echo json_encode($tree);
																			}
																		}
																	}
																	#folder lv4
																	
															}
														}
														#folder lv3
			
												}
											}#folder lv2
									}
											
								}
								#end folder lv1								
							}	
							
						}
						#end folder lv0
				}
				
			}
			#lv5
			
			
		}
		#end plant sub
		
		
		
		
	}
	
	
	
	
	function grid_sample()
	{
		#$node_id = filter_input(INPUT_GET, "node_id", FILTER_SANITIZE_NUMBER_INT);
		$node_id = strip_tags($this->input->post("node_id"));
		
		#level 0
		if( $node_id == NULL ) {
			echo json_encode(array(
								0 => array( "id" => 1, 
											"label" => "from PHP", 
											"type" => "folder",
											"icon" => "units"
										  ),
								1 => array( "id" => 2, 
											"label" => "from PHP 2", 
											"type" => "folder",
											"icon" => "units"
								 )
								)
							);
		}

		#level 1
		$node_element = array("Planning","Folder Object");
		
		if( $node_id == 1 ) {
			echo json_encode(array(
								0 => array( "id" => 3, 
											"label" => "Planing", 
											"type" => "folder"
										  ),
								1 => array( "id" => 4, 
											"label" => "sub 2 from PHP", 
											"type" => "folder"
								 )
								)
							);
		}
		
		if( $node_id == 2 ) {
			echo json_encode(array());
		}
		
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
		$ref3 = Modules::run('tree/getTreecatDropdown',$sch3_parm,3);
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
		$jum_record = $this->tree->getTotal($this->table,$sch1_parm,$sch2_parm,$sch3_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->tree->getList($this->table,$per_page,$lmt,$sch1_parm,$sch2_parm,$sch3_parm);
		$list = $folder_lv0 = $list_obj0 = $list_obj1 = $list_obj2 = $list_obj3 = $list_obj4 = $folder_lv1 = $folder_lv2 = $folder_lv3 = $folder_lv4 = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $r)
			{
				$no++;
				$zebra = $no % 2 == 0 ? "zebra" : "";
				
				$title = ucwords($r->title);
				$tree_cat_title = ucwords($this->tree->getTreecatTitle('tbl_ref_units',$r->id_ref_units));
				$title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				$publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				$create_date = date("d/m/Y H:i:s",strtotime($r->create_date));

				$qfolder = $this->tree->getListFolder('tbl_tree_folder',$r->id,0);
				$qf_num = $qfolder->num_rows();
				if($qfolder->num_rows() > 0){
					foreach ($qfolder->result() as $f) {
						//$f = $qfolder->row();
						$ftitle_lv0 = ucwords($f->title);
						$fparent_lv0 = ucwords($f->id_parent);

						/*list folder lv 1*/
						$qfoll = $this->tree->getListFolder('tbl_tree_folder',$r->id,$f->id);
						$qfl_num = $qfoll->num_rows();
						if($qfoll->num_rows() > 0){
							foreach ($qfoll->result() as $fl) {
								$ftitle_lvl = ucwords($fl->title);
								$ficon_lvl = '<i class="icon-folder-close"></i>';
								$fparent_lv1 = ucwords($fl->id_parent);

								/*list folder lv 2*/
								$qfoll2 = $this->tree->getListFolder('tbl_tree_folder',$r->id,$fl->id);
								$qfl2_num = $qfoll2->num_rows();
								if($qfoll2->num_rows() > 0){
									foreach ($qfoll2->result() as $fl2) {
										$ftitle_lv2 = ucwords($fl2->title);
										$ficon_lv2 = '<i class="icon-folder-close"></i>';
										$fparent_lv2 = ucwords($fl2->id_parent);

										/*list folder lv 3*/
										$qfoll3 = $this->tree->getListFolder('tbl_tree_folder',$r->id,$fl2->id);
										$qfl3_num = $qfoll3->num_rows();
										if($qfoll3->num_rows() > 0){
											foreach ($qfoll3->result() as $fl3) {
												$ftitle_lv3 = ucwords($fl3->title);
												$ficon_lv3 = '<i class="icon-folder-close"></i>';
												$fparent_lv3 = ucwords($fl3->id_parent);

												/*list folder lv 4*/
												$qfoll4 = $this->tree->getListFolder('tbl_tree_folder',$r->id,$fl3->id);
												$qfl4_num = $qfoll4->num_rows();
												if($qfoll4->num_rows() > 0){
													foreach ($qfoll4->result() as $fl4) {
														$ftitle_lv4 = ucwords($fl4->title);
														$ficon_lv4 = '<i class="icon-folder-close"></i>';
														$fparent_lv4 = ucwords($fl4->id_parent);

														/*list object lv 4*/
														$qobj4 = $this->tree->getListObject('tbl_item_object',$fl4->id);
														$qf4_num = $qobj4->num_rows();
														if($qobj4->num_rows() > 0){
															foreach ($qobj4->result() as $b4) {
																$btitle4 = ucwords($b4->obj_tag_no);
																$icon4 = ($b4->id_ref_item == 1) ? '<i class="icon-folder-close"></i>' : "";
																$list_obj4[] = array("bid4"=>$b4->id,"btitle4" =>$btitle4,"bicon4" =>$icon4);
															}
														}

														$folder_lv4[] = array("fid_lv4"=>$fl4->id,"ftitle_lv4" =>$ftitle_lv4,"ficon_lv4" =>$ficon_lv4,"list_obj4"=>$list_obj4);
													}
												}else{
													$folder_lv4[] = array("fid_lv4"=>'',"ftitle_lv4" =>'',"ficon_lv4" =>'');
												}

												/*list object lv 3*/
												$qobj3 = $this->tree->getListObject('tbl_item_object',$fl3->id);
												$qf3_num = $qobj3->num_rows();
												if($qobj3->num_rows() > 0){
													foreach ($qobj3->result() as $b3) {
														$btitle3 = ucwords($b3->obj_tag_no);
														$icon3 = ($b3->id_ref_item == 1) ? '<i class="icon-folder-close"></i>' : "";
														$list_obj3[] = array("bid3"=>$b3->id,"btitle3" =>$btitle3,"bicon3" =>$icon3);
													}
												}

												$folder_lv3[] = array("fid_lv3"=>$fl3->id,"ftitle_lv3" =>$ftitle_lv3,"ficon_lv3" =>$ficon_lv3,"folder_lv4"=>$folder_lv4,"list_obj3"=>$list_obj3);
											}
										}else{
											$folder_lv3[] = array("fid_lv3"=>'',"ftitle_lv3" =>'',"ficon_lv3" =>'');
										}

										/*list object lv 2*/
										$qobj2 = $this->tree->getListObject('tbl_item_object',$fl2->id);
										$qf2_num = $qobj2->num_rows();
										if($qobj2->num_rows() > 0){
											foreach ($qobj2->result() as $b2) {
												$btitle2 = ucwords($b2->obj_tag_no);
												$icon2 = ($b2->id_ref_item == 1) ? '<i class="icon-folder-close"></i>' : "";
												$list_obj2[] = array("bid2"=>$b2->id,"btitle2" =>$btitle2,"bicon2" =>$icon2);
											}
										}

										$folder_lv2[] = array("fid_lv2"=>$fl2->id,"ftitle_lv2" =>$ftitle_lv2,"ficon_lv2" =>$ficon_lv2,"folder_lv3"=>$folder_lv3,"list_obj2"=>$list_obj2);
									}
								}else{
									$folder_lv2[] = array("fid_lv2"=>'',"ftitle_lv2" =>'',"ficon_lv2" =>'');
								}

								/*list object lv 1*/
								$qobj1 = $this->tree->getListObject('tbl_item_object',$fl->id);
								$qf1_num = $qobj1->num_rows();
								if($qobj1->num_rows() > 0){
									foreach ($qobj1->result() as $b1) {
										$btitle1 = ucwords($b1->obj_tag_no);
										$icon1 = ($b1->id_ref_item == 1) ? '<i class="icon-folder-close"></i>' : "";
										$list_obj1[] = array("bid1"=>$b1->id,"btitle1" =>$btitle1,"bicon1" =>$icon1);
									}
								}

								$folder_lvl[] = array("fid_lvl"=>$fl->id,"ftitle_lvl" =>$ftitle_lvl,"ficon_lvl" =>$ficon_lvl,"folder_lv2"=>$folder_lv2,"list_obj1"=>$list_obj1);
							}
						}else{
							$folder_lvl[] = array("fid_lvl"=>'',"ftitle_lvl" =>'',"ficon_lvl" =>'');
						}

						/*list object lv 0*/
						$qobj0 = $this->tree->getListObject('tbl_item_object',$f->id);
						$qf0_num = $qobj0->num_rows();
						if($qobj0->num_rows() > 0){
							foreach ($qobj0->result() as $b0) {
								$btitle0 = ucwords($b0->obj_tag_no);
								$icon0 = ($b0->id_ref_item == 1) ? '<i class="icon-folder-close"></i>' : "";
								$list_obj0[] = array("bid0"=>$b0->id,"btitle0" =>$btitle0,"bicon0" =>$icon0);
							}
						}


						$folder_lv0[] = array("fid_lv0"=>$f->id,"fparent_lv0" =>$fparent_lv0,"ftitle_lv0" =>$ftitle_lv0,"list_obj0"=>$list_obj0,"folder_lvl"=>$folder_lvl);

						$list_obj0 = $list_obj1 = $list_obj2 = $list_obj3 = $list_obj4 = $folder_lvl = $folder_lv2 = $folder_lv3 = $folder_lv4 = array();
					}
				}

			
				$list[] = array(
								 "no"=>$no,
								 "id"=>$r->id,
								 "title" =>$title,
								 "parent" =>$tree_cat_title,
								 "publish"=>$publish,
								 "create_date"=>$create_date,
				  				 "folder_lv0"=>$folder_lv0
								);

				$folder_lv0 = array();
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
				  'title_link'=>$this->table,

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
		
		redirect($this->table."/pages/".$sch1."/".$sch2."/".$sch3."/".$per_page);
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
			/*$ref2_arr = array("No"=>"No","Yes"=>"Yes");*/
			$ref3_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			#record
			$q = $this->tree->getDetail($this->table,$id);
			$list = $list_term_option = array();
			if($q->num_rows() > 0){
				foreach($q->result() as $r){
					
					#ref dropdown multi value
					/*$ref2_select_arr[0] = $r->divider;
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);*/
					#end ref dropdown multi value
					
					#ref dropdown multi value					
					$ref3_select_arr[0] = $r->publish;
					$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,$ref3_select_arr,3);
					#end ref dropdown multi value
				
					$id_ref_units = $r->id_ref_units;
					$id = $r->id;

					$list[] = array(
									"id"=>$r->id,
									"id_ref_units"=>$r->id_ref_units,
									"title"=>$r->title,
									"desc_"=>$r->desc_,
									"create_date"=>$r->create_date,
									"ref3"=>$ref3
									);
				}
			}else{
				
				$id_ref_units = $id = "";
				
				#ref dropdown multi value
				/*$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,null,2);*/
				#end ref dropdown multi value
				
				#ref dropdown multi value
				$ref3 = Modules::run('widget/getStaticDropdown',$ref3_arr,null,3);
				#end ref dropdown multi value
				
				$list[] = array(
									"id"=>0,
									"id_ref_units"=>"",
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
			$ref1 = $this->getRefDropdownTreecat($id_ref_units,1);
			#end ref dropdown multi value
			
			$data = array(
					  'admin_url' => base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>$this->table,
					  'ref1'=>$ref1
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect($this->table);
		}
	}
	
	
	function submit()
	{
		$err = "";
		$ref1 = $this->input->post("ref1");
		$title = strip_tags($this->input->post("title"));
		$desc_ = $this->input->post("desc_");
		$ref2 = $this->input->post("ref2");
		$ref3 = $this->input->post("ref3");
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
				$this->tree->setUpdate($this->table,$id,$ref1,$title,$desc_,$ref3,$user_id);
				$this->session->set_flashdata("success","Data saved successful");
				redirect($this->table."/edit/".$id);
			}else{				
				$id_term = $this->tree->setInsert($this->table,$id,$ref1,$title,$desc_,$ref3,$user_id);
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
			$this->tree->ajaxsort($this->table,$val,$order);
		}
	}
	
	
	function delete($id=0)
	{
		$del_status = $this->tree->setDelete($this->table,$id);
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
	
	function getRefDropdownTreecat($parent_id,$name,$type=NULL)
	{
		$q = $this->tree->getTreecatList('tbl_ref_units');
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
	
	function getTreecatDropdown($id,$name,$type=NULL)
	{
		$q = $this->tree->getTreecatlist('tbl_ref_units',null,null,null);
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

/* End of file tree.php */
/* Location: ./application/modules/controller/tree.php */