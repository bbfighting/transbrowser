<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*****
 *** Website Controller ***
 *** Controller Name: Asyncs
 *** Description: Load asynchromous data
 *
 *****/

class Asyncs extends CI_Controller {
	// -------- -------- -------- --------
	// Private variables

	// -------- -------- -------- --------
	// Asyncs Method - 'batchresults'
	// -------- -------- -------- --------
	public function entrezsearch()
	{
		// Load model - batchresults
		$argumet_keys = array('target_query', 'rel_web');
		$key = $this->_load_requests($argumet_keys)['target_query'];
		$web = $this->_load_requests($argumet_keys)['rel_web'];
		
		$this->load->model('Page_entrez', 'pentrez');
		//$data['key'] = $key;
		echo  $this->pentrez->get_outputs_search($key, $web);
		//echo $this->load->view('main_result', $data, true);
	}
	// -------- -------- -------- --------

	// -------- -------- -------- --------
	// Asyncs Method - 'batchresults'
	// -------- -------- -------- --------
	public function search()
	{
		// Load model - batchresults
		$argumet_keys = array('target_query', 'rel_web', 'species');
		$key = $this->_load_requests($argumet_keys)['target_query'];
		$web = $this->_load_requests($argumet_keys)['rel_web'];
		$species = $this->_load_requests($argumet_keys)['species'];
		
		$this->load->model('Page_search', 'psearch');
		//$data['key'] = $key;
		echo  $this->psearch->get_outputs_search($key, $web, $species);
		//echo $this->load->view('main_result', $data, true);
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

/*****
 * End of file asyncs.php
 * Location: ./application/controllers/asyncs.php
 *****/