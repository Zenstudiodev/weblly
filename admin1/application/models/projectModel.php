<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class projectmodel extends CI_Model {
    var $table = 'com_main_post_article';
    var $column_order = array('user_id','title','postedTime','rating','total_likes','is_delete'); //set column field database for datatable orderable
    var $column_order1 = array('user_id','title','postedTime','rating','total_likes', 'total_views');

    var $column_search = array('user_id','title','projectDescription');
    var $order = array('postedTime' => 'desc');
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function projec_count_all(){
        $sql = "SELECT ca.*, concat(cu.firstName,' ',cu.lastName) as uname, cnt.short_name 
            FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id 
            LEFT JOIN com_countries cnt ON ca.countryID = cnt.id WHERE 1=1";

        if(isset($_POST['uid']) && $_POST['uid'] != 0){
            $condi = array('ID' => $_POST['uid']);
            $query = $this->db->get_where('com_user', $condi)->num_rows();
            if($query > 0){
                $sql = $sql." AND ca.user_id = '".$_POST['uid']."' ";
            }
        }
        if(isset($_POST['Category']) && $_POST['Category'] != ''){
            $sql = $sql." AND ca.categoryID = '".$_POST['Category']."' ";
        }
        return $this->db->query($sql)->num_rows();
    }

    public function projec_count_filtered(){
        $sql = "SELECT ca.*, concat(cu.firstName,' ',cu.lastName) as uname, cnt.short_name 
            FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id 
            LEFT JOIN com_countries cnt ON ca.countryID = cnt.id WHERE 1=1";
            if(isset($_POST['uid']) && $_POST['uid'] != 0){
                $condi = array('ID' => $_POST['uid']);
                $query = $this->db->get_where('com_user', $condi)->num_rows();
                if($query > 0){
                    $sql = $sql." AND ca.user_id = '".$_POST['uid']."' ";
                }
            }
            if(isset($_POST['Category']) && $_POST['Category'] != ''){
                $sql = $sql." AND ca.categoryID = '".$_POST['Category']."' ";
            }
            if($_POST['search']['value']){
                $q = $_POST['search']['value'];
                $sql = $sql." AND (ca.title LIKE '%$q%' OR cu.firstName LIKE '%$q%' OR cu.lastName LIKE '%$q%' OR cnt.short_name LIKE '%$q%') ";
            }
            return $this->db->query($sql)->num_rows();
    }
 
    public function getData(){
        $a = 1;
        if($a == 1){
            $sql = "SELECT ca.*, concat(cu.firstName,' ',cu.lastName) as uname, cnt.short_name 
            FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id 
            LEFT JOIN com_countries cnt ON ca.countryID = cnt.id WHERE 1=1";
            if(isset($_POST['uid']) && $_POST['uid'] != 0){
                $condi = array('ID' => $_POST['uid']);
                $query = $this->db->get_where('com_user', $condi)->num_rows();
                if($query > 0){
                    $sql = $sql." AND ca.user_id = '".$_POST['uid']."' ";
                }
            }
            if(isset($_POST['Category']) && $_POST['Category'] != ''){
                $sql = $sql." AND ca.categoryID = '".$_POST['Category']."' ";
            }
            if($_POST['search']['value']){
                $q = $_POST['search']['value'];
                $sql = $sql." AND (ca.title LIKE '%$q%' OR cu.firstName LIKE '%$q%' OR cu.lastName LIKE '%$q%' OR cnt.short_name LIKE '%$q%') ";
            }
            $order_field = 'ca.postedTime DESC';
            if(isset($_POST['order'])){
                if($_POST['order']['0']['column'] == 0){
                    $order_field = 'uname '.$_POST['order']['0']['dir'];
                } else if($_POST['order']['0']['column'] == 1){
                    $order_field = 'ca.title '.$_POST['order']['0']['dir'];
                } else if($_POST['order']['0']['column'] == 2){
                    $order_field = 'cnt.short_name '.$_POST['order']['0']['dir'];
                } else if($_POST['order']['0']['column'] == 3){
                    $order_field = 'ca.postedTime '.$_POST['order']['0']['dir'];
                } else if($_POST['order']['0']['column'] == 4){
                    $order_field = 'ca.rating '.$_POST['order']['0']['dir'];
                } else if($_POST['order']['0']['column'] == 5){
                    $order_field = 'ca.total_likes '.$_POST['order']['0']['dir'];
                } else if($_POST['order']['0']['column'] == 6){
                    $order_field = 'ca.total_views '.$_POST['order']['0']['dir'];
                } else if($_POST['order']['0']['column'] == 7){
                    $order_field = 'ca.is_delete '.$_POST['order']['0']['dir'];
                }
            }else if(isset($this->order)){
                // $order = $this->order;
                // $this->db->order_by(key($order), $order[key($order)]);
            }
            $sql= $sql." ORDER BY ".$order_field;
            if($_POST['length'] != -1){
                $sql = $sql." LIMIT ".$_POST['start'].",".$_POST['length'];
            }
            $result = $this->db->query($sql)->result_array();
        } else {
            $cond = array();
            if(isset($_POST['uid']) && $_POST['uid'] != 0){
                $condi = array('ID' => $_POST['uid']);
                $query = $this->db->get_where('com_user', $condi)->num_rows();
                if($query > 0){
                    $cond['user_id'] = $_POST['uid'];
                }
            }
            if(isset($_POST['Category']) && $_POST['Category'] != ''){
                $cond['categoryID'] = $_POST['Category'];
            }
            $this->db->where($cond);
            $this->db->from($this->table);
            $i = 0;
            foreach ($this->column_search as $item){
                if($_POST['search']['value']){ // if datatable send POST for search
                    if($i===0){ // first loop
                        //$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->like($item, $_POST['search']['value']);
                    }else{
                        $this->db->or_like($item, $_POST['search']['value']);
                    }
                    //if(count($this->column_search) - 1 == $i); //last loop
                        // $this->db->group_end(); //close bracket
                }
                $i++;
            }
        
            if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
            
            if(isset($_POST['order'])){ // here order processing
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            }else if(isset($this->order)){ 
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
            $query = $this->db->get();  
            $result = $query->result_array();
        }

        $datag = array();
        // foreach ($result as $index => $data) {
        //     $data['action']= 2;	$data['status_a']= 1;
        //     foreach($data as $k=>$a){
        //         if($k == 'id'){
        //             $id = $a;
        //             unset($data[$k]);
        //         }else if($k == 'user_id'){
        //             $this->db->where('id', $a);
        //             $this->db->from("com_user");
        //             $query = $this->db->get(); 
        //             $result = $query->row(); 
        //             $user = $result->firstName." ".$result->lastName;
        //             $datag[$index][] = $user;
        //         }else if($k == 'title'){
        //             $datag[$index][] = $a;
        //         }else if($k == 'is_delete'){
        //             if($a == 'Y') $datag[$index][] = '<span class="btn btn-round btn-info">Yes</span>';
        //             else if($a == 'N') $datag[$index][] = '<span class="btn btn-round btn-default">No</span>';
        //         }else if($k == 'postedTime'){
        //             $datag[$index][] =  date('m/d/Y', $a);
        //         }else if($k == 'rating'){
        //             $datag[$index][] = $a;
        //         }else if($k == 'total_likes'){
        //             $datag[$index][] = $a ;
        //         }else if($k == 'status_a'){
        //             if($data['status'] == 'P'){
        //                 $datag[$index][] = '<a class="apPost" data-toggle="tooltip" title="Approve" data-id="'.$id.'" value="Y"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a> | 
        //                 <a class="apPost" data-toggle="tooltip" title="Disapprove" data-id="'.$id.'" value="N"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
        //             }else if($data['status'] == 'Y'){
        //                 $datag[$index][] = '<span>Approved</span> | 
        //                 <a class="apPost" data-toggle="tooltip" title="Disapprove" data-id="'.$id.'" value="N"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
        //             }else{
        //                 $datag[$index][] = '<a class="apPost" data-toggle="tooltip" title="Approve" data-id="'.$id.'" value="Y"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a> | 
        //                 <span>Disapproved</span>';
        //             }
        //         } else if($k == 'action'){
        //             $datag[$index][] = '<a href="'.base_url("project/project-view/id/").$id.'"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a> | <a href="'.base_url("project-delete/id/").$id.'" class="delete-item-data"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>' ;
        //         }    
        //     }
        // }

        // print_r($result);die;
        foreach ($result as $index => $data) {
            $data['action']= 2;
            foreach($data as $k=>$a){
                if($k == 'id'){
                    $id = $a;
                    unset($data[$k]);
                }else if($k == 'uname'){
                    $datag[$index][0] = $a;
                }else if($k == 'title'){
                    $datag[$index][1] = $a;
                }else if($k == 'short_name'){
                    $datag[$index][2] = $a;
                }else if($k == 'is_delete'){
                    if($a == 'Y') $datag[$index][7] = '<span class="btn btn-round btn-info">Yes</span>';
                    else if($a == 'N') $datag[$index][7] = '<span class="btn btn-round btn-default">No</span>';
                }else if($k == 'postedTime'){
                    $datag[$index][3] =  date('m/d/Y', $a);
                }else if($k == 'rating'){
                    $datag[$index][4] = $a;
                }else if($k == 'total_likes'){
                    $datag[$index][5] = $a;
                }else if($k == 'total_views'){
                    $datag[$index][6] = $a ;
                }else if($k == 'status'){
                    if($a == 'P'){
                        $datag[$index][9] = '<a class="apPost" data-toggle="tooltip" title="Approve" data-id="'.$id.'" value="Y"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a> | 
                        <a class="apPost" data-toggle="tooltip" title="Disapprove" data-id="'.$id.'" value="N"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                    }else if($a == 'Y'){
                        $datag[$index][9] = '<span>Approved</span> | 
                        <a class="apPost" data-toggle="tooltip" title="Disapprove" data-id="'.$id.'" value="N"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                    }else{
                        $datag[$index][9] = '<a class="apPost" data-toggle="tooltip" title="Approve" data-id="'.$id.'" value="Y"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a> | 
                        <span>Disapproved</span>';
                    }
                } else if($k == 'action'){
                    $datag[$index][8] = '<a href="'.base_url("project/project-view/id/").$id.'"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a> | <a href="'.base_url("project-delete/id/").$id.'" class="delete-item-data"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>' ;
                } else {
                    unset($data[$k]);
                }
            }
        }
       return  $datag;
    }
    // function count_filtered(){
    //     // $cond = array('status'=>'Y');
    //     // if(isset($_POST['uid']) && $_POST['uid'] != 0){
	// 	// 	$condi = array('ID' => $_POST['uid']);
	// 	// 	$query = $this->db->get_where('com_user', $condi)->num_rows();
	// 	// 	if($query > 0){
	// 	// 		$cond['user_id'] = $_POST['uid'];
	// 	// 	}
    //     // }
    //     // if(isset($_POST['Category']) && $_POST['Category'] != ''){
    //     //     $cond['categoryID'] = $_POST['Category'];
    //     // }
    //     // if(isset($_POST['subCat']) && $_POST['subCat'] != ''){
    //     //     $cond['subCategoryID'] = $_POST['subCat'];
    //     // }
    //     // $this->db->where($cond);
    //     // $this->db->from($this->table);
    //     // $query = $this->db->get();
    //     // return $query->num_rows();


    //     $cond = array();
    //     if(isset($_POST['uid']) && $_POST['uid'] != 0){
	// 		$condi = array('ID' => $_POST['uid']);
	// 		$query = $this->db->get_where('com_user', $condi)->num_rows();
	// 		if($query > 0){
	// 			$cond['user_id'] = $_POST['uid'];
	// 		}
    //     }
    //     if(isset($_POST['Category']) && $_POST['Category'] != ''){
    //         $cond['categoryID'] = $_POST['Category'];
    //     }
    //     if(isset($_POST['subCat']) && $_POST['subCat'] != ''){
    //         $cond['subCategoryID'] = $_POST['subCat'];
    //     }
    //     $this->db->where($cond);
    //     $this->db->from($this->table);
        
    //     $query = $this->db->get();  
    //     return $query->num_rows();
    // }
 
    // public function count_all(){
    //     $cond = array();
    //     if(isset($_POST['uid']) && $_POST['uid'] != 0){
	// 		$condi = array('ID' => $_POST['uid']);
	// 		$query = $this->db->get_where('com_user', $condi)->num_rows();
	// 		if($query > 0){
	// 			$cond['user_id'] = $_POST['uid'];
	// 		}
    //     }
    //     if(isset($_POST['Category']) && $_POST['Category'] != ''){
    //         $cond['categoryID'] = $_POST['Category'];
    //     }
    //     if(isset($_POST['subCat']) && $_POST['subCat'] != ''){
    //         $cond['subCategoryID'] = $_POST['subCat'];
    //     }
    //     // if(isset($_POST['subCat']) && $_POST['subCat'] != ''){
    //     //     $cond['subCategoryID'] = $_POST['subCat'];
    //     // }
    //     $this->db->where($cond);
    //     $this->db->from($this->table);
    //     return $this->db->count_all_results();
    // }

    public function isInTopData(){
        $this->db->from($this->table);
		$this->db->where('is_in_top','1');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function isInTopYearData(){
        $cond['type'] = 'Y';
        if(isset($_POST['value']) && $_POST['value'] != ''){
            $cond['value'] = $_POST['value'];
        }else{
            $cond['value'] = Date('Y');
        }
        $this->db->from('com_hall_of_fame');
		$this->db->where($cond);
        $query = $this->db->get();
       // print_r(Date('m-Y'));die;
		return $query->result_array();
    }
    public function isInTopMonthData(){
        $cond['type'] = 'M';
        if(isset($_POST['value']) && $_POST['value'] != ''){
            $cond['value'] = $_POST['value'];
        }else{
            $cond['value'] = Date('m-Y');
        }
        
        $this->db->from('com_hall_of_fame');
		$this->db->where($cond);
		$query = $this->db->get();
		return $query->result_array();
    }

    // public function getCatDataHF(){
    //     $cond = array('status'=>'Y','is_delete'=>'N');
    //     if(isset($_POST['Category']) && $_POST['Category'] != ''){
    //         $cond['categoryID'] = $_POST['Category'];
    //     }
    //     if(isset($_POST['subCat']) && $_POST['subCat'] != ''){
    //         $cond['subCategoryID'] = $_POST['subCat'];
    //     }
    //     $this->db->where($cond);
    //     $this->db->from($this->table);
    //     // $this->db->join('comments', 'comments.id = articles.id');
    //     $i = 0;
    //     foreach ($this->column_search as $item){
    //         if($_POST['search']['value']){
    //             if($i===0){
    //                 $this->db->like($item, $_POST['search']['value']);
    //             }else{
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }
    //         }
    //         $i++;
    //     }
    
    //     if($_POST['length'] != -1)
    //     $this->db->limit($_POST['length'], $_POST['start']);
        
    //     if(isset($_POST['order'])){ // here order processing
    //     // print_r($_POST['order']);die;
    //         $this->db->order_by($this->column_order1[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     }else if(isset($this->order)){ 
    //         $order = $this->order;
    //         $this->db->order_by(key($order), $order[key($order)]);
    //     }
    //     $query = $this->db->get();
    //     // print_r($query);die;
    //     $result = $query->result_array();
        
    //     // print_r($result);die;
    //     $datag = array();
    //     foreach ($result as $index => $data1) {
    //         $data1['select'] = 1;
    //         foreach($data1 as $k=>$a){
    //             if($k == 'id'){
    //                 $id = $a;
    //                 unset($data[$k]);
    //             }else if($k == 'user_id'){
    //                 $this->db->where('id', $a);
    //                 $this->db->from("com_user");
    //                 $query = $this->db->get(); 
    //                 $result = $query->row(); 
    //                 $user = $result->firstName." ".$result->lastName;
    //                 $datag[$index][0] = $user;
    //             }else if($k == 'title'){
    //                 $datag[$index][1] = $a;
    //             }else if($k == 'postedTime'){
    //                 $datag[$index][2] =  date('m/d/Y', $a);
    //             }else if($k == 'rating'){
    //                 $datag[$index][3] = $a;
    //             }else if($k == 'total_likes'){
    //                 $datag[$index][4] = $a ;
    //             }else if($k == 'total_views'){
    //                 $datag[$index][5] = $a ;
    //             }else if($k == 'select'){
    //                 if(isset($_POST['type']) && $_POST['type'] != ''){
    //                     if($_POST['type'] == 'top'){$isIn = 'is_in_top';}
    //                     // else if($_POST['type'] == 'year'){$isIn = 'is_in_year';}
    //                     // else if($_POST['type'] == 'month'){$isIn = 'is_in_month';}
    //                     if($data1[$isIn] == 1){
    //                         $chacked = 'checked'; 
    //                     }else{
    //                         $chacked = ''; 
    //                     }
    //                 }
    //                 $datag[$index][6] = '<input type="radio" data-id="'.$id.'" class="catRadio" name="catRadio" value="'.$id.'" '.$chacked.' >';
    //             }
    //         }//die;
    //     }
    //     return  $datag;
    // }

    public function setCatDataHF(){
        if(isset($_POST['type']) && $_POST['type'] != ''){
            if($_POST['type'] == 'top'){
                $isIn = 'is_in_top';
                if(isset($_POST['selectedId']) && $_POST['selectedId'] != ''){
                    $condSelectedId[$isIn] = '0';
                    $selectedId = $_POST['selectedId'];
                    $this->db->set($condSelectedId);
                    $this->db->where('id', $selectedId );
                    $this->db->update($this->table);
                }
                if(isset($_POST['radioValue']) && $_POST['radioValue'] != ''){
                    $cond[$isIn] = '1';
                    $id = $_POST['radioValue'];
                    $this->db->set($cond);
                    $this->db->where('id', $id );
                    if($this->db->update($this->table)){
                        return $id;
                    }else{
                        return 0;
                    }
                }
            }
            if($_POST['post_type'] == 'Y'){
                $table = 'com_hall_of_fame';
                if(isset($_POST['selectedId']) && $_POST['selectedId'] != ''){
                    $cond['post_id'] = $_POST['selectedId'];
                    $cond['value'] = $_POST['value'];
                    $this->db->where($cond);
                    $this->db->delete($table);
                }
                if(isset($_POST['radioValue']) && $_POST['radioValue'] != ''){
                    $input_data = array(
                        'post_id'     => $_POST['radioValue'],
                        'value'       => $_POST['value'],
                        'type'        => $_POST['post_type'],
                        'category_id' => $_POST['cat_id'],
                        );
                    $this->db->insert($table,$input_data);
                    if($this->db->insert_id() != 0){
                        //print_r($_POST['radioValue']);die;
                        return $_POST['radioValue'];
                    }else{
                        return 0;
                    }
                }
            }else if($_POST['post_type'] == 'M'){
                $table = 'com_hall_of_fame';
                //print_r($_POST);die;
                if(isset($_POST['selectedId']) && $_POST['selectedId'] != ''){
                    $this->db->where('post_id', $_POST['selectedId']);
                    $this->db->delete($table);
                }
                if(isset($_POST['radioValue']) && $_POST['radioValue'] != ''){
                    $input_data = array(
                        'post_id'     => $_POST['radioValue'],
                        'value'       => $_POST['value'],
                        'type'        => $_POST['post_type'],
                        'category_id' => $_POST['cat_id'],
                        );
                    $this->db->insert($table,$input_data);
                    if($this->db->insert_id() != 0){
                        //print_r($_POST['radioValue']);die;
                        return $_POST['radioValue'];
                    }else{
                        return 0;
                    }
                }
            }

            
        }
    }
}