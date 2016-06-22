<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'traits/validation.php';
require_once 'traits/helper.php';

class Service extends CI_Controller {
	private $properties = array(
		'serv_title' => array(
			'label'		=> 'Title',
			'form_type'	=> 'text',
			'rules' => array(
				'field'		=> 'stitle',
				'label'		=> 'Title',
				'rules'		=> 'required'
			),
			'form'		=> array (
				'name'        	=> 'stitle',
			    'id'          	=> 'stitle',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Service Title',
			)
		),
		'serv_slug' => array(
			'label'		=> 'Permalink',
			'form_type'	=> 'text',
			'rules' => array(
				'field'		=> 'slug',
				'label'		=> 'Slug',
				'rules'		=> 'required'
			),
			'form'		=> array (
				'name'        	=> 'slug',
			    'id'          	=> 'slug',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Permalink',
			)
		),
		'serv_description' => array(
			'label'		=>	'Service Description',
			'form_type'	=> 'textarea',
			'rules' => array(
				'field'		=> 'sdesc',
				'label'		=> 'Description',
				'rules'		=> 'required'
			),
			'form'		=> array (
				'name'        	=> 'sdesc',
			    'id'          	=> 'sdesc',
			    'class'			=> '',
			    'placeholder'	=> 'Service Description',
			)
		),
		'search' => array(
			'label'		=> 'Search',
			'form_type'	=> 'text',
			'rules'		=> 'alpha_dash'
		)
	);

	private $data = array();

	private $user = null;

	use Validation, Helper;

	public function __construct()
	{
		parent::__construct();
		$this->user = Auth::user();
		$this->data = array();
		$this->data['class'] = $this->router->class;
		$this->data['method'] = $this->router->method;
		$this->data['form'] = array_diff_key($this->properties, array("search" => null));

		$this->load->model("ServiceModel");
		$exceptions = array('login', 'index');
		$restricted = array('delete', 'register', 'create', 'show');
		if(!in_array($this->data['method'], $exceptions)){
			if(!Auth::check()){
				redirect(base_url());
			} else {
				if((Auth::role() != 'admin') AND (in_array($this->data['method'], $restricted))) {
					redirect(base_url());
				}
			}
		}
	}

	public function index()
	{
		if(Auth::check()){
			$this->data['user'] = Auth::user();
			// $this->createPanel('user/table', $this->data);
			redirect(base_url('user/profile'));
		}else{
			$this->load->view('template/head');
			$this->load->view('log');
		}
	}

	/*
	 * Display of services.
	 */
	public function show ()
	{
		$this->data['url'] = "user/show";
		$this->data['per_page'] = $this->ServiceModel->limit;
		$seg = 3;

		$this->ServiceModel->findAll();
		$total = $this->ServiceModel->affected_rows();

		$last_page = ceil(($total/$this->data['per_page']));

		if($last_page < $this->uri->segment(3)) {
			($last_page == 0) ? redirect(base_url("user/show/")) : redirect(base_url("user/show/$last_page"));
		}

		if($this->uri->segment($seg)) {
			$this->data['page'] = (($this->uri->segment($seg)-1) == 0) ? 0 : (($this->uri->segment($seg)-1)*$this->data['per_page']);
		} else {
			$this->data['page'] = 0;
		}

		$this->data['table'] = $this->ServiceModel->find(null, $this->data['per_page'], $this->data['page']);

		$this->data['pagination'] = $this->getPagination($this->data['url'], $total, $this->data['per_page'], $seg);
		$this->createPanel('service/table', $this->data);
	}

	/*
	 * Display of Searched Users.
	 */
	public function search ()
	{
		$search = ($this->input->post("search")) ? $this->input->post("search") : NULL;
		$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : redirect(base_url("service/search/$search"));

		if($this->form_validation->required($search)) {
			if($search != NULL) {
				$this->data['table'] = $this->ServiceModel->find("(`serv_title` LIKE '%$search%') OR (`serv_description` LIKE '%$search%')");
			} else {
				 redirect(base_url("service/show/"));
			}

			$this->data['search'] = $search;
			$this->data['url'] = "service/search/$search";
			$this->data['per_page'] = $this->ServiceModel->limit;
			$seg = 4;

			$total = $this->ServiceModel->affected_rows();
			$last_page = ceil(($total/$this->data['per_page']));

			if($last_page < $this->uri->segment($seg)){
				if($total > 0) {
					($last_page == 0) ? redirect(base_url("service/search/$search")) : redirect(base_url("service/search/$search/$last_page"));
				} else {
					redirect(base_url("service/search/"));
				}
			}

			if($this->uri->segment($seg)) {
				$this->data['page'] = (($this->uri->segment($seg)-1) == 0) ? 0 : (($this->uri->segment($seg)-1)*$this->data['per_page']);
			} else {
				$this->data['page'] = 0;
			}

			if($search != NULL) {
				$this->data['table'] = $this->ServiceModel->find("(`serv_title` LIKE '%$search%') OR (`serv_description` LIKE '%$search%')", $this->data['per_page'], $this->data['page']);
			} else {
				$this->data['table'] = $this->ServiceModel->findAll();
			}

			$this->data['pagination'] = $this->getPagination($this->data['url'], $total, $this->data['per_page'], $seg);
			$this->createPanel('service/table', $this->data);
		} else {
			$this->data['search'] = $search;
			$this->data['table'] = array();
			$this->createPanel('service/table', $this->data);
		}
	}

