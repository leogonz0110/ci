<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Common.php';

class ServiceModel extends CI_Model {
	public $order = 'ASC';
	public $limit = 10;

	private $returnType = true;
	private $rows = 0;

	protected $table = 'service';
	protected $join = 'user';
	protected $primary_key = "";
	protected $fields = array();
	protected $fillable = ['serv_title', 'serv_slug', 'serv_description', 'serv_published', 'status'];
	protected $hidden = ['password', 'keycode'];
	protected $additional = array("CONCAT(`first_name`, ' ', `last_name`) as `name`",
							"DATE_FORMAT(date_created, '%b %Y') as `date_created`",
							"DATE_FORMAT(last_session, '%b %e, %Y %T') as `last_session`",
							"DATE_FORMAT(serv_published, '%b %e, %Y %T') as `publish`",
							);
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
