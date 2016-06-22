<?php
trait Helper {

    private function createPanel($template, $data) {
        $data['user'] = Auth::user();
        $this->getHeader($data);
        $this->load->view($template, $data);
        $this->getFooter($data);
    }

    private function getHeader($data) {
        $this->load->view('template/head', $data);
        $this->load->view('template/header', $data);
    }

    private function getFooter($data) {
        $this->load->view('template/footer',$data);
    }

    private function getPagination($url, $total, $per_page, $seg){
		$this->load->library('pagination');
        $config = array();

		$config['use_page_numbers'] = TRUE;
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $total;
        $config['per_page'] = $per_page;
        $config["uri_segment"] = $seg;

        $choice = $config["total_rows"]/$config["per_page"];
        $config["num_links"] = floor($choice);

        $config['full_tag_open'] = '<ul class="pagination pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        return $this->pagination->create_links();

    }

	/*
	 * Check if there is a change/s in the data from database.
	 */
	private function dataChange($original, $data) {
		foreach($data as $key => $val){
			if(array_key_exists($key, $data)) {
				if($val != $original->{$key}) {
					echo "mern";
					return true;
				}
			}
		}
		return false;
	}
}
