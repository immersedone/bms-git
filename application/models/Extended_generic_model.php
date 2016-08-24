<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Extended_generic_model extends Grocery_crud_model {

	private $query_str = '';
    private $like_arr = array();
    private $or_like_arr = array();
    private $order_by_str = '';
    private $limit_str = '';
    private $group_by_str = '';

	public function __construct(){
		parent::__construct();
	}

	function get_list() {
        $this->arrange_queries();
		$query=$this->db->query($this->query_str);
		$results_array=$query->result();
		//echo $this->db->last_query();
		return $results_array;		
	}

	public function set_query_str($query_str,$groupby = '') {
        $this->group_by_str = $groupby;
		$this->query_str = $query_str;
	}

    function order_by($order_by , $direction){
        $this->order_by_str = ' ORDER By '.$order_by.' '.$direction;
    }

    function like($field, $match = '', $side = 'both'){
        $like_str = ' '.$field.' LIKE ';
    	if($side=='both'){
    		$like_str.= '"%'.$match.'%"';
    	}else if($side=='before'){
    		$like_str.= '"%'.$match.'"';
    	}else if($side=='after'){
    		$like_str.= '"'.$match.'%"';
    	}
        $this->like_arr[] = $like_str;
    }

    function or_like($field, $match = '', $side = 'both'){
        $or_like_str = ' '.$field.' LIKE ';
        if($side=='both'){
            $or_like_str.= '"%'.$match.'%"';
        }else if($side=='before'){
            $or_like_str.= '"%'.$match.'"';
        }else if($side=='after'){
            $or_like_str.= '"'.$match.'%"';
        }
        $this->or_like_arr[] = $or_like_str;
    }

	function limit($value, $offset = ''){
        $this->limit_str = ' LIMIT '.($offset ? $offset.', ' : '').$value;
    }

	function get_total_results(){
        $this->arrange_queries();
		return $this->db->query($this->query_str)->num_rows();	
	}

	public function arrange_queries($limit=true) {
        $query = $this->query_str;
        $without_limit_str = str_replace($this->limit_str, '', $query);
        $without_order_by_str = str_replace($this->order_by_str, '', $without_limit_str);
        $without_group_by_str = str_replace($this->group_by_str,'',$without_order_by_str);
        $like_array = $this->like_arr;
        $like_str = ''; 
        $or_like_array = $this->or_like_arr;
        $or_like_str = '';
        $i = 0;
        foreach ($like_array as $value) {
            if($i==0){
                $like_str = ' AND ('.$value;
            }
            else{
                $like_str.= ' AND '.$value;
            }
            if($i==count($like_array)-1){
                $like_str.= ')';
            }
            $i++;
        }
        $i=0;
        foreach ($or_like_array as $value) {
            if($i==0){
                $or_like_str = ' AND ('.$value;
            }
            else{
                $or_like_str.= ' OR '.$value;
            }
            if($i==count($or_like_array)-1){
                $or_like_str.= ')';
            }
            $i++;
        }
		$query = $without_group_by_str.$like_str.$or_like_str.$this->group_by_str.$this->order_by_str;
		if($limit) $query .= $this->limit_str;
		$this->query_str = $query;
	}
}
