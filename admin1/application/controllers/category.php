<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

	public $data=array();
	private $table = TABLE_CATEGORY;
	
	function __construct() {
		parent::__construct();
		$this->load->model('userdata');
		$this->data=$this->defaultdata->getFrontendDefaultData();
		if($this->defaultdata->is_session_active() == 1) {
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}
		// if($this->session->userdata('admuname') == ''){
		// 	redirect(base_url('login'));
		// }
	}

	public function getCategoryList($id = 0){
		$this->db->order_by('weight','ASC');
		$query = $this->db->get($this->table);
		$result = $query->result_array();
		$optiondata = "";
		if(!empty($result)){
			foreach($result as $j=>$r){
				if($r['parentID'] == 0){
					$optiondata .= "<option value=".$r['id'].($id == $r['id'] ? ' selected' : '').">".$r['title']."</option>";
					foreach($result as $k=>$m){
						if($m['parentID'] == $r['id']){
							$optiondata .= "<option value=".$m['id'].($id == $m['id'] ? ' selected' : '').">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;» ".$m['title']."</option>";
							foreach($result as $i1=>$n){
								if($n['parentID'] == $m['id']){
									$optiondata .= "<option value=".$n['id'].($id == $n['id'] ? ' selected' : '').">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;» ".$n['title']."</option>";
									foreach($result as $i2=>$n1){
										if($n1['parentID'] == $n['id']){
											$optiondata .= "<option value=".$n1['id'].($id == $n1['id'] ? ' selected' : '').">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;» ".$n1['title']."</option>";
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $optiondata;
	}

	public function newCategory(){
		$this->defaultdata->checkLogin();
		$this->data['cate_list'] = $this->getCategoryList();
		$this->load->view('category/category_detail',$this->data);
	}

	public function newProcess(){
		$input_data = $this->input->post();
		if(empty($input_data) || !isset($input_data['title']) || $input_data['title'] == ''){
			$this->session->set_userdata('category_error','Please Enter required filds.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('category/new-category'));
		} else {
			$condi = array('id' => $input_data['category']);
			$query = $this->db->get_where($this->table, $condi);
			$cate_data = $query->row('type');
			if(empty($cate_data))$cate_data = strtoupper(substr($input_data['title'],0,3));
			$input_data = array(
				'languageID' => 1,
				'parentID' => (isset($input_data['category']) ? $input_data['category'] : 0),
				'title' => $input_data['title'],
				'type' => $cate_data,
				'weight' => (isset($input_data['weight']) ? $input_data['weight'] : 1),
				'status' => (isset($input_data['status']) ? $input_data['status'] : 'Y'),
			);
			$this->db->set($input_data);
			$this->db->insert($this->table);
			$this->session->set_userdata('category_sucess','Category inserted sucessfully.');
			redirect(base_url('category/list-category'));
		}
	}

	public function listCategory(){
		$this->defaultdata->checkLogin();
		$this->db->select("id, title");
		$condi = array('parentId' => 0);
		$query = $this->db->get_where($this->table,$condi);
		$result = $query->result_array();
		if(!empty($result)){
			foreach($result as $j=>$r){
				foreach($r as $k=>$a){
					$data[$j][] = $a;
				}
			}
		}
		$this->data['data'] = $data;
		$this->load->view('category/category_list',$this->data);
	}

	public function getSubCate(){
		$id = $_GET['id'];
		$type = $_GET['type'];
		$this->db->order_by('weight','ASC');
		$this->db->select("id, title");
		$condi = array('parentId' => $id);
		$query = $this->db->get_where($this->table,$condi);
		$result = $query->result_array();
		if($type == 'category'){
			$set_type = 'subCategory';
			$tName = 'Main';
		} else if($type == 'subCategory'){
			$set_type = 'subsubCategory';
			$tName = 'sub category';
		} else if($type == 'subsubCategory'){
			$set_type = 'subsubsubCategory';
			$tName = 'sub sub category';
		} else {
			$tName = 'sub sub sub category';
		}
		$act = '<a href="'.base_url("category/edit-category/id/".$id).'" class="col-md-12">
			<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit '.$tName.'
		</a>
		<a href="'.base_url("category/delete-category/id/".$id).'" class="col-md-12 delete-item-data">
			<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete '.$tName.'
		</a>';
		if($type == 'category'){
			$act .= '<a href="'.base_url("category/add-attribute/id/".$id).'"class="col-md-12">
						<span class="glyphicon glyphicon-tags" aria-hidden="true"></span> Add Attribute
					</a>';
		}
		if(!empty($result)){
			$data = '<select name="'.$set_type.'" id="category-data" size="17" style="width:100%; height:200px">';
			foreach($result as $j=>$r){
				$data .= '<option value="'.$r['id'].'">'.$r['title'].'</option>';
			}
			$data .=  '</select>';
		}
		echo json_encode(array('status'=>true,'data'=>$data, 'action'=> $act));die;
	}

	public function edit($id = 0){
		$this->defaultdata->checkLogin();
		if($id != 0 && $id != ""){
			$condi = array('id' => $id);
			$this->db->select("id, title, parentID, type, weight, status");
			$query = $this->db->get_where($this->table, $condi);
			$result = $query->row();
			$this->data['data'] = $result;
			$this->data['cate_list'] = $this->getCategoryList($result->parentID);
			$this->load->view('category/category_detail',$this->data);
		} else {
			redirect(base_url('category/listCategory'));
		}
	}

	public function editProcess($id = 0){
		$input_data = $this->input->post();
		if(!isset($id) || $id == 0 || $id == ""){
			redirect(base_url('/user/active'));
		} else if(empty($input_data) || !isset($input_data['title']) || $input_data['title'] == ''){
			$this->session->set_userdata('category_error','Please Enter required filds.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('category/edit-category/id/'.$id));
		} else {
			$condi = array('id' => $input_data['category']);
			$query = $this->db->get_where($this->table, $condi);
			$cate_data = $query->row('type');
			if(empty($cate_data))$cate_data = strtoupper(substr($input_data['title'],0,3));
			$input_data = array(
				'parentID' => (isset($input_data['category']) ? $input_data['category'] : 0),
				'title' => $input_data['title'],
				'type' => $cate_data,
				'weight' => (isset($input_data['weight']) ? $input_data['weight'] : 1),
				'status' => (isset($input_data['status']) ? $input_data['status'] : 'Y')
			);
			$this->db->set($input_data);
			$this->db->where('id', $id);
			$this->db->update($this->table);
			$this->session->set_userdata('category_sucess','Category updated sucessfully.');
			redirect(base_url('category/edit-category/id/'.$id));
		}
	}

	public function delete($id = 0){
		if($id != 0 && $id != ""){
			$this->db->where('id', $id);
			$this->db->or_where('parentId', $id); 
			$this->db->delete($this->table);
			if($this->db->affected_rows() > 0){
				echo json_encode(array('status'=>true));die;
			} else {
				echo json_encode(array('status'=>false,'message'=> 'Something went wrong.'));die;
			}
		}else{
			echo json_encode(array('status'=>false,'message'=>'Parameter not found.'));die;
		}
	}

	public function attrList($id = 0){
		$this->defaultdata->checkLogin();
		if($id != 0 && $id != ""){
			$attr_table = TABLE_CATEGORYATTR;
			$condi = array('pid' => $id);
			$this->db->group_by('typeID');
			$this->db->order_by('weight','ASC');
			$query = $this->db->get_where($attr_table, $condi);
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $j=>$r){
					foreach($r as $k=>$a){
						$data[$j][$k] = $a;
					}
				}
			}
			$this->data['data'] = $data;
			$this->data['cat_id'] = $id;
			$this->load->view('category/attr_list',$this->data);
		} else {
			redirect(base_url('category/listCategory'));
		}
	}

	public function getAllLanugage(){
		$table = TABLE_ALLLANGUAGE;
		$this->db->order_by('weight','ASC');
		$query = $this->db->get($table);
		$result = $query->result_array();
		$optiondata =  array();
		if(!empty($result)){
			foreach($result as $j=>$r){
				foreach($r as $k=>$a){
					$optiondata[$j][$k] = $a;
				}
			}
		}
		return $optiondata;
	}

	public function newAttr($id = 0){
		$this->defaultdata->checkLogin();
		if($id != 0 && $id != ""){
			$this->data['cat_id'] = $id;
			$this->data['language_list'] = $this->getAllLanugage();
			$this->load->view('category/attr_detail',$this->data);
		} else {
			redirect(base_url('category/add-attribute/id/'.$id));
		}
	}

	public function newAttrProccess($id = 0){
		if($id != 0 && $id != ""){
			$language_list = $this->getAllLanugage();
			if($language_list){
				$attr_table = TABLE_CATEGORYATTR;
				$arrtibute = TABLE_ATTR;
				$input_data = $this->input->post();
				$input_data1 = array(
					'pid' => $id,
					'fieldSlug' => $input_data['fieldSlug'],
					'type' => $input_data['type'],
					'weight' => (isset($input_data['weight']) ? $input_data['weight'] : 1),
					'status' => (isset($input_data['status']) ? $input_data['status'] : 'Y'),
					'is_required' => (isset($input_data['is_required']) ? $input_data['is_required'] : 'Y')
				);
				foreach($language_list as $j=>$r){
					if(!isset($input_data['fieldName'.$r['id']]) ||$input_data['fieldName'.$r['id']] == ''){
						$this->session->set_userdata('attr_error','please fill required fields.');
						$this->session->set_userdata('input_data',$input_data);
						redirect(base_url('category/new-attribute/id/'.$id));
						die;
					}
				}
				$this->db->order_by('id','DESC');
				$query = $this->db->get($attr_table,1,0);
				if($query->row('typeID')){
					$input_data1['typeID'] = $query->row('typeID')+5;
				} else {
					$input_data1['typeID'] = 50;
				}
				foreach($language_list as $j=>$r){
					$input_data1['fieldName'] = $input_data['fieldName'.$r['id']];
					$input_data1['languageID'] = $r['id'];
					$input_data1['postingTime'] = time();
					$this->db->set($input_data1);
					$this->db->insert($attr_table);
				}
				// print_r($input_data);
				if($input_data['type'] == 'List'){
					$cnt = 0;
					foreach($language_list as $j=>$r){
						$_temp = $input_data['option_'.$r['id']];
						$cnt=count($_temp);
					}
					// print_r($_temp);
					// echo $cnt;

					for($i=1;$i<$cnt;$i++){

						$this->db->order_by('id','DESC');
						$query = $this->db->get($arrtibute,1,0);
						if($query->row('typeID')){
							$attr_type_id = $query->row('typeID')+5;
						} else {
							$attr_type_id = 50;
						}

						foreach($language_list as $j=>$r){

							if($input_data['option_'.$r['id']][$i] != ''){
								
								// foreach($input_data['option_'.$r['id']] as $jj=>$rr){
									// if($rr && $rr != ''){
										
										$input_data_attr = array(
											'languageID' => $r['id'],
											'cattr' => $input_data1['typeID'],
											'title' => $input_data['option_'.$r['id']][$i],
											'type' => 'cat',
											'weight' => (isset($input_data['weight']) ? $input_data['weight'] : 1),
											'status' => (isset($input_data['status']) ? $input_data['status'] : 'Y'),
											'typeID' => $attr_type_id,
											'postingTime' => time()
										);
										// print_r($input_data_attr);
										$this->db->set($input_data_attr);
										$this->db->insert($arrtibute);
									// }
								// }
							}
						}
					}
				}
				$this->session->set_userdata('attr_sucess','Category attribute inserted sucessfully.');
				redirect(base_url('/category/add-attribute/id/'.$id));
			} else {
				$this->session->set_userdata('attr_error','No Language found.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('category/new-attribute/id/'.$id));
			}			
		} else {
			$this->session->set_userdata('attr_error','Data not found.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('category/new-attribute/id/'.$id));
		}
	}

	public function deleteAttr($id = 0){
		if($id != 0 && $id != ""){
			$attr_table = TABLE_CATEGORYATTR;
			$arrtibute = TABLE_ATTR;
			$this->db->where('typeID', $id);
			$this->db->delete($attr_table);
			if($this->db->affected_rows() > 0){
				$this->db->where('cattr', $id);
				$this->db->delete($arrtibute);
				echo json_encode(array('status'=>true));die;
			} else {
				echo json_encode(array('status'=>false,'message'=> 'Something went wrong.'));die;
			}
		}else{
			echo json_encode(array('status'=>false,'message'=>'Parameter not found.'));die;
		}
	}

	public function editAttr($id = 0, $attrid = 0){
		$this->defaultdata->checkLogin();
		if($id != 0 && $id != "" && $attrid != 0 && $attrid != ""){
			$this->data['cat_id'] = $id;
			$attr_table = TABLE_CATEGORYATTR;
			$arrtibute = TABLE_ATTR;
			$condi = array('pid' => $id, 'typeID'=>$attrid);
			$query = $this->db->get_where($attr_table, $condi);
			$result = $query->result_array();
			$result_row = $query->row();
			$data = array(
				'type' => $result_row->type,
				'weight' => $result_row->weight,
				'status' => $result_row->status,
				'is_required' => $result_row->is_required
			);
			foreach($result as $a){
				$data['fieldName'.$a['languageID']] = $a['fieldName'];
			}
			if($data['type'] == 'List'){
				$condi1 = array('cattr' => $result_row->typeID);
				$query1 = $this->db->get_where($arrtibute, $condi1);
				$result1 = $query1->result_array();
				$this->data['List_html'] = $this->getListLenHtml($result1);
			}
			$this->data['language_list'] = $this->getAllLanugage();
			$this->data['data'] = $data;
			$this->data['cat_id'] = $id;
			$this->data['attr_id'] = $attrid;
			$this->load->view('category/attr_detail',$this->data);
		} else {
			edirect(base_url('category/add-attribute/id/'.$id));
		}
	}

	public function editAttrProccess($id = 0, $attrid = 0){
		if($id != 0 && $id != ""){
			$language_list = $this->getAllLanugage();
			if($language_list){
				$attr_table = TABLE_CATEGORYATTR;
				$arrtibute = TABLE_ATTR;
				$input_data = $this->input->post();
				$input_data1 = array(
					'type' => $input_data['type'],
					'weight' => (isset($input_data['weight']) ? $input_data['weight'] : 1),
					'status' => (isset($input_data['status']) ? $input_data['status'] : 'Y'),
					'is_required' => (isset($input_data['is_required']) ? trim($input_data['is_required']) : 'Y'),
					'postingTime' => time()
				);
				foreach($language_list as $j=>$r){
					if(!isset($input_data['fieldName'.$r['id']]) || $input_data['fieldName'.$r['id']] == ''){
						$this->session->set_userdata('attr_error','please fill required fields.');
						$this->session->set_userdata('input_data',$input_data);
						redirect(base_url('category/attr-edit/id/'.$id.'/'.$attrid));
						die;
					}
				}
				foreach($language_list as $j=>$r){
					$input_data1['fieldName'] = $input_data['fieldName'.$r['id']];
					$input_data1['languageID'] = $r['id'];
					$old_data = $this->db->select()->from(TABLE_CATEGORYATTR)->where(array('pid'=> $id, 'typeID' => $attrid,'languageID'=>$r['id']))->get()->num_rows();
					if($old_data > 0){
						$this->db->set($input_data1);
						$this->db->where(array('pid'=> $id, 'typeID' => $attrid, 'languageID'=>$r['id']));
						$this->db->update($attr_table);
					} else {
						$input_data1['pid'] = $id;
						$input_data1['typeID'] = $attrid;
						$this->db->set($input_data1);
						$this->db->insert($attr_table);
					}
				}
				$this->db->where('cattr',$attrid);
				$this->db->delete($arrtibute);
				if($input_data['type'] == 'List'){
					$cnt = 0;
					foreach($language_list as $j=>$r){
						$_temp = $input_data['option_'.$r['id']];
						$cnt=count($_temp);
					}
					for($i=1;$i<$cnt;$i++){

						$this->db->order_by('id','DESC');
						$query = $this->db->get($arrtibute,1,0);
						if($query->row('typeID')){
							$attr_type_id = $query->row('typeID')+5;
						} else {
							$attr_type_id = 50;
						}
						foreach($language_list as $j=>$r){
							if($input_data['option_'.$r['id']][$i] != ''){
								$input_data_attr = array(
									'languageID' => $r['id'],
									'cattr' => $attrid,
									'title' => $input_data['option_'.$r['id']][$i],
									'type' => 'cat',
									'weight' => (isset($input_data['weight']) ? $input_data['weight'] : 1),
									'status' => (isset($input_data['status']) ? $input_data['status'] : 'Y'),
									'typeID' => $attr_type_id,
									'postingTime' => time()
								);
								$this->db->set($input_data_attr);
								$this->db->insert($arrtibute);
							}
						}
					}
				}
				$this->session->set_userdata('attr_sucess','Category attribute updated sucessfully.');
				redirect(base_url('/category/add-attribute/id/'.$id));
			} else {
				$this->session->set_userdata('attr_error','No Language found.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('category/attr-edit/id/'.$id.'/'.$attrid));
			}			
		} else {
			$this->session->set_userdata('attr_error','Data not found.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('category/attr-edit/id/'.$id.'/'.$attrid));
		}
	}

	public function getListLenHtml($dataArray){
		if($dataArray){
			$html = "<div class='col-md-12 col-sm-12 col-xs-12'><table width='100%' border='0' cellpadding='0' cellspacing='0' class='all_language'><tbody id='replicateme'><tr style='display:none;'>";
			$language_list = $this->getAllLanugage();
			foreach($language_list as $j=>$r){
				$html .= "<td width='648' align='left' ><input type='text' name='option_".$r['id']."[]' class='text_box_percen' style='width:100%;'/>";
			}
			$html .= "<td><a href='javascript:void(0)' id='list_add_link' class='btn btn-danger btn-xs remove_this_attr'><i class='fa fa-minus' aria-hidden='true'></i></a></tr><tr>";
			foreach($language_list as $j=>$r){
				$html .= "<th width='309'> Option (".$r['title'].")";
			}
			$html .= '</tr>';
			$html1 = '';
			$_cnt = 1;
			foreach($dataArray as $i=>$m){
				foreach($language_list as $j=>$r){
					if($r['id'] == $m['languageID']){
						if($html1 == ''){
							$html1 .= "<tr>";
						}
						$html1 .= "<td width='648' align='left' ><input type='text' name='option_".$r['id']."[]' value='".$m['title']."' class='text_box_percen' style='width:100%;'/></td>";
						if(($j+1) == count($language_list)){
							if($_cnt == 1){
								$html1 .= "</tr>";
							} else {
								$html1 .= "<td><a href='javascript:void(0)' id='list_add_link' class='btn btn-danger btn-xs remove_this_attr'><i class='fa fa-minus' aria-hidden='true'></i></a></tr>";
							}
							$_cnt++;
						}
					}
				}
			}
			$html .= $html1;
			$html .= "</tbody></table></div>";
			return $html;
		} else {
			return;
		}
	}

	public function getLen(){
		$len_table = TABLE_ALLLANGUAGE;
		$attr_table = TABLE_ATTR;
		$id = $_POST['id'];
		$language_list = $this->getAllLanugage();
		$html = "<div class='col-md-12 col-sm-12 col-xs-12'><table width='100%' border='0' cellpadding='0' cellspacing='0' class='all_language'><tbody id='replicateme'><tr style='display:none;'>";
		foreach($language_list as $j=>$r){
			$html .= "<td width='648' align='left' ><input type='text' name='option_".$r['id']."[]' class='text_box_percen' style='width:100%;'/>";
		}
		$html .= "<td><a href='javascript:void(0)' id='list_add_link' class='btn btn-danger btn-xs remove_this_attr'><i class='fa fa-minus' aria-hidden='true'></i></a></tr><tr>";
		foreach($language_list as $j=>$r){
			$html .= "<th width='309'> Option (".$r['title'].")";
		}
		$html .= '</tr>';
		if($id){
			$condi = array('cattr' => $id);
			$query = $this->db->get_where($attr_table, $condi);
			$dataArray = $query->result_array();
			$html1 = '';
			$_cnt = 1;
			foreach($dataArray as $i=>$m){
				foreach($language_list as $j=>$r){
					if($r['id'] == $m['languageID']){
						if($html1 == ''){
							$html1 .= "<tr>";
						}
						$html1 .= "<td width='648' align='left' ><input type='text' name='option_".$r['id']."[]' value='".$m['title']."' class='text_box_percen' style='width:100%;'/></td>";
						if(($j+1) == count($language_list)){
							if($_cnt == 1){
								$html1 .= "</tr>";
							} else {
								$html1 .= "<td><a href='javascript:void(0)' id='list_add_link' class='btn btn-danger btn-xs remove_this_attr'><i class='fa fa-minus' aria-hidden='true'></i></a></tr>";
							}
							$_cnt++;
						}
					}
				}
			}
			$html .= $html1;
			$html .= "</tbody></table></div>";
		} else {
			$html .= '<tr>';
			foreach($language_list as $j=>$r){
				$html .= "<td width='648' align='left' ><input type='text' name='option_".$r['id']."[]' class='text_box_percen' style='width:100%;'/>";
			}
			$html .= "</tr></tbody></table></div>";
		}
		echo $html;die;
	}
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */