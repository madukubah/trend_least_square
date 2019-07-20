<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model_core extends CI_Model {
	public $table = 'table_users';
	protected $join_key = NULL;

	
	function __construct( $table )
	{
		parent::__construct();
		$this->table = $table;
		$this->join_key = substr( $table, 6 )."_id";

	}
	public function set_join_key( $key)
	{
		$this->join_key = $key;
	}
	/**
	 * delete_foreign
	 *
	 * @param array  $data_param
	 * @return bool
	 * @author madukubah
	 */
	public function delete_foreign( $data_param , $models = array()  )
    {
		
		$this->load->model( $models ); 	

		foreach( $models as $model )
		{
			if( array_key_exists( "id", $data_param ) ){

				$_data_param[ $this->join_key] = $data_param["id"];
				if( !$this->$model->delete( $_data_param ) ) return FALSE;
				
			}else{
				foreach( $data_param as $key => $value )
				{
					$this->db->where( $this->table.'.'.$key , $value );
				}
	
				foreach( $this->db->get( $this->table )->result() as $item )
				{
					$_data_param[ $this->join_key]  = $item->id;
					echo var_dump( $_data_param );
					
					if( !$this->$model->delete( $_data_param ) ) return FALSE;
				}
			}
		}

		return TRUE;
	}

	/**
	 * @param string $table
	 * @param array  $data
	 *
	 * @return array
	 */
	protected function _filter_data($table, $data)
	{
		$filtered_data = array();
		$columns = $this->db->list_fields($table);

		if (is_array($data))
		{
			foreach ($columns as $column)
			{
				if (array_key_exists($column, $data))
					$filtered_data[$column] = $data[$column];
			}
		}
		return $filtered_data;
	}

	public function record_count(){
		return $this->db->count_all($this->table);
	}



	// new fetxh   edit by madukubah
	public function fetch_data( $data ) 
	{
		$start = (isset($data['start'])) ? $data['start'] : 0;
		$limit = (isset($data['limit'])) ? $data['limit'] : null;
		$where = (isset($data['where'])) ? $data['where'] : null;
		$select = (isset($data['select'])) ? $data['select'] : null;
		$select_join = (isset($data['select_join'])) ? $data['select_join'] : null;
		$join = (isset($data['join'])) ? $data['join'] : null;
		$like = (isset($data['like'])) ? $data['like'] : null;
		$order = (isset($data['order'])) ? $data['order'] : null;

		if($select==null )//edit by madukubah
		{
			$this->db->select('*');
		}else{
			if( !is_array( $select ) )//edit by madukubah
			{
				$select = [$select];//ubah menjadi array
			}
			foreach($select as $s){
				$this->db->select($this->table.'.'.$s);
			}
			if($select_join!=null) {
				if( !is_array( $select_join ) )//edit by madukubah
				{
					$select_join = [$select_join];
				}
				foreach ($select_join as $sj) {
					$this->db->select($sj);
				}
			}
		}

		$this->db->distinct();

		$this->db->from($this->table);

		if($join!=null && is_array($join)){
			foreach($join as $j){
				$this->db->join(
					$j['table'],
					$j['on'],//edit by madukubah
					$j['join']
				);
			}
		}

		if($where!=null  ) // edit by madukubah
		{
			// /edit by madukubah
			foreach( $where as $w )
			{
				$this->db->where($w);
			}
		}

		if($like!=null && is_array($like)){
			$this->db->group_start();
			$i=0;
			foreach($like['name'] as $l){
				if($i==0){
					$this->db->like($l, $like['key']);
				}else{
					$this->db->or_like($l, $like['key']);
				}
				$i++;
			}
			$this->db->group_end();
		}

		if($order!=null && is_array($order)){
			$this->db->order_by($order['field'],$order['type']);
		}

		//set limit / offset
        if( isset( $limit ) && isset( $start ) )
		{
			$this->db->limit($limit, $start);
			
		}
		else if (isset($limit))
		{
			$this->db->limit( $limit );
		}

		return $this->db->get( );
	}
  
}

