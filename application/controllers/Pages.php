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

	// -------- -------- -------- --------
	// Page Method - 'view'
	// -------- -------- -------- --------
	public function view()
	{
		$this->load->view('main_base');
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'home'
	// -------- -------- -------- --------
	public function home()
	{
		$data['title'] = "Home";
		$data['icon'] = "fa-home";
		$data['jsfile'] = "js.home.js";
		$data['searchkey'] = "";
		$data['mainbar'] = "";
		$data['result'] = $this->load->view('main_home', '', true);
		$this->load->view('main_base', $data);
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'predict'
	// -------- -------- -------- --------
	public function predict($method = "", $target_query = "")
	{
		$data['title'] = "IRES Prediction";
		$data['icon'] = "fa-cog";
		$data['jsfile'] = "js.predict.js";
		$data['searchkey'] = "";

		// Outputs after user upload => $method == "plot"
		// user upload => $method == ""
		if ($method == "plot")
		{
			// Load model - parsexml
			$this->load->model('page_parsexml', 'pparsexml');

			// Outputs
			$data2['content'] = $this->pparsexml->get_outputs("no", $target_query, "predict");
			$data['mainbar'] = "";
			$data['result'] = $this->load->view('main_result', $data2, true);
		}
		else   
		{
			// input from textbox
			$txt_input = $this->input->post('txt_input');

			if ($txt_input)
			{
				$txt_input = preg_split('/\n/', $this->input->post('txt_input'));
			}

			// input from upload the file
	        if (isset($_FILES["FILE"])) 
	        {
	            if ($_FILES["FILE"]["size"] != 0)
	            {
	            	$target_dir = "/home/testuser/public_html/IRES_test/SingleIRES/upload/";
					$target_file = $target_dir . basename($_FILES["FILE"]["name"]);
					$file_temp = pathinfo($target_file);
					$filename = str_replace(" ", "_", $file_temp['filename']);
					$txt_input = file($_FILES["FILE"]["tmp_name"]);
				}
			}
			
			// no $txt_input will show predict mode page
			if ($txt_input)
			{
				// FASTA type => $txt_input > 1
				if (count($txt_input) == 1){
					echo "Data is error";
				}

				// Load model - predict
				$this->load->model('page_predict', 'ppredict');

				// Outputs
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
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'search'
	// -------- -------- -------- --------
	public function search($method = "", $species = "", $key = "")
	{
		$data['title'] = "Search";
		$data['icon'] = "fa-search";
		$data['jsfile'] = "js.search.js";

		// $method == "" => search mode page
		// $method == "result" => after search will show the NCBI simple page correlates with use input
		// $method == "plot" => show image about IRES, uORF and ribosome
		if ($method == "result")
		{
			$data['title'] = "Search Result";

			// Load model - search
			$this->load->model('page_search', 'psearch');

			// Outputs
			$data2['content'] = $this->psearch->get_outputs_result($species, urldecode($key));
			$data['searchkey'] = sprintf(" (Input : %s)", urldecode($key));
			$data['mainbar'] = "";
			$data['result'] = $this->load->view('main_result', $data2, true);			
		}
		else if ($method == "plot")
		{
			$data['title'] = "Search Result";

			// Load model - parsexml
			$this->load->model('page_parsexml', 'pparsexml');

			// Outputs
			$data['searchkey'] = sprintf(" (Input : %s)", urldecode($key));
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
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'browse'
	// -------- -------- -------- --------
	public function browse($species = "", $ch = "")
	{
		$data['title'] = "Browse Page";
		$data['icon'] = "fa-check-square-o";
		$data['jsfile'] = "js.browse.js";
		$data['searchkey'] = "";
		$data['mainbar'] = $this->load->view('main_browse_bar', '', true);
		$data['result'] = "";

		if ($species != "")
		{
			$data['title'] = "Browse Result";

			// Load model - browse
			$this->load->model('page_browse', 'pbrowse');

			// Outputs
			$data2['content'] = $this->pbrowse->get_outputs_result($species, urldecode($ch));
			$data['searchkey'] = sprintf(" : Genes whose names start with %s", urldecode($ch));
			$data['mainbar'] = "";
			$data['result'] = $this->load->view('main_result', $data2, true);			
		}
		$this->load->view('main_base', $data);
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'help'
	// -------- -------- -------- --------
	public function help()
	{
		$data['title'] = "Help";
		$data['icon'] = "fa-book";
		$data['jsfile'] = "js.help.js";
		$data['searchkey'] = "";
		$data['mainbar'] = "";
		$data['result'] = "";
		$this->load->view('main_base', $data);
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'contact'
	// -------- -------- -------- --------	
	public function contact()
	{
		$data['title'] = "Contact";
		$data['icon'] = "fa-user";
		$data['jsfile'] = "js.contact.js";
		$data['searchkey'] = "";
		$data['mainbar'] = "";
		$data['result'] = $this->load->view('main_contact', '', true);
		$this->load->view('main_base', $data);
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'plot'
	// -------- -------- -------- --------
	public function plot($species, $target_name = "NM_000014", $cor1 = 0, $cor2 = -1, $method = "search", $ri_track = "no")
	{
		$argumet_keys = array('time');
		$time = $this->_load_requests($argumet_keys)['time'];

		// Load model - plot
        $this->load->model('page_plot', 'pplot');

        // initialize
		$this->pplot->initialize($species, $target_name, $cor1, $cor2, $method, $time);
		echo json_encode($this->pplot->run($ri_track));
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'plotseq'
	// -------- -------- -------- --------
	public function plotseq($target_name = "NM_000014", $cor1 = 0, $cor2 = -1, $method = "entrez")
	{
		// Load model - plot
        $this->load->model('page_plot', 'pplot');
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'getseq'
	// -------- -------- -------- --------
	public function getseq($species, $target_name = "")
	{
		// Load model - getseq
        $this->load->model('page_getseq', 'pgetseq');

        // Outputs
		echo $this->pgetseq->get_outputs($species, $target_name);
	}
	// -------- -------- -------- --------


	// -------- -------- -------- --------
	// Page Method - 'getexample'
	// -------- -------- -------- --------
	public function getexample()
	{
		// Load model - getseq
        $this->load->model('page_getseq', 'pgetseq');

        // get_ex
		echo $this->pgetseq->get_ex();
	}
	// -------- -------- -------- --------
	
	
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
