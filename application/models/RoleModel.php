<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Common.php';

class RoleModel extends CI_Model {
	public $order = 'ASC';
	public $limit = 10;

	private $returnType = true;
	private $rows = 0;

	protected $table = 'roles';
	protected $primary_key = "";
	protected $fields = array();
	protected $fillable = ['name'];
	protected $hidden = [];

	use Common;

	public function __construct()
	{
		$fields = array_diff($this->db->list_fields($this->table), $this->hidden);
		$this->primary_key = $this->setKey();
		foreach($fields as $field){
			array_push($this->fields, $this->table.'.'.$field);
		}
	}

}
