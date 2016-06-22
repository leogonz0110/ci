<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Common.php';

class PortfolioModel extends CI_Model {
	public $order = 'ASC';
	public $limit = 10;

	private $returnType = true;
	private $rows = 0;

	protected $table = 'portfolio';
	protected $join = 'user';
	protected $primary_key = "";
	protected $fields = array();
	protected $fillable = ['port_address', 'port_worth', 'port_desc', 'port_status'];
	protected $hidden = [];
	protected $additional = array("CONCAT(`first_name`, ' ', `last_name`) as `name`");

	use Common;

	public function __construct()
	{
		$fields = array_diff($this->db->list_fields($this->table), $this->hidden);
		$this->primary_key = $this->setKey();
		foreach($fields as $field){
			array_push($this->fields, $this->table.'.'.$field);
		}
	}
	private function join ()
	{
		$this->db->join($this->join, $this->table.".user_id = $this->join.id");
	}


}
