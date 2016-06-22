<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'traits/validation.php';
require_once 'traits/helper.php';

class User extends CI_Controller {
	private $properties = array(
		'email' => array(
			'label'		=> 'Email',
			'form_type'	=> 'text',
			'rules'		=> array(
				'field'		=> 'email',
				'label'		=> 'Email',
				'rules'		=> 'required|valid_email'
			),
			'form'		=> array (
				'name'        	=> 'email',
			    'id'          	=> 'email',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Email',
			)
		),
		'first_name' => array(
			'label'		=>	'First Name',
			'form_type'	=> 'text',
			'rules'		=> array(
				'field'		=> 'fname',
				'label'		=> 'First Name',
				'rules'		=> 'required|alpha'
			),
			'form'		=> array (
				'name'        	=> 'fname',
			    'id'          	=> 'fname',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'First Name',
			)
		),
		'last_name' => array(
			'label'		=> 'Last Name',
			'form_type'	=> 'text',
			'rules'		=> array(
				'field'		=> 'lname',
				'label'		=> 'Last Name',
				'rules'		=> 'required|alpha'
			),
			'form'		=> array (
				'name'        	=> 'lname',
			    'id'          	=> 'lname',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Last Name',
			)
		),
		'password' => array(
			'label'		=>	'Password',
			'form_type'	=> 'password',
			'rules'		=> array(
				'field'		=> 'password',
				'label'		=> 'Password',
				'rules'		=> 'required|alpha_dash'
			),
			'form'		=> array (
				'name'        	=> 'password',
			    'id'          	=> 'password',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Password',
			)
		),
		'chpassword' => array(
			'label'		=>	'Retype password',
			'form_type'	=> 'password',
			'rules'		=> array(
				'field'		=> 'chpassword',
				'label'		=> 'Retype password',
				'rules'		=> 'required|alpha_dash'
			),
			'form'		=> array (
				'name'        	=> 'chpassword',
			    'id'          	=> 'chpassword',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Retype password',
			)
		),
		'role' => array(
			'label'		=> 'Role',
			'form_type'	=> 'select',
			'values'	=> 'roles',
			'rules'		=> array(
				'field'		=> 'role',
				'label'		=> 'Role',
				'rules'		=> 'required|numeric'
			),
			'form'		=> array (
				'name'        	=> 'role',
			    'id'          	=> 'role',
			    'class'			=> 'form-control',
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

		$this->load->model("UserModel");
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

	public function signout()
	{
		Auth::destroy();
		redirect(base_url());
	}

	public function login()
	{
		$inputs = ['email', 'password'];
		$user = $this->getPost($inputs);
		if($this->validation($user)){
			$credentials = array('`email`' => $this->input->post('email'), '`password`' => $this->input->post('password'), '`status`' => '1');

			$this->data['user'] = $this->UserModel->find($credentials);

			if($this->UserModel->queryStatus()){
				$this->data['user'] = $this->data['user'][0];
				$session = array('key' => hash('sha256', Carbon::now().$this->data['user']->id),'date' =>Carbon::now());
				var_dump($this->data['user']);
				$this->session->set_userdata($session);
				$this->UserModel->update(['keycode' => $this->session->userdata('key'), 'last_session'=> Carbon::now()], ['id' => $this->data['user']->id]);
				if($this->UserModel->queryStatus()){
					redirect(base_url());
				}
			} else {
				$this->session->set_flashdata('message', "Wrong credentials.");
			}
		} else {
			$this->session->set_flashdata('message', "Please enter a valid email address. <br/>(Ex: johndoe@domain.com).");
		}
		$this->index();
	}

	/*
	 * Display of registered Users.
	 */
	public function show ()
	{
		$this->data['url'] = "user/show";
		$this->data['per_page'] = $this->UserModel->limit;
		$seg = 3;

		$this->UserModel->findAll();
		$total = $this->UserModel->affected_rows();

		$last_page = ceil(($total/$this->data['per_page']));

		if($last_page < $this->uri->segment(3)) {
			($last_page == 0) ? redirect(base_url("user/show/")) : redirect(base_url("user/show/$last_page"));
		}

		if($this->uri->segment($seg)) {
			$this->data['page'] = (($this->uri->segment($seg)-1) == 0) ? 0 : (($this->uri->segment($seg)-1)*$this->data['per_page']);
		} else {
			$this->data['page'] = 0;
		}

		$this->data['table'] = $this->UserModel->find(null, $this->data['per_page'], $this->data['page']);

		$this->data['pagination'] = $this->getPagination($this->data['url'], $total, $this->data['per_page'], $seg);
		$this->createPanel('user/table', $this->data);
	}

	/*
	 * Display of Searched Users.
	 */
	public function search ()
	{
		$search = ($this->input->post("search")) ? $this->input->post("search") : NULL;
		$search = ($this->uri->segment(3)) ? $this->uri->segment(3) :  redirect(base_url("user/search/$search");

		if($this->form_validation->alpha_dash($search)) {
			if($search != NULL) {
				$this->data['table'] = $this->UserModel->find("(`first_name` LIKE '%$search%') OR (`last_name` LIKE '%$search%') OR (`user.email` LIKE '%$search%')");
			} else {
				 redirect(base_url("user/view/"));
			}

			$this->data['search'] = $search;
			$this->data['url'] = "user/search/$search";
			$this->data['per_page'] = $this->UserModel->limit;
			$seg = 4;

			$total = $this->UserModel->affected_rows();
			$last_page = ceil(($total/$this->data['per_page']));

			if($last_page < $this->uri->segment($seg)){
				if($total > 0) {
					($last_page == 0) ? redirect(base_url("user/search/$search")) : redirect(base_url("user/search/$search/$last_page"));
				} else {
					redirect(base_url("user/search/"));
				}
			}

			if($this->uri->segment($seg)) {
				$this->data['page'] = (($this->uri->segment($seg)-1) == 0) ? 0 : (($this->uri->segment($seg)-1)*$this->data['per_page']);
			} else {
				$this->data['page'] = 0;
			}

			if($search != NULL) {
				$this->data['table'] = $this->UserModel->find("(`first_name` LIKE '%$search%') OR (`last_name` LIKE '%$search%') OR (`user.email` LIKE '%$search%')", $this->data['per_page'], $this->data['page']);
			} else {
				$this->data['table'] = $this->UserModel->findAll();
			}

			$this->data['pagination'] = $this->getPagination($this->data['url'], $total, $this->data['per_page'], $seg);
			$this->createPanel('user/table', $this->data);
		} else {
			$this->data['search'] = $search;
			$this->data['table'] = array();
			$this->createPanel('user/table', $this->data);
		}
	}

	/*
	 * Deactivation of user account.
	 */
	public function delete ()
	{
		$id = $this->uri->segment(3);
		if($id) {
			$info = $this->UserModel->find("user.id = '$id'");
			if($this->UserModel->queryStatus()) {
				$info = $info[0];
				if($info->status) {
					$this->UserModel->update(array('status' => 0), "user.id = '$id'");

					if($this->UserModel->queryStatus()) {
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
		$id = $this->uri->segment(3);

		if($id) {
			$info = $this->UserModel->find("user.id = '$id'");

			if($this->UserModel->queryStatus()) {
				$info = $info[0];
				if(!$info->status) {
					$this->UserModel->update(array('status' => 1), "user.id = '$id'");

					if($this->UserModel->queryStatus()) {
						$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully activate profile."));
					} else {
						$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while activating the profile."));
					}
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while activating the profile."));
				}
			} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Can't find user profile."));
			}
		}

		redirect($_SERVER['HTTP_REFERER']);
	}

	/*
	 * Dispaly of profile information that can be edited.
	 */
	public function profile ()
	{
		if(($this->user->role_name != 'admin') OR (!$this->uri->segment(3))) {
			$this->data['profile'] = $this->user;
			$this->data['form'] = array_diff_key($this->properties, array('password'=> null, 'chpassword' => null, 'search' => null, 'role' => null, 'email' => null));
		} else {
			$id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->user->id;
			$user = $this->UserModel->find("`user.id` = '$id'");

			$this->data['profile'] = ($this->UserModel->queryStatus()) ? $user[0]: $this->user;
			$this->session->set_userdata('form_data', $this->data['profile']);
			$this->properties['role']['values'] = $this->setRolesForm();
			$this->data['form'] = array_diff_key($this->properties, array('password'=> null, 'chpassword' => null, 'search' => null, 'email' => null));
		}
		$this->data['title'] = "Edit Profile";
		$this->data['url'] = 'user/update/';
		$this->load->model("RoleModel");

		$this->createPanel('user/form', $this->data);
	}

	/*
	 * Update the profile information in the database.
	 */
	public function update ()
	{
		$inputs = ['first_name', 'last_name'];
		if($this->user->role_name === 'admin')
			array_push($inputs, 'role');

		$user = $this->getPost($inputs);

		if($this->validation($user)){
			if($this->dataChange($this->session->userdata('form_data'), $user)) {
				$id = $this->session->userdata('form_data')->id;
				$this->UserModel->update($user, "id = $id");
				if($this->UserModel->queryStatus()) {
					$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully updated profile."));
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while updating profile."));
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
		$this->data['form']['role']['values'] = $this->setRolesForm();
		$this->createPanel('user/form', $this->data);
	}

	/*
	 * Creates new user in database.
	 */
	public function create() {
		$inputs = ['role', 'email', 'first_name', 'last_name', 'password', 'chpassword'];
		$user = $this->getPost($inputs);

		if($this->validation($user) AND ($user['password'] === $user['chpassword'])){
			$this->load->model('UserModel');
			$this->session->set_userdata('form_data', $this->arrToObj($user));
			if(!$this->check_user_email($user['email'])){
				$user = array_diff_key($user, array('chpassword' => null));
				$user['status'] = true;
				$this->UserModel->insert($user);
				if($this->UserModel->queryStatus()) {
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
		$this->load->model('UserModel');
    	$result = $this->UserModel->find("user.email  = '$email'");

	    return $this->UserModel->queryStatus();
	}
}