	/*
	 * Deactivation of user account.
	 */
	public function delete ()
	{
		$slug = $this->uri->segment(3);
		if($slug) {
			$info = $this->ServiceModel->find("serv_slug = '$slug'");
			if($this->ServiceModel->queryStatus()) {
				$info = $info[0];
				if($info->status) {
					$this->ServiceModel->update(array('status' => 0), "serv_slug = '$slug'");

					if($this->ServiceModel->queryStatus()) {
						$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully deleted profile."));
					} else {
						$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while deleting the profile."));
					}
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while deleting the profile."));
				}
			} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Can't find user profile."));
			}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	/*
	 * Activation of user account.
	 */
	public function activate()
	{
		$slug = $this->uri->segment(3);

		if($slug) {
			$info = $this->ServiceModel->find("serv_slug = '$slug'");

			if($this->ServiceModel->queryStatus()) {
				$info = $info[0];
				if(!$info->status) {
					$this->ServiceModel->update(array('status' => 1), "serv_slug = '$slug'");

					if($this->ServiceModel->queryStatus()) {
						$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully service entry."));
					} else {
						$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while activating service entry."));
					}
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while activating service entry."));
				}
			} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Can't find service entry."));
			}
		}

		redirect($_SERVER['HTTP_REFERER']);
	}

	/*
	 * Dispaly of profile information that can be edited.
	 */
	public function edit ()
	{
		$slug = $this->uri->segment(3);

		if($slug) {
			$this->data['service'] = $this->ServiceModel->find("`serv_slug` = '$slug' AND `user_id` = '".$this->user->id."'", 1);
			if(!$this->ServiceModel->queryStatus())
				redirect(base_url('service/view/'));
			$this->data['service'] = $this->data['service'][0];
			$this->session->set_userdata('form_service', $this->data['service']);
		} else {
			redirect(base_url('service/register/'));
		}
		$this->data['form'] = array_diff_key($this->properties, array('search' => null,));
		$this->data['title'] = "Edit Profile";
		$this->data['form_attr'] = array('class' => 'edit-form form-block');
		$this->data['url'] = 'service/update/';

		$this->createPanel('service/form', $this->data);
	}

	/*
	 * Update the profile information in the database.
	 */
	public function update ()
	{
		$inputs = ['serv_title', 'serv_description', 'serv_slug'];
		$service = $this->getPost($inputs);
		if($this->validation($service)){
			if($this->dataChange($this->session->userdata('form_service'), $service)) {
				$id = $this->session->userdata('form_service')->serv_id;
				$this->ServiceModel->update($service, "serv_id = $id");

				if($this->ServiceModel->queryStatus()) {
					$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully updated service entry."));
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while updating service entry."));
				}
			} else {
				$this->session->set_flashdata('message', array('type' => false, 'msg' => "No changes in form data."));
			}
		} else {
			$this->session->set_flashdata('message', array('type' => false, 'msg' => "Check form data."));
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	/*
	 * Form for registering new user.
	 */
	public function register() {
		$this->data['title'] = "Registeration";
		$this->data['class'] = 'user';
		$this->data['url'] = 'user/create';
		$this->data['form_attr'] = array('class' => 'register-form form-block');
		if($this->session->userdata('form_data'))
			$this->data['profile'] = $this->session->userdata('form_data');

		$this->data['form'] = array_diff_key($this->properties, array('search' => null));
		$this->createPanel('service/form', $this->data);
	}

	/*
	 * Creates new user in database.
	 */
	public function create() {
		$inputs = ['role', 'email', 'first_name', 'last_name', 'password', 'chpassword'];
		$user = $this->getPost($inputs);

		if($this->validation($user) AND ($user['password'] === $user['chpassword'])){
			$this->load->model('ServiceModel');
			$this->session->set_userdata('form_data', $this->arrToObj($user));
			if(!$this->check_user_email($user['email'])){
				$user = array_diff_key($user, array('chpassword' => null));
				$user['status'] = true;
				$this->ServiceModel->insert($user);
				if($this->ServiceModel->queryStatus()) {
					$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully created user profile."));
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while creating the profile."));
				}
			} else {
				$this->session->set_flashdata('message', array('type' => false, 'msg' => "Email is already in used."));
			}

			redirect($_SERVER['HTTP_REFERER']);
		} else {
			$this->session->set_flashdata('message', array('type' => false, 'msg' => "Be sure email is unique and other data is valid."));
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	/*
	 * Get roles from database and use it in the form/s.
	 */
	public function setRolesForm() {
		$this->load->model('RoleModel');
		$roles = $this->RoleModel->findAll();
		$format = array();

		foreach($roles as $row) {
			$format[$row->id] = $row->name;
		}
		return $format;
	}

	/*
	 * Check if email is unique.
	 */
	function check_user_email($email)
	{
		$this->load->model('ServiceModel');
    	$result = $this->ServiceModel->find("user.email  = '$email'");

	    return $this->ServiceModel->queryStatus();
	}
}
