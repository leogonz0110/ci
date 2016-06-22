<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

	protected $manager = [];



	public static function check($url = NULL)
	{
		$CI =& get_instance();

		if(!$CI->session->userdata('key') && !$CI->session->userdata('date')){
			if($url != NULL) {
				redirect($url);
			}
			return false;
		}
		return true;
	}

	public static function user()
	{
		$CI =& get_instance();

		if($CI->session->userdata('key') && $CI->session->userdata('date')){
			$CI->load->model('UserModel');
			$key = $CI->session->userdata('key');

			$user = $CI->UserModel->find(['keycode' => $key]);
			if($CI->UserModel->queryStatus()) {
				return $user[0];
			}
			return false;
		}
	}

	public static function destroy()
	{
		$CI =& get_instance();

		if($CI->session->userdata('key') && $CI->session->userdata('date')){
			$CI->session->sess_destroy();
		}
	}

	public static function role()
	{
		$user = static::user();

		return $user != NULL ? $user->role_name : NULL ;
	}


	public static function roleCheck()
	{
		$role = static::role();
	}

}
