<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'traits/validation.php';
require_once 'traits/helper.php';

class Portfolio extends CI_Controller {
	private $properties = array(
		'port_address' => array(
			'label'		=> 'Address',
			'form_type'	=> 'text',
			'rules'		=> array(
				'field'		=> 'paddress',
				'label'		=> 'Title',
				'rules'		=> 'required'
			),
			'form'		=> array (
				'name'        	=> 'paddress',
			    'id'          	=> 'paddress',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Address',
			)
		),
		'port_worth' => array(
			'label'		=> 'Worth',
			'form_type'	=> 'text',
			'rules' 	=> array(
				'field'		=> 'pworth',
				'label'		=> 'Slug',
				'rules'		=> 'required|numeric'
			),
			'form'		=> array (
				'name'        	=> 'pworth',
			    'id'          	=> 'pworth',
			    'class'			=> 'form-control',
			    'placeholder'	=> 'Worth',
			)
		),
		'port_desc' => array(
			'label'		=>	'Description',
			'form_type'	=> 'textarea',
			'rules' => array(
				'field'		=> 'pdesc',
				'label'		=> 'Description',
				'rules'		=> 'required'
			),
			'form'		=> array (
				'name'        	=> 'pdesc',
			    'id'          	=> 'pdesc',
			    'class'			=> '',
			    'placeholder'	=> 'Property Description',
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

		$this->load->model("PortfolioModel");
		$exceptions = array();
		$restricted = array();
		if(!in_array($this->data['method'], $exceptions)){
			if(!Auth::check()){
				redirect(base_url());
			} else {
				if(($this->user->role_name != 'admin') AND (in_array($this->data['method'], $restricted))) {
					redirect(base_url());
				}
			}
		}
	}

	/*
	 * Display of portfolio entry.
	 */
	public function show ()
	{
		$this->data['url'] = "portfolio/show";
		$this->data['per_page'] = $this->PortfolioModel->limit;
		$seg = 3;

		$this->PortfolioModel->findAll("user_id = '".$this->user->id."'");
		$total = $this->PortfolioModel->affected_rows();

		$last_page = ceil(($total/$this->data['per_page']));

		if($last_page < $this->uri->segment(3)) {
			($last_page == 0) ? redirect(base_url("portfolio/show/")) : redirect(base_url("portfolio/show/$last_page"));
		}

		if($this->uri->segment($seg)) {
			$this->data['page'] = (($this->uri->segment($seg)-1) == 0) ? 0 : (($this->uri->segment($seg)-1)*$this->data['per_page']);
		} else {
			$this->data['page'] = 0;
		}

		$this->data['table'] = $this->PortfolioModel->find("user_id = '".$this->user->id."'", $this->data['per_page'], $this->data['page']);

		$this->data['pagination'] = $this->getPagination($this->data['url'], $total, $this->data['per_page'], $seg);
		$this->createPanel('portfolio/table', $this->data);
	}

	/*
	 * Display of searched portfolio entry.
	 */
	public function search ()
	{
		$search = ($this->input->post("search")) ? $this->input->post("search") : NULL;
		$search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

		if($this->form_validation->alpha_dash($search)) {
			if($search != NULL) {
				$this->data['table'] = $this->PortfolioModel->find("((port_address LIKE '%$search%') OR (port_desc LIKE '%$search%')) AND (`user_id` = '".$this->user->id."')");
			} else {
				 redirect(base_url("portfolio/view/"));
			}

			$this->data['search'] = $search;
			$url = "portfolio/search/$search";
			$per_page = $this->PortfolioModel->limit;
			$seg = 4;

			$this->PortfolioModel->find();
			$total = $this->PortfolioModel->affected_rows();
			$last_page = ceil(($total/$per_page));

			if($last_page < $this->uri->segment(3)) {
				($last_page == 0) ? redirect(base_url("portfolio/view")) : redirect(base_url("portfolio/view/$last_page"));
			}

			if($this->uri->segment($seg)) {
				$this->data['page'] = (($this->uri->segment($seg)-1) == 0) ? 0 : (($this->uri->segment($seg)-1)*$per_page);
			} else {
				$data['page'] = 0;
			}

			$this->data['table'] = $this->PortfolioModel->find("((port_address LIKE '%$search%') OR (port_desc LIKE '%$search%')) AND (`user_id` = '".$this->user->id."')", $per_page, $data['page']);
			$this->data['class'] = 'portfolio';
			$this->data['pagination'] = $this->getPagination($url, $total, $per_page, $seg);

			$this->createPanel('portfolio/table', $this->data);
		} else {
			$this->data['class'] = 'portfolio';
			$this->data['search'] = $search;
			$this->data['table'] = array();
			echo "none";
			$this->createPanel('portfolio/table', $this->data);
		}
	}

	/*
	 * Deactivation of portfolio entry.
	 */
	public function delete ()
	{
		$id = $this->uri->segment(3);
		if($id) {
			$info = $this->PortfolioModel->find("port_id = '$id'");
			if($this->PortfolioModel->queryStatus()) {
				$info = $info[0];
				if($info->status) {
					$this->PortfolioModel->update(array('status' => 0), "port_id  = '$id'");

					if($this->PortfolioModel->queryStatus()) {
						$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully deleted portfolio entry."));
					} else {
						$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while deleting portfolio entry."));
					}
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while deleting portfolio entry."));
				}
			} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Can't find portfolio entry."));
			}
		} else {
			$this->session->set_flashdata('message', array('type' => false, 'msg' => "Can't find portfolio entry."));
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
			$info = $this->PortfolioModel->find("port_id = '$id'");

			if($this->PortfolioModel->queryStatus()) {
				$info = $info[0];
				if(!$info->status) {
					$this->PortfolioModel->update(array('status' => 1), "port_id = '$id'");

					if($this->PortfolioModel->queryStatus()) {
						$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully activate portfolio entry."));
					} else {
						$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while activating portfolio entry."));
					}
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while activating portfolio entry."));
				}
			} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Can't find portfolio entry."));
			}
		} else {
			$this->session->set_flashdata('message', array('type' => false, 'msg' => "Check form data."));
		}

		redirect($_SERVER['HTTP_REFERER']);
	}

