<?php
trait Common {

	public function filter($data)
	{
		$filtered = array();
		foreach($this->fillable as $key){
			if(array_key_exists($key, $data)){
				$filtered[$key] = $data[$key];
			}
		}
		return $filtered;
	}

	private function fieldsToString($fields = NULL)
	{
		if($fields != NULL){
			foreach($fields as $field){
				array_push($this->fields, $field);
			}
		}

		return implode(', ', $this->fields);
	}

	private function setKey()
	{
		$fields = $this->db->field_data($this->table);

		foreach ($fields as $field)
		{
			if($field->primary_key==1)
			{
				return $field->name;
			}
		}
	}

	public function save($data)
	{
		$op = 'update';
		$keyExists = FALSE;
    	$fields = $this->db->field_data($this->table);

		foreach ($fields as $field)
		{
			if($field->primary_key==1)
			{
				$keyExists = TRUE;
				if(isset($data[$field->name]))
				{
					$this->db->where($field->name, $data[$field->name]); echo $field->name;
				}
				else
				{
					echo $op = 'insert';
				}
			}
		}

		var_dump($keyExists && $op=='update');
		if($keyExists && $op=='update')
		{
			$this->db->set($data);
			$this->db->update($this->table);

			var_dump($this->db->affected_rows());
			if($this->db->affected_rows()==1)
			{
				return $this->rows = $this->db->affected_rows();
			}
		}

		$this->db->insert($this->table, $data);

		return $this->rows = $this->db->affected_rows() ;
	}

	function insert($data)
	{
		try{
			$this->db->insert($this->table, $data);
			$this->rows = $this->db->affected_rows();

			return true;
		}catch (Exception $e) {
			return false;
		}
	}

	function update($data, $conditions)
	{
		$this->db->where($conditions);
		$this->db->update($this->table, $data);
		$this->rows = $this->db->affected_rows() ;

		return $this->db->affected_rows();
	}

	function delete($conditions)
	{
		$this->db->where($conditions);
		$this->db->delete($this->table);

		$this->rows = $this->db->affected_rows();

		return $this->queryStatus();
	}
	function queryStatus()
	{
		return ($this->rows > 0) ? true : false;
	}

	function affected_rows()
	{
		return $this->rows;
	}

	public function findAll($conditions = NULL)
	{
		if($conditions != NULL) {
			return $this->find($conditions, 0, 0);
		} else {
			return $this->find(null, 0, 0);
		}
	}

	public function find($conditions = NULL, $limit = 10, $offset = 0)
	{
		if (!empty($this->additional)) {
			$this->db->select($this->fieldsToString($this->additional));
		} else {
			$this->db->select();
		}

		if($conditions != NULL)
			$this->db->where($conditions);

		if (!empty($this->join))
			$this->join();

		$this->db->order_by($this->table.".".$this->primary_key, $this->order);

		$query = $this->db->get($this->table, $limit, $offset);
		$results = ($this->returnType) ? $query->result() : $query->result_array();
		$this->rows = $this->db->affected_rows();

		return $results;
	}

	public function get($conditions = NULL, $limit = 10, $offset = 0)
	{
		if (!empty($this->additional)) {
			$this->db->select($this->fieldsToString($this->additional));
		} else {
			$this->db->select();
		}

		if($conditions != NULL)
			$this->db->where($conditions);

		if (!empty($this->join))
			$this->join();

		$this->db->order_by($this->table.".".$this->primary_key, $this->order);

		$query = $this->db->get($this->table, $limit, $offset);
		$results = ($this->returnType) ? $query->result() : $query->result_array();
		$this->rows = $this->db->affected_rows();
		var_dump($query);
		return $results;
	}
}
