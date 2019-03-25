<?php
class LanguageModel extends CI_Model {
    var $table = 'trans';
    var $column_order = array('TEXT'); //set column field database for datatable orderable
   
    var $column_search = array('TEXT');
    //var $order = array('ID' => 'ASC');
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    public function getData(){
        $this->db->select("ID,TEXT,CODE");
		$this->db->from($this->table);
		$this->db->group_by('TEXT');
        $i = 0;        
        foreach ($this->column_search as $item){
            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    //$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i); //last loop
                    // $this->db->group_end(); //close bracket

            }
            $i++;
        }
       
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        
		$query = $this->db->get();
		$datas = $query->result_array();
        //print_r($result);die;
        
       return  $datas;
    }
    function count_filtered()
    {
		$this->db->from($this->table);
        $this->db->group_by('TEXT');
        $i = 0;        
        foreach ($this->column_search as $item){
            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    //$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i); //last loop
                    // $this->db->group_end(); //close bracket

            }
            $i++;
        }
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->group_by('TEXT');
        $query = $this->db->get();
        return $query->num_rows();
       // return $this->db->count_all_results();
    }
    public function getlangs(){
        $this->db->select("*");
		$this->db->from("com_alllanguage");
		$this->db->where('status','Y');
		$query = $this->db->get();  
		return  $query->result_array();
    }
}