	/*
	 * Dispaly of profile information that can be edited.
	 */
	public function edit ()
	{
		$id = $this->uri->segment(3);

		if($id) {
			$this->data['portfolio'] = $this->PortfolioModel->find(array('port_id' => $id), 1);
			if(!$this->PortfolioModel->queryStatus())
				redirect(base_url('portfolio/view/'));
			$this->data['portfolio'] = $this->data['portfolio'][0];
		} else {
			redirect(base_url('portfolio/register/'));
		}

		$this->session->set_userdata('form_portfolio', $this->data['portfolio']);
		$this->data['title'] = "Edit portfolio";
		$this->data['class'] = 'portfolio';
		$this->data['url'] = 'portfolio/update/'.$id;
		$this->data['form'] = array_diff_key($this->properties,array('serv_published' => null, "search" => null));
		$this->data['form_attr'] = array('class' => 'edit-form form-block');

		$this->createPanel('portfolio/form', $this->data);
	}

	/*
	 * Update the profile information in the database.
	 */
	public function update ()
	{
		$inputs = ['port_address', 'port_worth', 'port_desc'];
		$portfolio = $this->getPost($inputs);

		var_dump($portfolio);
		if($this->validation($portfolio)){
			if($this->dataChange($this->session->userdata('form_portfolio'), $portfolio)) {
				$id = $this->session->userdata('form_portfolio')->port_id;
				$this->PortfolioModel->update($portfolio, "port_id = $id");

				if($this->PortfolioModel->queryStatus()) {
					$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully updated portfolio entry."));
				} else {
					$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while updating portfolio entry."));
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
		$this->data['url'] = 'portfolio/create';
		$this->data['form_attr'] = array('class' => 'register-form form-block');
		if($this->session->userdata('form_portfolio'))
			$this->data['profile'] = $this->session->userdata('form_data');

		$this->data['form'] = array_diff_key($this->properties, array('search' => null,));
		$this->createPanel('portfolio/form', $this->data);
	}

	/*
	 * Creates new user in database.
	 */
	public function create() {
		$inputs = ['port_address', 'port_worth', 'port_desc'];
		$portfolio = $this->getPost($inputs);
		$this->session->set_userdata('form_portfolio', $portfolio);

		if($this->validation($portfolio)){
			$portfolio['user_id'] = (int)$this->user->id;
			$portfolio['status'] = 1;
			$this->PortfolioModel->insert($portfolio);

			if($this->PortfolioModel->queryStatus()) {
				$this->session->set_flashdata('message', array('type' => true, 'msg' => "Successfully added new property portfolio."));
				$this->session->unset_userdata('form_portfolio');
			} else {
				$this->session->set_flashdata('message', array('type' => false, 'msg' => "Something went wrong while creating property portfolio."));
			}
		} else {
			$this->session->set_flashdata('message', array('type' => false, 'msg' => "Check form values."));
		}
	 	redirect($_SERVER['HTTP_REFERER']);
	}
}
