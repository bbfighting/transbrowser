<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function view()
	{
		$this->load->view('main_base');
	}
	public function home()
	{
		$data['title'] = "Home";
		$data['icon'] = "fa-home";
		$data['jsfile'] = "js.home.js";
		$data['searchkey'] = "";
		$data['mainbar'] = "";
		$data['result'] = "";
		$this->load->view('main_base', $data);
	}
	public function predict($method = "", $target_query = "")
	{
		$data['title'] = "IRES Prediction";
		$data['icon'] = "fa-cog";
		$data['jsfile'] = "js.predict.js";
		$data['searchkey'] = "";


		// $data2['key'] = urldecode($target_query);
		// $data2['content'] = "";
		// $data['result'] = "";

		if ($method == "plot")
		{
			$this->load->model('page_parsexml', 'pparsexml');
			$data2['content'] = $this->pparsexml->get_outputs("no", $target_query, "predict");
			//$result_data['result_table'] = $this->pIRES->createinput($target_time, $target_gene, $target_sc);
			$data['mainbar'] = "";
			$data['result'] = $this->load->view('main_result', $data2, true);
		}
		else
		{
			$txt_input = $this->input->post('txt_input');

			if ($txt_input)
			{
				$txt_input = preg_split('/\n/', $this->input->post('txt_input'));
			}

			$target_dir = "/home/testuser/public_html/IRES_test/SingleIRES/upload/";

	        if (isset($_FILES["FILE"])) 
	        {
	            if ($_FILES["FILE"]["size"] != 0)
	            {
					$target_file = $target_dir . basename($_FILES["FILE"]["name"]);
					$file_temp = pathinfo($target_file);
					$filename = str_replace(" ", "_", $file_temp['filename']);
					$txt_input = file($_FILES["FILE"]["tmp_name"]);
				}
			}
			
			if ($txt_input)
			{
				if (count($txt_input) == 1){
					echo "Data is error";
					//echo "<p><a href = 'javascript:history.go(-1)'>Back</a></p>";
					// unlink($target_file);
				}
				$this->load->model('page_predict', 'ppredict');
				$data2['content'] = $this->ppredict->CreateInput($txt_input, "aug");
				$data['mainbar'] = "";
				$data['result'] = $this->load->view('main_result', $data2, true);
			}
			else
			{
				$data['mainbar'] = $this->load->view('main_predict_bar', array(), true);
				$data['result'] = "";				
			}
		}

		$this->load->view('main_base', $data);
	}
	public function search($method = "", $species = "", $key = "")
	{
		$data['title'] = "Search";
		$data['icon'] = "fa-search";
		$data['jsfile'] = "js.search.js";

		if ($method == "result")
		{
			$this->load->model('page_search', 'psearch');
			$data2['content'] = $this->psearch->get_outputs_result($species, urldecode($key));
			$data['searchkey'] = " : " . urldecode($key);
			$data['mainbar'] = "";
			$data['result'] = $this->load->view('main_result', $data2, true);			
		}
		else if ($method == "plot")
		{
			$this->load->model('page_parsexml', 'pparsexml');
			$data['searchkey'] = " : " . urldecode($key);
			$data['mainbar'] = "";
			$data2['content'] = $this->pparsexml->get_outputs($species, $key, "search");			
			$data['result'] = $this->load->view('main_result', $data2, true);
		}
		else
		{
			$data['searchkey'] = "";
			$data['mainbar'] = $this->load->view('main_search_bar', '', true);
			$data['result'] = "";
		}
		$this->load->view('main_base', $data);
	}
	public function browse($species = "", $ch = "")
	{
		$data['title'] = "Browse";
		$data['icon'] = "fa-check-square-o";
		$data['jsfile'] = "js.browse.js";
		$data['searchkey'] = "";
		$data['mainbar'] = $this->load->view('main_browse_bar', '', true);
		$data['result'] = "";

		if ($species != "")
		{
			$this->load->model('page_browse', 'pbrowse');
			$data2['content'] = $this->pbrowse->get_outputs_result($species, urldecode($ch));
			$data['searchkey'] = " : " . urldecode($key);
			$data['mainbar'] = "";
			$data['result'] = $this->load->view('main_result', $data2, true);			
		}
		$this->load->view('main_base', $data);
	}	
	public function tutorial()
	{
		$data['title'] = "Tutorial";
		$data['icon'] = "fa-tags";
		$data['jsfile'] = "js.tutorial.js";
		$data['searchkey'] = "";
		$data['mainbar'] = "";
		$data['result'] = "";
		$this->load->view('main_base', $data);
	}	
	public function about()
	{
		$data['title'] = "About";
		$data['icon'] = "fa-book";
		$data['jsfile'] = "js.about.js";
		$data['searchkey'] = "";
		$data['mainbar'] = "";
		$data['result'] = "";
		$this->load->view('main_base', $data);
	}	
	public function contact()
	{
		$data['title'] = "Contact";
		$data['icon'] = "fa-user";
		$data['jsfile'] = "js.contact.js";
		$data['searchkey'] = "";
		$data['mainbar'] = "";
		$data['result'] = "";
		$this->load->view('main_base', $data);
	}


	public function plot($species, $target_name = "NM_000014", $cor1 = 0, $cor2 = -1, $method = "search", $ri_track = "no")
	{
		// Load model - batchresults
		$argumet_keys = array('time');
		$time = $this->_load_requests($argumet_keys)['time'];

        $this->load->model('page_plot', 'pplot');
		$this->pplot->initialize($species, $target_name, $cor1, $cor2, $method, $time);
		echo json_encode($this->pplot->run($ri_track));
	}
	public function plotseq($target_name = "NM_000014", $cor1 = 0, $cor2 = -1, $method = "entrez")
	{
        $this->load->model('page_plot', 'pplot');
		// $this->pplot->initialize($target_name, $cor1, $cor2, $method);
		// $this->pplot->runseq();
	}
	public function getseq($species, $target_name = "")
	{
        $this->load->model('page_getseq', 'pgetseq');
		echo $this->pgetseq->get_outputs($species, $target_name);
	}
	public function getexample()
	{
        $this->load->model('page_getseq', 'pgetseq');
		echo $this->pgetseq->get_ex();
	}

	// -------- --------
	// Private Function load_requests - load the requests
	private function _load_requests( /*ref array*/ &$keys_list, /*string*/ $action = 'exit' )
	{
		$temp_array = array();
		
		foreach ($keys_list as $key)
		{
			if ($this->input->post($key) !== FALSE)
			{
				$temp_array[$key] = $this->input->post($key);
			}
			else
			{
				if ($action == 'exit') exit();
				else $temp_array[$key] = "";
			}
		}
		return $temp_array;
	}
}
