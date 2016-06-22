<?php
trait Validation {

	private function validation($fields = NULL)
	{
		if($fields == NULL){
			return false;
		}

		$filterConfig = array ();
		$this->err = array();

		if(is_array($fields)) {
			foreach($fields as $key => $val) {
				$rules = explode("|", $this->properties[$key]['rules']['rules']);
				foreach($rules as $rule) {
					if(!$this->form_validation->{$rule}($val)) {
						array_push($this->err, $key);
						break;
					}
				}
			}
			return (!$this->err) ? true : false;;
		}
		return false;
	}

	private function getPost($inputs)
	{
		$data = array();

		foreach($inputs as $input){
			$value = $this->input->post($this->properties[$input]['form']['name']);

			if(!empty($value)){
				$data[$input] = $value;
			}
		}
		return $data;
	}

	private function arrToObj ($arr)
	{
		$data = new stdClass();
		foreach($arr as $key => $val) {
			$data->$key = $val;
		}
		return $data;
	}
}
